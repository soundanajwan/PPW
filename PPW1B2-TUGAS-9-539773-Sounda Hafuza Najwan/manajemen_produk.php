<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'tugas2';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi ke database gagal']);
    exit;
}

$sql = "SELECT 
            p.PRODUK_ID AS ID, 
            p.NAMA_PRODUK AS Nama_Produk, 
            p.KATEGORI AS Kategori, 
            COALESCE(s.NAMA_SUPPLIER, 'Kosong') AS Supplier, 
            p.STOK AS Stock, 
            p.HARGA_SATUAN AS Harga 
        FROM PRODUK p 
        LEFT JOIN SUPPLIER s ON p.SUPPLIER_ID = s.SUPPLIER_ID";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Query gagal: ' . $conn->error]);
    exit;
}

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($data);
?>
