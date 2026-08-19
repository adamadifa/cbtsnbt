# Panduan & TODO List: Perbaikan & Optimasi Server CBT (100+ Siswa)

Dokumen ini berisi daftar tugas dan penjelasan teknis mengenai optimasi kode untuk mengatasi server lambat (*lag/lola*) saat digunakan oleh 115+ siswa secara bersamaan.

---

## 📊 Analisis Masalah Utama (Mengapa Server Lola?)

1. **Sinkronisasi WebSocket (`ShouldBroadcastNow`):** Setiap klik jawaban siswa mengirim sinyal ke admin saat itu juga. PHP terpaksa menunggu koneksi WebSocket selesai terkirim sebelum membalas ke browser siswa. Ini memblokir prosesor server.
2. **Kalkulasi Skor Berulang:** Database dipaksa melakukan fungsi penjumlahan nilai (`SUM`) untuk seluruh jawaban dan melakukan `UPDATE` tabel setiap kali siswa mengisi satu soal.
3. **Overload Request Admin:** Browser admin melakukan request AJAX penuh (meminta seluruh halaman HTML baru) terus-menerus setiap kali ada siswa yang menjawab soal, mirip seperti membanjiri server sendiri (*DDoS internal*).

---

## 🛠️ TODO List Perbaikan Kode & Efeknya

### 1. Optimasi Controller Ujian Siswa (`app/Http/Controllers/Student/ExamController.php`)
Mengurangi beban tulis (Disk Write I/O) database secara drastis saat ujian berlangsung.

*   [ ] **Hapus kalkulasi skor otomatis pada method `saveAnswer`:**
    *   *Penjelasan:* Baris `StudentAnswer::where(...)->sum('points')` dihapus dari method simpan jawaban.
    *   *Tujuan:* Mencegah database melakukan pencarian dan penjumlahan berulang-ulang untuk setiap klik jawaban.
    *   *Efek Setelah Diubah:* Beban query baca/penjumlahan database menjadi nol selama ujian. Respon penyimpanan jawaban menjadi jauh lebih cepat.
*   [ ] **Hapus pembaruan field `total_score` di tabel `exam_results` saat menyimpan jawaban:**
    *   *Penjelasan:* Baris `$examResult->update(['total_score' => $totalScore])` dihapus dari method `saveAnswer`.
    *   *Tujuan:* Menghilangkan operasi `UPDATE` pada baris hasil ujian selama siswa masih mengerjakan soal.
    *   *Efek Setelah Diubah:* Menghilangkan antrean penguncian baris database (*row lock*) di tabel `exam_results`. MySQL tidak akan kelebihan beban antrean koneksi.
*   [ ] **Hapus pemanggilan `event(new MonitorExamUpdated(...))` di method `saveAnswer`:**
    *   *Penjelasan:* Menghentikan pengiriman sinyal WebSocket untuk setiap klik jawaban siswa.
    *   *Tujuan:* Mencegah kemacetan antrean HTTP request siswa.
    *   *Efek Setelah Diubah:* Menghilangkan beban jaringan HTTP siswa ke server WebSocket untuk setiap klik. Siswa mendapatkan respon "Jawaban Tersimpan" di bawah 50ms (instan).
*   [ ] **Pastikan kalkulasi skor tetap berjalan di method `finish`:**
    *   *Penjelasan:* Skor akhir tetap dihitung dan disimpan saat siswa secara resmi menekan tombol "Selesai Ujian".
    *   *Tujuan:* Memastikan nilai akhir siswa tetap akurat dan tersimpan di akhir ujian.
    *   *Efek Setelah Diubah:* Nilai ujian siswa tetap 100% akurat saat mereka selesai, tanpa perlu membebani server sepanjang waktu pengerjaan ujian.

### 2. Optimasi Pengiriman Event Realtime (`app/Events/MonitorExamUpdated.php`)
Membuat pengiriman WebSocket berjalan di latar belakang (Background Process).

*   [ ] **Ubah implementasi `ShouldBroadcastNow` menjadi `ShouldBroadcast`:**
    *   *Penjelasan:* Mengganti interface event agar Laravel memasukkan proses kirim data WebSocket ke dalam sistem antrean (*Queue*).
    *   *Tujuan:* Agar siswa langsung mendapatkan respon tanpa harus menunggu proses kirim data WebSocket selesai.
    *   *Efek Setelah Diubah:* PHP-FPM tidak lagi diblokir oleh proses pengiriman data WebSocket. Seluruh sisa sirkulasi pengiriman event monitoring admin akan dikerjakan di latar belakang secara asinkron.
