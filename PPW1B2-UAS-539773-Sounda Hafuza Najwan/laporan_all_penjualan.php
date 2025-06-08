<?php
header('Content-Type: application/json');

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'tugas';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Koneksi ke database gagal']);
    exit;
}

$sql = "SELECT SUM(dp.SUBTOTAL) AS total_penjualan
        FROM DETAIL_PENJUALAN dp
        LEFT JOIN PENJUALAN p ON dp.PENJUALAN_ID = p.PENJUALAN_ID";

$result = $conn->query($sql);
$data = $result->fetch_assoc();

echo json_encode([
    'total_penjualan' => $data['total_penjualan'] ?? 0
]);
$conn->close();
