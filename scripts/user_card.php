<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    exit('No user chosen.');
}
$uid = (int)$_GET['user_id'];

$stmt = $pdo->prepare("
    SELECT name, address, mobile_number, telephone_number, email, user_type
    FROM   users
    WHERE  user_id = ?
");
$stmt->execute([$uid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) exit('User not found.');

function safe($v){ return htmlspecialchars($v ?? '—'); }

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>User Card – <?= safe($user['name']) ?></title>
<link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="card-wrapper">
  <h2><?= safe($user['name']) ?></h2>
  <p><em><?= safe($user['address']) ?></em></p>

  <?php if ($user['mobile_number']): ?>
    <p><span class="icon"><strong>Mobile Number: </strong></span><?= $user['mobile_number'] ?></p>
  <?php endif; ?>

  <?php if ($user['telephone_number']): ?>
    <p><span class="icon"><strong>Telephone Number: </strong></span><?= $user['telephone_number'] ?></p>
  <?php endif; ?>

  <p>
    <span class="icon"><strong>Email: </strong></span>
    <a href="mailto:<?= safe($user['email']) ?>"><?= safe($user['email']) ?></a>
  </p>

  <p style="margin-top:1rem;font-size:.85rem;color:#555;">
     <?= ucfirst($user['user_type']) ?>
  </p>
</div>

</body>
</html>
