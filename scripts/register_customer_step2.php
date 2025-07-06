<?php
session_start();

if (!isset($_SESSION['register_customer'])) {
    header('Location: register_customer_step1.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    $valid = preg_match('/^[0-9].{4,13}[a-z]$/', $password);

    if (empty($email) || empty($password) || empty($confirm)) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif (!$valid) {
        $error = 'Password must be 6–15 characters, start with a digit, and end with a lowercase letter.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $_SESSION['register_customer']['email'] = $email;
        $_SESSION['register_customer']['password'] = $password;

        header('Location: register_customer_step3.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Registration - Step 2</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="page-layout">
  <?php include('../includes/nav.php'); ?>

  <main>
    <h2>Customer Registration – Step 2: Account Setup</h2>

    <?php if ($error): ?>
      <p style="color: red; font-weight: bold;">
        <?= htmlspecialchars($error) ?>
      </p>
    <?php endif; ?>

    <form method="POST" action="register_customer_step2.php">
      <label for="email">Email:</label>
      <input type="email" name="email" id="email" required>

      <label for="password">Password:</label>
      <input
        type="password"
        name="password"
        id="password"
        required
        minlength="6"
        maxlength="15"
        pattern="^[0-9].{4,13}[a-z]$"
        title="6–15 characters. Must start with a digit and end with a lowercase letter."
      >

      <label for="confirm">Confirm Password:</label>
      <input type="password" name="confirm" id="confirm" required>

      <button type="submit">Next Step</button>
    </form>
  </main>
</div>

<?php include('../includes/footer.php'); ?>

</body>
</html>