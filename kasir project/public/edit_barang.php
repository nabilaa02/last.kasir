<?php
session_start();
require_once __DIR__ . '/../app/config.php';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$id_barang = $_GET['id'];


$stmt = $pdo->prepare("SELECT * FROM barang WHERE id_barang = ?");
$stmt->execute([$id_barang]);
$barang = $stmt->fetch();


if (!$barang) {
    header("Location: barang.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    $stmt = $pdo->prepare("UPDATE barang SET nama_barang = ?, harga = ?, stok = ? WHERE id_barang = ?");
    if ($stmt->execute([$nama_barang, $harga, $stok, $id_barang])) {
        header("Location: barang.php");
        exit();
    } else {
        $error_message = "Error updating barang.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - Aplikasi Kasir</title>
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
                <div class="text-xl font-semibold">Edit Barang</div>
            </div>

         
            <?php if (isset($error_message)): ?>
                <div class="bg-red-500 text-white text-center py-2 mb-4 rounded">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>


            <div class="bg-white p-6 rounded-lg shadow-md">
                <form method="POST">
                    <div class="mb-4">
                        <label for="nama_barang" class="block text-sm font-medium text-gray-700">Nama Barang</label>
                        <input type="text" id="nama_barang" name="nama_barang" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?= $barang['nama_barang'] ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="number" id="harga" name="harga" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?= $barang['harga'] ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" id="stok" name="stok" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?= $barang['stok'] ?>" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md">Update Barang</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
