# Panduan Uji Beban (Stress Test) Ujian Online dengan k6

Dokumen ini menjelaskan langkah-langkah melakukan simulasi 100+ pengguna aktif secara bersamaan untuk menguji performa server CBT Anda.

---

## ⚠️ Aturan Emas: Jangan Jalankan Uji Beban di VPS yang Sama!

> [!CAUTION]  
> **Jalankan aplikasi k6 di laptop/PC lokal Anda**, kemudian arahkan targetnya ke IP/Domain VPS aaPanel Anda.
> 
> Jika Anda menjalankan k6 langsung di dalam VPS yang sama (lewat terminal aaPanel), k6 akan menghabiskan CPU & RAM VPS Anda untuk membuat robot virtual. Akibatnya, server akan crash bukan karena website Anda berat, melainkan karena k6 berebut sumber daya dengan PHP/MySQL.

---

## Langkah 1: Install k6 di Laptop/PC Lokal Anda

Silakan install k6 di perangkat lokal Anda sesuai sistem operasi yang Anda gunakan:

*   **macOS (via Homebrew):**
    ```bash
    brew install k6
    ```
*   **Windows (via Chocolatey):**
    ```bash
    choco install k6
    ```
    *(Atau unduh installer resminya di [k6.io](https://k6.io/docs/get-started/installation/))*
*   **Linux (Ubuntu/Debian):**
    ```bash
    sudo gpg --no-default-keyring --keyring /usr/share/keyrings/k6-archive-keyring.gpg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C5D5E997615694C7
    echo "deb [signed-by=/usr/share/keyrings/k6-archive-keyring.gpg] https://dl.k6.io/deb stable main" | sudo tee /etc/apt/sources.list.d/k6.list
    sudo apt-get update && sudo apt-get install k6
    ```

---

## Langkah 2: Siapkan File Skrip Pengujian

Buat file baru di komputer lokal Anda dengan nama `cbt_stress_test.js` dan isi dengan kode berikut:

```javascript
import http from 'k6/http';
import { sleep, check } from 'k6';

// 1. Konfigurasi beban uji (Simulasi 100 User)
export const options = {
    stages: [
        { duration: '1m', target: 100 }, // Naikkan jumlah user perlahan sampai 100 dalam 1 menit
        { duration: '3m', target: 100 }, // Pertahankan 100 user aktif selama 3 menit
        { duration: '1m', target: 0 },   // Turunkan kembali jumlah user ke 0
    ],
};

// GANTI DENGAN IP VPS ATAU DOMAIN WEBSITE CBT ANDA
const BASE_URL = 'http://IP_VPS_AAPANEL_ANDA:8000'; 

export default function () {
    const uniqueId = __VU + '-' + __ITER;
    const name = `Robot User ${uniqueId}`;
    const email = `robot_${uniqueId}@gmail.com`;
    const password = 'password123';
    const school = 'SMA Negeri Simulator';

    // ---- A. PROSES DAFTAR AKUN ----
    // Ambil halaman register untuk simulasi biasa
    http.get(`${BASE_URL}/register`);
    
    // Kirim request pendaftaran
    let regPayload = {
        name: name,
        email: email,
        password: password,
        password_confirmation: password,
        school: school,
    };
    
    let resRegister = http.post(`${BASE_URL}/register`, regPayload);
    check(resRegister, {
        'Register berhasil (302/200)': (r) => r.status === 200 || r.status === 302,
    });

    sleep(3); // Jeda seolah-olah membaca instruksi dashboard

    // ---- B. PROSES MULAI UJIAN ----
    // Sesuaikan ID sesi ujian aktif di server Anda
    let startPayload = {
        exam_session_id: 1, 
        token: 'TOKEN123',  
    };
    
    let resStart = http.post(`${BASE_URL}/exam/start`, startPayload);
    check(resStart, {
        'Mulai Ujian berhasil (302/200)': (r) => r.status === 200 || r.status === 302,
    });

    sleep(3); // Jeda loading halaman soal

    // ---- C. PROSES MENJAWAB SOAL (Simulasi 10 Kali Klik Jawaban) ----
    // Asumsikan robot menjawab soal ID 1 sampai 10 secara bergantian
    for (let questionId = 1; questionId <= 10; questionId++) {
        let answerPayload = {
            question_id: questionId,
            option_id: Math.floor(Math.random() * 4) + 1, // Pilih opsi acak (1-4)
        };

        // Ganti angka /1/ di url berikut sesuai dengan ID ExamResult yang sedang aktif 
        let resAnswer = http.post(`${BASE_URL}/exam/1/save-answer`, answerPayload);
        
        check(resAnswer, {
            'Jawaban Tersimpan (200)': (r) => r.status === 200,
        });

        // Jeda membaca soal sebelum lanjut (acak 5 - 15 detik)
        sleep(Math.floor(Math.random() * 10) + 5); 
    }
}
```

---

## Langkah 3: Cara Menjalankan Pengujian

1. Hubungkan laptop Anda ke koneksi internet yang stabil.
2. Buka terminal/command prompt di komputer lokal Anda, lalu masuk ke direktori tempat file `cbt_stress_test.js` disimpan.
3. Jalankan perintah berikut:
   ```bash
   k6 run cbt_stress_test.js
   ```

---

## Langkah 4: Apa yang Harus Dipantau di aaPanel VPS?

Sembari k6 membombardir server Anda dengan 100 robot virtual, buka panel **aaPanel** di browser Anda dan pantau bagian berikut:

1. **Dashboard Utama (CPU & RAM):**
   * Perhatikan grafik penggunaan CPU. Jika CPU tetap di bawah 80%, berarti 2 Core VPS Anda masih sangat aman.
   * Perhatikan pemakaian RAM. Dengan optimasi cache Redis, pemakaian RAM harusnya stabil dan tidak melonjak drastis.
2. **Resource Monitor (Load Average):**
   * Cek angka load average server Anda. Jika angkasa di bawah `2.00` (untuk 2 Core CPU), berarti server tidak mengalami kemacetan antrean sistem.
3. **Log Error (Nginx & PHP):**
   * Masuk ke aaPanel -> **Files** -> buka berkas log error di `storage/logs/laravel.log`. Pastikan tidak ada error *Lock Wait Timeout* database atau error fatal lainnya.
