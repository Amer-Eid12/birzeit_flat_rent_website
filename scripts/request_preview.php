<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'customer') {
  $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
  header("Location: login.php");
  exit;
}

if (!isset($_GET['flat_id'])) {
  header("Location: search.php");
  exit;
}

$flatId = intval($_GET['flat_id']);
$today = date('Y-m-d');

$stmt = $pdo->prepare("SELECT * FROM time_slots 
                       WHERE flat_id = ? AND available_date >= ? AND is_booked = 0
                       ORDER BY available_date, time");
$stmt->execute([$flatId, $today]);
$slots = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Request Flat Preview</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>

  <h2>Request Flat Preview</h2>

  <?php if (empty($slots)): ?>
    <p>No available preview slots for this flat.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr><th>Date</th><th>Time</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php foreach ($slots as $slot): ?>
          <tr>
            <td><?= htmlspecialchars($slot['available_date']) ?></td>
            <td><?= htmlspecialchars($slot['time']) ?></td>
            <td>
              <a href="book_preview.php?slot_id=<?= $slot['slot_id'] ?>&flat_id=<?= $flatId ?>">Book</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

</main></div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
