<?php
require_once 'koneksi_mp1.php'; 

if (!$conn) {
    die(json_encode(['status' => 'error', 'message' => 'Koneksi database gagal']));
}


$sql = "DELETE FROM DETAIL_PENJUALAN"; 

if (mysqli_query($conn, $sql)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
}
?>
