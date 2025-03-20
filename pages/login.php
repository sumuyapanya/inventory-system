<?php
session_start();
require_once "../config/Database.php";
require_once "../classes/User.php"; // Include the User class for login handling

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Create a new User object and try to login
    $user = new User();
    $loginResult = $user->login($username, $password);

    if ($loginResult) {
        $_SESSION['user_id'] = $loginResult['id'];
        $_SESSION['username'] = $loginResult['username'];
        $_SESSION['role'] = $loginResult['role'];

        // Redirect based on user role
        if ($_SESSION['role'] == 'Logistic Officer') {
            header("Location: dashboard.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style1.css">
</head>
<body>

    <header>
        <div class="container">
            <h1>Inventory Management System - Login</h1>
            
        </div>
    </header>

    <div class="login-container">
        <h2>Login</h2>

        <?php if (isset($error_message)) { ?>
            <p class="error"><?= $error_message ?></p>
        <?php } ?>

        <form method="POST" class="login-form" >
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <p class="form-link">Don't have an account? <a href="register.php">Register here</a></p>
   
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 Inventory Management System. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
