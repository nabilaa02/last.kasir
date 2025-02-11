<?php
require_once __DIR__ . '/../app/config.php';

if (isset($_GET['id_pelanggan'])) {
    $id_pelanggan = $_GET['id_pelanggan'];
    $stmt = $pdo->prepare("SELECT nama_pelanggan FROM pelanggan WHERE id_pelanggan = :id_pelanggan");
    $stmt->execute([':id_pelanggan' => $id_pelanggan]);
    $pelanggan = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($pelanggan);
}
?>
