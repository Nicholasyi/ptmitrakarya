<?php
require "config.php";

// Access control - only admin can delete users
if (!isset($_SESSION['user']) || $_SESSION['user']['access'] !== 'admin') {
    header("Location: beranda.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $user_id = $_POST['id'];
    
    // Prevent admin from deleting their own account
    if ($user_id == $_SESSION['user']['id']) {
        echo "<script>alert('You cannot delete your own account!'); location='penggunaakun.php';</script>";
        exit;
    }
    
    // Perform DELETE operation
    $query = $koneksi->query("DELETE FROM pengguna WHERE id='$user_id'");
    
    if ($query) {
        echo "<script>alert('User deleted successfully!'); location='penggunaakun.php';</script>";
    } else {
        echo "<script>alert('Failed to delete user!'); location='penggunaakun.php';</script>";
    }
} else {
    header("Location: penggunaakun.php");
    exit;
}
?>
