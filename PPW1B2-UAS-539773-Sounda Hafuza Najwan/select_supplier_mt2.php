<?php
include 'koneksi_mp1.php';

if (isset($_GET['produk_id'])) {
    $produk_id = $_GET['produk_id'];
    $query = "SELECT s.SUPPLIER_ID, s.NAMA_SUPPLIER FROM supplier s
              WHERE s.SUPPLIER_ID IN (
              SELECT SUPPLIER_ID FROM PRODUK WHERE PRODUK_ID = '$produk_id'
              )";
} else {
    $query = "SELECT SUPPLIER_ID, NAMA_SUPPLIER FROM supplier";
}

$result = mysqli_query($conn, $query);
$suppliers = [];

while ($row = mysqli_fetch_assoc($result)) {
    $suppliers[] = $row;
}

echo json_encode($suppliers);
?>