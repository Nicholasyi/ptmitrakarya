# Sistem Manajemen Inventaris & Penjualan — PT Mitra Karya

## Deskripsi Aplikasi

Aplikasi berbasis web yang dibangun dengan PHP dan MySQL untuk mengelola operasional bisnis PT Mitra Karya. Sistem ini mencakup:

- **Autentikasi pengguna** dengan kontrol akses berbasis peran
- **Manajemen penjualan** — buat pesanan, kelola detail transaksi pelanggan
- **Manajemen pembelian** — catat pembelian dari supplier, kelola stok masuk
- **Manajemen inventaris** — pantau stok produk secara real-time
- **Manajemen produk** — tambah dan kelola data produk beserta harga
- **Retur produk** — proses pengembalian barang dengan penyesuaian stok otomatis
- **Pengiriman** — kelola pengiriman produk ke pelanggan
- **Invoice & Faktur** — buat dan cetak invoice/nota pembayaran
- **Dashboard** — ringkasan KPI bulanan (total penjualan, pembelian, jumlah pesanan)

Stack teknologi: PHP 8.2, MySQLi, Bootstrap 5.3, SweetAlert2, PHPUnit 11.5

---

## Cara Menjalankan Aplikasi

### Prasyarat

- [XAMPP](https://www.apachefriends.org/) (PHP 8.2 + MariaDB) atau server lokal sejenis
- [Composer](https://getcomposer.org/)

### Langkah-langkah

1. Clone repositori ini ke folder `htdocs` XAMPP:
   ```bash
   git clone <url-repo> htdocs/ptmitrakarya
   ```

2. Import database:
   - Buka phpMyAdmin (`http://localhost/phpmyadmin`)
   - Buat database baru bernama `ptmitrakarya`
   - Import file `database/schema.sql`

3. Sesuaikan konfigurasi database di `config.php` jika diperlukan:
   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "ptmitrakarya";
   ```

4. Install dependensi Composer:
   ```bash
   composer install
   ```

5. Jalankan Apache dan MySQL melalui XAMPP Control Panel, lalu akses:
   ```
   http://localhost/ptmitrakarya
   ```

---

## Cara Menjalankan Test

### Prasyarat Testing

Buat database khusus untuk testing bernama `test_ptmitrakarya` dan import skema yang sama dari `database/schema.sql`.

Konfigurasi koneksi database testing ada di `config/database_test.php`. Secara default menggunakan:

| Variabel | Default |
|---|---|
| DB_HOST | localhost |
| DB_USER | root |
| DB_PASS | _(kosong)_ |
| DB_NAME | test_ptmitrakarya |

Bisa juga dikonfigurasi via environment variable sebelum menjalankan test.

### Menjalankan Semua Test

```bash
composer test
```

Atau langsung menggunakan PHPUnit:

```bash
./vendor/bin/phpunit
```

### Menjalankan Test Spesifik

```bash
# Hanya unit test
./vendor/bin/phpunit tests/unit

# Hanya integration test
./vendor/bin/phpunit tests/integration

# File test tertentu
./vendor/bin/phpunit tests/unit/HargaProdukTest.php
```

---

## Penjelasan Strategi Pengujian

Pengujian dibagi menjadi dua lapisan: **unit test** dan **integration test**.

### Unit Test (`tests/unit/`)

Menguji fungsi-fungsi bisnis secara terisolasi tanpa ketergantungan pada database. Fokus pada validasi input dan logika kalkulasi.

| File | Cakupan |
|---|---|
| `HargaProdukTest.php` | Validasi harga produk (positif, negatif, nol) dan stok produk |
| `TanggalTest.php` | Validasi tanggal transaksi (hari ini, masa lalu, masa depan) |
| `DataPribadiTest.php` | Validasi nama, nomor telepon, dan format email |

### Integration Test (`tests/integration/`)

Menguji alur bisnis end-to-end dengan koneksi ke database testing (`test_ptmitrakarya`). Setiap test menggunakan `setUp()` untuk menyiapkan data awal dan `tearDown()` untuk rollback transaksi, sehingga data test tidak saling mempengaruhi.

| File | Cakupan |
|---|---|
| `LoginTest.php` | Autentikasi pengguna dengan kredensial valid/invalid |
| `OrderTest.php` | Proses pemesanan, pengurangan stok otomatis, kalkulasi total |
| `PurchaseTest.php` | Proses pembelian dari supplier, penambahan stok |
| `InventoryTest.php` | Manajemen stok produk di gudang |
| `ReturnTest.php` | Proses retur produk dan penyesuaian stok |

# Build Status :
[![Automated Testing (Unit & Integration)](https://github.com/Nicholasyi/ptmitrakarya/actions/workflows/test.yml/badge.svg)](https://github.com/Nicholasyi/ptmitrakarya/actions/workflows/test.yml)

# Test Coverage :
[![codecov](https://codecov.io/gh/Nicholasyi/ptmitrakarya/graph/badge.svg?token=0CL443N56Y)](https://codecov.io/gh/Nicholasyi/ptmitrakarya)
