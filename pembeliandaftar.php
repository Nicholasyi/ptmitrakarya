<?php
require "config.php";
?>
<?php include 'header.php'; ?>
<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1>Purchase List</h1>

		<div class="row">
			<div class="col-12">
				<div class="card shadow mb-4">
					<div class="card-body">
						<table class="table table-bordered table-striped" id="table">
							<thead class="bg-primary text-white">
								<tr>
									<th>No</th>
									<th>Purchase ID</th>
									<th>Supplier ID</th>
									<th>Purchase Date</th>
									<th>Total Amount</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$nomor = 1;
								$ambil = $koneksi->query("SELECT * FROM purchase ORDER BY purchaseDate DESC");
								while ($row = $ambil->fetch_assoc()) {
								?>
									<tr>
										<td><?php echo $nomor; ?></td>
										<td><?php echo htmlspecialchars($row['purchaseID']); ?></td>
										<td><?php echo htmlspecialchars($row['supplierID']); ?></td>
										<td><?php echo date("d-m-Y", strtotime($row['purchaseDate'])); ?></td>
										<td>Rp <?php echo number_format($row['totalAmount'], 2, ',', '.'); ?></td>
										<td>
											<a href="pembeliandetail.php?purchaseID=<?php echo $row['purchaseID']; ?>" class="btn btn-primary btn-sm">View Details</a>
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
					<a href="pembeliantambah.php" class="btn btn-primary">
						<i class="bi bi-plus-lg"></i> Add Purchase
					</a>
				</div>
			</div>

		</div>
	</div>
</div>
<?php include 'footer.php'; ?>
