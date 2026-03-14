<?php
use PHPUnit\Framework\TestCase;

    class DataPribadiTest extends TestCase{

        public function testNamaSemuaHuruf(){
            $nama = "Ahok";
            $this->assertTrue(validateName($nama));
        }
        public function testNamaSelainHuruf(){
            $nama = "123";
            $this->assertFalse(validateName($nama));
        }
        public function testKombinasiNama(){
            $nama = "Ahok77";
            $this->assertFalse(validateName($name));
        }
        public function testNamaKosong(){
            $nama = "";
            $this->assertFalse(validateName($name));
        }
        public function testNomorTeleponValid(){
            $telepon = "081234567890";
            $this->assertTrue(validatePhone($telepon));
        }
        public function testNomorTeleponKurangDari10(){
            $telepon = "081234567";
            $this->assertFalse(validatePhone($telepon));
        }
        public function testNomorTeleponLebihDari13(){
            $telepon = "08123456789012";
            $this->assertFalse(validatePhone($telepon));
        }
        public function testNomorTeleponSelainAngka(){
            $telepon = "abcde";
            $this->assertFalse(validatePhone($telepon));
        }
        public function testEmailValid(){
            $email = "abc@gmail.com";
            $this->assertTrue(validateEmail($email));
        }
        public function testEmailInvalid(){
            $email = "abcgmail.com";
            $this->assertFalse(validateEmail($email));
        }
    }

?>