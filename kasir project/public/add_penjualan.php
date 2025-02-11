<?php
session_start();
require_once __DIR__ . '/../app/config.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Add Sale Record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_sale'])) {
    $tanggal_penjualan = $_POST['tanggal_penjualan'];
    $total_harga = $_POST['total_harga'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_barang = $_POST['id_barang'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    if (empty($total_harga)) {
        $total_harga = $harga * $jumlah;
    }

    $stmt = $pdo->prepare("INSERT INTO penjualan (tanggal_penjualan, total_harga, id_pelanggan, id_barang, harga, jumlah) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$tanggal_penjualan, $total_harga, $id_pelanggan, $id_barang, $harga, $jumlah])) {
        $success_message = "Sales record added successfully with ID: " . $pdo->lastInsertId();  // Show generated ID
    } else {
        $error_message = "Error adding sales record.";
    }
}

// Fetch list of customers for the dropdown
$stmt = $pdo->prepare("SELECT id_pelanggan, nama_pelanggan FROM pelanggan");
$stmt->execute();
$customers = $stmt->fetchAll();

// Fetch list of products for the dropdown
$stmt_barang = $pdo->prepare("SELECT id_barang, nama_barang FROM barang");
$stmt_barang->execute();
$products = $stmt_barang->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan - Aplikasi Kasir</title>
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
                <li><a href="add_penjualan.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Add Penjualan</a></li>
                <li><a href="logout.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Logout</a></li>
            </ul>
        </div>

        <div class="flex-1 p-6">
            <div class="bg-white shadow-md flex justify-between items-center p-4 rounded-lg mb-6">
                <div class="text-xl font-semibold">Tambah Penjualan</div>
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

            <!-- Form for Adding Sale Record -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold mb-4">Tambah Penjualan</h2>
                <form method="POST">
                    <div class="mb-4">
                        <label for="tanggal_penjualan" class="block text-sm font-medium text-gray-700">Tanggal Penjualan</label>
                        <input type="date" id="tanggal_penjualan" name="tanggal_penjualan" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="id_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <select id="id_barang" name="id_barang" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="">Select Product</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?= $product['id_barang'] ?>"><?= $product['nama_barang'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="number" id="harga" name="harga" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" step="0.01" required>
                    </div>

                    <div class="mb-4">
                        <label for="jumlah" class="block text-sm font-medium text-gray-700">Jumlah</label>
                        <input type="number" id="jumlah" name="jumlah" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="mb-4">
                        <label for="total_harga" class="block text-sm font-medium text-gray-700">Total Harga</label>
                        <input type="number" id="total_harga" name="total_harga" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" step="0.01" required>
                    </div>

                    <div class="mb-4">
                        <label for="id_pelanggan" class="block text-sm font-medium text-gray-700">Pelanggan</label>
                        <select id="id_pelanggan" name="id_pelanggan" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                            <option value="">Select Pelanggan</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?= $customer['id_pelanggan'] ?>"><?= $customer['nama_pelanggan'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" name="add_sale" class="w-full bg-blue-500 text-white p-2 rounded-md">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript to Auto Calculate Total Harga -->
    <script>
        const hargaInput = document.getElementById('harga');
        const jumlahInput = document.getElementById('jumlah');
        const totalHargaInput = document.getElementById('total_harga');

        function calculateTotalHarga() {
            const harga = parseFloat(hargaInput.value);
            const jumlah = parseInt(jumlahInput.value);

            if (!isNaN(harga) && !isNaN(jumlah)) {
                const total = harga * jumlah;
                totalHargaInput.value = total.toFixed(2);
            } else {
                totalHargaInput.value = '';
            }
        }

        hargaInput.addEventListener('input', calculateTotalHarga);
        jumlahInput.addEventListener('input', calculateTotalHarga);
    </script>
</body>
</html>
