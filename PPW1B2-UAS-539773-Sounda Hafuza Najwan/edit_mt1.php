<?php
include 'koneksi_mp1.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("
            SELECT dp.*, pj.TANGGAL, pr.NAMA_PRODUK 
            FROM DETAIL_PENJUALAN dp
            JOIN PENJUALAN pj ON dp.PENJUALAN_ID = pj.PENJUALAN_ID
            LEFT JOIN PRODUK pr ON dp.PRODUK_ID = pr.PRODUK_ID
            WHERE dp.DETAIL_ID = ?
        ");
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
    $tanggal = $_POST['Tanggal'] ?? '';
    $id = $_POST['ID'] ?? '';
    $idp = $_POST['IDP'] ?? '';
    $produk_id = $_POST['Nama_Produk'] ?? '';
    $jumlah = intval($_POST['Jumlah'] ?? 0);

    if (!$tanggal || !$id || !$idp || !$produk_id || $jumlah <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
        exit;
    }

    $date_obj = date_create($tanggal);
    if (!$date_obj) {
        echo json_encode(['status' => 'error', 'message' => 'Format tanggal tidak valid']);
        exit;
    }
    $tanggal_formatted = date_format($date_obj, 'Y-m-d');

    $conn->begin_transaction();
    try {

        $affected = 0;
        $cekPenjualan = $conn->prepare("SELECT PENJUALAN_ID FROM PENJUALAN WHERE PENJUALAN_ID = ?");
        $cekPenjualan->bind_param("s", $idp);
        $cekPenjualan->execute();
        $cekPenjualan->store_result();

        if ($cekPenjualan->num_rows === 0) {
            $buatPenjualan = $conn->prepare("INSERT INTO PENJUALAN (PENJUALAN_ID, TANGGAL) VALUES (?, ?)");
            $buatPenjualan->bind_param("ss", $idp, $tanggal_formatted);
            $buatPenjualan->execute();
            $buatPenjualan->close();
        } else {
            $updateTanggal = $conn->prepare("UPDATE PENJUALAN SET TANGGAL = ? WHERE PENJUALAN_ID = ?");
            $updateTanggal->bind_param("ss", $tanggal_formatted, $idp);
            $updateTanggal->execute();
            $affected += $updateTanggal->affected_rows;
            $updateTanggal->close();
        }
        $cekPenjualan->close();
        
        $getProduk = $conn->prepare("SELECT HARGA_SATUAN, NAMA_PRODUK FROM PRODUK WHERE PRODUK_ID = ?");
        $getProduk->bind_param("s", $produk_id);
        $getProduk->execute();
        $result = $getProduk->get_result();
        $produkData = $result->fetch_assoc();
        $getProduk->close();

        if (!$produkData || is_null($produkData['HARGA_SATUAN'])) {
            throw new Exception('Produk tidak ditemukan atau tidak memiliki harga.');
        }

        $harga_satuan = floatval($produkData['HARGA_SATUAN']);
        $nama_produk = $produkData['NAMA_PRODUK'];
        $subtotal = $harga_satuan * $jumlah;

        $stmt = $conn->prepare("UPDATE DETAIL_PENJUALAN SET PENJUALAN_ID = ?, PRODUK_ID = ?, JUMLAH = ?, SUBTOTAL = ? WHERE DETAIL_ID = ?");
        $stmt->bind_param("ssids", $idp, $produk_id, $jumlah, $subtotal, $id);
        $stmt->execute();
        $affected += $stmt->affected_rows;

        if ($affected > 0) {
            $conn->commit();
            echo json_encode(['status' => 'success']);
        } else {
            $conn->rollback();
            echo json_encode(['status' => 'no_change', 'message' => 'Tidak ada data yang diperbarui.']);
        }

        
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Update gagal: ' . $e->getMessage()]);
    }
}
?>