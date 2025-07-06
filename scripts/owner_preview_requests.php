<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'owner') {
  header("Location: login.php");
  exit;
}

$ownerId = $_SESSION['user']['user_id'];

if (isset($_GET['action'], $_GET['appointment_id']) && is_numeric($_GET['appointment_id'])) {
  $appointmentId = intval($_GET['appointment_id']);
  $action = $_GET['action'];

  if (in_array($action, ['accepted', 'rejected'])) {
    $stmt = $pdo->prepare("UPDATE preview_appointments SET status = ? 
                            WHERE appointment_id = ? AND flat_id IN (SELECT flat_id FROM flats WHERE owner_id = ?)");
    $stmt->execute([$action, $appointmentId, $ownerId]);

    $stmt = $pdo->prepare("SELECT customer_id FROM preview_appointments WHERE appointment_id = ?");
    $stmt->execute([$appointmentId]);
    $customerId = $stmt->fetchColumn();

    $stmt = $pdo->prepare("INSERT INTO messages (recipient_id, sender_role, title, body) 
                            VALUES (?, 'owner', ?, ?)");
    $stmt->execute([
      $customerId,
      'Preview Appointment ' . ucfirst($action),
      "Your preview request has been {$action}."
    ]);
  }

  header("Location: owner_preview_requests.php");
  exit;
}

$stmt = $pdo->prepare("SELECT a.*, u.name AS customer_name, f.reference_number 
                       FROM preview_appointments a
                       JOIN users u ON a.customer_id = u.user_id
                       JOIN flats f ON a.flat_id = f.flat_id
                       WHERE f.owner_id = ?
                       ORDER BY a.appointment_date, a.appointment_time");
$stmt->execute([$ownerId]);
$appointments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Owner Preview Requests</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>
  <h2>Preview Appointment Requests</h2>

  <?php if (empty($appointments)): ?>
    <p>No preview appointments found.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Flat Ref</th>
          <th>Customer</th>
          <th>Date</th>
          <th>Time</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($appointments as $app): ?>
          <tr>
            <td><?= htmlspecialchars($app['reference_number'] ?? 'Pending Approval') ?></td>
            <td><?= htmlspecialchars($app['customer_name']) ?></td>
            <td><?= htmlspecialchars($app['appointment_date']) ?></td>
            <td><?= htmlspecialchars($app['appointment_time']) ?></td>
            <td><?= htmlspecialchars($app['status']) ?></td>
            <td>
              <?php if ($app['status'] === 'pending'): ?>
                <a href="?appointment_id=<?= $app['appointment_id'] ?>&action=accepted">Accept</a> | 
                <a href="?appointment_id=<?= $app['appointment_id'] ?>&action=rejected">Reject</a>
              <?php else: ?>
                --
              <?php endif; ?>
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
