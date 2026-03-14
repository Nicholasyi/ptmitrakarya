<?php
include 'config.php';
include 'header.php';
require 'functions.php';

// Fetch suppliers and products
$order = $koneksi->query("SELECT * FROM penjualan_detail");
$produkResult = $koneksi->query("SELECT * FROM produk");
?>

<div class="container mt-4">
    <h1>Return</h1>
    <form method="post">
        <!-- Supplier Selection -->
<div class="mb-3">
    <label for="orderID" class="form-label">OrderID</label>
    <select name="orderID" class="form-control" required>
        <option value="">Select Order</option>
        <?php while ($row = $order->fetch_assoc()) { ?>
            <option value="<?= $row['orderID'] ?>"><?= $row['orderID'] ?></option>
        <?php } ?>
    </select>
</div>
        <!-- Purchase Table -->
        <table class="table table-bordered" id="purchase_table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Reason</th>
                    <th>Quantity</th>
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
                    <td><input type="text" name="reason[]" class="form-control reason"required></td>
                    <td><input type="number" name="jumlah[]" class="form-control jumlah" min="1" value="1"></td>
                </tr>
            </tbody>
        </table>

        <button type="submit" name="simpan" class="btn btn-primary">Save Data</button>
        <br><br>
        <a href="retur.php" class="btn btn-secondary mt-3">Back to Return List</a>
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

if (isset($_POST['simpan'])) {
    $orderID = $_POST['orderID'];

    $productData = [];
        foreach ($_POST['productID'] as $index => $pid) {
            if (!empty($pid)) {
                $productData[] = [
                    'productID' => $pid,
                    'jumlah'    => $_POST['jumlah'][$index],
                    'reason'    => $_POST['reason'][$index]
                ];
            }
        }
    
    if (!empty($productData) && returnProducts($koneksi, $orderID, $productData)) {
            echo "<script>alert('Retur berhasil diproses!'); location='retur.php';</script>";
    } else {
            echo "<script>alert('Gagal memproses retur. Periksa kembali data Anda!');</script>";
    }

}
?>