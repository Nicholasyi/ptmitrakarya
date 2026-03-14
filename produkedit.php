<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require "config.php";
require "functions.php";
include 'header.php';

// Get product data
$ambil = $koneksi->query("SELECT * FROM produk WHERE productID='$_GET[id]'");
$pecah = $ambil->fetch_assoc();
?>

<div class="display-kontainer kontainer px-5 pt-5" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1>Edit Product Data</h1>
		<div class="row">
			<div class="col l12">
				<div class="kontainer">
					<form method="post">
						<div class="kolominput mb-3">
							<label>Product Name</label>
							<input type="text" name="name" value="<?php echo htmlspecialchars($pecah['name']); ?>" class="form-control" required>
						</div>
						<div class="kolominput mb-3">
							<label>Description</label>
							<textarea name="description" class="form-control" required><?php echo htmlspecialchars($pecah['description']); ?></textarea>
						</div>
						<div class="kolominput mb-3">
							<label>Price</label>
							<input type="number" name="price" value="<?php echo $pecah['price']; ?>" class="form-control" required>
						</div>
						<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
if (isset($_POST['simpan'])) {
	$name = mysqli_real_escape_string($koneksi, $_POST['name']);
	$description = mysqli_real_escape_string($koneksi, $_POST['description']);
	$price = (int) $_POST['price'];

	if(validatePrice($price)){
		$koneksi->query("UPDATE produk SET name='$name', description='$description', price='$price' WHERE productID='$_GET[id]'");
		showSuccessAlert("Produk berhasil diedit !", "produkdaftar.php");
	}
	else{
		showErrorAlert();
	}
}
?>

<?php include 'footer.php'; ?>
