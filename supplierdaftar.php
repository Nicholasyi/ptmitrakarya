<?php
require "config.php";

// Access control - only admin can access supplier
if (!isset($_SESSION['user']) || $_SESSION['user']['access'] !== 'admin') {
    header("Location: beranda.php");
    exit;
}
?>

<?php include 'header.php'; ?>
<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1 class="pb-3">Supplier List</h1>

		<div class="row">
			<div class="col l12">
				<div class="card shadow mb-4">

					<div class="card-body">
						<table class="table table-bordered table-striped" id="table">
							<thead class="bg-primary text-white">
								<tr>
									<th>No</th>
									<th>Name</th>
									<th>Telephone</th>
									<th>Email</th>
									<th>Address</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $nomor = 1; ?>
								<?php $ambil = $koneksi->query("SELECT * FROM supplier"); ?>
								<?php while ($pecah = $ambil->fetch_assoc()) { ?>
									<tr>
										<td><?php echo $nomor; ?></td>
										<td><?php echo htmlspecialchars($pecah['name']); ?></td>
										<td><?php echo htmlspecialchars($pecah['phone']); ?></td>
										<td><?php echo htmlspecialchars($pecah['email']); ?></td>
										<td><?php echo htmlspecialchars($pecah['address']); ?></td>
										
										<td>
											<div class="d-flex gap-2">
												<a href="supplieredit.php?id=<?php echo $pecah['supplierID']; ?>" class="btn btn-primary btn-sm">Edit</a>
												<a href="supplierhapus.php?id=<?php echo $pecah['supplierID']; ?>" class="btn btn-primary btn-sm" >Delete</a>
											</div>
										</td>


									</tr>
									<?php $nomor++; ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="d-flex justify-content-end mb-3">
				<a href="suppliertambah.php" class="btn btn-primary">Add Supplier</a>
			</div>
		</div>
	</div>
</div>
<?php
include 'footer.php';
?>
