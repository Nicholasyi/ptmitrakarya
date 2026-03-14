<?php
include 'config.php';
include 'header.php';

// Paginasi
$perPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $perPage;

try {
    // Hitung total data
    $totalQuery = "SELECT COUNT(*) as total FROM `return`";
    $totalResult = $koneksi->query($totalQuery);
    if (!$totalResult) {
        throw new Exception("Error counting total: " . $koneksi->error);
    }
    $totalRow = $totalResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRow / $perPage);

    // Ambil data dengan limit dan offset
    $query = "SELECT 
            r.returnID, 
            r.returnDate, 
            r.reason, 
            r.qty, 
            p.name AS productName, 
            o.orderID AS orderID  -- Gunakan alias untuk kejelasan
          FROM `return` r
          LEFT JOIN produk p ON r.productID = p.productID
          LEFT JOIN penjualan o ON r.orderID = o.orderID  -- Pastikan JOIN ini benar
          LIMIT ? OFFSET ?";
    $stmt = $koneksi->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $koneksi->error);
    }
    $stmt->bind_param("ii", $perPage, $offset);
    $stmt->execute();
    $ambil = $stmt->get_result();
} catch (Exception $e) {
    echo "<div class='alert alert-danger text-center'>Error: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>

<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
    <div class="kontainer padding-32">
        <h1 class="pb-3">Return Product</h1>

        <div class="row">
            <div class="col l12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Return Date</th>
                                    <th>Order ID</th>
                                    <th>Product Name</th>
                                    <th>Reason</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $nomor = ($page - 1) * $perPage + 1;
                                if (isset($ambil) && $ambil->num_rows > 0) {
                                    while ($pecah = $ambil->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?= htmlspecialchars($nomor++) ?></td>
                                            <td><?= htmlspecialchars($pecah['returnDate'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($pecah['orderID'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($pecah['productName'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($pecah['reason'] ?? 'N/A') ?></td>
                                            <td><?= htmlspecialchars($pecah['qty'] ?? 'N/A') ?></td>
                                            <td>
                                                <a href="returdetail.php?returnID=<?= htmlspecialchars($pecah['returnID']) ?>" class="btn btn-primary btn-sm">View Details</a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center'>No return data found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paginasi -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <a href="?page=<?= $page > 1 ? $page - 1 : 1 ?>" class="btn btn-secondary <?= $page <= 1 ? 'disabled' : '' ?>">Previous</a>
                <span class="mx-2">Page <?= $page ?> of <?= $totalPages ?></span>
                <a href="?page=<?= $page < $totalPages ? $page + 1 : $totalPages ?>" class="btn btn-secondary <?= $page >= $totalPages ? 'disabled' : '' ?>">Next</a>
            </div>
            <div>
                <a href="addretur.php" class="btn btn-primary">Add Return</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>