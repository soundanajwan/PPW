<?php
include 'koneksi_mp1.php';
header('Content-Type: application/json');

$tanggal = $_POST['Tanggal'] ?? '';
$idp = $_POST['IDP'] ?? '';              
$id = $_POST['ID'] ?? '';                
$nama = $_POST['Nama_Produk'] ?? '';     
$jumlah = intval($_POST['Jumlah'] ?? 0); 

if (!$tanggal || !$id || !$nama || !$jumlah || !$idp) {
    echo json_encode(['status' => 'invalid', 'message' => 'Data tidak lengkap']);
    exit;
}

$date_obj = date_create($tanggal);
if ($date_obj === false) {
    echo json_encode(['status' => 'invalid', 'message' => 'Format tanggal tidak valid']);
    exit;
}
$tanggal_formatted = date_format($date_obj, 'Y-m-d');

try {
    $cek = $conn->prepare("SELECT 1 FROM detail_penjualan WHERE DETAIL_ID = ?");
    $cek->bind_param("s", $id);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo json_encode(['status' => 'duplicate', 'message' => 'ID detail sudah digunakan']);
        $cek->close();
        $conn->close();
        exit;
    }
    $cek->close();

    $conn->begin_transaction();

    $cekPenjualan = $conn->prepare("SELECT 1 FROM penjualan WHERE PENJUALAN_ID = ?");
    $cekPenjualan->bind_param("s", $idp);
    $cekPenjualan->execute();
    $cekPenjualan->store_result();

    if ($cekPenjualan->num_rows === 0) {
        $cekPenjualan->close(); 

        $insertPenjualan = $conn->prepare("INSERT INTO penjualan (PENJUALAN_ID, TANGGAL) VALUES (?, ?)");
        $insertPenjualan->bind_param("ss", $idp, $tanggal_formatted);
        if (!$insertPenjualan->execute()) {
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Insert penjualan gagal: ' . $insertPenjualan->error]);
            $insertPenjualan->close();
            $conn->close();
            exit;
        }
        $insertPenjualan->close();
    } else {
        $cekPenjualan->close();
    }

    $insertDetail = $conn->prepare("INSERT INTO detail_penjualan (DETAIL_ID, PENJUALAN_ID, PRODUK_ID, JUMLAH) VALUES (?, ?, ?, ?)");
    $insertDetail->bind_param("sssi", $id, $idp, $nama, $jumlah);

    if ($insertDetail->execute()) {
        $conn->commit();
        echo json_encode(['status' => 'success']);
    } else {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Insert detail gagal: ' . $insertDetail->error]);
    }

    $insertDetail->close();

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['status' => 'error', 'message' => 'Transaksi gagal: ' . $e->getMessage()]);
}

$conn->close();
