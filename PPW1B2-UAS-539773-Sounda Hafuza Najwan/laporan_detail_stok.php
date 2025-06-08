<?php
include 'koneksi_mp1.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $q1 = mysqli_query($conn, "SELECT 
    p.PRODUK_ID AS IDP3,
    p.SUPPLIER_ID AS ID3, 
    p.NAMA_PRODUK AS Produk3, 
    p.STOK AS Total3,
    p.HARGA_SATUAN AS Jual3,
    COALESCE(dp.HARGA_BELI, 0) AS Beli3, 
    COALESCE(s.NAMA_SUPPLIER, pe.NAMA_SUPPLIER_BACKUP, 'Supplier Kosong') AS Supplier3  
        FROM PRODUK p 
        LEFT JOIN SUPPLIER s ON p.SUPPLIER_ID = s.SUPPLIER_ID
        LEFT JOIN DETAIL_PEMBELIAN dp ON p.PRODUK_ID = dp.PRODUK_ID
        LEFT JOIN PEMBELIAN pe ON p.SUPPLIER_ID = pe.SUPPLIER_ID  
        WHERE p.PRODUK_ID = '$id'");

    $data = mysqli_fetch_assoc($q1);

    echo json_encode([
        'IDP3' => $data['IDP3'],
        'Produk3' => $data['Produk3'],
        'ID3' => $data['ID3'],
        'Supplier3' => $data['Supplier3'],
        'Total3' => $data['Total3'],
        'Jual3' => $data['Jual3'],
        'Beli3' => $data['Beli3'],
    ]);
}
?>
