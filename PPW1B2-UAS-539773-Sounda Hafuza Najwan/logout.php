<?php
session_start();
session_unset();
session_destroy();

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");

header("Location: index.html?message=logout");
exit;
?>
