<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'customer') {
  header("Location: login.php");
  exit;
}

$customerId = $_SESSION['user']['user_id'];
$slotId = intval($_GET['slot_id']);
$flatId = intval($_GET['flat_id']);

$stmt = $pdo->prepare("SELECT * FROM time_slots WHERE slot_id = ? AND is_booked = 0");
$stmt->execute([$slotId]);
$slot = $stmt->fetch();

if (!$slot) {
  echo "Slot unavailable.";
  exit;
}

$stmt = $pdo->prepare("INSERT INTO preview_appointments (flat_id, customer_id, appointment_date, appointment_time)
                       VALUES (?, ?, ?, ?)");
$stmt->execute([
  $flatId, 
  $customerId, 
  $slot['available_date'], 
  $slot['time']
]);

$stmt = $pdo->prepare("UPDATE time_slots SET is_booked = 1 WHERE slot_id = ?");
$stmt->execute([$slotId]);

$stmt = $pdo->prepare("SELECT owner_id FROM flats WHERE flat_id = ?");
$stmt->execute([$flatId]);
$ownerId = $stmt->fetchColumn();

$stmt = $pdo->prepare("INSERT INTO messages (recipient_id, sender_role, title, body) VALUES (?, 'system', ?, ?)");
$stmt->execute([
  $ownerId,
  'New Preview Appointment Request',
  'A customer requested a preview appointment for flat ID: ' . $flatId
]);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Preview Requested</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>
  <h2>Request Submitted</h2>
  <p>Your preview request has been sent to the owner. You will be notified when it is accepted.</p>
  <a href="../pages/home.php">Return to Home</a>
</main></div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
