<?php
require_once "../classes/User.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Create a new User object
    $user = new User();

    // Call the register method
    $isRegistered = $user->register($username, $password, $role);

    if ($isRegistered) {
        echo "<p>Registration successful! You can now <a href='login.php'>login</a>.</p>";

        } else {
        echo "<p>Error during registration.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/style1.css">
</head>
<body>

    <header>
        <div class="container">
            <h1>Inventory Management System - Register</h1>
            
        </div>
    </header>

    <div class="register-container">
        <h2>Register</h2>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="Logistic Officer">Logistic Officer</option>
                <option value="Coordinator">Coordinator</option>
                <option value="HoD">HoD</option>
            </select>
            <button type="submit" class="btn" >Register</button>
        </form>
        <p class="form-link">Already have an account? <a href="login.php">Login here</a></p>
  
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 Inventory Management System. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
