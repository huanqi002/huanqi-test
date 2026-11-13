<?php
// ===================================================================
// Database connection settings for XAMPP (default MySQL: root / no password)
// Change these three values if your XAMPP setup is different.
// ===================================================================
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'support_system';

$localConfig = __DIR__ . '/config.local.php';
if (is_file($localConfig)) {
    require $localConfig;
}

// XAMPP defaults to Europe/Berlin; session times are Malaysian local time
date_default_timezone_set('Asia/Kuala_Lumpur');

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

session_start();
?>
