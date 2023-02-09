# Bio Interface Bank UMKM JATIM

## Requirements:

-   Semua persyaratan pada framework [Laravel](https://laravel.com/docs/9.x/deployment#server-requirements)
-   PHP versi `>= 8.1`
-   Database `MySQL`

## Installation Guide

-   Clone atau download repository ini
-   Lakukan pemasangan dependensi yang diperlukan
    ```bash
    composer install
    ```
-   Sesuaikan konfigurasi pada environment file
    ```bash
    nano .env
    ```
-   Jalankan migration untuk membuat tabel pada database
    ```bash
    php artisan migrate
    ```
-   Jalankan aplikasi
    -   Jika anda mendeploy app ini pada server/production, letakkan folder public pada root directory server
    -   Atau, anda bisa mengarahkan root directory untuk server pada folder `public`
    -   Pada proses development, maka cukup lakukan perintah
        ```bash
        php artisan serve
        ```
-   Setup cron\
     Setup cron ini digunakan untuk membackup database pada setiap tanggal 26 dan akhir dari setiap bulan\
     `    1 0 * * * /usr/bin/php /path/to/project/artisan schedule:run >> /dev/null 2>&1`
