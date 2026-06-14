<?php
// includes/db.php - database connection (MySQLi)
// Update credentials here if your XAMPP setup is different.
$DB_HOST = 'sql104.infinityfree.com';
$DB_USER = 'if0_42175115';
$DB_PASS = 'o4RQpgEPmrd';
$DB_NAME = 'if0_42175115_phandasql';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
