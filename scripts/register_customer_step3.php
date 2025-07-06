<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['register_customer'])) {
    header('Location: register_customer_step1.php');
    exit;
}

$submitted = false;
$error = '';
$data = $_SESSION['register_customer'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO users
              (national_id, name, user_type,
               address, date_of_birth,
               mobile_number, telephone_number,
               email, username, password_hash)
            VALUES
              (:nid, :name, 'customer',
               :addr, :dob,
               :mobile, :tel,
               :email, :email, :pass)
        ");

        $stmt->execute([
            'nid'    => $data['national_id'],
            'name'   => $data['name'],
            'addr'   => $data['address'],
            'dob'    => $data['dob'],
            'mobile' => $data['mobile'],
            'tel'    => $data['telephone'],
            'email'  => $data['email'],
            'pass'   => $data['password']        
        ]);

        $submitted = true;
        unset($_SESSION['register_customer']);

    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Registration - Step 3</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include('../includes/header.php'); ?>
<div class="page-layout">
  <?php include('../includes/nav.php'); ?>

  <main>
    <h2>Customer Registration – Step 3: Confirm & Submit</h2>

    <?php if ($submitted): ?>
      <p style="color: green; font-weight: bold;">Registration successful. You may now <a href="login.php">log in</a>.</p>
    <?php else: ?>
      <?php if ($error): ?>
        <p style="color: red; font-weight: bold;">
          <?= htmlspecialchars($error) ?>
        </p>
      <?php endif; ?>

      <form method="POST" action="register_customer_step3.php">
        <p><strong>National ID:</strong> <?= htmlspecialchars($data['national_id']) ?></p>
        <p><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($data['dob']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($data['address']) ?></p>
        <p><strong>Mobile:</strong> <?= htmlspecialchars($data['mobile']) ?></p>
        <p><strong>Telephone:</strong> <?= htmlspecialchars($data['telephone']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>

        <button type="submit">Confirm & Register</button>
      </form>
    <?php endif; ?>
  </main>
</div>

<?php include('../includes/footer.php'); ?>
</body>
</html>
