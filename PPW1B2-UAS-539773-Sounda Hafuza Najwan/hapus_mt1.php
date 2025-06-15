<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";
$database = "tugas";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    $stmt = $conn->prepare("DELETE FROM DETAIL_PENJUALAN WHERE DETAIL_ID = ?");
    $stmt->bind_param("s", $id); 

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan.']);
}

$conn->close();
?>
