# SyahriahManage - Sistem Manajemen Keuangan Pesantren

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  Aplikasi web modern untuk mengelola pembayaran syahriah (SPP) dan administrasi keuangan di lingkungan pondok pesantren.
</p>

---

## ✨ Fitur Utama

- **🔐 Keamanan & Otentikasi Tingkat Lanjut**:
  - **Login via Google**: Pendaftaran dan login sekali klik menggunakan akun Google.
  - **Verifikasi Email Wajib**: Pengguna baru harus memverifikasi alamat email mereka sebelum dapat mengakses sistem.
  - **Alur Persetujuan Admin**: Setiap pendaftaran baru (manual atau Google) masuk ke dalam status `pending` dan harus disetujui oleh Admin, yang kemudian dapat menetapkan peran (Role) yang sesuai.
  - **Manajemen Peran (Role)**: Sistem peran yang fleksibel (Admin, Bendahara, Wali Santri) untuk membatasi akses fitur.

- **💰 Manajemen Keuangan Komprehensif**:
  - **Manajemen Santri & Biaya**: Kelola profil santri dan definisikan berbagai jenis biaya.
  - **Proses Pembayaran**: Catat dan lacak pembayaran syahriah dengan riwayat transaksi yang detail.
  - **Manajemen Tunggakan**: Lacak dan kelola pembayaran santri yang tertunggak secara otomatis.
  - **Manajemen Kas**: Pantau arus kas masuk dan keluar dari berbagai rekening (misalnya, Bank, Tunai).

- **📊 Pelaporan & Notifikasi Modern**:
  - **Ekspor Laporan**: Hasilkan laporan keuangan (bulanan, tahunan, tunggakan) dalam format PDF dan Excel.
  - **Notifikasi Real-time**: Admin menerima notifikasi (email dan di dalam aplikasi) setiap kali ada pendaftaran pengguna baru.
  - **UI/UX Modern**: Antarmuka yang bersih dan responsif dengan notifikasi interaktif menggunakan **SweetAlert2**.

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 9, PHP 8+
- **Frontend**: Vite, Bootstrap 5, CSS Kustom
- **Database**: MySQL / MariaDB
- **Paket Utama**:
  - `laravel/socialite`: Untuk otentikasi sosial (Google Login).
  - `barryvdh/laravel-dompdf`: Untuk ekspor PDF.
  - `maatwebsite/excel`: Untuk ekspor Excel.

## 🚀 Instalasi dan Konfigurasi

1.  **Clone Repository**:
    ```bash
    git clone https://your-repository-url.git
    cd syahriahManage
    ```

2.  **Install Dependensi**:
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment (.env)**:
    - Salin file contoh: `cp .env.example .env`
    - Buat kunci aplikasi: `php artisan key:generate`

4.  **Update File `.env` Anda**:
    - **Database**: Atur koneksi database Anda.
      ```
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=nama_database_anda
      DB_USERNAME=root
      DB_PASSWORD=
      ```
    - **Email (Penting untuk Verifikasi & Notifikasi)**: Konfigurasikan SMTP driver Anda (contoh menggunakan Gmail).
      ```
      MAIL_MAILER=smtp
      MAIL_HOST=smtp.gmail.com
      MAIL_PORT=587
      MAIL_USERNAME=alamat.email.anda@gmail.com
      MAIL_PASSWORD=app_password_anda
      MAIL_ENCRYPTION=tls
      MAIL_FROM_ADDRESS="${MAIL_USERNAME}"
      MAIL_FROM_NAME="${APP_NAME}"
      ```
      > **Catatan**: Untuk `MAIL_PASSWORD`, gunakan [Google App Password](https://support.google.com/accounts/answer/185833), bukan password utama Gmail Anda.

    - **Google Login (Opsional, tapi direkomendasikan)**: Tambahkan kredensial Google OAuth Anda.
      ```
      GOOGLE_CLIENT_ID=client_id_anda_dari_google_cloud
      GOOGLE_CLIENT_SECRET=client_secret_anda_dari_google_cloud
      GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
      ```

5.  **Migrasi Database**:
    ```bash
    php artisan migrate
    ```
    > Jalankan `--seed` jika Anda memiliki seeder untuk data awal: `php artisan migrate --seed`

6.  **Build Aset Frontend**:
    ```bash
    npm run build
    ```

## ▶️ Menjalankan Aplikasi

1.  **Jalankan Vite Server**:
    ```bash
    npm run dev
    ```

2.  **Di terminal terpisah, jalankan PHP Server**:
    ```bash
    php artisan serve
    ```

Buka aplikasi di browser Anda: `http://127.0.0.1:8000`.

## 📄 Lisensi

Proyek ini berlisensi di bawah [Lisensi MIT](https://opensource.org/licenses/MIT).
