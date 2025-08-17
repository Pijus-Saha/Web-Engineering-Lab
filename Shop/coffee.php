<?php
session_start();

$username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
$email = isset($_SESSION["email"]) ? $_SESSION["email"] : null;
$ordered_product = isset($_GET['ordered']) ? $_GET['ordered'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Coffee Shop</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #3e2c23;
      padding: 15px 30px;
      color: white;
    }

    .navbar .brand {
      font-size: 24px;
      font-weight: bold;
    }

    .navbar .nav-links {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    .navbar .nav-links a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }

    .navbar .nav-links a:hover {
      color: #ffdd99;
    }

    .banner {
      width: 100%;
      height: 500px;
      background: url('coffee_banner.avif') no-repeat center center/cover;
      position: relative;
    }

    .order-btn {
      position: absolute;
      bottom: 30px;
      left: 30px;
      padding: 14px 28px;
      background-color: #ffcc00;
      color: black;
      border: none;
      font-weight: bold;
      font-size: 18px;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s ease;
    }

    .order-btn:hover {
      background-color: #ffaa00;
    }

    .message {
      background: #d4edda;
      color: #155724;
      padding: 10px 20px;
      margin: 15px;
      border-left: 5px solid green;
      font-weight: bold;
    }

    .product-section {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      padding: 40px;
      background-color: #fdf6f0;
    }

    .product-card {
      background-color: white;
      border-radius: 10px;
      padding: 15px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: 0.3s ease;
    }

    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    }

    .product-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-radius: 6px;
    }

    .product-card h3 {
      margin: 10px 0;
    }

    .product-card .add-to-cart {
      padding: 10px 16px;
      background-color: #6f4e37;
      color: white;
      border: none;
      border-radius: 5px;
      margin-top: 10px;
      cursor: pointer;
      transition: 0.3s;
    }

    .product-card .add-to-cart:hover {
      background-color: #4b3325;
    }

    footer {
      background-color: #3e2c23;
      color: white;
      text-align: center;
      padding: 10px;
      font-size: 14px;
      margin-top: 50px;
    }

    .discounted-price {
      color: #e67e22;
      font-weight: bold;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="brand">Coffee Shop</div>
    <div class="nav-links">
      <a href="#">Home</a>
      <a href="#">Menu</a>
      <a href="#">Offers</a>
      <a href="#">Contact</a>

      <?php if ($username): ?>
        <a href="logout.php" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
        <span class="profile">Welcome <?= htmlspecialchars($username) ?></span>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Order Confirmation -->
  <?php if ($ordered_product): ?>
    <div class="message">
      Order placed for <strong><?= htmlspecialchars($ordered_product) ?></strong>!
    </div>
  <?php endif; ?>

  <!-- Banner -->
  <section class="banner">
    <button class="order-btn" onclick="scrollToProducts()">Order Coffee</button>
  </section>

  <!-- Product Section -->
  <section class="product-section" id="products">
    <?php
    $products = [
      ["Espresso", "Espresso.jpeg", 180, 0.15],
      ["Cappuccino", "Cappuccino.jpeg", 250, 0.25],
      ["Latte", "Latte.jpeg", 150, 0.1],
      ["Mocha", "Mocha.jpeg", 300, 0.30]
    ];

    foreach ($products as $p):
      [$name, $image, $price, $discount] = $p;
      $discounted = $discount > 0 ? round($price * (1 - $discount)) : $price;
    ?>
      <div class="product-card" data-price="<?= $price ?>" data-discount="<?= $discount ?>">
        <img src="images/<?= $image ?>" alt="<?= $name ?>">
        <h3><?= $name ?></h3>
        <p>
          <span class="original-price" style="<?= $discount ? 'text-decoration:line-through;color:#888' : '' ?>">
            BDT-<?= $price ?>
          </span><br>
          <?php if ($discount): ?>
            <span class="discount-info">Discount: <?= $discount * 100 ?>% OFF</span><br>
            <span class="discounted-price">Now: BDT-<?= $discounted ?></span>
          <?php endif; ?>
        </p>
        <?php if ($email): ?>
          <form method="POST" action="order_handler.php">
            <input type="hidden" name="product_name" value="<?= $name ?>">
            <button type="submit" class="add-to-cart">Order Now</button>
          </form>
        <?php else: ?>
          <p style="color: #b00; font-size: 14px;"><em>Login to order</em></p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </section>

  <!-- Footer -->
  <footer>
    &copy; <?= date('Y') ?> Coffee Shop. All rights reserved.
  </footer>

  <!-- JavaScript -->
  <script>
    function scrollToProducts() {
      document.getElementById("products").scrollIntoView({ behavior: "smooth" });
    }
  </script>
</body>
</html>
