<?php
require "config.php";

// Helper functions for formatting date
function tanggal($tgl)
{
    $tanggal = substr($tgl, 8, 2);
    $bulan = getBulan(substr($tgl, 5, 2));
    $tahun = substr($tgl, 0, 4);
    return $tanggal . ' ' . $bulan . ' ' . $tahun;
}

function getBulan($bln)
{
    switch ($bln) {
        case 1: return "Januari"; break;
        case 2: return "Februari"; break;
        case 3: return "Maret"; break;
        case 4: return "April"; break;
        case 5: return "Mei"; break;
        case 6: return "Juni"; break;
        case 7: return "Juli"; break;
        case 8: return "Agustus"; break;
        case 9: return "September"; break;
        case 10: return "Oktober"; break;
        case 11: return "November"; break;
        case 12: return "Desember"; break;
    }
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

// Get the current month
$thismonth = date('m');

// Total purchases this month (in price)
$stmt = $koneksi->prepare("SELECT SUM(totalAmount) as total FROM purchase WHERE MONTH(purchaseDate) = ?");
$stmt->bind_param("s", $thismonth);
$stmt->execute();
$result = $stmt->get_result();
$totalpembelian = $result->fetch_assoc()['total'] ?? 0;
$stmt->close();

// Total sales this month (in price)
$stmt = $koneksi->prepare("SELECT SUM(totalAmount) as total FROM penjualan WHERE MONTH(orderDate) = ?");
$stmt->bind_param("s", $thismonth);
$stmt->execute();
$result = $stmt->get_result();
$totalpenjualan = $result->fetch_assoc()['total'] ?? 0;
$stmt->close();

// Total number of orders (all time)
$stmt = $koneksi->prepare("SELECT COUNT(*) as total_orders FROM penjualan");
$stmt->execute();
$result = $stmt->get_result();
$total_orders_today = $result->fetch_assoc()['total_orders'] ?? 0;
$stmt->close();


// Total number of purchases (all time)
$stmt = $koneksi->prepare("SELECT COUNT(*) as total_purchases FROM purchase");
$stmt->execute();
$result = $stmt->get_result();
$total_purchases_today = $result->fetch_assoc()['total_purchases'] ?? 0;
$stmt->close();


// Fetch recent orders made today
$stmt = $koneksi->prepare("SELECT p.orderDate, p.totalAmount, c.custName 
                            FROM penjualan p 
                            INNER JOIN pelanggan c ON p.customerID = c.customerID
                            WHERE DATE(p.orderDate) = CURDATE() 
                            ORDER BY p.orderDate DESC");

$stmt->execute();
$recent_sales = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>


<?php include 'header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">Dashboard</h1>
    <div class="row flex-row-reverse justify-content-end">
        <div class="col-12 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-text fw-lighter">Total Sales This Month</h4>
                    <h2><?php echo number_format($totalpenjualan, 2, ',', '.'); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-text fw-lighter">Total Purchases This Month </h4>
                    <h2><?php echo number_format($totalpembelian, 2, ',', '.'); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mt-4">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-text fw-lighter">Number of Orders </h4>
                    <h2><?php echo number_format($total_orders_today, 0, ',', '.'); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 mt-4">
            <div class="card text-center">
                <div class="card-body">
                    <h4 class="card-text fw-lighter">Number of Purchases </h4>
                    <h2><?php echo number_format($total_purchases_today, 0, ',', '.'); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h3>Today Order</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recent_sales)): ?>
                        <?php foreach ($recent_sales as $sale): ?>
                            <tr>
                                <td><?php echo tanggal($sale['orderDate']); ?></td>
                                <td><?php echo htmlspecialchars($sale['custName']); ?></td>
                                <td><?php echo number_format($sale['totalAmount'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No orders today.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
