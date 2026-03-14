<?php
require "config.php";
?>

<?php include 'header.php'; ?>
<div class="display-kontainer kontainer" style="padding-bottom: 50px;">
	<div class="kontainer padding-32 px-5" >
		<h1>Customer Data</h1>
<br>

		<div class="row ">
			<div class="col-12">
				<div class="card shadow mb-4">

					<div class="card-body">
						<table class="table table-bordered table-striped" id="table">
							<thead class="bg-primary text-white">
								<tr>
									<th>No</th>
									<th>Name</th>

									<th>Telephone</th>
									<th>Address</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php $number = 1; ?>
								<?php
								$query= $koneksi->query("SELECT * FROM pelanggan");
								?>
								<?php while ($pecah = $query->fetch_assoc()) { ?>
									<tr>
										<td><?php echo $number; ?></td>
										<td><?php echo $pecah['custName'] ?></td>
										<td><?php echo $pecah['phone'] ?></td>
										<td><?php echo $pecah['address'] ?></td>
										<td>
											<!-- Edit Form -->
											<a href="penggunaedit.php?customerID=<?php echo $pecah['customerID']; ?>" class="btn btn-primary btn-sm">Edit</a>

											<!-- Delete Form -->
											<form action="penggunahapus.php" method="POST" style="display:inline;">
												<input type="hidden" name="customerID" value="<?php echo $pecah['customerID']; ?>">
												<button type="submit" name="delete" class="btn btn-primary btn-sm">Delete</button>
											</form>
										</td>
									</tr>
									<?php $number++; ?>
								<?php } ?>
							</tbody>
						</table>
						
	
					</div>
				</div>
			</div>
		</div>
		<div class="d-flex justify-content-end mb-3">
    <a href="penggunatambah.php" class="btn btn-primary">Add Customer</a>
</div>
	</div>
</div>
<?php
include 'footer.php';
?>