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

// Konfigurasi Beban Simulasi (100 User Bersamaan)
export const options = {
    stages: [
        { duration: '1m', target: 100 }, // Naikkan perlahan ke 100 user dalam 1 menit
        { duration: '3m', target: 100 }, // Pertahankan beban 100 user selama 3 menit
        { duration: '1m', target: 0 },   // Turunkan kembali jumlah user ke 0
    ],
};

export default function () {
    // Generate data akun robot unik untuk pendaftaran
    const uniqueId = __VU + '-' + __ITER;
    const name = `Robot User ${uniqueId}`;
    const email = `robot_${uniqueId}@gmail.com`;
    const password = 'password123';
    const school = 'SMA Negeri Simulator';

    // ---- A. PROSES DAFTAR AKUN BARU ----
    // Pura-puranya robot membuka halaman register
    http.get(`${BASE_URL}/register`);

    // Kirim request POST register akun baru
    let regPayload = {
        name: name,
        email: email,
        password: password,
        password_confirmation: password,
        school: school,
    };

    let resRegister = http.post(`${BASE_URL}/register`, regPayload);
    check(resRegister, {
        '1. Register Berhasil (302/200)': (r) => r.status === 200 || r.status === 302,
    });

    sleep(2); // Siswa menunggu halaman dashboard memuat modal target kampus

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
    // *Catatan: Pastikan database Anda memiliki record prodi dengan ID 1, atau sesuaikan ID-nya.
    let targetPayload = JSON.stringify({
        targets: [
            { campus_prodi_id: 1 }
        ]
    });

    let targetHeaders = {
        headers: { 'Content-Type': 'application/json' }
    };

    let resSaveTargets = http.post(`${BASE_URL}/student/targets`, targetPayload, targetHeaders);
    check(resSaveTargets, {
        'Penyimpanan Kampus Tujuan Berhasil (200)': (r) => r.status === 200,
    });

    sleep(3); // Siswa membaca panduan dashboard selama 3 detik sebelum mulai ujian

    // ---- C. PROSES MULAI UJIAN ----
    let startPayload = {
        exam_session_id: EXAM_SESSION_ID,
        token: EXAM_TOKEN,
    };

    let resStart = http.post(`${BASE_URL}/exam/start`, startPayload);
    check(resStart, {
        '2. Mulai Ujian Berhasil (302/200)': (r) => r.status === 200 || r.status === 302,
    });

    sleep(3); // Loading halaman soal ujian selama 3 detik

    // ---- C. PROSES SIMULASI MENJAWAB SOAL ----
    // Robot menyimulasikan menjawab soal ID 1 sampai 10 secara bergantian
    for (let questionId = 1; questionId <= 10; questionId++) {
        let answerPayload = {
            question_id: questionId,
            option_id: Math.floor(Math.random() * 4) + 1, // Pilih opsi acak (1-4)
        };

        const examResultId = getExamResultId(__VU);

        // Mengirim jawaban ke server
        let resAnswer = http.post(`${BASE_URL}/exam/${examResultId}/save-answer`, answerPayload);

        check(resAnswer, {
            '3. Jawaban Tersimpan (200)': (r) => r.status === 200,
        });

        // Waktu tunggu siswa membaca soal (acak antara 5 s.d 15 detik) sebelum lanjut
        sleep(Math.floor(Math.random() * 10) + 5);
    }
}
