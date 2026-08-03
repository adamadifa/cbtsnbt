# Panduan Optimasi VPS untuk Ujian Online (100+ Concurrent Students)

Panduan ini berisi langkah-langkah optimasi server (VPS) yang menggunakan control panel seperti **aaPanel** untuk melayani **100+ siswa yang menempuh ujian secara bersamaan (concurrent users)** dengan lancar dan aman.

---

## 1. Rekomendasi Spesifikasi VPS

Ujian online (CBT) bersifat **Write-Heavy** (menulis jawaban ke database secara berkala melalui autosave). Disarankan menggunakan VPS SSD/NVMe dengan spesifikasi:

* **Spesifikasi Minimum (RAM 4GB):** 2 Core CPU, 4 GB RAM, SSD Storage (Wajib SSD).
* **Spesifikasi Rekomendasi (RAM 8GB):** 4 Core CPU, 8 GB RAM, NVMe SSD Storage.

---

## 2. Optimasi PHP-FPM (Sangat Penting)

Proses default PHP-FPM di aaPanel biasanya sangat kecil (max_children = 20-30). Jika ada 100 siswa mengakses secara bersamaan, server akan menghasilkan error **502 Bad Gateway**.

Ubah pengaturan PHP-FPM (masuk ke aaPanel -> **App Store** -> **PHP-8.3** -> **Setting** -> **FPM Profile / PHP-FPM Setting**):

```ini
pm = dynamic
pm.max_children = 100
pm.start_servers = 15
pm.min_spare_servers = 10
pm.max_spare_servers = 30
pm.max_requests = 1000
```

> [!NOTE]
> Nilai `pm.max_children = 100` berarti server siap melayani hingga 100 request PHP secara paralel. Sesuaikan dengan RAM:
> * VPS RAM 4GB: Set `pm.max_children = 70`
> * VPS RAM 8GB: Set `pm.max_children = 120`

---

## 3. Optimasi Database MySQL / MariaDB

Ubah pengaturan MySQL/MariaDB melalui aaPanel (**App Store** -> **MySQL** -> **Setting** -> **Configuration**):

### A. Alokasi Buffer Pool (RAM)
Sesuaikan `innodb_buffer_pool_size` untuk menampung data indeks di RAM.
* VPS RAM 4GB: `innodb_buffer_pool_size = 1.5G`
* VPS RAM 8GB: `innodb_buffer_pool_size = 3.5G`

### B. Jumlah Koneksi Maksimum
* `max_connections = 300` (untuk menghindari error *Too many connections*)

---

## 4. Optimasi Aplikasi Laravel (.env)

Edit file `.env` di server production untuk mengoptimalkan penanganan session dan caching:

```env
APP_ENV=production
APP_DEBUG=false

# Gunakan redis atau database agar penanganan session tidak membebani Disk I/O file
SESSION_DRIVER=database
CACHE_STORE=database
```

Setelah memperbarui kode program, jalankan perintah optimasi berikut di terminal VPS:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 5. Menjalankan Laravel Reverb (Realtime WebSocket)

Laravel Reverb sangat efisien karena berjalan secara asinkron (ReactPHP). Di server production, Reverb harus berjalan sebagai background process yang dipantau oleh **Supervisor**.

Buat file konfigurasi Supervisor di server (misal `/etc/supervisor/conf.d/reverb.conf`):

```ini
[program:laravel-reverb]
process_name=%(program_name)s_%(process_num)02d
command=php /www/wwwroot/cbt/artisan reverb:start
autostart=true
autorestart=true
user=www
numprocs=1
redirect_stderr=true
stdout_logfile=/www/wwwroot/cbt/storage/logs/reverb.log
stopasgroup=true
killasgroup=true
```

Jalankan perintah berikut untuk mengaktifkan:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-reverb:*
```
