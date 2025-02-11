<?php
session_start();
require_once __DIR__ . '/../app/config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Delete Sale Record
if (isset($_GET['delete_sale_id'])) {
    $id_penjualan = $_GET['delete_sale_id'];

    $stmt = $pdo->prepare("DELETE FROM penjualan WHERE id_penjualan = ?");
    if ($stmt->execute([$id_penjualan])) {
        $success_message = "Sales record deleted successfully.";
    } else {
        $error_message = "Error deleting sales record.";
    }
}

// Fetch all sales records for displaying in the table
$stmt_penjualan = $pdo->prepare("SELECT p.*, b.nama_barang, pl.nama_pelanggan FROM penjualan p
    JOIN barang b ON p.id_barang = b.id_barang
    JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan");
$stmt_penjualan->execute();
$sales = $stmt_penjualan->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penjualan - Aplikasi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100">

    <!-- Sidebar -->
    <div class="flex">
        <div class="w-64 bg-green-800 text-white h-screen">
            <div class="p-6 text-center font-bold text-xl">Aplikasi Kasir</div>
            <ul class="mt-6 space-y-4">
                <li><a href="dashboard.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Dashboard</a></li>
                <li><a href="penjualan.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Penjualan</a></li>
                <li>
                    <a href="add_penjualan.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Add Penjualan</a>
                </li>
                <li><a href="logout.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Logout</a></li>
            </ul>
        </div>

        <div class="flex-1 p-6">
            <div class="bg-white shadow-md flex justify-between items-center p-4 rounded-lg mb-6">
                <div class="text-xl font-semibold">Daftar Penjualan</div>
            </div>

            <!-- Success or Error Message -->
            <?php if (isset($success_message)): ?>
                <div class="bg-green-500 text-white text-center py-2 mb-4 rounded">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="bg-red-500 text-white text-center py-2 mb-4 rounded">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Sales Table -->
            <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Pelanggan</th>
                            <th class="px-4 py-2">Barang</th>
                            <th class="px-4 py-2">Jumlah</th>
                            <th class="px-4 py-2">Total Harga</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sales as $sale): ?>
                            <tr>
                                <td class="px-4 py-2"><?= $sale['tanggal_penjualan'] ?></td>
                                <td class="px-4 py-2"><?= $sale['nama_pelanggan'] ?></td>
                                <td class="px-4 py-2"><?= $sale['nama_barang'] ?></td>
                                <td class="px-4 py-2"><?= $sale['jumlah'] ?></td>
                                <td class="px-4 py-2"><?= $sale['total_harga'] ?></td>
                                <td class="px-4 py-2">
                                    <a href="edit_penjualan.php?id=<?= $sale['id_penjualan'] ?>" class="text-blue-500 hover:text-blue-700">Edit</a> |
                                    <a href="?delete_sale_id=<?= $sale['id_penjualan'] ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this sale?')">Delete</a>
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
