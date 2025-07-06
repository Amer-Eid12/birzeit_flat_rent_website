<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['register_owner'])) {
  header('Location: register_owner_step1.php');
  exit;
}

$data = $_SESSION['register_owner'];
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try {
    $stmt = $pdo->prepare("
  INSERT INTO users
    (national_id, name, user_type, date_of_birth, 
     email, username, password_hash,
     address, mobile_number, telephone_number, bank_name, bank_branch, account_number)
  VALUES
    (:nid, :name, 'owner', :dob,
     :email, :email, :password,
     :address,:mobile, :tel, :bank_name, :bank_branch, :acc)");


    $stmt->execute([
      'nid'         => $data['national_id'],
      'name'        => $data['name'],
      'dob'         => $data['dob'],
      'email'       => $data['email'],
      'password'    => $data['password'], 
      'address'     => $data['address'],
      'mobile'      => $data['mobile'],
      'tel'         => $data['telephone'],
      'bank_name'   => $data['bank_name'],
      'bank_branch' => $data['bank_branch'],
      'acc'         => $data['account_number']
    ]);


    $success = true;
    unset($_SESSION['register_owner']);
  } catch (PDOException $e) {
    $error = 'Failed to register: ' . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Owner Registration - Step 3</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <?php include('../includes/header.php'); ?>
  <div class="page-layout">
    <?php include('../includes/nav.php'); ?>
    <main>
      <h2>Owner Registration – Step 3: Review & Confirm</h2>
      <?php if ($success): ?>
        <p style="color: green;">Registration successful! You may <a href="login.php">log in</a>.</p>
      <?php else: ?>
        <?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
        <form method="post">
          <p><strong>Name:</strong> <?= htmlspecialchars($data['name']) ?></p>
          <p><strong>National ID:</strong> <?= htmlspecialchars($data['national_id']) ?></p>
          <p><strong>Date of Birth:</strong> <?= htmlspecialchars($data['dob']) ?></p>
          <p><strong>Address:</strong> <?= htmlspecialchars($data['address']) ?></p>
          <p><strong>Mobile:</strong> <?= htmlspecialchars($data['mobile']) ?></p>
          <p><strong>Telephone:</strong> <?= htmlspecialchars($data['telephone']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($data['email']) ?></p>
          <p><strong>Bank:</strong> <?= htmlspecialchars($data['bank_name']) ?>, <?= htmlspecialchars($data['bank_branch']) ?></p>
          <p><strong>Account #:</strong> <?= htmlspecialchars($data['account_number']) ?></p>
          <button type="submit">Confirm & Register</button>
        </form>
      <?php endif; ?>
    </main>
  </div>
  <?php include('../includes/footer.php'); ?>
</body>

</html>