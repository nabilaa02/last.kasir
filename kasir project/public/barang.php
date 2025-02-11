<?php
session_start();
require_once __DIR__ . '/../app/config.php';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


$userRole = $_SESSION['role'];


$stmt = $pdo->prepare("SELECT * FROM barang");
$stmt->execute();
$barangList = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang - Aplikasi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100">

    <div class="flex">
        <div class="w-64 bg-green-800 text-white h-screen">
            <div class="p-6 text-center font-bold text-xl">Aplikasi Kasir</div>
            <ul class="mt-6 space-y-4">
                <li>
                    <a href="dashboard.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Dashboard</a>
                </li>
                <li>
                    <a href="add_barang.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Add Barang</a>
                </li>
                <li>
                    <a href="barang.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Data Barang</a>
                </li>
                <li>
                    <a href="logout.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Logout</a>
                </li>
            </ul>
        </div>

        <div class="flex-1 p-6">
            <div class="bg-white shadow-md flex justify-between items-center p-4 rounded-lg mb-6">
                <div class="text-xl font-semibold">Data Barang</div>
                <a href="add_barang.php" class="bg-blue-500 text-white px-4 py-2 rounded-md">Tambah Barang</a>
            </div>

            <div class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold mb-4">Daftar Barang</h2>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">ID Barang</th>
                            <th class="px-4 py-2 text-left">Nama Barang</th>
                            <th class="px-4 py-2 text-left">Harga</th>
                            <th class="px-4 py-2 text-left">Stok</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($barangList) > 0): ?>
                            <?php foreach ($barangList as $barang): ?>
                                <tr class="border-b">
                                    <td class="px-4 py-2"><?= $barang['id_barang'] ?></td>
                                    <td class="px-4 py-2"><?= $barang['nama_barang'] ?></td>
                                    <td class="px-4 py-2">Rp <?= number_format($barang['harga'], 2, ',', '.') ?></td>
                                    <td class="px-4 py-2"><?= $barang['stok'] ?></td>
                                    <td class="px-4 py-2">
                                        <a href="edit_barang.php?id=<?= $barang['id_barang'] ?>" class="text-blue-500">Edit</a> |
                                        <a href="delete_barang.php?id=<?= $barang['id_barang'] ?>" class="text-red-500">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-500">Tidak ada data barang.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
