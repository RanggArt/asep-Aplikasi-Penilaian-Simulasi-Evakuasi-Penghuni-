# Deploy Laravel ke cPanel

Target aplikasi: `https://cegah.jakartafsc.com`

Saya belum mengunggah aplikasi karena workspace ini tidak memiliki koneksi ke akun cPanel. Ikuti langkah berikut untuk men-deploy dengan aman.

## 1. Siapkan build lokal

Jalankan dari folder project:

```sh
npm ci
npm run build
```

Pastikan folder `public/build` ikut terunggah. Jangan unggah `.env`, `.git`, `node_modules`, atau `storage/logs`.

## 2. Atur subdomain dan document root

Di cPanel, buat subdomain `cegah.jakartafsc.com` dan atur document root ke folder `public` aplikasi Laravel. Contoh susunan:

```text
/home/CPANEL_USER/asep-app/        # seluruh source Laravel, .env, vendor, storage
/home/CPANEL_USER/asep-app/public/ # document root cegah.jakartafsc.com
```

Jangan jadikan folder utama Laravel sebagai document root; hanya folder `public` yang boleh diakses web. Jika cPanel tidak mengizinkan document root di luar `public_html`, minta hosting mengatur virtual host agar menunjuk ke folder `asep-app/public`.

## 3. Siapkan PHP, Composer, dan database

- Pilih PHP 8.1 atau lebih baru untuk subdomain di **cPanel → Software → MultiPHP Manager**. Aktifkan ekstensi yang diperlukan Laravel, termasuk `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `curl`, dan `xml`.
- Buat database MySQL dan user database di cPanel, lalu berikan user tersebut hak akses ke database.
- Upload source Laravel ke folder di atas document root. Upload `public/build` setelah langkah build lokal.
- Jika tersedia Terminal cPanel, jalankan `composer install --no-dev --optimize-autoloader` dari folder project.

## 4. Isi konfigurasi produksi

Buat `.env` di folder utama Laravel di server. Jangan memakai `.env` lokal atau mengunggahnya. Isi nilai produksi, contoh:

```dotenv
APP_NAME="APEM Sudin Gulkarmat"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://cegah.jakartafsc.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=CPANEL_PREFIX_database
DB_USERNAME=CPANEL_PREFIX_user
DB_PASSWORD="PASSWORD_DATABASE"
DB_PREFIX=apem_

SESSION_DRIVER=file
SESSION_SECURE_COOKIE=true
FILESYSTEM_DISK=public

GOOGLE_CLIENT_ID=CLIENT_ID_DARI_GOOGLE_CLOUD
GOOGLE_CLIENT_SECRET=CLIENT_SECRET_DARI_GOOGLE_CLOUD
GOOGLE_REDIRECT_URI=https://cegah.jakartafsc.com/auth/google/callback
```

Ganti semua nilai contoh dengan nilai dari hosting. Jangan mengirim atau menyimpan password database maupun client secret ke Git.

`DB_PREFIX=apem_` membuat tabel aplikasi ini terpisah dari tabel yang sudah ada di database yang sama. Contohnya, Laravel akan menggunakan `apem_users`, `apem_migrations`, dan `apem_apems`. Jangan kosongkan prefix untuk database yang sudah berisi aplikasi lain.

Di Google Cloud Console, tambahkan `https://cegah.jakartafsc.com` sebagai authorized JavaScript origin dan `https://cegah.jakartafsc.com/auth/google/callback` sebagai authorized redirect URI pada OAuth client yang dipakai aplikasi.

## 5. Jalankan perintah Laravel

Dari folder utama Laravel di Terminal cPanel:

```sh
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize
```

Pastikan folder `storage` dan `bootstrap/cache` dapat ditulis oleh PHP. Upload berkas formulir tersimpan di `storage/app/public`; `storage:link` membuat berkas tersebut tersedia melalui `public/storage`.

Seeder membuat akun berikut untuk panel admin:

| Role | ID login | Password awal |
| --- | --- | --- |
| Admin | `175254` | `123456` |
| Super admin | `176096` | `123456` |

Login admin menerima ID tersebut atau email. Ganti password awal setelah deployment.

## 6. Batas upload

Form APEM menerima hingga 10 berkas, masing-masing maksimal 10 MB. Atur batas PHP untuk subdomain melalui **MultiPHP INI Editor** agar `upload_max_filesize` minimal `10M`, `post_max_size` minimal `110M`, dan `max_file_uploads` minimal `10`.

## 7. Periksa setelah deploy

- Buka `https://cegah.jakartafsc.com` dan pastikan SSL aktif.
- Uji login admin dan super admin, logout, login Google, pengiriman formulir, akses dokumen, dan perubahan status.
- Pastikan `APP_DEBUG=false` tetap berlaku. Jika ada error, periksa `storage/logs/laravel.log` di server dan hapus kredensial sebelum membagikan log.

Catatan: document root harus mengarah ke `public`; ini sesuai panduan deployment Laravel. Versi PHP per domain dapat diatur melalui MultiPHP Manager cPanel.
