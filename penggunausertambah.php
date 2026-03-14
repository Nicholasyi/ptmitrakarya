<?php
require "config.php";
include 'header.php';
?>

<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1 class="pb-2">Add New User</h1>
		<div class="row">
			<div class="col l12">
				<div class="kontainer">
					<form method="post" action="">
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Name</label>
							<input type="text" name="name" class="form-control" required>
						</div>
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Email</label>
							<input type="email" name="email" class="form-control" required>
						</div>
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Password</label>
							<input type="password" name="password" class="form-control" required>
						</div>
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Access Level</label>
							<select name="access" class="form-control" required>
								<option value="">-- Select Access Level --</option>
								<option value="admin">Admin</option>
								<option value="gudang">Gudang</option>
								<option value="kasir">Kasir</option>
							</select>
						</div>
						<button type="submit" name="simpan" class="btn btn-primary">Save</button>
						<a href="penggunaakun.php" class="btn btn-secondary">Cancel</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
if (isset($_POST['simpan'])) {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $access = mysqli_real_escape_string($koneksi, $_POST['access']);
    
    if (!empty($name) && !empty($email) && !empty($password) && !empty($access)) {
        // Check if email already exists
        $check_query = $koneksi->query("SELECT * FROM pengguna WHERE email='$email'");
        if ($check_query->num_rows > 0) {
            echo "<script>alert('Email already exists!');</script>";
        } else {
            $query = $koneksi->query("INSERT INTO pengguna (name, email, password, access) 
                                      VALUES('$name', '$email', '$password', '$access')");
            
            if ($query) {
                echo "<script>alert('User added successfully!'); location='penggunaakun.php';</script>";
            } else {
                echo "<script>alert('Failed to add user!');</script>";
            }
        }
    }
}
?>

<?php
include 'footer.php';
?>
