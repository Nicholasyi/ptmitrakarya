<?php
require "config.php";
?>
<?php include 'header.php'; ?>
<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1>Payment List</h1>

		<div class="row">
			<div class="col-12">
				<div class="card shadow mb-4">
					<div class="card-body">
						<table class="table table-bordered table-striped" id="table">
							<thead class="bg-primary text-white">
								<tr>
									<th>No</th>
									<th>Payment Date</th>
									<th>Customer ID</th>
									<th>Product</th>
                                    <th>Quantity</th>
									<th>Total Amount</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$nomor = 1;
								$ambil = $koneksi->query("
    							SELECT i.*, pr.name AS productName 
   								FROM invoice i
    							JOIN produk pr ON i.productID = pr.productID
								");
								while ($row = $ambil->fetch_assoc()) {
								?>
									<tr>
										<td><?php echo $nomor; ?></td>
										<td><?php echo htmlspecialchars($row['paymentDate']); ?></td>
										<td><?php echo htmlspecialchars($row['customerID']); ?></td>
										<td><?php echo htmlspecialchars($row['productName']); ?></td>
										<td><?php echo htmlspecialchars($row['qty']); ?></td>
										<td>Rp <?php echo number_format($row['totalAmount'], 2, ',', '.'); ?></td>
										<td>
											<a href="tesfaktur.php?invoiceID=<?= $row['invoiceID'] ?>" class="btn btn-primary" target="_blank">View Details</a>
										</td>

									</tr>
								<?php $nomor++; } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="row mb-3">
				<div class="col text-end">
					<a href="Invoicedetail.php" class="btn btn-primary">
						<i class="bi bi-plus-lg"></i> Add Payment
					</a>
				</div>
			</div>

		</div>
	</div>
</div>
<?php include 'footer.php'; ?>
