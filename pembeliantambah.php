<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
include 'config.php';
include 'header.php';
require 'functions.php';

// Fetch suppliers and products
$suppliers = $koneksi->query("SELECT * FROM supplier");
$produkResult = $koneksi->query("SELECT * FROM produk");
?>

<div class="container mt-4">
    <h1>Add Purchase</h1>
    <form method="post">
        <!-- Supplier Selection -->
<div class="mb-3">
    <label for="supplierID" class="form-label">Supplier Name</label>
    <select name="supplierID" class="form-control" required>
        <option value="">Select Supplier</option>
        <?php while ($row = $suppliers->fetch_assoc()) { ?>
            <option value="<?= $row['supplierID'] ?>"><?= $row['name'] ?></option>
        <?php } ?>
    </select>
</div>


        <!-- Purchase Date -->
        <div class="mb-3">
            <label for="tanggal" class="form-label">Purchase Date</label>
            <input type="date" name="tanggalpembelian" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <!-- Purchase Table -->
        <table class="table table-bordered" id="purchase_table">
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
                    <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" value="1"></td>
                    <td><input type="text" name="total[]" class="form-control total" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove_row">X</button></td>
                </tr>
            </tbody>
        </table>

        <!-- Grand Total -->
        <div class="mb-3">
            <label for="grandtotal" class="form-label">Grand Total</label>
            <input type="text" id="grandtotal" name="grandtotal" class="form-control" readonly>
        </div>

        <button type="submit" name="simpan" class="btn btn-primary">Save Purchase</button>
    </form>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function updateTotals() {
        let grandTotal = 0;
        $('#purchase_table tbody tr').each(function() {
            const harga = parseFloat($(this).find('.harga').val()) || 0;
            const jumlah = parseInt($(this).find('.jumlah').val()) || 0;
            const total = harga * jumlah;
            $(this).find('.total').val(total.toFixed(2));
            grandTotal += total;
        });
        $('#grandtotal').val(grandTotal.toFixed(2));
    }

    $('#purchase_table').on('input', '.harga, .jumlah', function() {
        updateTotals();
    });

    $('#add_row').click(function() {
        const row = $('#purchase_table tbody tr:first').clone();
        row.find('input').val('');
        row.find('select').val('');
        $('#purchase_table tbody').append(row);
    });

    $('#purchase_table').on('click', '.remove_row', function() {
        if ($('#purchase_table tbody tr').length > 1) {
            $(this).closest('tr').remove();
            updateTotals();
        }
    });
});
</script>

<?php
// Handle form submission
if (isset($_POST['simpan'])) {
    $supplierID = $_POST['supplierID'];
    $tanggal = $_POST['tanggalpembelian'];

    if(!validateDate($tanggal)){
        showInvalidDate();
    }

    $productData = [];
        foreach ($_POST['productID'] as $i => $productID) {
            $productData[] = [
            'productID' => $productID,
            'harga' => $_POST['harga'][$i],
            'jumlah' => $_POST['jumlah'][$i]
            ];
        }
    $purchaseID = purchaseProducts($koneksi, $supplierID, $tanggal, $productData);

    if($purchaseID){
        echo "<script>location='pembeliandaftar.php?id=$purchaseID';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error saving purchase!</div>";
    }
}
?>
