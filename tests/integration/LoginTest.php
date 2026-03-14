<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase {
    private $conn;

    protected function setUp(): void {
        $this->conn = get_test_db_connection();
        mysqli_query($this->conn, "SET FOREIGN_KEY_CHECKS = 0");
        mysqli_query($this->conn, "TRUNCATE TABLE pengguna");
        mysqli_query($this->conn, "SET FOREIGN_KEY_CHECKS = 1");
        
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION = [];
    }

    public function testLoginSuccessWithValidCredentials() {
        $email = 'admin@gmail.com';
        $password = 'admin';
        mysqli_query($this->conn, "INSERT INTO pengguna (name,email,password,access) VALUES ('Administrator', '$email', '$password','admin')");

        $result = loginUser($this->conn, $email, $password);

        $this->assertTrue($result);
        $this->assertEquals('Administrator', $_SESSION['user']['name']);
        $this->assertEquals($email, $_SESSION['user']['email']);
    }
}