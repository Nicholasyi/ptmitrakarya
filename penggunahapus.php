<?php
require "config.php";

// Check if delete is confirmed via POST
if (isset($_POST['delete'])) {
    $customerID = $_POST['customerID'];

    // Perform DELETE operation using customerID
    $koneksi->query("DELETE FROM pelanggan WHERE customerID='$customerID'");

    // Redirect to the customer list page after deletion
    header("Location: penggunadaftar.php");
    exit;
} else {
    echo "Invalid request.";
}
?>
