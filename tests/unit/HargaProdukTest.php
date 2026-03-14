<?php
use PHPUnit\Framework\TestCase;

    class HargaProdukTest extends TestCase{

        public function testHargaProdukValid(){
            $price = 1000;
            $this->assertTrue(validatePrice($price));
        }
        public function testHargaProdukNegatif(){
            $price = -1000;
            $this->assertFalse(validatePrice($price));
        }
        public function testHargaProdukNol(){
            $price = 0;
            $this->assertFalse(validatePrice($price));
        }
        public function testStokProdukValid(){
            $stock_quantity = 20;
            $this->assertTrue(validateStock($stock_quantity));
        }
        public function testStokProdukNegatif(){
            $stock_quantity = -20;
            $this->assertFalse(validateStock($stock_quantity));
        }
        public function testStokProdukNol(){
            $stock_quantity = 0;
            $this->assertFalse(validateStock($stock_quantity));
        }
    }
?>