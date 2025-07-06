<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

$userId   = $_SESSION['user']['user_id'];
$userType = $_SESSION['user']['user_type'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$userData = $stmt->fetch();
if (!$userData) {
    exit('User not found.');
}

$photo = $userData['photo'] ?: '../images/default-user.png';

function show($value) { return $value ? htmlspecialchars($value) : 'N/A'; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>

<h2>My Profile</h2>

<div style="display:flex;align-items:center;gap:2rem;margin-top:1rem;">
  <img src="<?= htmlspecialchars($photo) ?>" alt="User Photo"
       style="width:120px;height:120px;border-radius:50%;border:2px solid #ccc;">

  <div>
    <p><strong>Name:</strong> <?= show($userData['name']) ?></p>
    <p><strong>Email:</strong> <?= show($userData['email']) ?></p>
    <p><strong>User Type:</strong> <?= ucfirst($userData['user_type']) ?></p>
    <p><strong>National ID:</strong> <?= show($userData['national_id']) ?></p>

    <p><strong>Address:</strong> <?= show($userData['address']) ?></p>
    <p><strong>Mobile:</strong> <?= show($userData['mobile_number']) ?></p>
    <p><strong>Telephone:</strong> <?= show($userData['telephone_number']) ?></p>

    <?php if ($userType === 'owner'): ?>
      <p><strong>Bank Name:</strong> <?= show($userData['bank_name']) ?></p>
      <p><strong>Bank Branch:</strong> <?= show($userData['bank_branch']) ?></p>
      <p><strong>Account #:</strong> <?= show($userData['account_number']) ?></p>
    <?php endif; ?>
  </div>
</div>

</main></div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
