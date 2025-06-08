<?php
session_start();
if (!isset($_SESSION['USER_ID'])) {
    header("Location: Login.html?message=needlogin");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");
?>

<?php include("Dashboard.html"); ?>
