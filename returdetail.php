<?php
include 'config.php';

$returnID = $_GET['returnID'] ?? '';
if (empty($returnID)) {
    echo "<script>alert('Return ID tidak ditemukan! Debug: returnID = " . htmlspecialchars($returnID) . "'); window.location.href='retur.php';</script>";
    exit;
}

try {
    $stmt = $koneksi->prepare("SELECT r.*, p.name AS productName, o.orderID AS orderID 
                              FROM `return` r
                              JOIN produk p ON r.productID = p.productID
                              LEFT JOIN penjualan o ON r.orderID = o.orderID
                              WHERE r.returnID = ?");
    $stmt->bind_param("i", $returnID);
    $stmt->execute();
    $result = $stmt->get_result();
    $returnData = $result->fetch_assoc();

    if (!$returnData) {
        echo "<script>alert('Data return tidak ditemukan untuk returnID: " . htmlspecialchars($returnID) . "'); window.location.href='retur.php';</script>";
        exit;
    }
} catch (Exception $e) {
    echo "<script>alert('Terjadi kesalahan: " . addslashes($e->getMessage()) . "'); window.location.href='retur.php';</script>";
    exit;
}

include 'header.php';
?>

<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
    <h1 class="pb-3">Return Details</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr><th>Return Date</th><td><?= htmlspecialchars($returnData['returnDate']) ?></td></tr>
                    <tr><th>Order ID</th><td><?= htmlspecialchars($returnData['orderID']) ?></td></tr>
                    <tr><th>Product</th><td><?= htmlspecialchars($returnData['productName']) ?></td></tr>
                    <tr><th>Reason</th><td><?= htmlspecialchars($returnData['reason']) ?></td></tr>
                    <tr><th>Quantity</th><td><?= htmlspecialchars($returnData['qty']) ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex justify-content-start">
        <a href="retur.php" class="btn btn-secondary mt-3">Back to Return List</a>
    </div>
</div>

<?php include 'footer.php'; ?>