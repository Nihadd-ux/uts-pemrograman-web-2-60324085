<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'uts_perpustakaan_[60324085]'); // Ganti dengan NIM Anda
 
// Buat koneksi
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
 

$conn->set_charset("utf8mb4");


function escape($conn, $data) {
    return htmlspecialchars($conn->real_escape_string($data), ENT_QUOTES, 'UTF-8');
}
?>