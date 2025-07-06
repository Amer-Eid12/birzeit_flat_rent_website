<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'manager') {
  header("Location: login.php");
  exit;
}

if (isset($_GET['approve']) && is_numeric($_GET['approve'])) {
  $flatId = intval($_GET['approve']);

  do {
    $refNo = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM flats WHERE reference_number = ?");
    $stmt->execute([$refNo]);
  } while ($stmt->fetchColumn() > 0);

  $stmt = $pdo->prepare("UPDATE flats SET approved = 1, reference_number = ? WHERE flat_id = ?");
  $stmt->execute([$refNo, $flatId]);

  $stmt = $pdo->prepare("SELECT owner_id FROM flats WHERE flat_id = ?");
  $stmt->execute([$flatId]);
  $ownerId = $stmt->fetchColumn();

  $stmt = $pdo->prepare("INSERT INTO messages (recipient_id, sender_role, title, body) 
                         VALUES (?, 'manager', ?, ?)");
  $stmt->execute([
    $ownerId,
    'Flat Approved',
    'Your flat has been approved with reference number: ' . $refNo
  ]);

  header("Location: manager_approval.php?approved=1");
  exit;
}

$stmt = $pdo->query("SELECT f.*, u.name AS owner_name 
                     FROM flats f 
                     JOIN users u ON f.owner_id = u.user_id
                     WHERE f.approved = 0
                     ORDER BY f.flat_id DESC");
$pendingFlats = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manager Approval</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="page-layout">
  <?php include('../includes/nav.php'); ?>
  <main>
    <h2>Pending Flats for Approval</h2>

    <?php if (isset($_GET['approved'])): ?>
      <p style="color: green;">Flat approved successfully!</p>
    <?php endif; ?>

    <?php if (empty($pendingFlats)): ?>
      <p>No flats waiting for approval.</p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Location</th>
            <th>Address</th>
            <th>Owner</th>
            <th>Price</th>
            <th>Period</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($pendingFlats as $flat): ?>
            <tr>
              <td><?= htmlspecialchars($flat['flat_id']) ?></td>
              <td><?= htmlspecialchars($flat['location']) ?></td>
              <td><?= htmlspecialchars($flat['address']) ?></td>
              <td><?= htmlspecialchars($flat['owner_name']) ?></td>
              <td><?= htmlspecialchars($flat['price']) ?> JD</td>
              <td><?= htmlspecialchars($flat['available_from']) ?> → <?= htmlspecialchars($flat['available_to']) ?></td>
              <td>
                <a href="?approve=<?= $flat['flat_id'] ?>" onclick="return confirm('Approve this flat?')">Approve</a>
              </td>
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
