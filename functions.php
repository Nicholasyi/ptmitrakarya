<?php
    function validatePrice($price){
        if($price > 0){
            return true;
        }
        return false;
    }

    function validateStock($stock_quantity){
        if($stock_quantity > 0){
            return true;
        }
        return false;
    }

    function showErrorAlert(){
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Input tidak valid',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.history.back();
        });
    </script>";
    exit;
    }

    function showSuccessAlert($message,$location){
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success...',
            text: '$message',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            location = '$location';
        });
    </script>";
    exit;
    }

    function showInsufficientStockAlert($produkID){
         echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Stok produk $produkID tidak mencukupi !',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.history.back();
        });
    </script>";
    exit;
    }

    function showInvalidDate(){
         echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Tanggal Pembelian / Pesanan tidak valid !',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            window.history.back();
        });
    </script>";
    exit;
    }

    function validateName($name){
        if(empty($name)){
            return false;
        }
        if(ctype_alpha($name)){
            return true;
        }
        return false;
    }

    function validatePhone($phone) {
        $phone = trim((string)$phone);
        if(preg_match('/^[0-9]{10,13}$/',$phone)){
            return true;
        }
        return false;
    }

    function validateEmail($email){
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        }
        return false;
    }

    function validateDate($date){
        $input = new DateTime($date);

        $errors = DateTime::getLastErrors();
        if ($errors && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
            return false; 
        }

        $today = new DateTime('today');
        if($input > $today){
            return false;
        }
        return true;
    }

    function total($price,$quantity){
        return $price * $quantity;
    }

    function purchaseProducts($koneksi,$supplierID,$tanggal,$productData){
    
    $query = "INSERT INTO purchase (supplierID, purchaseDate, totalAmount) 
              VALUES ('$supplierID', '$tanggal', '0')";
    
    if (!$koneksi->query($query)) return false;

    $purchaseID = $koneksi->insert_id;
    $grandTotal = 0;

    foreach ($productData as $item) {
        $productID = $item['productID'];
        $harga = $item['harga'];
        $jumlah = $item['jumlah'];
        $total = total($harga,$jumlah); 

        $koneksi->query("INSERT INTO purchase_detail (purchaseID, productID, qty, price) 
                         VALUES ('$purchaseID', '$productID', '$jumlah', '$harga')");

        $koneksi->query("UPDATE inventory SET stock_quantity = stock_quantity + '$jumlah' 
                         WHERE productID = '$productID'");

        $grandTotal += $total;
    }

    $koneksi->query("UPDATE purchase SET totalAmount = '$grandTotal' 
                     WHERE purchaseID = '$purchaseID'");

    return $purchaseID; 
    }

    function orderProducts($koneksi, $customerID, $tanggal, $productData) {
   
    $query = "INSERT INTO penjualan (customerID, orderDate, totalAmount) 
              VALUES ('$customerID', '$tanggal', '0')";
    
    if (!$koneksi->query($query)) return false;

    $orderID = $koneksi->insert_id;
    $grandTotal = 0;

    foreach ($productData as $item) {
        $productID = $item['productID'];
        $harga = $item['harga'];
        $jumlah = $item['jumlah'];
        $total = total($harga,$jumlah); 

        $res = $koneksi->query("SELECT stock_quantity FROM inventory WHERE productID = '$productID'");
        $row = $res->fetch_assoc();
        if ($row['stock_quantity'] < $jumlah) {
            return false; 
        }

        $koneksi->query("INSERT INTO penjualan_detail (orderID, productID, qty, price) 
                        VALUES ('$orderID', '$productID', '$jumlah', '$harga')");

        $koneksi->query("UPDATE inventory SET stock_quantity = stock_quantity - '$jumlah' 
                        WHERE productID = '$productID'");

        $grandTotal += $total;
    }
    $koneksi->query("UPDATE penjualan SET totalAmount = '$grandTotal' 
                    WHERE orderID = '$orderID'");

    return $orderID; 
    }

    function addProducts($koneksi, $name, $description, $price, $stock_quantity){
        if(validatePrice($price) && validateStock($stock_quantity)){

            $query = "INSERT INTO produk (name, description, price) VALUES ('$name','$description','$price')";
            $result = $koneksi->query($query);

            if($result){
                $productID = $koneksi->insert_id;

                $sql_inventory = "INSERT INTO inventory (productID, stock_quantity) VALUES ('$productID', '$stock_quantity')";
                $result_inventory = $koneksi->query($sql_inventory);

                return $productID;
            }
        } else{
            return false;
        }
    }

    function returnProducts($koneksi, $orderID, $productData){

        $returnDate = date('Y-m-d');

    if(!validateDate($returnDate)) return false;

    $koneksi->begin_transaction();

    try {
        foreach ($productData as $item) {
            $productID = $item['productID'];
            $qty       = intval($item['jumlah']);
            $reason    = $koneksi->real_escape_string($item['reason']);

            $checkQuery = $koneksi->query("SELECT qty FROM penjualan_detail WHERE orderID = '$orderID' AND productID = '$productID'");
            if ($checkQuery->num_rows === 0) throw new Exception("Product not found");

            $row = $checkQuery->fetch_assoc();
            if ($qty > $row['qty']) throw new Exception("Qty exceeds order");

            // Insert ke tabel return
            $koneksi->query("INSERT INTO `return` (orderID, productID, qty, returnDate, reason)
                             VALUES ('$orderID', '$productID', '$qty', '$returnDate', '$reason')");

            // Update Stok
            $koneksi->query("UPDATE inventory SET stock_quantity = stock_quantity + '$qty' WHERE productID = '$productID'");
        }

        $koneksi->commit(); 
        return true; 
    } catch (Exception $e) {
        $koneksi->rollback(); 
        return false;
    }
    }

    function loginUser($koneksi, $email, $password){

        $email = $koneksi->real_escape_string($email);
        $password = $koneksi->real_escape_string($password);

        $query = "SELECT * FROM pengguna WHERE email='$email' AND password='$password' LIMIT 1 ";
        $result = $koneksi->query($query);

        if ($result && $result->num_rows === 1) {
        $account = $result->fetch_assoc();
        
        $_SESSION['user'] = $account;
        return true; 
        }
        return false;
    }

?>