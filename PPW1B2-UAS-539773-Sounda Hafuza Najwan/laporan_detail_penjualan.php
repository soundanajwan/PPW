<?php
include 'koneksi_mp1.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $q1 = mysqli_query($conn, "SELECT PENJUALAN_ID AS ID, TANGGAL AS Tanggal, TOTAL_HARGA AS Total 
        FROM PENJUALAN  
        WHERE PENJUALAN_ID = '$id'");

    $data = mysqli_fetch_assoc($q1);

    $q2 = mysqli_query($conn, "SELECT DETAIL_ID AS IDP, 
    JUMLAH AS Jumlah, 
    NAMA_PRODUK AS Produk, 
    HARGA_SATUAN AS Harga, 
    SUBTOTAL AS Subtotal 
    FROM DETAIL_PENJUALAN 
    WHERE PENJUALAN_ID = '$id'");

    $detail = [];
    while ($row = mysqli_fetch_assoc($q2)) {
        $detail[] = $row;
    }

    echo json_encode([
        'Tanggal' => $data['Tanggal'],
        'ID' => $data['ID'],
        'Total' => $data['Total'],
        'Detail' => $detail
    ]);
}
?>
