<?php
require "config.php";

// Get purchaseID from URL parameter
$purchaseID = isset($_GET['purchaseID']) ? $_GET['purchaseID'] : '';

// Ensure that the purchaseID is not empty
if (empty($purchaseID)) {
    echo "No purchase ID provided.";
    exit;
}

// Fetch the purchase information
$query = "SELECT * FROM purchase WHERE purchaseID = '$purchaseID'";
$result = $koneksi->query($query);

if ($result->num_rows == 0) {
    echo "Purchase not found.";
    exit;
}

$row = $result->fetch_assoc();
?>

<?php include 'header.php'; ?>

<div class="container px-5 pt-3" style="padding-bottom: 50px;">
    <h1 class="pb-3">Purchase Details</h1>

    <table class="table table-bordered table-striped">
        <thead class="bg-primary text-white">
            <tr>
                <th>No</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch product details for the specific purchase
            $productQuery = "SELECT pd.productID, p.name AS productName, pd.qty, pd.price, (pd.qty * pd.price) AS total
                             FROM purchase_detail pd
                             JOIN produk p ON pd.productID = p.productID
                             WHERE pd.purchaseID = '$purchaseID'";
            $productResult = $koneksi->query($productQuery);

            $nomor = 1;
            while ($product = $productResult->fetch_assoc()) {
            ?>
                <tr>
                    <td><?php echo $nomor; ?></td>
                    <td><?php echo htmlspecialchars($product['productName']); ?></td>
                    <td><?php echo htmlspecialchars($product['qty']); ?></td>
                    <td>Rp <?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                    <td>Rp <?php echo number_format($product['total'], 2, ',', '.'); ?></td>
                </tr>
            <?php
                $nomor++;
            }
            ?>
        </tbody>
    </table>

    <div class="row mb-3">
        <div class="col text-end">
            <a href="pembeliandaftar.php" class="btn btn-secondary">Back to Purchase List</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
