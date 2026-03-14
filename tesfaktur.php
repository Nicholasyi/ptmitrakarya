<?php
require "config.php";

// Ambil ID invoice dari URL
$invoiceID = $_GET['invoiceID'] ?? 0;

// Query data invoice dengan join ke pelanggan dan produk
$query = "SELECT i.*, p.custName, p.address, p.phone, pr.name as productName 
          FROM invoice i
          JOIN pelanggan p ON i.customerID = p.customerID
          JOIN produk pr ON i.productID = pr.productID
          WHERE i.invoiceID = $invoiceID";

$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nota Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px }
        .header { text-align: center; margin-bottom: 30px }
        .info { margin-bottom: 20px }
        table { width: 100%; border-collapse: collapse; margin-top: 20px }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left }
        .total { font-weight: bold; font-size: 1.1em }
        @media print {
            button { display: none }
        }
    </style>
</head>
<body>
    <?php if($result->num_rows > 0): ?>
        <?php 
        $firstRow = $result->fetch_assoc();
        $total = 0;
        $result->data_seek(0); // Reset pointer hasil query
        ?>
        
        <div class="header">
            <h2>NOTA PEMBAYARAN</h2>
            <h3>PT MITRA KARYA UNIVERSAL</h3>
            <p>Alamat, Kota</p>
        </div>

        <div class="info">
            <p>Pelanggan: <strong><?= htmlspecialchars($firstRow['custName']) ?></strong></p>
            <p>Alamat: <?= htmlspecialchars($firstRow['address']) ?></p>
            <p>Telepon: <?= htmlspecialchars($firstRow['phone']) ?></p>
            <p>No. Invoice: <?= $invoiceID ?></p>
            <p>Tanggal: <?= date('d/m/Y', strtotime($firstRow['paymentDate'])) ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga Satuan</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): 
                    $subtotal = $row['totalAmount'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['productName']) ?></td>
                        <td>Rp <?= number_format($row['totalAmount']/$row['qty'], 0, ',', '.') ?></td>
                        <td><?= $row['qty'] ?></td>
                        <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="total" style="margin-top: 20px; text-align: right">
            TOTAL PEMBAYARAN: Rp <?= number_format($total, 0, ',', '.') ?>
        </div>
    <?php else: ?>
        <p>Invoice tidak ditemukan</p>
    <?php endif; ?>
</body>
</html>