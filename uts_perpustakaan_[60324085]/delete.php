<?php
require_once 'config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id == 0) {
    header("Location: index.php?pesan=ID tidak valid");
    exit();
}


$cek = $conn->prepare("SELECT id_kategori FROM kategori WHERE id_kategori = ?");
$cek->bind_param("i", $id);
$cek->execute();
$cek->store_result();

if ($cek->num_rows == 0) {
    header("Location: index.php?pesan=Kategori tidak ditemukan");
    exit();
}
$cek->close();


$stmt = $conn->prepare("DELETE FROM kategori WHERE id_kategori = ?");
$stmt->bind_param("i", $id);
$stmt->execute();


if ($stmt->affected_rows > 0) {
    header("Location: index.php?pesan=Kategori berhasil dihapus");
} else {
    header("Location: index.php?pesan=Gagal menghapus kategori");
}

$stmt->close();
exit();
?>