<?php
// Include the database connection
require 'config.php';

// Ensure that the 'id' parameter is available
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']); // Sanitize the 'id'
    $query1 = "DELETE FROM inventory WHERE productID=$id";
    $query2 = "DELETE FROM produk WHERE productID=$id";

    // Run DELETE query
    $result = $koneksi->query($query1);
    $result2 = $koneksi->query($query2);

}

// Redirect to the product list page
echo "<script>location='produkdaftar.php';</script>";
?>
