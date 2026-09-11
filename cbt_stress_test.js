import http from 'k6/http';
import { sleep, check } from 'k6';

/**
 * =========================================================================
 * BAGIAN YANG HARUS DISESUAIKAN (KONFIGURASI UTAMA)
 * =========================================================================
 */
// 1. Domain website target pengujian Anda
const BASE_URL = 'https://snbt.gaweid.my.id';

// 2. ID Sesi Ujian aktif di database server Anda (cek tabel `exam_sessions`)
const EXAM_SESSION_ID = 7;

// 3. Token ujian aktif untuk sesi di atas (agar robot lolos validasi token)
const EXAM_TOKEN = 'MANSACIS';

// 4. Perkiraan ID hasil ujian yang sedang berjalan.
// Pada k6, kita buat ID dinamis berdasarkan ID Robot (Virtual User) agar robot mengupdate ExamResult-nya masing-masing.
// *Catatan: Pastikan database bersih sebelum test atau sesuaikan rumus ID ini.
function getExamResultId(vuId) {
    return vuId; // Contoh sederhana: Robot VU #1 menargetkan ExamResult ID #1
}
/**
 * =========================================================================
 */

// Konfigurasi Beban Simulasi (40 User Bersamaan)
export const options = {
    hosts: {
        'snbt.gaweid.my.id': '72.61.213.199',
    },
    stages: [
        { duration: '30s', target: 40 }, // Naikkan perlahan ke 40 user dalam 30 detik
        { duration: '1m30s', target: 40 }, // Pertahankan beban 40 user selama 1.5 menit
        { duration: '30s', target: 0 },   // Turunkan kembali jumlah user ke 0
    ],
};

