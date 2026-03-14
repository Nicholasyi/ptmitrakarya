<?php
include 'config.php';

$produkResult = $koneksi->query("SELECT * FROM produk");

if (isset($_POST['simpan'])) {
    // Ambil data dari form
    $deliveryDate = $_POST['DeliveryDate'] ?? ''; // Perbaiki penulisan variabel
    $customerID = $_POST['customerID'] ?? '';
    $productID = $_POST['productID'] ?? '';
    $qty = $_POST['qty'] ?? 0;

    // Validasi input
    if (empty($deliveryDate) || empty($customerID) || empty($productID) || $qty <= 0) {
        echo "<script>alert('Semua field harus diisi dengan benar!');</script>";
    } else {
        try {
            // Gunakan satu query insert dengan semua kolom
            $stmt = $koneksi->prepare("INSERT INTO pengiriman 
                (deliveryDate, customerID, productID, qty) 
                VALUES (?, ?, ?, ?)");
            
            $stmt->bind_param("siii", $deliveryDate, $customerID, $productID, $qty);

            if ($stmt->execute()) {
                header("Location: delivery.php");
                exit();
            } else {
                throw new Exception("Gagal menyimpan delivery");
            }
        } catch (Exception $e) {
            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
        }
    }
}
?>

<?php include 'header.php'; ?>
<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
    <div class="kontainer padding-32">
        <h1>Add Delivery Data</h1>
        <div class="row">
            <div class="col l12">
                <div class="kontainer">
                    <form method="post">
                        <div class="kolominput mb-4">
                            <label>Delivery Date</label>
                            <input type="date" name="DeliveryDate" class="form-control" required 
                                   value="<?= date('Y-m-d') ?>">
                        </div>
                        
                        <!-- Customer Selection -->
                        <div class="kolominput mb-4">
                            <label>Customer</label>
                            <select name="customerID" class="form-control" id="customerID" required>
                                <option value="">Select Customer</option>
                                <?php
                                $customers = $koneksi->query("SELECT * FROM pelanggan");
                                while ($row = $customers->fetch_assoc()) {
                                    $selected = ($row['customerID'] == $_POST['customerID']) ? 'selected' : '';
                                    echo "<option value='{$row['customerID']}' $selected>{$row['custName']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Product Selection -->
                        <div class="kolominput mb-4">
                            <label>Product</label>
                            <select name="productID" class="form-control" required>
                                <option value="">Select Product</option>
                                <?php $produkResult->data_seek(0); while ($row = $produkResult->fetch_assoc()) { ?>
                                    <option value="<?= $row['productID'] ?>"><?= $row['name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        
                        <div class="kolominput mb-4">
                            <label>Quantity</label>
                            <input type="number" name="qty" class="form-control" min="1" required>
                        </div>
                        
                        <button type="submit" name="simpan" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>