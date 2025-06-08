<?php
include 'koneksi_mp1.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("SELECT * FROM SUPPLIER WHERE SUPPLIER_ID = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode($data);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID tidak dikirim']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    file_put_contents('debug_log.txt', print_r($_POST, true));

    $nama = $_POST['Nama_Supplier'] ?? '';
    $id = $_POST['ID2'] ?? '';
    $alamat = $_POST['Alamat'] ?? '';
    $kontak = $_POST['Kontak'] ?? '';

    if (empty($id)) {
        echo json_encode(['status' => 'error', 'message' => 'Supplier ID kosong']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE SUPPLIER SET NAMA_SUPPLIER=?, ALAMAT=?, KONTAK=? WHERE SUPPLIER_ID=?");
    $stmt->bind_param("ssss", $nama, $alamat, $kontak, $id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'no_change', 'message' => 'Data tidak berubah atau ID tidak ditemukan']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
}
?>
