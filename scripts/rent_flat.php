<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'customer') {
  $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
  header("Location: login.php");
  exit;
}

if (!isset($_GET['flat_id']) || !is_numeric($_GET['flat_id'])) {
  header("Location: search.php");
  exit;
}
$flatId = (int) $_GET['flat_id'];

$stmt = $pdo->prepare("
    SELECT f.*, u.name AS owner_name
    FROM flats f
    JOIN users u ON f.owner_id = u.user_id
    WHERE f.flat_id = ? AND f.approved = 1
");
$stmt->execute([$flatId]);
$flat = $stmt->fetch();

if (!$flat) {
  exit('Flat not found or not available.');
}

$error = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $start = $_POST['rent_start'] ?? '';
  $end   = $_POST['rent_end']   ?? '';

  if (!$start || !$end) {
    $error = 'Please choose both start- and end-dates.';
  } elseif ($start > $end) {
    $error = 'Start-date must be before End-date.';
  } elseif ($flat['available_from'] && $start < $flat['available_from']) {
    $error = 'Start-date is earlier than the flat availability.';
  } elseif ($flat['available_to'] && $end > $flat['available_to']) {
    $error = 'End-date exceeds the owner’s available-to date.';
  }

  if (!$error) {

    $stmt = $pdo->prepare("
            INSERT INTO rentals (flat_id, customer_id, rent_start, rent_end, confirmed)
            VALUES (?, ?, ?, ?, 0)
        ");
    $stmt->execute([
      $flatId,
      $_SESSION['user']['user_id'],
      $start,
      $end
    ]);

    $nextDay = date('Y-m-d', strtotime($end . ' +1 day'));

    if (!$flat['available_to'] || $nextDay > $flat['available_to']) {
      $pdo->prepare("
        UPDATE flats
        SET available_from = NULL, available_to   = NULL
        WHERE flat_id = ?
    ")->execute([$flatId]);
    } else {
      $pdo->prepare("
        UPDATE flats SET available_from = ?
        WHERE flat_id = ?
    ")->execute([$nextDay, $flatId]);
    }


    $success = true;
  }
}

$minDate = $flat['available_from'] ?: date('Y-m-d');
$maxDate = $flat['available_to']   ?: '';   
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Rent Flat</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <?php include('../includes/header.php'); ?>
  <div class="page-layout">
    <?php include('../includes/nav.php'); ?>
    <main>

      <?php if ($success): ?>
        <h2>Rental Added to Basket</h2>
        <p>Your rental has been saved. You can complete confirmation from your basket.</p>
        <a href="basket.php">Go to Basket</a>

      <?php elseif (!$flat['approved']): ?>
        <h2>Not Available</h2>
        <p>Sorry, this flat is no longer available for booking.</p>

      <?php else: ?>

        <h2>Rent Flat</h2>
        <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

        <h3>Flat Information</h3>
        <p><strong>Reference #:</strong> <?= htmlspecialchars($flat['reference_number']) ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars($flat['location']) ?></p>
        <p><strong>Price:</strong> <?= htmlspecialchars($flat['price']) ?> JD / month</p>
        <p><strong>Available:</strong>
          <?= $flat['available_from'] ? htmlspecialchars($flat['available_from']) : 'any' ?> →
          <?= $flat['available_to']   ? htmlspecialchars($flat['available_to'])   : 'open' ?>
        </p>

        <h3>Choose Rental Period</h3>
        <form method="post">
          <label>Start Date:
            <input type="date" name="rent_start"
              min="<?= $minDate ?>"
              <?= $maxDate ? 'max="' . $maxDate . '"' : '' ?>
              required>
          </label><br><br>

          <label>End Date:
            <input type="date" name="rent_end"
              min="<?= $minDate ?>"
              <?= $maxDate ? 'max="' . $maxDate . '"' : '' ?>
              required>
          </label><br><br>

          <button type="submit">Add to Basket</button>
        </form>

      <?php endif; ?>

    </main>
  </div>
  <?php include('../includes/footer.php'); ?>
</body>

</html>