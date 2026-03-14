<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require 'config.php';
require 'functions.php'; // Use config instead of koneksi
?>

<?php include 'header.php'; ?>
<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
    <div class="kontainer padding-32">
        <h1>Add Supplier Data</h1>
        <div class="row">
            <div class="col l12">
                <div class="kontainer">
				<form method="post">
					<div class="kolominput mb-3">
						<label>Name</label>
						<input type="text" name="name" class="form-control" required>
					</div>
					<div class="kolominput mb-3">
						<label>Telephone Number</label>
						<input type="text" name="phone" class="form-control" required>
					</div>
					<div class="kolominput mb-3">
						<label>Email</label>
						<input type="email" name="email" class="form-control" required>
					</div>
					<div class="kolominput mb-3">
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
if (isset($_POST['simpan'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];

	if(validateName($name) && validatePhone($phone) && validateEmail($email)){
		$koneksi->query("INSERT INTO supplier (name, phone, email, address) 
		VALUES ('$name', '$phone', '$email', '$address')");
		showSuccessAlert("Supplier berhasil ditambahkan !","supplierdaftar.php");
	}
	else{
		showErrorAlert();
	}
}
?>

<?php include 'footer.php'; ?>
