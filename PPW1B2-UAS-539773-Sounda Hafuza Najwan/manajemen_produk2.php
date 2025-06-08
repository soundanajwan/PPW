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
            S.NAMA_SUPPLIER AS Nama_Supplier,
            S.SUPPLIER_ID AS ID2,
            S.ALAMAT AS Alamat,
            S.KONTAK AS Kontak
        FROM SUPPLIER S";

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
