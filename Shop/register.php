<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coffee_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $dob = DateTime::createFromFormat('d-m-Y', $_POST["dob"]);
    $password = $_POST["password"];
    $year = $dob->format('Y');

    if (!str_ends_with($email, ".cse@diu.edu.bd")) {
        die("Only .cse@diu.edu.bd emails are allowed.");
    }

    $check = $conn->prepare("SELECT * FROM users WHERE email=? OR username=?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        die("Email or Username already exists.");
    }

    $getNextID = $conn->query("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'coffee_shop' AND TABLE_NAME = 'users'");
    $nextID = $getNextID->fetch_assoc()["AUTO_INCREMENT"];
    $serial_number = $year . "-" . str_pad($nextID, 2, "0", STR_PAD_LEFT);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $dob_sql = $dob->format('Y-m-d');

    $stmt = $conn->prepare("INSERT INTO users (serial_number, username, email, dob, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $serial_number, $username, $email, $dob_sql, $hashed_password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Register - Coffee Shop</title>
  <link rel="stylesheet" href="style.css">
  <script src="validate.js" defer></script>
</head>
<body>
  <div class="form-container">
    <h2>Register to Coffee Shop</h2>
    <form method="POST" onsubmit="return validateForm()">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email (.cse@diu.edu.bd)" required>
      <input type="text" name="dob" placeholder="Date of Birth (dd-mm-yyyy)" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Register</button>
    </form>
  </div>
</body>
</html>
