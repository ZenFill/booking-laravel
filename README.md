# StudioBook - Sistem Booking Ruangan & Studio 🎸⚽💼

StudioBook adalah aplikasi manajemen pemesanan ruangan berbasis web yang modern dan responsif. Aplikasi ini dirancang untuk memudahkan pengelolaan jadwal sewa Studio Musik, Lapangan Futsal, dan Ruang Meeting.

![StudioBook Dashboard Screenshot](screenshots/dashboard.jpg)

## 🚀 Fitur Utama

-   **Booking Online Mudah**: Pengguna dapat memilih ruangan, tanggal, dan waktu dengan antarmuka yang intuitif.
-   **Dashboard Interaktif**: Statistik booking, status pending/approved, dan ringkasan jadwal dalam satu tampilan.
-   **Kalender Jadwal**: Tampilan kalender (FullCalendar) untuk melihat ketersediaan ruangan secara visual.
-   **Manajemen Status**: Admin dapat menyetujui (Approve) atau menolak (Reject) pesanan.
-   **Notifikasi Email**: Sistem otomatis mengirim email notifikasi saat booking dibuat dan saat status berubah.
-   **Laporan PDF**: Fitur export laporan booking ke PDF dengan filter otomatis (Admin: Semua Data, User: Data Pribadi).
-   **Upload Bukti Bayar**: Validasi aman untuk upload bukti transfer pembayaran.
-   **Keamanan Terjamin**: Proteksi terhadap IDOR, XSS, dan validasi input yang ketat.
-   **Tampilan Modern**: Didesain dengan Tailwind CSS yang rapi, responsif, dan bebas emoji (profesional).

## 🛠️ Teknologi yang Digunakan

-   **Framework**: Laravel 10 / 11
-   **Database**: PostgreSQL / MySQL
-   **Frontend**: Blade Templates, Tailwind CSS
-   **JavaScript Libraries**: Alpine.js, SweetAlert2, FullCalendar
-   **Fitur Lain**: DomPDF (Laporan), Laravel Breeze (Auth)

## 📦 Panduan Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan project di komputer Anda:

1.  **Clone Repository**

    ```bash
    git clone https://github.com/ZenFill/booking-laravel.git
    cd booking-laravel
    ```

2.  **Install Dependencies**

    ```bash
    composer install
    npm install
    ```

3.  **Setup Environment**

    -   Copy file `.env.example` menjadi `.env`
    -   Sesuaikan konfigurasi database (DB_DATABASE, DB_USERNAME, dll)

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Migrasi Database**

    ```bash
    php artisan migrate
    ```

5.  **Jalankan Project**
    Buka dua terminal terpisah:

    ```bash
    # Terminal 1 (Server Laravel)
    php artisan serve

    # Terminal 2 (Compile Aset Frontend)
    npm run dev
    ```

6.  **Akses Aplikasi**
    Buka browser dan kunjungi `http://localhost:8000`.

## 🛡️ Keamanan

Project ini telah diaudit keamanannya, mencakup:

-   **Authorization**: Menggunakan Policy/Gate check di Controller untuk mencegah IDOR.
-   **Sanitization**: escaping output pada JavaScript untuk mencegah XSS.
-   **Validation**: Validasi ketat pada setiap form input dan file upload.

## 📝 Lisensi

StudioBook adalah software open-source di bawah lisensi [MIT](https://opensource.org/licenses/MIT).
