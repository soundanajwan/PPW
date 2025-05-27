<?php
include 'koneksi_mp1.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("SELECT * FROM produk WHERE PRODUK_ID = ?");
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

    $id = $_POST['id'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $supplier_id = $_POST['supplier'] ?? '';
    $stok = $_POST['stok'] ?? 0;
    $harga = $_POST['harga'] ?? 0;

    if (empty($supplier_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Supplier ID kosong']);
        exit;
    }

    $stmt = $conn->prepare("UPDATE produk SET NAMA_PRODUK=?, KATEGORI=?, STOK=?, HARGA_SATUAN=?, SUPPLIER_ID=? WHERE PRODUK_ID=?");
    $stmt->bind_param("ssidss", $nama, $kategori, $stok, $harga, $supplier_id, $id);

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
