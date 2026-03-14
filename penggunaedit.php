<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
require "config.php";
require "functions.php";
include 'header.php';

// Ensure 'customerID' is passed in the URL
if (isset($_GET['customerID'])) {
    $customerID = $_GET['customerID'];  // Get customerID from the URL

    // Fetch customer data based on customerID
    $query = $koneksi->query("SELECT * FROM pelanggan WHERE customerID='$customerID'");
    
    // Check if the record is found
    if ($query->num_rows > 0) {
        $pecah = $query->fetch_assoc();
    } else {
        
        echo "<script>location='penggunadaftar.php';</script>";
        exit;  // Stop further execution if customer is not found
    }
} else {
    
    echo "<script>location='penggunadaftar.php';</script>";
    exit;
}
?>

<div class="display-kontainer kontainer px-5 pt-5" style="padding-bottom: 50px;">
    <div class="kontainer padding-32">
        <h1>Edit Customer</h1>
        <div class="row">
            <div class="col 12">
                <div class="kontainer">
                    <form method="post">
                        <div class="kolominput">
                            <label>Customer Name</label>
                            <input type="text" name="custName" value="<?php echo $pecah['custName']; ?>" class="form-control" required>
                        </div>
                        <div class="kolominput">
                            <label>Telephone</label>
                            <input type="text" name="phone" value="<?php echo $pecah['phone']; ?>" class="form-control" required>
                        </div>
                        <div class="kolominput">
                            <label>Address</label>
                            <textarea name="address" rows="5" class="form-control" required><?php echo $pecah['address']; ?></textarea>
                        </div>
						<br>
                        <button type="submit" name="simpan" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Save the edited data
if (isset($_POST['simpan'])) {
    $custName = $_POST['custName'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if(validateName($custName)&&validatePhone($phone)){
         // Perform the update query
    $koneksi->query("UPDATE pelanggan SET 
        custName='$custName', 
        address='$address', 
        phone='$phone' 
        WHERE customerID='$customerID'");
    showSuccessAlert("Customer berhasil diedit !", "penggunadaftar.php");
    }
    else{
        showErrorAlert();
    }
}

include 'footer.php';
