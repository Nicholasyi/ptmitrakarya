<?php
require "config.php";
?>

<?php include 'header.php'; ?>
<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1>Product List</h1>

		<div class="row">
			<div class="col-12">
				<div class="card shadow mb-4">
					<div class="card-body">
						<table class="table table-bordered table-striped" id="table">
							<thead class="bg-primary text-white">
								<tr>
									<th>No</th>
									<th>Product Name</th>
									<th>Description</th>
									<th>Price</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$nomor = 1;
								$ambil = $koneksi->query("SELECT * FROM produk");
								while ($pecah = $ambil->fetch_assoc()) {
								?>
									<tr>
										<td><?php echo $nomor; ?></td>
										<td><?php echo htmlspecialchars($pecah['name']); ?></td>
										<td><?php echo htmlspecialchars($pecah['description']); ?></td>
										<td>Rp <?php echo number_format($pecah['price'], 2, ',', '.'); ?></td>
										<td>
											<div class="d-flex gap-2">
												<a href="produkedit.php?id=<?php echo $pecah['productID']; ?>" class="btn btn-primary btn-sm">Edit</a>
												<a href="produkhapus.php?id=<?php echo $pecah['productID']; ?>" class="btn btn-primary btn-sm">Delete</a>
											</div>
										</td>
									</tr>
								<?php $nomor++; } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="d-flex justify-content-end mb-3">
				<a href="produktambah.php" class="btn btn-primary">Add Product</a>
			</div>
		</div>
	</div>
</div>
<?php
include 'footer.php';
?>
