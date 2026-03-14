<?php
// Tambahkan koneksi database
include 'config.php';

// Pastikan parameter 'id' tersedia sebelum digunakan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Jalankan query DELETE
    $koneksi->query("DELETE FROM supplier WHERE supplierID='$id'");

  
} else {
   
}

echo "<script>location='supplierdaftar.php';</script>";
?>
