<?php
session_start();
if (!isset($_SESSION['register_owner'])) {
    header('Location: register_owner_step1.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    $valid = preg_match('/^[0-9].{4,13}[a-z]$/', $password);

    if (!$email || !$password || !$confirm) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif (!$valid) {
        $error = 'Password must be 6–15 characters, start with a digit, and end with a lowercase letter.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $_SESSION['register_owner']['email'] = $email;
        $_SESSION['register_owner']['password'] = $password;
        header('Location: register_owner_step3.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Owner Registration - Step 2</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="page-layout">
  <?php include('../includes/nav.php'); ?>
  <main>
    <h2>Owner Registration – Step 2: Account Info</h2>
    <?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
    <form method="post">
      <label>Email:</label>
      <input type="email" name="email" required>
      <label>Password:</label>
      <input type="password" name="password" required pattern="^[0-9].{4,13}[a-z]$"
             title="6–15 chars, start with digit, end with lowercase letter">
      <label>Confirm Password:</label>
      <input type="password" name="confirm" required>
      <button type="submit">Next Step</button>
    </form>
  </main>
</div>
<?php include('../includes/footer.php'); ?>
</body>
</html>