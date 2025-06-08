<?php
include 'koneksi_mp1.php';  

$query = "SELECT SUPPLIER_ID, NAMA_SUPPLIER FROM supplier";
$result = mysqli_query($conn, $query);

$suppliers = [];

while ($row = mysqli_fetch_assoc($result)) {
    $suppliers[] = $row;
}

echo json_encode($suppliers);
?>
