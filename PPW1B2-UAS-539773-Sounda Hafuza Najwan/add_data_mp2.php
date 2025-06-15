<?php
include 'koneksi_mp1.php';

header('Content-Type: application/json');

$nama = $_POST['Nama_Supplier'] ?? '';
$id = $_POST['ID2'] ?? '';
$alamat = $_POST['Alamat'] ?? '';
$kontak = $_POST['Kontak'] ?? '';

if ($nama && $alamat && $kontak && $id) {
    $cek = $conn->prepare("SELECT SUPPLIER_ID FROM SUPPLIER WHERE SUPPLIER_ID = ?");
    $cek->bind_param("s", $id);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo json_encode(['status' => 'duplicate']);
    } else {
        $stmt = $conn->prepare("INSERT INTO SUPPLIER (SUPPLIER_ID, NAMA_SUPPLIER, ALAMAT, KONTAK) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $id, $nama, $alamat, $kontak);

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
