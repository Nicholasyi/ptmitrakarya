<?php
require "config.php";
include 'header.php';

// Get current user from session
$user_id = $_SESSION['user']['id'] ?? null;

// Fetch fresh user data from database
if ($user_id) {
    $result = $koneksi->query("SELECT * FROM pengguna WHERE id = '$user_id'");
    $current_user = $result->fetch_assoc();
} else {
    $current_user = $_SESSION['user'] ?? null;
}

// Handle form submission for updating user info
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    
    if (!empty($name) && !empty($email)) {
        $query = $koneksi->query("UPDATE pengguna SET name='$name', email='$email' WHERE id='$user_id'");
        
        if ($query) {
            // Update session with new data
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            $current_user['name'] = $name;
            $current_user['email'] = $email;
            echo "<script>alert('Account updated successfully!');</script>";
        }
    }
}

// Handle password change form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = mysqli_real_escape_string($koneksi, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);
    
    // Verify current password
    if ($current_password === $current_user['password']) {
        if ($new_password === $confirm_password && !empty($new_password)) {
            $query = $koneksi->query("UPDATE pengguna SET password='$new_password' WHERE id='$user_id'");
            
            if ($query) {
                $_SESSION['user']['password'] = $new_password;
                echo "<script>alert('Password changed successfully!');</script>";
            }
        } else {
            echo "<script>alert('New passwords do not match or are empty!');</script>";
        }
    } else {
        echo "<script>alert('Current password is incorrect!');</script>";
    }
}
?>

<div class="display-kontainer kontainer px-5 pt-5" style="padding-bottom: 50px;">
    <div class="kontainer padding-32" style="max-width: 600px; margin: 0 auto;">
        <h1 class="text-center mb-4">Account Settings</h1>

        <div class="row">
            <div class="col-12">
                <!-- User Information Card -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Update Profile</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($current_user['name'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($current_user['email'] ?? ''); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="access" class="form-label">Access Level</label>
                                <input type="text" class="form-control" id="access" name="access" value="<?php echo htmlspecialchars($current_user['access'] ?? ''); ?>" readonly>
                            </div>

                            <div class="text-center">
                                <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Change Card -->
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>

                            <div class="text-center">
                                <button type="submit" name="change_password" class="btn btn-warning">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>