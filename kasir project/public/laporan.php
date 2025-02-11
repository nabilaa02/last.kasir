<?php
session_start();
require_once __DIR__ . '/../app/config.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission for adding a new laporan
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_laporan'])) {
    $id_penjualan = $_POST['id_penjualan'];
    $id_barang = $_POST['id_barang'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $jumlah_terjual = $_POST['jumlah_terjual'];
    $total_harga = $_POST['total_harga'];

    // Insert new laporan into database
    $stmt_laporan = $pdo->prepare("INSERT INTO laporan (id_penjualan, id_barang, id_pelanggan, jumlah_terjual, total_harga) 
    VALUES (:id_penjualan, :id_barang, :id_pelanggan, :jumlah_terjual, :total_harga)");
    $stmt_laporan->execute([
        ':id_penjualan' => $id_penjualan,
        ':id_barang' => $id_barang,
        ':id_pelanggan' => $id_pelanggan,
        ':jumlah_terjual' => $jumlah_terjual,
        ':total_harga' => $total_harga,
    ]);

    // Update the stock of the item after the sale
    $stmt_barang = $pdo->prepare("SELECT stok FROM barang WHERE id_barang = :id_barang");
    $stmt_barang->execute([':id_barang' => $id_barang]);
    $barang = $stmt_barang->fetch();

    $new_stok = $barang['stok'] - $jumlah_terjual;
    $stmt_update_stok = $pdo->prepare("UPDATE barang SET stok = :stok WHERE id_barang = :id_barang");
    $stmt_update_stok->execute([':stok' => $new_stok, ':id_barang' => $id_barang]);
}

// Handle delete laporan
if (isset($_GET['delete_id'])) {
    $id_laporan = $_GET['delete_id'];

    // Fetch laporan details
    $stmt_laporan = $pdo->prepare("SELECT id_barang, jumlah_terjual FROM laporan WHERE id_laporan = :id_laporan");
    $stmt_laporan->execute([':id_laporan' => $id_laporan]);
    $laporan = $stmt_laporan->fetch();

    // Update stock of the item before deleting laporan
    $stmt_barang = $pdo->prepare("SELECT stok FROM barang WHERE id_barang = :id_barang");
    $stmt_barang->execute([':id_barang' => $laporan['id_barang']]);
    $barang = $stmt_barang->fetch();

    $updated_stok = $barang['stok'] + $laporan['jumlah_terjual']; // Add back the sold quantity
    $stmt_update_stok = $pdo->prepare("UPDATE barang SET stok = :stok WHERE id_barang = :id_barang");
    $stmt_update_stok->execute([':stok' => $updated_stok, ':id_barang' => $laporan['id_barang']]);

    // Delete the laporan
    $stmt_delete = $pdo->prepare("DELETE FROM laporan WHERE id_laporan = :id_laporan");
    $stmt_delete->execute([':id_laporan' => $id_laporan]);

    // Redirect after deletion
    header("Location: laporan.php");
    exit();
}

// Handle edit laporan
if (isset($_GET['edit_id'])) {
    $id_laporan = $_GET['edit_id'];
    $stmt_laporan = $pdo->prepare("SELECT * FROM laporan WHERE id_laporan = :id_laporan");
    $stmt_laporan->execute([':id_laporan' => $id_laporan]);
    $laporan = $stmt_laporan->fetch();
}

// Fetch list of barang for the dropdown
$stmt_barang = $pdo->prepare("SELECT id_barang, nama_barang FROM barang");
$stmt_barang->execute();
$barang_list = $stmt_barang->fetchAll();

// Fetch list of pelanggan for the dropdown
$stmt_pelanggan = $pdo->prepare("SELECT id_pelanggan, nama_pelanggan FROM pelanggan");
$stmt_pelanggan->execute();
$pelanggan_list = $stmt_pelanggan->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Kasir</title>
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
                <li>
                    <a href="laporan.php" class="block px-4 py-2 text-white bg-green-700 rounded transition-colors duration-300">Laporan</a>
                </li>
                <li>
                    <a href="logout.php" class="block px-4 py-2 text-white hover:bg-green-700 rounded transition-colors duration-300">Logout</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="bg-white shadow-md flex justify-between items-center p-4 rounded-lg mb-6">
                <div class="text-xl font-semibold">Form Laporan</div>
                <div class="text-sm text-gray-600"><?= $_SESSION['username'] ?></div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold mb-4">Tambah Laporan Penjualan</h2>

                <!-- Laporan Form -->
                <form action="laporan.php" method="POST" class="space-y-4">
                    <div>
                        <label for="id_penjualan" class="block text-sm font-medium text-gray-700">ID Penjualan</label>
                        <input type="number" name="id_penjualan" id="id_penjualan" value="<?= isset($laporan) ? $laporan['id_penjualan'] : '' ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="id_barang" class="block text-sm font-medium text-gray-700">ID Barang</label>
                        <select name="id_barang" id="id_barang" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" onchange="updateBarangDetails()">
                            <?php foreach ($barang_list as $barang) { ?>
                                <option value="<?= $barang['id_barang'] ?>" <?= isset($laporan) && $laporan['id_barang'] == $barang['id_barang'] ? 'selected' : '' ?>><?= $barang['id_barang'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang" value="<?= isset($laporan) ? $laporan['nama_barang'] : '' ?>
                    </div>
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stok" id="stok" value="<?= isset($laporan) ? $laporan['stok'] : '' ?>
                    </div>
                    <div>
                        <label for="id_pelanggan" class="block text-sm font-medium text-gray-700">ID Pelanggan</label>
                        <select name="id_pelanggan" id="id_pelanggan" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" onchange="updatePelangganDetails()">
                            <?php foreach ($pelanggan_list as $pelanggan) { ?>
                                <option value="<?= $pelanggan['id_pelanggan'] ?>" <?= isset($laporan) && $laporan['id_pelanggan'] == $pelanggan['id_pelanggan'] ? 'selected' : '' ?>><?= $pelanggan['id_pelanggan'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" value="<?= isset($laporan) ? $laporan['nama_pelanggan'] : '' ?>
                    </div>
                    <div>
                        <label for="jumlah_terjual" class="block text-sm font-medium text-gray-700">Jumlah Terjual</label>
                        <input type="number" name="jumlah_terjual" id="jumlah_terjual" value="<?= isset($laporan) ? $laporan['jumlah_terjual'] : '' ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="total_harga" class="block text-sm font-medium text-gray-700">Total Harga</label>
                        <input type="number" step="0.01" name="total_harga" id="total_harga" value="<?= isset($laporan) ? $laporan['total_harga'] : '' ?>" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div class="mt-4">
                        <button type="submit" name="submit_laporan" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <?= isset($laporan) ? 'Update Laporan' : 'Submit Laporan' ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Laporan -->
            <div class="bg-white p-6 rounded-lg shadow-md mt-6">
                <h2 class="text-2xl font-semibold mb-4">Daftar Laporan Penjualan</h2>
                <table class="w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border">ID Penjualan</th>
                            <th class="px-4 py-2 border">Jumlah Terjual</th>
                            <th class="px-4 py-2 border">Total Harga</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt_laporan_list = $pdo->prepare("SELECT * FROM laporan");
                        $stmt_laporan_list->execute();
                        $laporan_list = $stmt_laporan_list->fetchAll();

                        foreach ($laporan_list as $laporan) { ?>
                            <tr>
                                <td class="px-4 py-2 border"><?= $laporan['id_penjualan'] ?></td>
                                <td class="px-4 py-2 border"><?= $laporan['jumlah_terjual'] ?></td>
                                <td class="px-4 py-2 border"><?= $laporan['total_harga'] ?></td>
                                <td class="px-4 py-2 border">
                                    <a href="laporan.php?edit_id=<?= $laporan['id_laporan'] ?>" class="text-blue-600 hover:underline">Edit</a> | 
                                    <a href="laporan.php?delete_id=<?= $laporan['id_laporan'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')">Delete</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Function to update barang details dynamically
        function updateBarangDetails() {
            const id_barang = document.getElementById('id_barang').value;
            const nama_barang = document.getElementById('nama_barang');
            const stok = document.getElementById('stok');

            fetch(`get_barang_details.php?id_barang=${id_barang}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        nama_barang.value = data.nama_barang;
                        stok.value = data.stok;
                    }
                })
                .catch(error => console.log('Error fetching barang details:', error));
        }

        // Function to update pelanggan details dynamically
        function updatePelangganDetails() {
            const id_pelanggan = document.getElementById('id_pelanggan').value;
            const nama_pelanggan = document.getElementById('nama_pelanggan');

            fetch(`get_pelanggan_details.php?id_pelanggan=${id_pelanggan}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        nama_pelanggan.value = data.nama_pelanggan;
                    }
                })
                .catch(error => console.log('Error fetching pelanggan details:', error));
        }

        // Trigger update when the page loads to populate data for editing
        window.onload = function() {
            updateBarangDetails();
            updatePelangganDetails();
        };
    </script>
</body>
</html>
