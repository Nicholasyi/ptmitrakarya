<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require "config.php";
require "functions.php";
include 'header.php'; 
?>

<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1 class="pb-2">Add Customer</h1>
		<div class="row">
			<div class="col l12">
				<div class="kontainer">
					<form method="post" action="">
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Name</label>
							<input type="text" name="custName" class="form-control" required>
						</div>
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Telephone</label>
							<input type="text" name="phone" class="form-control" required>
						</div>
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Address</label>
							<textarea rows="5" class="form-control" name="address" required></textarea>
						</div>
						<button type="submit" name="simpan" class="btn btn-primary">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
// Check if the form is submitted
if (isset($_POST['simpan'])) {
    // Sanitize inputs to prevent SQL injection
    $custName = mysqli_real_escape_string($koneksi, $_POST['custName']);
    $phone = mysqli_real_escape_string($koneksi, $_POST['phone']);
    $address = mysqli_real_escape_string($koneksi, $_POST['address']);
    
	if(validateName($custName) && validatePhone($phone)){
	// Insert data into the pelanggan table
    $query = $koneksi->query("INSERT INTO pelanggan (custName, address, phone) 
                            VALUES('$custName', '$address', '$phone')");
	showSuccessAlert("Customer berhasil ditambahkan !", "penggunadaftar.php");
	}
	else{
		showErrorAlert();
	}
}
?>

<?php
include 'footer.php';
?>
