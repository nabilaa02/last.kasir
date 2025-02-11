<?php
session_start();
require_once __DIR__ . '/../app/config.php';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$id_pelanggan = $_GET['id'];


$stmt = $pdo->prepare("DELETE FROM pelanggan WHERE id_pelanggan = ?");
if ($stmt->execute([$id_pelanggan])) {
    header("Location: pelanggan.php");
    exit();
} else {
    echo "Error deleting pelanggan.";
}
?>