*   [ ] **Pastikan server menjalankan queue driver & worker:**
    *   *Penjelasan:* Memastikan VPS menjalankan perintah `php artisan queue:work` secara terus-menerus (bisa menggunakan Supervisor).
    *   *Tujuan:* Memproses tugas-tugas antrean pengiriman WebSocket di latar belakang.
    *   *Efek Setelah Diubah:* Data aktivitas penting (seperti pelanggaran siswa atau ujian selesai) tetap terkirim ke halaman admin dengan aman tanpa mengorbankan kecepatan akses siswa.

### 3. Proteksi Halaman Dashboard Monitoring Admin (`resources/views/admin/exam-sessions/show.blade.php`)
Mengurangi beban request AJAX dari browser admin ke server.

*   [ ] **Tambahkan mekanisme Throttling / Debouncing pada JavaScript:**
    *   *Penjelasan:* Membatasi fungsi `fetchMonitorData()` agar hanya bisa mengirim request ke server maksimal sekali setiap 10 atau 15 detik.
    *   *Tujuan:* Mencegah browser admin mengirim puluhan request render HTML berat ke server dalam waktu yang sangat singkat.
    *   *Efek Setelah Diubah:* Server terproteksi dari overload akibat render HTML dashboard monitor yang dipicu secara bertubi-tubi ketika banyak siswa menjawab soal secara bersamaan.

### 4. Optimasi Pemuatan Data Halaman Ujian Siswa (`app/Http/Controllers/Student/ExamController.php`)
Mengurangi penggunaan RAM server secara signifikan saat 100+ siswa membuka halaman ujian.

*   [ ] **Kurangi eager loading pada method `show` agar hanya memuat subtest yang sedang aktif:**
    *   *Penjelasan:* Saat ini baris `$examResult->examSession()->with('examPackage.subtests.questions.options')->first()` memuat **seluruh** subtest, soal, dan opsi jawaban ke dalam memori RAM, padahal siswa hanya mengerjakan **satu subtest** pada satu waktu. Contoh: jika paket ujian punya 5 subtest × 40 soal × 5 opsi = **1.000 record** dimuat per siswa. Dengan 115 siswa = **115.000 record** dimuat bersamaan ke RAM server.
    *   *Tujuan:* Hanya memuat data subtest yang sedang dikerjakan siswa saat itu, bukan seluruh paket ujian.
    *   *Efek Setelah Diubah:* Penggunaan RAM server turun drastis (hingga 80%) karena hanya memuat data yang benar-benar dibutuhkan. Halaman ujian siswa terbuka lebih cepat dan server mampu menampung lebih banyak koneksi bersamaan.

### 5. Hapus Validasi `exists` yang Redundan pada `saveAnswer` (`app/Http/Controllers/Student/ExamController.php`)
Mengurangi jumlah query database yang tidak perlu saat menyimpan jawaban.

*   [ ] **Hapus rule validasi `exists:questions,id` pada method `saveAnswer`:**
    *   *Penjelasan:* Rule `'question_id' => 'required|exists:questions,id'` menyebabkan Laravel melakukan query `SELECT` tambahan ke tabel `questions` untuk mengecek apakah ID tersebut ada. Padahal, di baris berikutnya sudah ada kode `Question::with('options')->findOrFail($request->question_id)` yang secara otomatis akan mengembalikan error 404 jika soal tidak ditemukan. Jadi validasi `exists` ini **duplikat** dan membuang 1 query database per penyimpanan jawaban.
    *   *Tujuan:* Menghilangkan 1 query database yang tidak perlu di setiap proses simpan jawaban.
    *   *Efek Setelah Diubah:* Mengurangi 1 query `SELECT` ke database per klik jawaban siswa. Dengan 115 siswa × rata-rata 100 soal = **11.500 query database** yang bisa dihemat dalam satu sesi ujian.

### 6. Optimasi Driver Session pada `.env` (`.env`)
Mengurangi beban tulis session database yang terjadi di setiap request siswa.

*   [ ] **Ubah `SESSION_DRIVER=database` menjadi `SESSION_DRIVER=file` atau `redis`:**
    *   *Penjelasan:* Saat ini driver session di `.env` diset ke `database`. Artinya, setiap kali siswa memuat halaman atau menyimpan jawaban, Laravel dipaksa melakukan query `UPDATE` ke tabel `sessions` untuk memperbarui waktu aktivitas (*timestamp*). Dengan 115 siswa aktif, ini membuat ribuan query `UPDATE` tidak penting menumpuk di database.
    *   *Tujuan:* Menghilangkan penulisan session dari database ke sistem file server (RAM/Disk) yang jauh lebih cepat.
    *   *Efek Setelah Diubah:* Mengurangi beban tulis database secara konstan untuk aktivitas penjejakan session. VPS menjadi jauh lebih stabil karena MySQL hanya fokus memproses data jawaban ujian.

