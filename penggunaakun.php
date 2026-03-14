<?php
require "config.php";

// Allow all users to view, but store admin status for conditional display
$is_admin = ($_SESSION['user']['access'] === 'admin');

include 'header.php';
?>

<div class="display-kontainer kontainer px-5 pt-5" style="padding-bottom: 50px;">
	<div class="kontainer padding-32 px-5" >
		<h1>Users</h1>
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
									<th>Email</th>
									<th>Access</th>
									<?php if ($is_admin): ?><th>Action</th><?php endif; ?>
								</tr>
							</thead>
							<tbody>
								<?php $number = 1; ?>
								<?php
								$query = $koneksi->query("SELECT * FROM pengguna");
								while ($row = $query->fetch_assoc()) { 
									$is_current_user = ($row['id'] == $_SESSION['user']['id']); ?>
									<tr>
										<td><?php echo $number; ?></td>
										<td><?php echo htmlspecialchars($row['name']); ?></td>
										<td><?php echo htmlspecialchars($row['email']); ?></td>
										<td><?php echo htmlspecialchars($row['access']); ?></td>
									<?php if ($is_admin): ?>
									<td>
										<a href="penggunauseredit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
										<?php if (!$is_current_user): ?>
											<form action="penggunauserhapus.php" method="POST" style="display:inline;">
												<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
												<button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
											</form>
										<?php endif; ?>
									</td>
									<?php endif; ?>
									</tr>
									<?php $number++; ?>
								<?php } ?>
							</tbody>
						</table>
						
			
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($is_admin): ?>
	<div class="d-flex justify-content-end mb-3">
    	<a href="penggunausertambah.php" class="btn btn-primary">Add User</a>
	</div>
	<?php endif; ?>
</div>

<?php
include 'footer.php';
?>