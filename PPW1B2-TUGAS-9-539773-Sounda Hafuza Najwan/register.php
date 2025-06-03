<?php
include 'koneksi_mp1.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (strlen($password) < 6 || strlen($password) > 20) {
    echo "Password harus minimal 6 karakter<br>dan maksimal 20 karakter!";
    exit;
}

if (strlen($username) > 30 || empty($username)) {
    echo "Username tidak boleh kosong dan maksimal 30 karakter!";
    exit;
}

$sqlCheck = "SELECT * FROM USER WHERE USERNAME = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("s", $username);
$stmtCheck->execute();
$result = $stmtCheck->get_result();

if ($result->num_rows > 0) {
    echo "Username sudah digunakan!";
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$profilePictureName = "";
$uploadDir = "uploads/";

if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profilePicture']['tmp_name'];
    $fileName = basename($_FILES['profilePicture']['name']);
    $fileSize = $_FILES['profilePicture']['size'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "Format gambar tidak valid! (jpg, jpeg, png, gif)";
        exit;
    }

    if ($fileSize > 5 * 1024 * 1024) {
        echo "Ukuran gambar maksimal 5 MB!";
        exit;
    }

    $profilePictureName = uniqid('profile_', true) . '.' . $fileExtension;
    $destPath = $uploadDir . $profilePictureName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        echo "Gagal mengunggah gambar!";
        exit;
    }
}

$sql = "INSERT INTO USER (USERNAME, PASSWORD, PROFILE_PICTURE) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $hashedPassword, $profilePictureName);

if ($stmt->execute()) {
    echo "Pendaftaran berhasil. Silakan login.";
} else {
    echo "Error saat mendaftar: " . $stmt->error;
}
