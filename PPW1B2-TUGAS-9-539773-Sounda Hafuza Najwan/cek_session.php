<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['USER_ID'])) {
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'not_logged_in']);
}
