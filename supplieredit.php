<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require 'config.php';
require 'functions.php';

?>

<?php include 'header.php'; ?>
<?php
$ambil = $koneksi->query("SELECT * FROM supplier WHERE supplierID='$_GET[id]'");
$pecah = $ambil->fetch_assoc();
?>
<div class="display-kontainer kontainer px-5 pt-5" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1>Edit Supplier Data</h1>
		<div class="row">
			<div class="col l12">
				<div class="kontainer">
					<form method="post">
						<div class="kolominput mb-3">
							<label>Name</label>
							<input type="text" name="name" value="<?php echo $pecah['name']; ?>" class="form-control" required>
						</div>
						<div class="kolominput mb-3">
							<label>Telephone Number</label>
							<input type="text" name="phone" value="<?php echo $pecah['phone']; ?>" class="form-control" required>
						</div>
						<div class="kolominput mb-3">
							<label>Address</label>
							<textarea rows="3" class="form-control" name="address" required><?php echo $pecah['address']; ?></textarea>
						</div>
						<div class="kolominput mb-3">
							<label>Email</label>
							<input type="email" name="email" value="<?php echo $pecah['email']; ?>" class="form-control" required>
						</div>
						<button type="submit" name="simpan" class="btn btn-primary">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
if (isset($_POST['simpan'])) {
	$id = $_GET['id'];
	$name = $_POST['name'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];
	$email = $_POST['email'];

	if(!validateName($name) or !validatePhone($phone)){
		showErrorAlert();
	}

	$koneksi->query("UPDATE supplier SET 
		name='$name', 
		phone='$phone', 
		address='$address', 
		email='$email' 
		WHERE supplierID='$id'");


	echo "<script>location='supplierdaftar.php';</script>";
}
?>

<?php include 'footer.php'; ?>
