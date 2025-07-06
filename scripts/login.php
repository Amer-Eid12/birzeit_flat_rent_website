<?php
session_start();
require_once '../dbconfig.inc.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    $error = 'Please enter both username and password.';
  } else {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && $user['password_hash'] === $password) {
      $_SESSION['user'] = [
        'user_id' => $user['user_id'],
        'name' => $user['name'],
        'user_type' => $user['user_type'], 
        'photo' => $user['photo'] ?? '../images/default-user.png'
      ];


      if (isset($_SESSION['redirect_after_login'])) {
        $redirect = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);
        header("Location: $redirect");
      } else {
        header('Location: ../pages/home.php');
      }
      exit;
    } else {
      $error = 'Invalid username or password.';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login - Birzeit Flat Rent</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>

  <?php include('../includes/header.php'); ?>

  <div class="page-layout">
    <?php include('../includes/nav.php'); ?>

    <main>
      <h2>Login</h2>

      <?php if (!empty($error)): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <label for="username">Email:</label>
        <input type="email" name="username" id="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
      </form>
    </main>
  </div>

  <?php include('../includes/footer.php'); ?>

</body>

</html>