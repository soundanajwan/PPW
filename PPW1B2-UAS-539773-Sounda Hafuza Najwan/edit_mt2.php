<?php
include 'koneksi_mp1.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("
            SELECT dp.*, p.TANGGAL, s.SUPPLIER_ID, s.NAMA_SUPPLIER, pr.NAMA_PRODUK
            FROM DETAIL_PEMBELIAN dp
            JOIN PEMBELIAN p ON dp.PEMBELIAN_ID = p.PEMBELIAN_ID
            LEFT JOIN PRODUK pr ON dp.PRODUK_ID = pr.PRODUK_ID
            LEFT JOIN SUPPLIER s ON p.SUPPLIER_ID = s.SUPPLIER_ID
            WHERE dp.DETAIL_PEMBELIAN_ID = ?");
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

    $tanggal = $_POST['Tanggal2'] ?? '';
    $idp = $_POST['IDP2'] ?? '';
    $id = $_POST['ID2'] ?? '';
    $supplier = $_POST['Supplier2'] ?? '';
    $produk = $_POST['Produk2'] ?? '';
    $jumlah = $_POST['Jumlah2'] ?? 0;

    if (!$tanggal || !$id || !$idp || !$supplier || !$produk || $jumlah <= 0) {
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
        $cekPenjualan = $conn->prepare("SELECT SUPPLIER_ID FROM PEMBELIAN WHERE PEMBELIAN_ID = ?");
        $cekPenjualan->bind_param("s", $idp);
        $cekPenjualan->execute();
        $cekPenjualan->store_result();
        $cekPenjualan->bind_result($existingSupplier);
        $cekPenjualan->fetch();

        if ($cekPenjualan->num_rows === 0) {
            $buatPenjualan = $conn->prepare("INSERT INTO PEMBELIAN (PEMBELIAN_ID, TANGGAL, SUPPLIER_ID) VALUES (?, ?, ?)");
            $buatPenjualan->bind_param("sss", $idp, $tanggal_formatted, $supplier);
            $buatPenjualan->execute();
            $buatPenjualan->close();
        } else {
            if ($existingSupplier !== $supplier) {
                $cekKecocokan = $conn->prepare("
                    SELECT COUNT(*) 
                    FROM DETAIL_PEMBELIAN dp 
                    JOIN PRODUK p ON dp.PRODUK_ID = p.PRODUK_ID 
                    WHERE dp.PEMBELIAN_ID = ? AND p.SUPPLIER_ID != ?");
                $cekKecocokan->bind_param("ss", $idp, $supplier);
                $cekKecocokan->execute();
                $cekKecocokan->bind_result($jumlahTidakCocok);
                $cekKecocokan->fetch();
                $cekKecocokan->close();

                if ($jumlahTidakCocok > 0) {
                    $conn->rollback();
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Tidak bisa mengganti supplier karena detail pembelian lainnya masih terkait dengan supplier sebelumnya.'
                    ]);
                    exit;
                }

                $updateTanggal = $conn->prepare("UPDATE PEMBELIAN SET TANGGAL = ?, SUPPLIER_ID = ? WHERE PEMBELIAN_ID = ?");
                $updateTanggal->bind_param("sss", $tanggal_formatted, $supplier, $idp);
                $updateTanggal->execute();
                $updateTanggal->close();
            } else {
                $updateTanggal = $conn->prepare("UPDATE PEMBELIAN SET TANGGAL = ? WHERE PEMBELIAN_ID = ?");
                $updateTanggal->bind_param("ss", $tanggal_formatted, $idp);
                $updateTanggal->execute();
                $updateTanggal->close();
            }
        }
        $cekPenjualan->close();

        $getHarga = $conn->prepare("SELECT HARGA_SATUAN, SUPPLIER_ID FROM PRODUK WHERE PRODUK_ID = ?");
        $getHarga->bind_param("s", $produk);
        $getHarga->execute();
        $result = $getHarga->get_result();
        $hargaData = $result->fetch_assoc();
        $getHarga->close();

        if (!$hargaData) {
            echo json_encode(['status' => 'error', 'message' => 'Produk tidak ditemukan']);
            exit;
        }

        if ($hargaData['SUPPLIER_ID'] !== $supplier) {
            echo json_encode(['status' => 'error', 'message' => 'Produk tidak cocok dengan supplier yang dipilih']);
            exit;
        }

        $harga_satuan = floatval($hargaData['HARGA_SATUAN']);
        $subtotal = $harga_satuan * $jumlah;

        $stmt = $conn->prepare("UPDATE DETAIL_PEMBELIAN SET PRODUK_ID = ?, JUMLAH = ?, SUBTOTAL = ? WHERE DETAIL_PEMBELIAN_ID = ?");
        $stmt->bind_param("sids", $produk, $jumlah, $subtotal, $id);
        $stmt->execute();

        $conn->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Update gagal: ' . $e->getMessage()]);
    }
}
