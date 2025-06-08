<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";
$database = "tugas";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id"])) {
    $id = $_POST["id"];

    // Ambil PEMBELIAN_ID dari detail yang akan dihapus
    $getPembelianID = $conn->prepare("SELECT PEMBELIAN_ID FROM DETAIL_PEMBELIAN WHERE DETAIL_PEMBELIAN_ID = ?");
    $getPembelianID->bind_param("s", $id);
    $getPembelianID->execute();
    $getPembelianID->bind_result($pembelian_id);
    $getPembelianID->fetch();
    $getPembelianID->close();

    if (!$pembelian_id) {
        echo json_encode(['status' => 'error', 'message' => 'Detail tidak ditemukan.']);
        exit;
    }

    // Hapus detail pembelian
    $stmt = $conn->prepare("DELETE FROM DETAIL_PEMBELIAN WHERE DETAIL_PEMBELIAN_ID = ?");
    $stmt->bind_param("s", $id); 

    if ($stmt->execute()) {
        // Cek apakah masih ada detail dengan ID pembelian yang sama
        $cekDetail = $conn->prepare("SELECT COUNT(*) FROM DETAIL_PEMBELIAN WHERE PEMBELIAN_ID = ?");
        $cekDetail->bind_param("s", $pembelian_id);
        $cekDetail->execute();
        $cekDetail->bind_result($jumlahDetail);
        $cekDetail->fetch();
        $cekDetail->close();

        // Jika tidak ada, hapus juga dari tabel PEMBELIAN
        if ($jumlahDetail == 0) {
            $hapusPembelian = $conn->prepare("DELETE FROM PEMBELIAN WHERE PEMBELIAN_ID = ?");
            $hapusPembelian->bind_param("s", $pembelian_id);
            $hapusPembelian->execute();
            $hapusPembelian->close();
        }

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID tidak ditemukan.']);
}

$conn->close();
