<?php

require_once __DIR__ . '/../app/config.php'; 

// Initialize the success message variable
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password']; // Capture password from form
    $role = $_POST['role'];

    // Prepare SQL query to insert user into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $password, $role])) {
        // Set success message when user is successfully registered
        $successMessage = "User registered successfully.";
    } else {
        // Set error message if something goes wrong
        $successMessage = "Error registering user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 h-screen flex justify-center items-center">
    <div class="bg-green-200 p-6 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-semibold mb-6 text-center">Register</h2>
        <form method="POST">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select id="role" name="role" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                    <option value="admin">Admin</option>
                    <option value="petugas">Petugas</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md">Register</button>
        </form>

        <!-- Show success or error message here -->
        <?php if ($successMessage): ?>
            <div class="mt-4 text-center text-green-600">
                <?php echo $successMessage; ?>
            </div>
        <?php endif; ?>

        <div class="mt-4 text-center">
            <a href="login.php" class="text-blue-500">Already have an account? Login here.</a>
        </div>
    </div>
</body>
</html>
