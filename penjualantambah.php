<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
include 'config.php'; // replaces koneksi.php
include 'header.php';
require 'functions.php';

// Check if products exist
$produkResult = $koneksi->query("SELECT * FROM produk");
$customers = $koneksi->query("SELECT * FROM pelanggan");

if ($produkResult->num_rows === 0) {
    echo "<div class='alert alert-danger'>Tidak ada produk tersedia untuk dijual.</div>";
    include 'footer.php';
    exit;
}
?>

<div class="container mt-4">
    <h1>Add Order</h1>
    <form method="post">
        <!-- Customer Selection -->
        <div class="mb-3">
            <label for="customerID" class="form-label">Customer Name</label>
            <select name="customerID" class="form-control" required>
                <option value="">Select Customer</option>
                <?php while ($row = $customers->fetch_assoc()) { ?>
                    <option value="<?= $row['customerID'] ?>"><?= $row['custName'] ?></option>
                <?php } ?>
            </select>
        </div>

        <!-- Sales Date -->
        <div class="mb-3">
            <label for="tanggal" class="form-label">Order Date</label>
            <input type="date" name="tanggalpenjualan" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <!-- Order Table -->
        <table class="table table-bordered" id="order_table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th><button type="button" id="add_row" class="btn btn-success btn-sm">+</button></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="namabarang[]" class="form-control produk-select" required>
                            <option value="">Select Product</option>
                            <?php while ($row = $produkResult->fetch_assoc()) { ?>
                                <option value="<?= $row['productID'] ?>" data-price="<?= $row['price'] ?>">
                                    <?= $row['name'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><input type="text" name="harga[]" class="form-control harga" readonly></td>
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

        <button type="submit" name="simpan" class="btn btn-primary">Save Order</button>
    </form>
</div>

<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function updateTotals() {
        let grandTotal = 0;
        $('#order_table tbody tr').each(function() {
            const harga = parseFloat($(this).find('.harga').val()) || 0;
            const jumlah = parseInt($(this).find('.jumlah').val()) || 0;
            const total = harga * jumlah;
            $(this).find('.total').val(total.toFixed(2));
            grandTotal += total;
        });
        $('#grandtotal').val(grandTotal.toFixed(2));
    }

    $('#order_table').on('change', '.produk-select', function() {
        const price = $(this).find(':selected').data('price') || 0;
        $(this).closest('tr').find('.harga').val(price);
        updateTotals();
    });

    $('#order_table').on('input', '.jumlah', function() {
        updateTotals();
    });

    $('#add_row').click(function() {
        const row = $('#order_table tbody tr:first').clone();
        row.find('input').val('');
        row.find('select').val('');
        $('#order_table tbody').append(row);
    });

    $('#order_table').on('click', '.remove_row', function() {
        if ($('#order_table tbody tr').length > 1) {
            $(this).closest('tr').remove();
            updateTotals();
        }
    });
});
</script>

<?php
// Handle submission
if (isset($_POST['simpan'])) {

    $tanggal = $_POST['tanggalpenjualan'];
    $customerID = $_POST['customerID'];

    if(!validateDate($tanggal) || empty($tanggal)){
        showErrorAlert();
    }

    $productData = [];
    foreach($_POST['namabarang'] as $i => $productID){
        $productData [] = [
            'productID' => $productID,
            'harga' => $_POST['harga'][$i],
            'jumlah' => $_POST['jumlah'][$i],
            'total' => $_POST['total'][$i]
        ];
    }

    $orderID = orderProducts($koneksi,$customerID,$tanggal,$productData);
    if($orderID){
        showSuccessAlert("Pesanan berhasil dibuat !", "penjualandaftar.php");
    }
    else{
        showInsufficientStockAlert();
    }
}
?>
