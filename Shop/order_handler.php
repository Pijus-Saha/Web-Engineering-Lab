<?php
session_start();

if (!isset($_SESSION["email"])) {
  header("Location: login.php");
  exit();
}

$email = $_SESSION["email"];
$product = $_POST["product_name"] ?? null;

if (!$product) {
  die("Invalid product.");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "coffee_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

$stmt = $conn->prepare("INSERT INTO orders (email, product_name) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $product);

if ($stmt->execute()) {
  header("Location: coffee.php?ordered=" . urlencode($product));
} else {
  echo "Failed to order: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
