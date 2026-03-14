<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require "config.php";
require "functions.php"; 
?>

<?php include 'header.php'; ?>
<div class="display-kontainer kontainer px-5 pt-5" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1>Add Product</h1>
		<br>
		<div class="row">
			<div class="col l12">
				<div class="kontainer">
					<form method="post">
						<div class="kolominput mb-3">
							<label>Product Name</label>
							<input type="text" name="name" class="form-control" required>
						</div>
						<div class="kolominput mb-3">
							<label>Description</label>
							<textarea name="description" class="form-control" required></textarea>
						</div>
						<div class="kolominput mb-3">
							<label>Price</label>
							<input type="number" name="price" class="form-control" required>
						</div>
						<div class="kolominput mb-3">
							<label>Stock</label>
							<input type="number" name="stock_quantity" class="form-control" min="1" required>
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
	$name = $_POST["name"];
	$description = $_POST["description"];
	$price = $_POST["price"];
	$stock_quantity = $_POST["stock_quantity"];

	$result = addProducts($koneksi, $name, $description, $price, $stock_quantity);

	if($result){
		showSuccessAlert("Produk berhasil ditambahkan !","produkdaftar.php");
	} else{
		showErrorAlert();
	}
	}
?>

<?php include 'footer.php'; ?>
