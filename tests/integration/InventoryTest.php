<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../config/database_test.php';
require_once __DIR__ . '/../../functions.php';

class InventoryTest extends TestCase{
    private $conn;

    protected function setUp() : void{
        $this->conn = get_test_db_connection();
        mysqli_query($this->conn, "SET FOREIGN_KEY_CHECKS = 0");
            
        
            mysqli_query($this->conn, "TRUNCATE TABLE penjualan_detail");
            mysqli_query($this->conn, "TRUNCATE TABLE penjualan");
            mysqli_query($this->conn, "TRUNCATE TABLE inventory");
            mysqli_query($this->conn, "TRUNCATE TABLE produk");
            mysqli_query($this->conn, "TRUNCATE TABLE `return` ");
            
            mysqli_query($this->conn, "SET FOREIGN_KEY_CHECKS = 1");
        mysqli_begin_transaction($this->conn);
    }

    public function testAddProductUpdateInventory(){
        $name = 'Semen Roda Tiga';
        $description = 'Semen Berkualitas';
        $price = '10000.00';
        $stock_quantity = '100';

        $result = addProducts($this->conn, $name, $description, $price, $stock_quantity);

        $resProduk = mysqli_query($this->conn, "SELECT * FROM produk WHERE productID = '$result'");
        $produk = mysqli_fetch_assoc($resProduk);

        $this->assertEquals($name, $produk['name']);
        $this->assertEquals($price, $produk['price']);

        $resInventori = mysqli_query($this->conn, "SELECT * FROM inventory WHERE productID = '$result'");
        $inventori = mysqli_fetch_assoc($resInventori);

        $this->assertEquals($stock_quantity, $inventori['stock_quantity']);
    }
    protected function tearDown() : void {
        mysqli_rollback($this->conn);
        mysqli_close($this->conn);
    }
}
?>