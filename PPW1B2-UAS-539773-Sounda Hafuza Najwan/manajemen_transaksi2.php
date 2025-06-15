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
    p.TANGGAL AS Tanggal2,
    p.PEMBELIAN_ID as IDP2,
    dp.DETAIL_PEMBELIAN_ID as ID2,
    COALESCE(p.NAMA_SUPPLIER_BACKUP, 'Supplier tidak tersedia') AS Supplier2, 
    COALESCE(dp.nama_produk_backup, 'Produk tidak tersedia') AS Produk2,
    dp.JUMLAH AS Jumlah2, 
    COALESCE(dp.harga_beli, 0) AS Harga2,
    dp.SUBTOTAL AS Total2,
    CASE 
        WHEN pr.PRODUK_ID IS NULL OR s.SUPPLIER_ID IS NULL THEN 0 
        ELSE 1 
    END AS Bisa_Edit
FROM detail_pembelian dp 
LEFT JOIN pembelian p ON dp.pembelian_id = p.pembelian_id
LEFT JOIN produk pr ON dp.produk_id = pr.produk_id
LEFT JOIN supplier s ON pr.supplier_id = s.supplier_id;
";

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
