# SISTEM AKSES KONTROL RUANG KELAS DAN PRESENSI MAHASISWA

Proyek ini adalah **Sistem Akses Kontrol Ruang Kelas dan Presensi Mahasiswa** berbasis **QR Code** dan **IoT**. Sistem ini berfungsi untuk mengelola akses kontrol pada ruang kelas dan melakukan presensi mahasiswa berdasarkan **jadwal perkuliahan** yang terintegrasi dengan database sistem.

## Fitur Utama

-   **Akses Kontrol Ruang Kelas**: Dosen dapat membuka atau menutup pintu ruang kelas menggunakan QR Code atau token yang telah di-generate dan didaftarkan di sistem. Jika dosen tidak dapat hadir, akses kelas dapat diberikan menggunakan keypad dengan token khusus.
-   **Presensi Mahasiswa**: Mahasiswa dapat melakukan presensi menggunakan QR Code yang akan dipindai oleh **QR Code Scanner**. Presensi mahasiswa hanya dapat dilakukan jika dosen telah membuka akses ruang kelas terlebih dahulu. Foto mahasiswa akan diambil menggunakan kamera (ESP32 Wrover CAM) dan diunggah ke server sebagai bagian dari proses presensi.

-   **Berbasis IoT**: Sistem ini menggunakan **ESP32 Wrover CAM**, **GM67 QR Code Scanner**, dan komponen lain seperti relay, buzzer, keypad, dan LCD untuk mengontrol pintu dan presensi mahasiswa secara otomatis sesuai jadwal yang ada di database.

## Teknologi yang Digunakan

-   **Laravel**: Sebagai web framework utama untuk server-side aplikasi dan manajemen database.
-   **ESP32 Wrover CAM**: Untuk pengambilan gambar mahasiswa saat presensi.
-   **GM67 QR Code Scanner**: Untuk memindai QR Code dosen dan mahasiswa.
-   **MySQL**: Sebagai database untuk menyimpan data presensi, jadwal ruang kelas, mahasiswa, dan dosen.
-   **PHP & JavaScript**: Digunakan untuk backend dan interaksi client-side.
-   **HTML/CSS**: Untuk tampilan web admin dan halaman manajemen.

## Instalasi

1. **Clone repository ini**:

    ```bash
    git clone https://github.com/ilyasdwisantoso/booking.git
    ```

2. **Masuk ke direktori proyek**:

    ```bash
    cd booking
    ```

3. **Instal dependencies dengan Composer**:

    ```bash
    composer install
    ```

4. **Salin file `.env.example` menjadi `.env`**:

    ```bash
    cp .env.example .env
    ```

5. **Atur file `.env` dengan konfigurasi database yang benar**:

    ```plaintext
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database
    DB_USERNAME=user_database
    DB_PASSWORD=password_database
    ```

6. **Generate application key**:

    ```bash
    php artisan key:generate
    ```

7. **Jalankan migration untuk membuat tabel di database**:

    ```bash
    php artisan migrate
    ```

8. **Jalankan aplikasi Laravel**:
    ```bash
    php artisan serve
    ```

## Konfigurasi IoT

-   **ESP32 Wrover CAM**:
    -   Digunakan untuk pengambilan gambar mahasiswa saat melakukan presensi.
    -   Terhubung ke sistem Laravel untuk mengirim data presensi beserta gambar menggunakan HTTP Client.
-   **GM67 QR Code Scanner**:
    -   Digunakan untuk memindai QR Code dosen dan mahasiswa sesuai dengan jadwal ruang kelas yang telah ditentukan.
-   **Relay & Keypad**:
    -   Mengontrol akses pintu ruang kelas dan pengisian token untuk akses kelas jika dosen tidak hadir.

## Cara Kerja

1. **Akses Kelas**:

    - Dosen memindai QR Code mereka untuk membuka pintu kelas.
    - Jika dosen tidak hadir, admin dapat memberikan token untuk akses manual menggunakan keypad.

2. **Presensi Mahasiswa**:

    - Setelah pintu kelas dibuka oleh dosen, mahasiswa dapat memindai QR Code mereka untuk melakukan presensi.
    - Kamera ESP32 Wrover CAM akan mengambil gambar mahasiswa sebagai bukti kehadiran dan mengunggahnya ke server menggunakan HTTP Client.

3. **Integrasi IoT dengan HTTP Client**:
    - Semua perangkat IoT terhubung ke sistem Laravel melalui HTTP Client yang memastikan data presensi dan akses kontrol tersimpan di server dengan aman.

## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini:

1. Fork repository ini.
2. Buat branch fitur baru (`git checkout -b fitur-baru`).
3. Commit perubahan Anda (`git commit -am 'Tambah fitur baru'`).
4. Push branch Anda (`git push origin fitur-baru`).
5. Buat Pull Request.

## Lisensi

Proyek ini dilisensikan di bawah lisensi **MIT**. Lihat file [LICENSE](LICENSE) untuk detail lebih lanjut.
