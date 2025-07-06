<?php
session_start();
require_once '../dbconfig.inc.php';

$ownerId = $_SESSION['user']['user_id'];
$stmt = $pdo->prepare("SELECT * FROM flats WHERE owner_id = :id ORDER BY flat_id DESC");
$stmt->execute(['id' => $ownerId]);
$flats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Listed Flats</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="page-layout">
  <?php include('../includes/nav.php'); ?>
  <main>
    <h2>My Listed Flats</h2>
    <?php if (count($flats) === 0): ?>
      <p>You have not listed any flats yet. <a href="offer_flat_step1.php">Add a new flat</a>.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Reference #</th>
            <th>Location</th>
            <th>Address</th>
            <th>Price</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($flats as $flat): ?>
            <tr>
              <td><?= htmlspecialchars($flat['reference_number'] ?? '-') ?></td>
              <td><?= htmlspecialchars($flat['location']) ?></td>
              <td><?= htmlspecialchars($flat['address']) ?></td>
              <td>$<?= htmlspecialchars($flat['price']) ?></td>
              <td><?= $flat['approved'] ? 'Approved' : 'Pending' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </main>
</div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
