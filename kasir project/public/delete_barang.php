<?php
session_start();
require_once __DIR__ . '/../app/config.php';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$userRole = $_SESSION['role'];


$id_barang = $_GET['id'];


$stmt = $pdo->prepare("DELETE FROM barang WHERE id_barang = ?");
if ($stmt->execute([$id_barang])) {
    header("Location: barang.php");
    exit();
} else {
    echo "Error deleting barang.";
}
?>
