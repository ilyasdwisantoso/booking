SISTEM AKSES KONTROL RUANG KELAS DAN PRESENSI MAHASISWA
Proyek ini adalah Sistem Akses Kontrol Ruang Kelas dan Presensi Mahasiswa berbasis QR Code dan IoT. Sistem ini berfungsi untuk mengelola akses kontrol pada ruang kelas dan melakukan presensi mahasiswa berdasarkan jadwal perkuliahan yang terintegrasi dengan database sistem.

Fitur Utama
Akses Kontrol Ruang Kelas: Dosen dapat membuka atau menutup pintu ruang kelas menggunakan QR Code atau token yang telah di-generate dan didaftarkan di sistem. Jika dosen tidak dapat hadir, akses kelas dapat diberikan menggunakan keypad dengan token khusus.

Presensi Mahasiswa: Mahasiswa dapat melakukan presensi menggunakan QR Code yang akan dipindai oleh QR Code Scanner. Presensi mahasiswa hanya dapat dilakukan jika dosen telah membuka akses ruang kelas terlebih dahulu. Foto mahasiswa akan diambil menggunakan kamera (ESP32 Wrover CAM) dan diunggah ke server sebagai bagian dari proses presensi.

Berbasis IoT: Sistem ini menggunakan ESP32 Wrover CAM, GM67 QR Code Scanner, dan komponen lain seperti relay, buzzer, keypad, dan LCD untuk mengontrol pintu dan presensi mahasiswa secara otomatis sesuai jadwal yang ada di database.

Teknologi yang Digunakan
Laravel: Sebagai web framework utama untuk server-side aplikasi dan manajemen database.
ESP32 Wrover CAM: Untuk pengambilan gambar mahasiswa saat presensi.
GM67 QR Code Scanner: Untuk memindai QR Code dosen dan mahasiswa.
MySQL: Sebagai database untuk menyimpan data presensi, jadwal ruang kelas, mahasiswa, dan dosen.
PHP & JavaScript: Digunakan untuk backend dan interaksi client-side.
HTML/CSS: Untuk tampilan web admin dan halaman manajemen.
Instalasi
Clone repository ini:

bash
Salin kode
git clone https://github.com/ilyasdwisantoso/booking.git
Masuk ke direktori proyek:

bash
Salin kode
cd booking
Instal dependencies dengan Composer:

bash
Salin kode
composer install
Salin file .env.example menjadi .env:

bash
Salin kode
cp .env.example .env
Atur file .env dengan konfigurasi database yang benar:

plaintext
Salin kode
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=password_database
Generate application key:

bash
Salin kode
php artisan key:generate
Jalankan migration untuk membuat tabel di database:

bash
Salin kode
php artisan migrate
Jalankan aplikasi Laravel:

bash
Salin kode
php artisan serve
Konfigurasi IoT
ESP32 Wrover CAM:

Digunakan untuk pengambilan gambar mahasiswa saat melakukan presensi.
Terhubung ke sistem Laravel untuk mengirim data presensi beserta gambar.
GM67 QR Code Scanner:

Digunakan untuk memindai QR Code dosen dan mahasiswa sesuai dengan jadwal ruang kelas yang telah ditentukan.
Relay & Keypad:

Mengontrol akses pintu ruang kelas dan pengisian token untuk akses kelas jika dosen tidak hadir.
Cara Kerja
Akses Kelas:

Dosen memindai QR Code mereka untuk membuka pintu kelas.
Jika dosen tidak hadir, admin dapat memberikan token untuk akses manual menggunakan keypad.
Presensi Mahasiswa:

Setelah pintu kelas dibuka oleh dosen, mahasiswa dapat memindai QR Code mereka untuk melakukan presensi.
Kamera ESP32 Wrover CAM akan mengambil gambar mahasiswa sebagai bukti kehadiran.
Integrasi IoT:

Semua perangkat IoT terhubung ke sistem Laravel melalui komunikasi MQTT yang memastikan data presensi dan akses kontrol disinkronkan dengan sistem.
Kontribusi
Jika Anda ingin berkontribusi pada proyek ini:

Fork repository ini.
Buat branch fitur baru (git checkout -b fitur-baru).
Commit perubahan Anda (git commit -am 'Tambah fitur baru').
Push branch Anda (git push origin fitur-baru).
Buat Pull Request.
Lisensi
Proyek ini dilisensikan di bawah lisensi MIT. Lihat file LICENSE untuk detail lebih lanjut.
