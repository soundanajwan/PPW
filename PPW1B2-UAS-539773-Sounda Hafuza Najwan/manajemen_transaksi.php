<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'tugas';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi ke database gagal']);
    exit;
}

$sql = "SELECT 
    p.TANGGAL AS Tanggal,
    p.PENJUALAN_ID AS IDP, 
    dp.DETAIL_ID AS ID, 
    COALESCE(dp.NAMA_PRODUK, 'Produk tidak tersedia') AS Nama_Produk,
    dp.JUMLAH AS Jumlah, 
    COALESCE(dp.HARGA_SATUAN, 0) AS Harga,
    dp.SUBTOTAL AS Total,
    CASE 
        WHEN pr.PRODUK_ID IS NULL THEN 0 
        ELSE 1 
    END AS Bisa_Edit
FROM DETAIL_PENJUALAN dp 
LEFT JOIN PENJUALAN p ON dp.PENJUALAN_ID = p.PENJUALAN_ID
LEFT JOIN PRODUK pr ON dp.PRODUK_ID = pr.PRODUK_ID";

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