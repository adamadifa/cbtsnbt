# Panduan Deployment Aplikasi CBT di aaPanel

Dokumen ini berisi panduan lengkap langkah-demi-langkah untuk melakukan deployment aplikasi CBT berbasis Laravel 11, Vite, dan Laravel Reverb (WebSockets) pada server VPS menggunakan control panel **aaPanel**.

---

## 1. Persiapan Server & Software di aaPanel

Sebelum mengunggah kode, pastikan modul/software berikut telah terinstal melalui **App Store** di aaPanel Anda:
* **Nginx** (Direkomendasikan versi 1.22 atau terbaru)
* **MySQL** (Direkomendasikan versi 5.7 atau 8.0)
* **PHP-8.3** (Pastikan versi PHP minimal 8.2/8.3)
* **Redis** (Opsional, untuk caching/queue yang lebih cepat)
* **Supervisor** (Wajib, untuk menjalankan Queue Worker dan Laravel Reverb di background)

### Ekstensi PHP yang Wajib Diaktifkan:
Masuk ke menu **App Store** -> Klik **PHP 8.3** -> **Install Extensions** -> Pastikan ekstensi berikut terinstal:
* `redis`
* `fileinfo`
* `opcache`

---

## 2. Membuat Website Baru di aaPanel

1. Masuk ke menu **Website** -> Klik **Add Site**.
2. Masukkan nama domain Anda (contoh: `cbt.domainanda.com`).
3. Pilih **Database** -> Pilih **MySQL** (buat database dan user baru secara langsung, lalu catat detailnya).
4. Klik **Submit**.

---

## 3. Unggah dan Konfigurasi Project

1. Buka menu **Files** di aaPanel dan arahkan ke folder website Anda (`/www/wwwroot/cbt.domainanda.com`).
2. Hapus file bawaan default (`index.html`, `.htaccess`, dll.).
3. Tarik/unggah project CBT Anda (atau gunakan fitur **Git** di terminal/aaPanel untuk melakukan clone repository).
4. Pastikan file `.env` diisi dengan benar:
   ```env
   APP_NAME="CBT SNBT"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://cbt.domainanda.com

   LOG_CHANNEL=stack
   LOG_LEVEL=error

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=user_database_anda
   DB_PASSWORD=password_database_anda

   # Laravel Reverb (WebSockets)
   REVERB_APP_ID=some_app_id
   REVERB_APP_KEY=some_app_key
   REVERB_APP_SECRET=some_app_secret
   REVERB_HOST="cbt.domainanda.com" # Gunakan subdomain/domain Anda
   REVERB_PORT=443
   REVERB_SCHEME=https

   VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
   VITE_REVERB_HOST="${VITE_REVERB_HOST}"
   VITE_REVERB_PORT="${VITE_REVERB_PORT}"
   VITE_REVERB_SCHEME="${VITE_REVERB_SCHEME}"
   ```

---

## 4. Konfigurasi SSL & HTTPS

1. Masuk ke menu **Website** -> Klik nama domain website Anda -> Pilih tab **SSL**.
2. Centang domain Anda, lalu gunakan **Let's Encrypt** untuk mendapatkan sertifikat SSL gratis.
3. Klik **Apply** dan aktifkan **Force HTTPS**.

---

## 5. Konfigurasi Nginx di aaPanel

Agar Laravel berjalan dengan benar (menghilangkan error 404 pada route) dan agar WebSockets Reverb dapat bekerja melalui port HTTPS standar (Reverse Proxy), Anda perlu mengubah konfigurasi Nginx situs Anda.

1. Klik domain website Anda -> Pilih tab **Site Directory**.
2. Ubah **Running Directory** dari `/` menjadi **/public**, lalu klik **Save**.
3. Pilih tab **URL Rewrite**, pilih template **laravel**, lalu klik **Save**.
4. Pilih tab **Config** (Konfigurasi Nginx mentah) dan tambahkan blok Reverse Proxy untuk Laravel Reverb (WebSockets) di dalam blok server utama sebelum baris penutup `access_log`:

```nginx
# Proxy untuk Laravel Reverb WebSockets
location /app {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "Upgrade";
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
}
```
*Catatan: Port `8080` adalah port default lokal tempat Laravel Reverb akan dijalankan oleh Supervisor.*

---

## 6. Instalasi via Terminal (Composer & NPM)

Buka **Terminal** di aaPanel Anda (atau gunakan SSH) dan jalankan perintah berikut di dalam direktori project Anda (`/www/wwwroot/cbt.domainanda.com`):

```bash
# 1. Install dependensi PHP
composer install --no-dev --optimize-autoloader

# 2. Generate Application Key (jika belum ada di .env)
php artisan key:generate

# 3. Jalankan Migrasi Database dan Seeders data awal
php artisan migrate --force
php artisan db:seed --force

# 4. Atur Permission Folder Storage dan Cache
chmod -R 775 storage bootstrap/cache
chown -R www:www .

# 5. Install dependensi JS & Build Aset Frontend
npm install
npm run build
```

---

## 7. Konfigurasi Supervisor (Background Process)

Untuk memastikan sistem antrean ujian (Queue) dan server Realtime Monitor (Reverb) berjalan terus-menerus di server, instal **Supervisor** dari App Store aaPanel, lalu buat dua task baru:

### Task 1: Laravel Queue Worker
* **Name**: `cbt-queue-worker`
* **Run User**: `www`
* **Run Directory**: `/www/wwwroot/cbt.domainanda.com`
* **Start Command**: `php artisan queue:work --sleep=3 --tries=3 --max-time=3600`
* **Processes**: `1`

### Task 2: Laravel Reverb Server (WebSockets)
* **Name**: `cbt-reverb`
* **Run User**: `www`
* **Run Directory**: `/www/wwwroot/cbt.domainanda.com`
* **Start Command**: `php artisan reverb:start --host=127.0.0.1 --port=8080`
* **Processes**: `1`

Setelah kedua task tersebut dibuat, klik **Start** pada masing-masing task di menu Supervisor.

---

## 8. Optimasi Performa (Opsional - Production Mode)

Jalankan perintah ini di terminal project untuk mempercepat loading website:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Aplikasi CBT Anda sekarang telah sepenuhnya online dan siap digunakan di aaPanel dengan koneksi HTTPS aman dan sistem realtime monitor!
