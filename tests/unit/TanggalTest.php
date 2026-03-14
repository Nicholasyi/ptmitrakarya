<?php 
use PHPUnit\Framework\TestCase;

    class TanggalTest extends TestCase{

            public function testTanggalHariIni(){
                $tanggal = date('Y-m-d');
                $this->assertTrue(validateDate($tanggal));
            }
            public function testTanggalLewat(){
                $tanggal = "03/12/2026";
                $this->assertTrue(validateDate($tanggal));
            }
            public function testTanggalBesok(){
                $tanggal = date('Y-m-d', strtotime('+1 day'));
                $this->assertFalse(validateDate($tanggal));
            }
        }
?>