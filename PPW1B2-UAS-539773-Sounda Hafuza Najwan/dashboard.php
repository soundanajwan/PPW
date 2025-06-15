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
    p.PRODUK_ID AS ID, 
    p.NAMA_PRODUK AS Nama_Produk, 
    p.KATEGORI AS Kategori, 
    COALESCE(s.NAMA_SUPPLIER, 'Supplier Kosong') AS Supplier, 
    p.STOK AS Stock, 
    p.HARGA_SATUAN AS Harga, 
    COALESCE(dp_terakhir.HARGA_BELI, 0) AS Harga2
FROM PRODUK p 
LEFT JOIN SUPPLIER s ON p.SUPPLIER_ID = s.SUPPLIER_ID
LEFT JOIN (
    SELECT dp1.PRODUK_ID, dp1.HARGA_BELI
    FROM DETAIL_PEMBELIAN dp1
    INNER JOIN (
        SELECT PRODUK_ID, MAX(DETAIL_PEMBELIAN_ID) AS MaxID
        FROM DETAIL_PEMBELIAN
        GROUP BY PRODUK_ID
    ) dp2 ON dp1.PRODUK_ID = dp2.PRODUK_ID AND dp1.DETAIL_PEMBELIAN_ID = dp2.MaxID
) dp_terakhir ON p.PRODUK_ID = dp_terakhir.PRODUK_ID";

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
