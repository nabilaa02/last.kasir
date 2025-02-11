<?php
session_start();
require_once __DIR__ . '/../app/config.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get pelanggan id from query string
$id_pelanggan = $_GET['id'];

// Fetch pelanggan data
$stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE id_pelanggan = ?");
$stmt->execute([$id_pelanggan]);
$pelanggan = $stmt->fetch();


if (!$pelanggan) {
    header("Location: pelanggan.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pelanggan = $_POST['nama_pelanggan'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];

   
    $stmt = $pdo->prepare("UPDATE pelanggan SET nama_pelanggan = ?, alamat = ?, no_telepon = ? WHERE id_pelanggan = ?");
    if ($stmt->execute([$nama_pelanggan, $alamat, $no_telepon, $id_pelanggan])) {
        header("Location: pelanggan.php");
        exit();
    } else {
        $error_message = "Error updating pelanggan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan - Aplikasi Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100">

    <div class="flex">
        <div class="w-64 bg-green-800 text-white h-screen">
            <div class="p-6 text-center font-bold text-xl">Aplikasi Kasir</div>
            <ul class="mt-6 space-y-4">
                <li><a href="dashboard.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Dashboard</a></li>
                <li><a href="add_barang.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Add Barang</a></li>
                <li><a href="barang.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Data Barang</a></li>
                <li><a href="pelanggan.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Data Pelanggan</a></li>
                <li><a href="logout.php" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 rounded">Logout</a></li>
            </ul>
        </div>

        <div class="flex-1 p-6">
            <div class="bg-white shadow-md flex justify-between items-center p-4 rounded-lg mb-6">
                <div class="text-xl font-semibold">Edit Pelanggan</div>
            </div>

            <!-- Error Message -->
            <?php if (isset($error_message)): ?>
                <div class="bg-red-500 text-white text-center py-2 mb-4 rounded">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

           
            <div class="bg-white p-6 rounded-lg shadow-md">
                <form method="POST">
                    <div class="mb-4">
                        <label for="nama_pelanggan" class="block text-sm font-medium text-gray-700">Nama Pelanggan</label>
                        <input type="text" id="nama_pelanggan" name="nama_pelanggan" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?= $pelanggan['nama_pelanggan'] ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea id="alamat" name="alamat" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required><?= $pelanggan['alamat'] ?></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" id="no_telepon" name="no_telepon" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" value="<?= $pelanggan['no_telepon'] ?>" required>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md">Update Pelanggan</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
