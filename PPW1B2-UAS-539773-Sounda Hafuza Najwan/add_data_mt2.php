<?php
include 'koneksi_mp1.php';

header('Content-Type: application/json');

$tanggal = $_POST['Tanggal2'] ?? '';
$idp = $_POST['IDP2'] ?? '';
$id = $_POST['ID2'] ?? '';
$supplier = $_POST['Supplier2'] ?? '';
$produk = $_POST['Produk2'] ?? '';
$jumlah = intval($_POST['Jumlah2'] ?? 0);

file_put_contents('debug_post.txt', print_r($_POST, true));

if (
    isset($_POST['Tanggal2'], $_POST['IDP2'], $_POST['ID2'], $_POST['Supplier2'], $_POST['Produk2'], $_POST['Jumlah2']) &&
    $_POST['Tanggal2'] !== '' &&
    $_POST['IDP2'] !== '' &&
    $_POST['ID2'] !== '' &&
    $_POST['Supplier2'] !== '' &&
    $_POST['Produk2'] !== '' &&
    intval($_POST['Jumlah2']) > 0
) {
    $date_obj = date_create($tanggal);
    if ($date_obj === false) {
        echo json_encode(['status' => 'invalid', 'message' => 'Format tanggal tidak valid']);
        exit;
    }
    $tanggal_formatted = date_format($date_obj, 'Y-m-d');

    $cek = $conn->prepare("SELECT DETAIL_PEMBELIAN_ID FROM DETAIL_PEMBELIAN WHERE DETAIL_PEMBELIAN_ID = ?");
    $cek->bind_param("s", $id);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo json_encode(['status' => 'duplicate']);
    } else {
        $cekKecocokan = $conn->prepare("SELECT 1 FROM PRODUK WHERE PRODUK_ID = ? AND SUPPLIER_ID = ?");
        $cekKecocokan->bind_param("ss", $produk, $supplier);
        $cekKecocokan->execute();
        $cekKecocokan->store_result();

        if ($cekKecocokan->num_rows === 0) {
            echo json_encode(['status' => 'error', 'message' => 'Produk tidak dimiliki oleh supplier yang dipilih.']);
            exit;
        }

        $conn->begin_transaction();

        try {
            $cekPembelian = $conn->prepare("SELECT PEMBELIAN_ID FROM PEMBELIAN WHERE PEMBELIAN_ID = ?");
            $cekPembelian->bind_param("s", $idp);
            $cekPembelian->execute();
            $cekPembelian->store_result();

            if ($cekPembelian->num_rows === 0) {
                $insertPembelian = $conn->prepare("INSERT INTO PEMBELIAN (PEMBELIAN_ID, TANGGAL, SUPPLIER_ID) VALUES (?, ?, ?)");
                $insertPembelian->bind_param("sss", $idp, $tanggal_formatted, $supplier);
                $insertPembelian->execute();
                $insertPembelian->close();
            } else {
                $cekSupplier = $conn->prepare("SELECT SUPPLIER_ID FROM PEMBELIAN WHERE PEMBELIAN_ID = ?");
                $cekSupplier->bind_param("s", $idp);
                $cekSupplier->execute();
                $cekSupplier->bind_result($existingSupplier);
                $cekSupplier->fetch();
                $cekSupplier->close();

                if ($existingSupplier !== $supplier) {
                    $conn->rollback();
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Tidak bisa menambahkan detail ke ID Pembelian ini karena supplier berbeda.'
                    ]);
                    exit;
                }
                $updatePembelian = $conn->prepare("UPDATE PEMBELIAN SET TANGGAL = ?, SUPPLIER_ID = ? WHERE PEMBELIAN_ID = ?");
                $updatePembelian->bind_param("sss", $tanggal_formatted, $supplier, $idp);
                $updatePembelian->execute();
                $updatePembelian->close();
            }

            $getHarga = $conn->prepare("SELECT HARGA_SATUAN FROM PRODUK WHERE PRODUK_ID = ?");
            $getHarga->bind_param("s", $produk);
            $getHarga->execute();
            $getHarga->bind_result($hargaJual);
            $getHarga->fetch();
            $getHarga->close(); 

            $hargaBeli = max(0, $hargaJual - 1000); 


            $insertDetail = $conn->prepare("INSERT INTO DETAIL_PEMBELIAN (DETAIL_PEMBELIAN_ID, PEMBELIAN_ID, PRODUK_ID, JUMLAH, HARGA_BELI) VALUES (?, ?, ?, ?, ?)");
            $insertDetail->bind_param("sssid", $id, $idp, $produk, $jumlah, $hargaBeli); 

            if ($insertDetail->execute()) {
                $conn->commit();
                echo json_encode(['status' => 'success']);
            } else {
                $conn->rollback();
                echo json_encode(['status' => 'error', 'message' => $insertDetail->error]);
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(['status' => 'error', 'message' => 'Transaksi gagal: ' . $e->getMessage()]);
        }

        $cekPembelian->close();
        $insertDetail->close();
    }

    $cek->close();
} else {
    echo json_encode(['status' => 'invalid', 'message' => 'Data tidak lengkap']);
}

$conn->close();
?>