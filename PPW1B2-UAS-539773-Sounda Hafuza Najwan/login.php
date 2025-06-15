<?php
session_start();
include 'koneksi_mp1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM USER WHERE USERNAME = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['PASSWORD'])) {
            $_SESSION['USER_ID'] = $user['ID'];
            $_SESSION['USERNAME'] = $user['USERNAME'];
            echo "success"; 
            exit;
        } else {
            echo "Password salah!";
        }
    } else {
        echo "User tidak ditemukan!";
    }
} else {
    echo "Metode tidak valid!";
}
?>
