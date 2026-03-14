<?php
require "config.php";
include 'header.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = $koneksi->query("SELECT * FROM pengguna WHERE id='$user_id'");
    
    if ($query->num_rows == 0) {
        echo "<script>location='penggunaakun.php';</script>";
        exit;
    }
    
    $user = $query->fetch_assoc();
} else {
    echo "<script>location='penggunaakun.php';</script>";
    exit;
}
?>

<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1 class="pb-2">Edit User</h1>
		<div class="row">
			<div class="col l12">
				<div class="kontainer">
					<form method="post" action="">
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Name</label>
							<input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
						</div>
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Email</label>
							<input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
						</div>
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Access Level</label>
							<select name="access" class="form-control" required>
								<option value="admin" <?php echo ($user['access'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
								<option value="gudang" <?php echo ($user['access'] === 'gudang') ? 'selected' : ''; ?>>Gudang</option>
								<option value="kasir" <?php echo ($user['access'] === 'kasir') ? 'selected' : ''; ?>>Kasir</option>
							</select>
						</div>
						<div class="kolominput" style="margin-bottom: 15px;">
							<label>Change Password (leave blank to keep current)</label>
							<input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
						</div>
						<button type="submit" name="update" class="btn btn-primary">Save Changes</button>
						<a href="penggunaakun.php" class="btn btn-secondary">Cancel</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $access = mysqli_real_escape_string($koneksi, $_POST['access']);
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if (!empty($name) && !empty($email) && !empty($access)) {
        // Check if email already exists (except for current user)
        $check_query = $koneksi->query("SELECT * FROM pengguna WHERE email='$email' AND id != '$user_id'");
        
        if ($check_query->num_rows > 0) {
            echo "<script>alert('Email already exists!');</script>";
        } else {
            if (!empty($password)) {
                $password = mysqli_real_escape_string($koneksi, $password);
                $query = $koneksi->query("UPDATE pengguna SET name='$name', email='$email', password='$password', access='$access' WHERE id='$user_id'");
            } else {
                $query = $koneksi->query("UPDATE pengguna SET name='$name', email='$email', access='$access' WHERE id='$user_id'");
            }
            
            if ($query) {
                echo "<script>alert('User updated successfully!'); location='penggunaakun.php';</script>";
            } else {
                echo "<script>alert('Failed to update user!');</script>";
            }
        }
    }
}
?>

<?php
include 'footer.php';
?>
