<?php
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

$month = isset($_GET['month']) ? intval($_GET['month']) : 0;

error_log("BULAN = " . $month);

$sql = "SELECT 
            p.TANGGAL AS Tanggal2,
            p.PEMBELIAN_ID AS ID2,
            SUM(dp.SUBTOTAL) AS Total2,
            SUM(dp.Jumlah) AS Jumlah2
        FROM DETAIL_PEMBELIAN dp
        LEFT JOIN Pembelian p ON dp.PEMBELIAN_ID = p.PEMBELIAN_ID";

if ($month >= 1 && $month <= 12) {
    $sql .= " WHERE MONTH(p.TANGGAL) = $month";
}

$sql .= " GROUP BY p.PEMBELIAN_ID";

$result = $conn->query($sql);
if (!$result) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Query gagal: ' . $conn->error]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($data);
