<?php
include 'koneksi_mp1.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (strlen($password) < 6 || strlen($password) > 20) {
    echo "Password harus minimal 6 karakter dan maksimal 20 karakter!";
    exit;
}

if (strlen($username) > 30 || empty($username)) {
    echo "Username tidak boleh kosong dan maksimal 30 karakter!";
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$sqlCheck = "SELECT * FROM USER WHERE USERNAME = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("s", $username);
$stmtCheck->execute();
$result = $stmtCheck->get_result();

if ($result->num_rows > 0) {
    echo "Username sudah digunakan!";
    exit;
}

$sql = "INSERT INTO USER (USERNAME, PASSWORD) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $hashedPassword);

if ($stmt->execute()) {
    echo "Pendaftaran berhasil. Silakan login.";
} else {
    echo "Error saat mendaftar: " . $stmt->error;
}
?>
