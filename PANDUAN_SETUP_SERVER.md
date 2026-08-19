# Panduan Setup & Optimasi VPS Ujian Online (100+ Concurrent Students)

Dokumen ini berisi panduan konfigurasi yang harus Anda terapkan secara langsung pada server VPS production (aaPanel/Ubuntu) untuk menyelaraskan perubahan kode optimasi terbaru.

---

## 1. Pembaruan Konfigurasi `.env`

Buka file `.env` di folder root Laravel server Anda, lalu perbarui parameter berikut:

```env
# 1. Alihkan Session dari database ke Redis (Sangat direkomendasikan) atau File
SESSION_DRIVER=redis

# 2. Alihkan Cache dari database ke Redis
CACHE_STORE=redis

# 3. Gunakan phpredis (driver bawaan ekstensi PHP aaPanel)
REDIS_CLIENT=phpredis

# 4. Pastikan Queue menggunakan Driver Database
QUEUE_CONNECTION=database
```

---

## 2. Cara Menginstal & Menerapkan Redis di aaPanel

Jika Anda ingin menggunakan **Redis** (sangat direkomendasikan untuk performa terbaik), ikuti langkah instalasi berikut di aaPanel:

### Langkah A: Instalasi Redis Server
1. Masuk ke panel **aaPanel** Anda.
2. Buka menu **App Store** di bilah sisi kiri.
3. Cari kata kunci **"Redis"** pada kolom pencarian.
4. Klik tombol **Install** di sebelah kanan aplikasi Redis. Pilih mode instalasi cepat (*Fast*) dan tunggu hingga prosesnya selesai.

### Langkah B: Instalasi Ekstensi PHP Redis
Agar aplikasi Laravel (PHP) dapat berkomunikasi dengan Redis server, Anda wajib mengaktifkan ekstensinya:
1. Di **App Store** aaPanel, cari versi PHP yang Anda gunakan (contoh: **PHP-8.3**).
2. Klik tombol **Setting** di sebelah kanan PHP tersebut.
3. Pada jendela popup yang muncul, klik menu **Install extensions** di sebelah kiri.
4. Cari ekstensi bernama **"redis"**, lalu klik tombol **Install** di sebelah kanannya.
5. Tunggu proses instalasi ekstensi selesai (biasanya memakan waktu 1-2 menit).

Setelah Langkah A dan B selesai, Redis siap digunakan dan Anda tinggal mengubah konfigurasi `.env` ke `redis` seperti contoh di atas.

---


## 2. Menjalankan Queue Worker di Background (Wajib)

Karena event real-time sekarang menggunakan sistem antrean (`ShouldBroadcast`), Anda **wajib** menjalankan worker antrean di latar belakang server.

### A. Cara Menjalankan via Terminal (Untuk Test Sementara)
Jalankan perintah ini di terminal root folder aplikasi Anda:
```bash
php artisan queue:work --queue=default --tries=3
```

### B. Cara Menjalankan Permanen via Supervisor (Sangat Direkomendasikan)
Buat berkas konfigurasi baru di VPS Anda (misalnya `/etc/supervisor/conf.d/laravel-worker.conf`):

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /www/wwwroot/nama-folder-cbt-anda/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www
numprocs=2
redirect_stderr=true
stdout_logfile=/www/wwwroot/nama-folder-cbt-anda/storage/logs/worker.log
stopwaitsecs=3600
```
> [!IMPORTANT]  
> Ubah `/www/wwwroot/nama-folder-cbt-anda/` sesuai dengan letak folder project aplikasi CBT Anda di server.

Setelah file disimpan, jalankan perintah ini di terminal server:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

---

## 3. Optimasi PHP-FPM (Melalui aaPanel)

Masuk ke aaPanel -> **App Store** -> **PHP-8.3** -> **Setting** -> **PHP-FPM Setting / FPM Profile** -> Ubah konfigurasinya menjadi:

```ini
pm = dynamic
pm.max_children = 100
pm.start_servers = 15
pm.min_spare_servers = 10
pm.max_spare_servers = 30
pm.max_requests = 1000
```
*(Nilai `max_children = 100` disesuaikan untuk kapasitas server RAM 8GB dengan CPU 2 Core agar tidak terjadi overload proses).*

---

## 4. Perintah Pembersihan Cache Ujian

Setelah seluruh kode di-upload dan `.env` diperbarui, jalankan rangkaian perintah ini secara berurutan di terminal server (folder project) untuk membersihkan cache lama:

```bash
# 1. Bersihkan cache konfigurasi, route, dan view
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Hapus cache data yang lama di aplikasi (termasuk cache list kampus lama)
php artisan cache:clear

# 3. Restart process queue agar membaca perubahan kode yang baru
php artisan queue:restart
```
