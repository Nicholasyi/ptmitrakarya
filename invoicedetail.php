<?php
include 'config.php';
include 'header.php';

// Handle form submission
if (isset($_POST['simpan'])) {
    $koneksi->begin_transaction();
    try {
        $customerID = $_POST['customerID'];
        $paymentDate = $_POST['paymentDate'];

        foreach ($_POST['productID'] as $i => $productID) {
            // Ambil harga dari database
            $produk = $koneksi->query("SELECT price FROM produk WHERE productID = '$productID'");
            $harga = $produk->fetch_assoc()['price'];
            $qty = $_POST['qty'][$i];
            $total = $harga * $qty;

            // Validasi stok
            $checkStock = $koneksi->query("SELECT stock_quantity FROM inventory WHERE productID = '$productID'");
            $stock = $checkStock->fetch_assoc()['stock_quantity'];
            if ($stock < $qty) {
                throw new Exception("Stok produk tidak mencukupi!");
            }

            // Insert ke invoice
            $result = $koneksi->query("SELECT MAX(invoiceID) AS last_id FROM invoice");
            $row = $result->fetch_assoc();
            $last_id = $row['last_id'] ?? 0; // Jika tabel kosong, mulai dari 0
            $new_id = $last_id + 1; // Increment manual
            
            // Simpan data dengan ID baru
            $stmt = $koneksi->prepare("
                INSERT INTO invoice (invoiceID, customerID, productID, paymentDate, qty, totalAmount) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iiisid", $new_id, $customerID, $productID, $paymentDate, $qty, $total);
            $stmt->execute();

            // Update stok
            $koneksi->query("UPDATE inventory SET stock_quantity = stock_quantity - $qty WHERE productID = '$productID'");
        }

        $koneksi->commit();
        header("Location: invoice.php?success=1");
        exit();
    } catch (Exception $e) {
        $koneksi->rollback();
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}

// Fetch customers and products
$customers = $koneksi->query("SELECT * FROM pelanggan");
$produkResult = $koneksi->query("SELECT * FROM produk");
?>

<div class="container mt-4">
    <h1>Add Invoice</h1>
    <form method="post">
        <div class="kolominput mb-4">
            <label>Customer</label>
            <select name="customerID" class="form-control" id="customerID" required>
                <option value="">Select Customer</option>
                <?php
                while ($row = $customers->fetch_assoc()) {
                    $selected = ($row['customerID'] == $_POST['customerID']) ? 'selected' : '';
                    echo "<option value='{$row['customerID']}' $selected>{$row['custName']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Payment Date</label>
            <input type="date" name="paymentDate" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <table class="table table-bordered" id="invoice_table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Custom Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th><button type="button" id="add_row" class="btn btn-success btn-sm">+</button></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="productID[]" class="form-control" required>
                            <option value="">Select Product</option>
                            <?php $produkResult->data_seek(0); while ($row = $produkResult->fetch_assoc()) { ?>
                                <option value="<?= $row['productID'] ?>"><?= $row['name'] ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><input type="number" name="harga[]" class="form-control harga" min="0" step="0.01" required></td>
                    <td><input type="number" name="qty[]" class="form-control jumlah" min="1" value="1"></td>
                    <td><input type="text" name="total[]" class="form-control total" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove_row">X</button></td>
                </tr>
            </tbody>
        </table>

        <div class="mb-3">
            <label for="grandtotal" class="form-label">Grand Total</label>
            <input type="text" id="grandtotal" name="grandtotal" class="form-control" readonly>
        </div>

        <button type="submit" name="simpan" class="btn btn-primary">Save Invoice</button>
    </form>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function updateTotals() {
        let grandTotal = 0;
        $('#invoice_table tbody tr').each(function() {
            const harga = parseFloat($(this).find('.harga').val()) || 0;
            const jumlah = parseInt($(this).find('.jumlah').val()) || 0;
            const total = harga * jumlah;
            $(this).find('.total').val(total.toFixed(2));
            grandTotal += total;
        });
        $('#grandtotal').val(grandTotal.toFixed(2));
    }

    $('#invoice_table').on('input', '.harga, .jumlah', function() {
        updateTotals();
    });

    $('#add_row').click(function() {
        const row = $('#invoice_table tbody tr:first').clone();
        row.find('input').val('');
        row.find('select').val('');
        $('#invoice_table tbody').append(row);
    });

    $('#invoice_table').on('click', '.remove_row', function() {
        if ($('#invoice_table tbody tr').length > 1) {
            $(this).closest('tr').remove();
            updateTotals();
        }
    });
});
</script>