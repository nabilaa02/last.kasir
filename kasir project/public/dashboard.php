<?php
session_start();
require_once __DIR__ . '/../app/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$userRole = $_SESSION['role'];

// Fetch total sales
$stmt_sales = $pdo->prepare("SELECT SUM(total_harga) AS total_sales FROM penjualan");
$stmt_sales->execute();
$total_sales = $stmt_sales->fetch()['total_sales'];

// Fetch total products
$stmt_products = $pdo->prepare("SELECT COUNT(*) AS total_products FROM barang");
$stmt_products->execute();
$total_products = $stmt_products->fetch()['total_products'];

// Fetch total customers
$stmt_customers = $pdo->prepare("SELECT COUNT(*) AS total_customers FROM pelanggan");
$stmt_customers->execute();
$total_customers = $stmt_customers->fetch()['total_customers'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100">

    <!-- Sidebar -->
    <div class="flex">
        <div class="w-64 bg-green-800 text-white h-screen">
            <div class="p-6 text-center font-bold text-xl">Aplikasi Kasir</div>
            <ul class="mt-6 space-y-4">
                <li>
                    <a href="dashboard.php" class="block px-4 py-2 text-white hover:bg-green-700 rounded transition-colors duration-300">Dashboard</a>
                </li>

                <?php if ($userRole == 'admin') { ?>
                    <li>
                        <a href="register.php" class="block px-4 py-2 text-white hover:bg-green-700 rounded transition-colors duration-300">Register User</a>
                    </li>
                    <li>
                        <a href="barang.php" class="block px-4 py-2 text-white hover:bg-green-700 rounded transition-colors duration-300">Data Barang</a>
                    </li>
                    <li>
                        <a href="pelanggan.php" class="block px-4 py-2 text-white hover:bg-green-700 rounded transition-colors duration-300">Pelanggan</a>
                    </li>
                <?php } ?>
                <li>
                    <a href="penjualan.php" class="block px-4 py-2 text-white hover:bg-green-700 rounded transition-colors duration-300">Penjualan</a>
                </li>
                <li>
                    <a href="laporan.php" class="block px-4 py-2 text-white hover:bg-green-700 rounded transition-colors duration-300">Laporan</a>
                </li>
                <li>
                    <a href="logout.php" class="block px-4 py-2 text-white hover:bg-green-700 rounded transition-colors duration-300">Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="bg-white shadow-md flex justify-between items-center p-4 rounded-lg mb-6">
                <div class="text-xl font-semibold">Welcome to Kasir Dashboard</div>
                <div class="text-sm text-gray-600"><?= $_SESSION['username'] ?></div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold mb-4">Dashboard</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <a href="penjualan.php" class="bg-pink-400 p-4 text-white rounded-lg shadow-md hover:bg-pink-500 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold">Total Penjualan</h3>
                        <p class="text-xl">Rp <?= number_format($total_sales, 2, ',', '.') ?></p>
                    </a>
                    <a href="barang.php" class="bg-green-400 p-4 text-white rounded-lg shadow-md hover:bg-green-500 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold">Total Barang</h3>
                        <p class="text-xl"><?= $total_products ?> Produk</p>
                    </a>
                    <a href="pelanggan.php" class="bg-blue-300 p-4 text-white rounded-lg shadow-md hover:bg-blue-400 transition duration-300 ease-in-out">
                        <h3 class="text-lg font-semibold">Total Pelanggan</h3>
                        <p class="text-xl"><?= $total_customers ?> Pelanggan</p>
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
