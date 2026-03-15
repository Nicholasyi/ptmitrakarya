<?php 
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../../config/database_test.php';
require_once __DIR__ . '/../../functions.php';

    class ReturnTest extends TestCase{
        private $conn;
        protected function setUp(): void {
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
        public function testReturnUpdateStock(){
            $productID = 1;
            $orderID = 1;
            $initialStock = 10;
            $qtySold = 5;
            $qtyReturned = 2;
            $orderDate = '2026-03-15';

            mysqli_query($this->conn, "INSERT INTO produk (productID, name, price) VALUES ($productID, 'Semen', 10000)");
            mysqli_query($this->conn, "INSERT INTO inventory (productID, stock_quantity) VALUES ($productID, $initialStock)");
            
            mysqli_query($this->conn, "INSERT INTO penjualan (orderID, customerID, orderDate, totalAmount) VALUES ('$orderID', 1, '$orderDate' 50000)");
            mysqli_query($this->conn, "INSERT INTO penjualan_detail (orderID, productID, qty, price) VALUES ('$orderID', $productID, $qtySold, 10000)");

            $inputData = [
                [
                    'productID' => $productID,
                    'jumlah'    => $qtyReturned,
                    'reason'    => 'Barang Rusak'
                ]
            ];

            $result = returnProducts($this->conn, $orderID, $inputData);

            $this->assertTrue($result);

            // Cek apakah data masuk ke tabel 'return'
            $resReturn = mysqli_query($this->conn, "SELECT * FROM `return` WHERE orderID = '$orderID' AND productID = $productID");
            $dataReturn = mysqli_fetch_assoc($resReturn);
            
            $this->assertNotNull($dataReturn);
            $this->assertEquals($qtyReturned, $dataReturn['qty']);

            // Cek apakah stok di inventory bertambah: 10 + 2 = 12
            $resInv = mysqli_query($this->conn, "SELECT stock_quantity FROM inventory WHERE productID = $productID");
            [ 'stock_quantity' => $currentStock ] = mysqli_fetch_assoc($resInv); 

            $expectedStock = $initialStock + $qtyReturned;
            $this->assertEquals($expectedStock, $currentStock);
        }
    protected function tearDown() : void {
        mysqli_rollback($this->conn);
        mysqli_close($this->conn);
    }
    }
?>