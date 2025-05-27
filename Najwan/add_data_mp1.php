<?php
include 'koneksi_mp1.php';

header('Content-Type: application/json');

$nama = $_POST['nama'] ?? '';
$kategori = $_POST['kategori'] ?? '';
$supplier_id = $_POST['supplier'] ?? '';
$stok = intval($_POST['stok'] ?? 0);
$harga = floatval($_POST['harga'] ?? 0);
$id = $_POST['ID'] ?? '';

if ($nama && $kategori && $supplier_id && $id) {
    $cek = $conn->prepare("SELECT PRODUK_ID FROM produk WHERE PRODUK_ID = ?");
    $cek->bind_param("s", $id);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo json_encode(['status' => 'duplicate']);
    } else {
        $stmt = $conn->prepare("INSERT INTO produk (PRODUK_ID, NAMA_PRODUK, KATEGORI, STOK, HARGA_SATUAN, SUPPLIER_ID) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssids", $id, $nama, $kategori, $stok, $harga, $supplier_id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $stmt->error]);
        }

        $stmt->close();
    }

    $cek->close();
} else {
    echo json_encode(['status' => 'invalid', 'message' => 'Data tidak lengkap']);
}

$conn->close();
