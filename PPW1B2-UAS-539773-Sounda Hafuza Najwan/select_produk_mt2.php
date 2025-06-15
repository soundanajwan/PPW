<?php
include 'koneksi_mp1.php'; 

$query = "SELECT PRODUK_ID, NAMA_PRODUK FROM PRODUK";
$result = mysqli_query($conn, $query);

$suppliers = [];

while ($row = mysqli_fetch_assoc($result)) {
    $suppliers[] = $row;
}

echo json_encode($suppliers);
?>
