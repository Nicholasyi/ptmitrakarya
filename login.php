<?php
require "config.php";
require "functions.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet">
</head>
<body>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["password"])) {

        $user = loginUser($koneksi,$_POST['email'],$_POST['password']);
        if($user){
            echo "<script>location='beranda.php';</script>";
        }
        else{
            echo "<script>alert('Login failed, please check your email or password');</script>";
            echo "<script>location='index.php';</script>";
        }
    } 
        ?>

</body>
</html>