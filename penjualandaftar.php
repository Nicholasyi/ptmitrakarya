<?php
require "config.php";
?>
<?php include 'header.php'; ?>
<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1>Order List</h1>

		<div class="row">
			<div class="col-12">
				<div class="card shadow mb-4">
					<div class="card-body">
						<table class="table table-bordered table-striped" id="table">
							<thead class="bg-primary text-white">
								<tr>
									<th>No</th>
									<th>Order ID</th>
									<th>Customer ID</th>
									<th>Order Date</th>
									<th>Total Amount</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$nomor = 1;
								$ambil = $koneksi->query("SELECT * FROM penjualan");
								while ($row = $ambil->fetch_assoc()) {
								?>
									<tr>
										<td><?php echo $nomor; ?></td>
										<td><?php echo htmlspecialchars($row['orderID']); ?></td>
										<td><?php echo htmlspecialchars($row['customerID']); ?></td>
										<td><?php echo htmlspecialchars($row['orderDate']); ?></td>
										<td>Rp <?php echo number_format($row['totalAmount'], 2, ',', '.'); ?></td>
										<td>
											<a href="penjualandetail.php?orderID=<?php echo $row['orderID']; ?>" class="btn btn-primary btn-sm">View Details</a>
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
        <a href="penjualantambah.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Order
        </a>
    </div>
</div>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>
