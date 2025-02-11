<?php
session_start();
require_once __DIR__ . '/../app/config.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Check user role (admin or petugas)
$userRole = $_SESSION['role'];

// Fetch all pelanggan data
$stmt = $pdo->prepare("SELECT * FROM pelanggan");
$stmt->execute();
$pelangganList = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelanggan - Aplikasi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100">

    <div class="flex">
        <div class="w-64 bg-green-800 text-white h-screen">
            <div class="p-6 text-center font-bold text-xl">Aplikasi Kasir</div>
            <ul class="mt-6 space-y-4">
                <li><a href="dashboard.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Dashboard</a></li>
                <li><a href="pelanggan.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Data Pelanggan</a></li>
                <li><a href="add_pelanggan.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Tambah Pelanggan</a></li>
                <li><a href="logout.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Logout</a></li>
            </ul>
        </div>

        <div class="flex-1 p-6">
            <div class="bg-white shadow-md flex justify-between items-center p-4 rounded-lg mb-6">
                <div class="text-xl font-semibold">Data Pelanggan</div>
                <a href="add_pelanggan.php" class="bg-blue-500 text-white px-4 py-2 rounded-md">Tambah Pelanggan</a>
            </div>

            <!-- Pelanggan Table -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold mb-4">Daftar Pelanggan</h2>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left">ID Pelanggan</th>
                            <th class="px-4 py-2 text-left">Nama Pelanggan</th>
                            <th class="px-4 py-2 text-left">Alamat</th>
                            <th class="px-4 py-2 text-left">Nomor Telepon</th>
                            <th class="px-4 py-2 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pelangganList as $pelanggan): ?>
                            <tr class="border-b">
                                <td class="px-4 py-2"><?= $pelanggan['id_pelanggan'] ?></td>
                                <td class="px-4 py-2"><?= $pelanggan['nama_pelanggan'] ?></td>
                                <td class="px-4 py-2"><?= $pelanggan['alamat'] ?></td>
                                <td class="px-4 py-2"><?= $pelanggan['no_telepon'] ?></td>
                                <td class="px-4 py-2">
                                    <a href="edit_pelanggan.php?id=<?= $pelanggan['id_pelanggan'] ?>" class="text-blue-500 hover:text-blue-700">Edit</a> |
                                    <a href="delete_pelanggan.php?id=<?= $pelanggan['id_pelanggan'] ?>" class="text-red-500 hover:text-red-700">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>
