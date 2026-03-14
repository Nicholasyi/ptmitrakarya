<?php

function get_test_db_connection() {

    $host = getenv('DB_HOST') ?: 'localhost';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: ''; 
    $db   = getenv('DB_NAME') ?: 'test_ptmitrakarya';

    $conn = mysqli_connect($host, $user, $pass, $db);

    if (!$conn) {
        die("Koneksi DB Testing Gagal: " . mysqli_connect_error());
    }

    return $conn;
}