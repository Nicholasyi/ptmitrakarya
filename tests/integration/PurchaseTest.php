<?php 
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../config/database_test.php';
require_once __DIR__ . '/../../functions.php';

    class PurchaseTest extends TestCase{
        private $conn;
        protected function setUp(): void {
        $this->conn = get_test_db_connection();
        mysqli_begin_transaction($this->conn);
        }
        public function testPurchaseUpdateStock(){
            mysqli_query($this->conn, "INSERT INTO supplier (supplierID, name, address, phone, email) VALUES (1, 'Supplier A', 'Jalan Mawar', '081234567890', 'supplierA@gmail.com')");
            mysqli_query($this->conn, "INSERT INTO produk (productID, name, description, price) VALUES (10, 'Semen Tiga Roda', 'Semen', 10000)");
            mysqli_query($this->conn, "INSERT INTO inventory (productID, stock_quantity) VALUES (10, 100)");

        $productData = [
        ['productID' => 10, 'harga' => 50000, 'jumlah' => 10]
        ];

        $purchaseID = purchaseProducts($this->conn, 1, '2026-03-13', $productData);

        $this->assertNotEmpty($purchaseID);

        $resStock = mysqli_query($this->conn, "SELECT stock_quantity FROM inventory WHERE productID = 10");
        $result = mysqli_fetch_assoc($resStock);
        $this->assertEquals(110, $result['stock_quantity']);

        $resPurchase = mysqli_query($this->conn, "SELECT totalAmount FROM purchase WHERE purchaseID = $purchaseID");
        $pur = mysqli_fetch_assoc($resPurchase);
        $this->assertEquals(500000, $pur['totalAmount']);

        }
    protected function tearDown() : void {
        mysqli_rollback($this->conn);
        mysqli_close($this->conn);
    }
    }
?>