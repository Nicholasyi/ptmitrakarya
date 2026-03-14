<?php
require "config.php";


if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$bgImage = 'assets/header.png';  
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        .navbar-bg {
            background-size: cover;
            background-position: center;
        }
        
        .dropdown-menu {
            background-color: rgba(255, 255, 255, 0.9);
        }
        .navbar-bg .dropdown-item {
            color: #000;          /* or #fff, whichever contrast you want */
        }  
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light navbar-bg"
         style="background-image:url('<?= $bgImage ?>');">
        <div class="container-fluid d-flex flex-column">

            <!-- brand container (above) -->
            <div class="brand-container w-100 mb-2 d-flex justify-content-between align-items-center">
                <a class="navbar-brand text-white" href="beranda.php">PT Mitra Karya Universal</a>
                <?php if (isset($_SESSION['user'])): ?>
                    <a class="nav-link text-white" href="account.php">
                        <i class="fa fa-user"></i> Account
                    </a>
                <?php endif; ?>
            </div>

            <hr class="my-1 w-100">

            <!-- navigation container (below, centred) -->
            <div class="nav-container w-100">
                <button class="navbar-toggler" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="beranda.php">Home</a>
                </li>

                <!-- master‑data dropdown – note the normal hyphen in data-bs-toggle -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#"
                       id="masterDataDropdown"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Master Data
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="masterDataDropdown">
                        <li><a class="dropdown-item" href="gudangdaftar.php">Warehouse</a></li>
                        <li><a class="dropdown-item" href="supplierdaftar.php">Supplier</a></li>
                        <li><a class="dropdown-item" href="penggunadaftar.php">Customer</a></li>
                        <li><a class="dropdown-item" href="produkdaftar.php">Product</a></li>
                    </ul>
                </li>

                <!-- transaksi dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#"
                       id="transaksiDropdown"
                       role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Transaksi
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="transaksiDropdown">
                        <li><a class="dropdown-item" href="penjualandaftar.php">Order</a></li>
                        <li><a class="dropdown-item" href="pembeliandaftar.php">Purchase</a></li>
                        <li><a class="dropdown-item" href="retur.php">Return</a></li>
                    </ul>
                </li>

                        <li class="nav-item"><a class="nav-link text-white" href="invoice.php">Payment</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="delivery.php">Delivery</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="penggunaakun.php">User List</a></li>
                        <?php if (isset($_SESSION['user'])) { ?>
                            <li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</body>
</html>