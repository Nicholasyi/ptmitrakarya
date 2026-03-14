<?php
require "config.php";
include "header.php";

$orderID = $_GET['orderID'];

$detail = $koneksi->query("SELECT * FROM penjualan_detail WHERE orderID = '$orderID'");
?>

<div class="container py-4">
	<h2>Order Details for Order ID: <?php echo htmlspecialchars($orderID); ?></h2>
	<table class="table table-bordered table-striped mt-3">
		<thead class="bg-secondary text-white">
			<tr>
				<th>Order Detail ID</th>
				<th>Order ID</th>
				<th>Product ID</th>
				<th>Quantity</th>
				<th>Price</th>
			</tr>
		</thead>
		<tbody>
			<?php while ($row = $detail->fetch_assoc()) { ?>
				<tr>
					<td><?php echo htmlspecialchars($row['orderDetailID']); ?></td>
					<td><?php echo htmlspecialchars($row['orderID']); ?></td>
					<td><?php echo htmlspecialchars($row['productID']); ?></td>
					<td><?php echo htmlspecialchars($row['qty']); ?></td>
					<td>Rp <?php echo number_format($row['price'], 2, ',', '.'); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<a href="javascript:history.back()" class="btn btn-secondary">Back</a>
</div>

<?php include "footer.php"; ?>
