<?php
session_start();
require_once __DIR__ . '/../app/config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle delete operation
if (isset($_GET['delete_sale_id'])) {
    $id_penjualan = $_GET['delete_sale_id'];

    $stmt = $pdo->prepare("DELETE FROM penjualan WHERE id_penjualan = ?");
    if ($stmt->execute([$id_penjualan])) {
        header("Location: penjualan.php?success=Sales record deleted successfully.");
        exit();
    } else {
        header("Location: penjualan.php?error=Error deleting sales record.");
        exit();
    }
}
?>
