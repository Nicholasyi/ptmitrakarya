<?php
require "config.php";
?>

<?php include 'header.php'; ?>

<div class="display-kontainer kontainer px-5 pt-5" style="padding-bottom: 50px;">
    <div class="kontainer padding-32">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="pb-3">Warehouse</h1>
        </div>
        <div class="row">
            <div class="col l12">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="table">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Stock Quantity</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $nomor = 1;
                                // Modify the query to join the 'inventory' and 'produk' tables based on 'productID'
                                $ambil = $koneksi->query("SELECT i.productID, p.name, i.stock_quantity FROM inventory i
                                                          INNER JOIN produk p ON i.productID = p.productID");
                                ?>
                                <?php while ($pecah = $ambil->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $nomor; ?></td>
                                        <td><?php echo $pecah['productID']; ?></td>
                                        <td><?php echo $pecah['name']; ?></td>
                                        <td><?php echo $pecah['stock_quantity']; ?></td>
                                        
                                    </tr>
                                    <?php $nomor++; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
