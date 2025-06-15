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

$month = isset($_GET['month']) ? intval($_GET['month']) : null;

if ($month && $month >= 1 && $month <= 12) {
    $sql = "SELECT 
        p.TANGGAL AS Tanggal,
        SUM(dp.SUBTOTAL) AS Total,
        SUM(dp.Jumlah) AS Jumlah
    FROM DETAIL_PENJUALAN dp
    LEFT JOIN PENJUALAN p ON dp.PENJUALAN_ID = p.PENJUALAN_ID
    WHERE MONTH(p.TANGGAL) = $month
    GROUP BY p.TANGGAL";
} else {
    $sql = "SELECT 
        p.TANGGAL AS Tanggal,
        SUM(dp.SUBTOTAL) AS Total,
        SUM(dp.Jumlah) AS Jumlah
    FROM DETAIL_PENJUALAN dp
    LEFT JOIN PENJUALAN p ON dp.PENJUALAN_ID = p.PENJUALAN_ID
    GROUP BY p.TANGGAL";
}

$result = $conn->query($sql);
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Query gagal: ' . $conn->error]);
    exit;
}

$data = [];
while ($row = $result->fetch_assoc()) {
    $row['ID'] = $row['Tanggal']; 
    $data[] = $row;
}

$conn->close();
header('Content-Type: application/json');
echo json_encode($data);
?>