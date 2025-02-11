<?php
require_once __DIR__ . '/../app/config.php';

if (isset($_GET['id_barang'])) {
    $id_barang = $_GET['id_barang'];

    // Query to get barang details
    $stmt = $pdo->prepare("SELECT id_barang, nama_barang, stok FROM barang WHERE id_barang = :id_barang");
    $stmt->execute([':id_barang' => $id_barang]);
    $barang = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return as JSON
    echo json_encode($barang);
}
?>
