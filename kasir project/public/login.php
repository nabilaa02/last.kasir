<?php
session_start();
require_once __DIR__ . '/../app/config.php';


if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

   
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['id_users'] = $user['id'];

        
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 h-screen flex justify-center items-center">

    <div class="bg-green p-6 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-semibold mb-6 text-center">Login</h2>
        
       
        <?php if (isset($error_message)): ?>
            <div class="bg-red-500 text-white text-center py-2 mb-4 rounded">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md">Login</button>
        </form>
        
        <div class="mt-4 text-center">
            <a href="register.php" class="text-blue-500">Don't have an account? Register here.</a>
        </div>
    </div>

</body>
</html>