export default function () {
    // Generate data akun robot unik untuk pendaftaran
    const uniqueId = __VU + '-' + __ITER + '-' + Date.now();
    const name = `Robot User ${uniqueId}`;
    const email = `robot_${uniqueId}@gmail.com`;
    const password = 'password123';
    const school = 'SMA Negeri Simulator';

    // ---- A. PROSES DAFTAR AKUN BARU ----
    // Pura-puranya robot membuka halaman register
    let resRegisterPage = http.get(`${BASE_URL}/register`);
    
    // Ambil CSRF Token untuk registrasi dari HTML
    let registerCsrf = '';
    let registerMatch = resRegisterPage.body.match(/<meta[^>]*name="csrf-token"[^>]*content="([^"]+)"/i)
                     || resRegisterPage.body.match(/<meta[^>]*content="([^"]+)"[^>]*name="csrf-token"/i)
                     || resRegisterPage.body.match(/name="_token"\s+value="([^"]+)"/);
    if (registerMatch) {
        registerCsrf = registerMatch[1];
    }

    // Kirim request POST register akun baru
    let regPayload = {
        _token: registerCsrf,
        name: name,
        email: email,
        password: password,
        password_confirmation: password,
        school: school,
    };

    // Kirim request POST register akun baru dengan menonaktifkan redirect otomatis k6
    let resRegister = http.post(`${BASE_URL}/register`, regPayload, { redirects: 0 });
    
    console.log(`[DEBUG REGISTER] Status: ${resRegister.status}, Location: ${resRegister.headers['Location'] || resRegister.headers['location']}`);

    if (resRegister.status !== 302) {
        console.log(`[ERROR REGISTER] Gagal daftar. Status: ${resRegister.status}, Respon: ${resRegister.body}`);
    }

    check(resRegister, {
        '1. Register Berhasil (302)': (r) => r.status === 302,
    });

    sleep(2); // Siswa menunggu halaman dashboard memuat modal target kampus

    // Ambil halaman dashboard untuk mendapatkan CSRF Token sesi login yang baru
    let resDashboard = http.get(`${BASE_URL}/dashboard`);
    let sessionCsrf = '';
    let sessionMatch = resDashboard.body.match(/<meta[^>]*name="csrf-token"[^>]*content="([^"]+)"/i)
                    || resDashboard.body.match(/<meta[^>]*content="([^"]+)"[^>]*name="csrf-token"/i)
                    || resDashboard.body.match(/name="_token"\s+value="([^"]+)"/);
    if (sessionMatch) {
        sessionCsrf = sessionMatch[1];
    } else {
        console.log(`[ERROR DASHBOARD] Gagal mendapatkan session CSRF token dari: ${resDashboard.url}`);
        sessionCsrf = registerCsrf; // fallback
    }

    // ---- B. PROSES SIMULASI PILIH KAMPUS ----
    // 1. Simulasi ketik mencari nama kampus "Universitas" di Select2
    let resSearchCampus = http.get(`${BASE_URL}/api/campuses-list?q=Universitas`);
    check(resSearchCampus, {
        'Pencarian Kampus Berhasil (200)': (r) => r.status === 200,
    });

    // 2. Simulasi mengambil daftar prodi dari salah satu kampus (contoh: Universitas Indonesia)
    let resSearchProdi = http.get(`${BASE_URL}/api/campus-prodis-list?campus=Universitas+Indonesia`);
    check(resSearchProdi, {
        'Pemuatan Prodi Berhasil (200)': (r) => r.status === 200,
    });

    // 3. Simpan Kampus Tujuan (POST payload berupa JSON)
    let targetPayload = JSON.stringify({
        targets: [
            { campus_prodi_id: 1 }
        ]
    });

    let targetHeaders = {
        headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': sessionCsrf
        },
        redirects: 0
    };

    let resSaveTargets = http.post(`${BASE_URL}/student/targets`, targetPayload, targetHeaders);
    console.log(`[DEBUG TARGETS] Status: ${resSaveTargets.status}, Respon: ${resSaveTargets.body}`);

    check(resSaveTargets, {
        'Penyimpanan Kampus Tujuan Berhasil (200)': (r) => r.status === 200,
    });

    sleep(3); // Siswa membaca panduan dashboard selama 3 detik sebelum mulai ujian

    // ---- C. PROSES MULAI UJIAN ----
    let startPayload = {
        _token: sessionCsrf,
        exam_session_id: EXAM_SESSION_ID,
        token: EXAM_TOKEN,
    };

    let startHeaders = {
        headers: {
            'X-CSRF-TOKEN': sessionCsrf
        },
        redirects: 0
    };

    let resStart = http.post(`${BASE_URL}/exam/start`, startPayload, startHeaders);
    console.log(`[DEBUG START] Status: ${resStart.status}, Location: ${resStart.headers['Location'] || resStart.headers['location']}`);

    check(resStart, {
        '2. Mulai Ujian Berhasil (302)': (r) => r.status === 302,
    });

    // Ikuti redirect dari start ke halaman exam secara manual untuk mengambil detailnya
    let redirectUrl = resStart.headers['Location'] || resStart.headers['location'] || '';
    if (!redirectUrl.startsWith('http')) {
        redirectUrl = BASE_URL + redirectUrl;
    }

    let resExamPage = http.get(redirectUrl, { redirects: 0 });
    console.log(`[DEBUG EXAM PAGE GET] Status: ${resExamPage.status}, Location: ${resExamPage.headers['Location'] || resExamPage.headers['location']}`);
    
    // Ambil ID hasil ujian dinamis dari URL redirect
    let match = resExamPage.url.match(/\/exam\/(\d+)/) || redirectUrl.match(/\/exam\/(\d+)/);
    let examResultId = match ? match[1] : null;

    if (!examResultId) {
        console.log(`[ERROR START] Gagal mendapatkan examResultId dari URL: ${resExamPage.url}`);
    }

    // Ambil CSRF Token khusus untuk sesi ujian dari halaman exam/show
    console.log(`[DEBUG BODY] First 1000 chars: ${resExamPage.body.substring(0, 1000)}`);

    let examCsrf = '';
    let examCsrfMatch = resExamPage.body.match(/<meta[^>]*name="csrf-token"[^>]*content="([^"]+)"/i)
                     || resExamPage.body.match(/<meta[^>]*content="([^"]+)"[^>]*name="csrf-token"/i)
                     || resExamPage.body.match(/name="_token"\s+value="([^"]+)"/);
    if (examCsrfMatch) {
        examCsrf = examCsrfMatch[1];
        console.log(`[DEBUG EXAM CSRF] Berhasil mendapatkan token: ${examCsrf}`);
    } else {
        console.log(`[ERROR EXAM PAGE] Gagal mendapatkan CSRF token sesi ujian dari HTML`);
        examCsrf = sessionCsrf; // fallback
    }

    // Ekstraksi array allQuestions secara dinamis dari HTML script tag
    let questionsMatch = resExamPage.body.match(/allQuestions:\s*(\[[\s\S]*?\])\s*,/i);
    let questions = [];
    if (questionsMatch) {
        try {
            questions = JSON.parse(questionsMatch[1]);
            console.log(`[DEBUG EXAM] Berhasil mengekstrak ${questions.length} soal.`);
        } catch (e) {
            console.log(`[ERROR EXAM JSON] Gagal parse JSON soal: ${e.message}`);
        }
    } else {
        console.log(`[ERROR EXAM] Gagal mencocokkan regex allQuestions dari HTML`);
    }

    sleep(3); // Loading halaman soal ujian selama 3 detik

    // ---- C. PROSES SIMULASI MENJAWAB SOAL ----
    // Robot menyimulasikan menjawab seluruh soal yang berhasil diekstrak
    for (let i = 0; i < questions.length; i++) {
        let question = questions[i];
        let questionId = question.id;

        let answerPayload = {
            _token: examCsrf,
            question_id: questionId,
        };

        // Mengisi opsi jawaban berdasarkan jenis soal
        if (question.type === 'pilihan_ganda') {
            let options = question.options || [];
            if (options.length > 0) {
                let randomOption = options[Math.floor(Math.random() * options.length)];
                answerPayload.option_id = randomOption.id;
            }
        } else if (question.type === 'pilihan_ganda_kompleks') {
            let options = question.options || [];
            if (options.length > 0) {
                let randomOption = options[Math.floor(Math.random() * options.length)];
                answerPayload.option_ids = [randomOption.id];
            }
        }

        let answerHeaders = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': examCsrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            redirects: 0
        };

        // Mengirim jawaban ke server menggunakan ID dinamis
        let resAnswer = http.post(`${BASE_URL}/exam/${examResultId}/save-answer`, JSON.stringify(answerPayload), answerHeaders);
        console.log(`[DEBUG ANSWER] Question ID: ${questionId} - Status: ${resAnswer.status}, Respon: ${resAnswer.body}`);

        check(resAnswer, {
            '3. Jawaban Tersimpan (200)': (r) => r.status === 200,
        });

        // Waktu tunggu siswa membaca soal (acak antara 2 s.d 5 detik agar stress test efisien)
        sleep(Math.floor(Math.random() * 4) + 2);
    }
}
