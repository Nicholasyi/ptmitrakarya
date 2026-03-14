<?php
require "config.php";
?>
<?php include 'header.php'; ?>
<div class="display-kontainer kontainer px-5 pt-3" style="padding-bottom: 50px;">
	<div class="kontainer padding-32">
		<h1>Delivery List</h1>

		<div class="row">
			<div class="col-12">
				<div class="card shadow mb-4">
					<div class="card-body">
						<table class="table table-bordered table-striped" id="table">
							<thead class="bg-primary text-white">
								<tr>
									<th>No</th>
                                    <th>Delivery Date</th>
									<th>Delivery ID</th>
									<th>Customer ID</th>
									<th>Product</th>
									<th>Quantity</th>
								</tr>	
								</thead>	
								<tbody>
								<?php
								$nomor = 1;
								$ambil = $koneksi->query("
							    SELECT p.*, pr.name AS productName 
						    	FROM pengiriman p
    							JOIN produk pr ON p.productID = pr.productID
								");
								while ($row = $ambil->fetch_assoc()) {
								?>
									<tr>
										<td><?php echo $nomor; ?></td>
										<td><?php echo htmlspecialchars($row['deliveryDate']); ?></td>
										<td><?php echo htmlspecialchars($row['deliveryID']); ?></td>
										<td><?php echo htmlspecialchars($row['customerID']); ?></td>
										<td><?php echo htmlspecialchars($row['productName']); ?></td>
										<td><?php echo htmlspecialchars($row['qty']); ?></td>
									</tr>
								<?php $nomor++; } ?>
							</tbody>				
						</table>
					</div>
				</div>
			</div>
			<div class="row mb-3">
    <div class="col text-end">
        <a href="deliverydetail.php" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Delivery
        </a>
    </div>
</div>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>
