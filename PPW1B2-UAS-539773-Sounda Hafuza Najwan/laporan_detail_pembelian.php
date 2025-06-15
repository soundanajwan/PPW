<?php
include 'koneksi_mp1.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $q1 = mysqli_query($conn, "SELECT TANGGAL AS Tanggal2, TOTAL AS Total2 
        FROM PEMBELIAN  
        WHERE PEMBELIAN_ID = '$id'");

    $data1 = mysqli_fetch_assoc($q1);
    $tanggal = $data1['Tanggal2'];
    $total = $data1['Total2'];

    $q2 = mysqli_query($conn, "SELECT 
        dp.PEMBELIAN_ID AS ID2,
        dp.DETAIL_PEMBELIAN_ID AS IDP2, 
        p.NAMA_SUPPLIER_BACKUP AS Supplier2, 
        dp.JUMLAH AS Jumlah2, 
        dp.NAMA_PRODUK_BACKUP AS Produk2, 
        dp.HARGA_BELI AS Harga2, 
        dp.SUBTOTAL AS Subtotal2  
        FROM DETAIL_PEMBELIAN dp
        JOIN PEMBELIAN p ON dp.PEMBELIAN_ID = p.PEMBELIAN_ID  
        WHERE p.PEMBELIAN_ID = '$id'");

    $detail = [];
    while ($row = mysqli_fetch_assoc($q2)) {
        $detail[] = $row;
    }

    echo json_encode([
        'Tanggal2' => $tanggal,
        'Total2' => $total,
        'Detail2' => $detail
    ]);
}


?>
