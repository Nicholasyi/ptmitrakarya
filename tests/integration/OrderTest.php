<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../config/database_test.php';
require_once __DIR__ . '/../../functions.php';

class OrderTest extends TestCase{
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

    public function testOrderUpdateStock(){
        mysqli_query($this->conn, "INSERT INTO produk (productID, name, description, price) VALUES (10, 'Semen Tiga Roda', 'Semen', 10000)");
        mysqli_query($this->conn, "INSERT INTO inventory (productID, stock_quantity) VALUES (10, 100)");
        mysqli_query($this->conn, "INSERT INTO pelanggan (customerID, custName, address, phone) VALUES (1, 'Ahok', 'Jalan Merdeka', '081234567890')");

        $productData = [
        ['productID' => 10, 'harga' => 50000, 'jumlah' => 10]
        ];

        $tanggal = date('Y-m-d');

        $orderID = orderProducts($this->conn, 1, $tanggal, $productData);

        $this->assertNotEmpty($orderID);

        $resStock = mysqli_query($this->conn, "SELECT stock_quantity FROM inventory WHERE productID = 10");
        $result = mysqli_fetch_assoc($resStock);
        $this->assertEquals(90, $result['stock_quantity']);

        $resOrder = mysqli_query($this->conn, "SELECT totalAmount FROM penjualan WHERE orderID = $orderID");
        $ord = mysqli_fetch_assoc($resOrder);
        $this->assertEquals(500000, $ord['totalAmount']);
    }

    protected function tearDown() : void {
        mysqli_rollback($this->conn);
        mysqli_close($this->conn);
    }
}
?>