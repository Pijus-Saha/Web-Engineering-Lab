<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coffee_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST["user_id"];
    $password_input = $_POST["password"];

    // Determine if input is email or serial number
    if (filter_var($user_input, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT * FROM users WHERE email = ?";
    } else {
        $query = "SELECT * FROM users WHERE serial_number = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password_input, $user["password"])) {
            $_SESSION["username"] = $user["username"];
            $_SESSION["serial_number"] = $user["serial_number"];
            $_SESSION["email"] = $user["email"];
            header("Location: coffee.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email or serial number.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login - Coffee Shop</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="form-container">
    <h2>Login to Coffee Shop</h2>
    <?php if ($error): ?>
      <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
      <input type="text" name="user_id" placeholder="Email or Serial Number" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
  </div>
</body>
</html>
