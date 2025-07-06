<?php
session_start();
require_once '../dbconfig.inc.php';

if (!function_exists('int_val_safe')) {
  function int_val_safe($v)
  {
    return isset($v) && $v !== '' ? (int)$v : 0;
  }
}
if (!function_exists('bool_val_safe')) {
  function bool_val_safe($v)
  {
    return empty($v) ? 0 : 1;
  }
}

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'owner') {
  header("Location: offer_flat_step1.php");
  exit;
}
if (
  !isset($_SESSION['offer_flat'])      ||
  !isset($_SESSION['offer_flat_photos']) ||
  !isset($_SESSION['offer_flat_slots'])
) {
  header("Location: offer_flat_step1.php");
  exit;
}

$flat      = $_SESSION['offer_flat'];             
$marketing = $_SESSION['offer_flat_marketing'] ?? null;
$slots     = $_SESSION['offer_flat_slots'];        
$photos    = $_SESSION['offer_flat_photos'];        
$phone     = $_SESSION['offer_flat_phone'] ?? '';
$owner_id  = $_SESSION['user']['user_id'];

$success = false;
$error   = '';

try {
  $pdo->beginTransaction();

  $stmt = $pdo->prepare("
        INSERT INTO flats
          (owner_id, location, address, price,
           available_from, available_to,
           num_bedrooms, num_bathrooms, size_sqm,
           rent_conditions, heating, air_conditioning, access_control,
           approved)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,0)
    ");
  $stmt->execute([
    $owner_id,
    $flat['location']          ?? '',
    $flat['address']           ?? '',
    int_val_safe($flat['price']),
    $flat['available_from']    ?: null,
    $flat['available_to']      ?: null,
    int_val_safe($flat['num_bedrooms']),
    int_val_safe($flat['num_bathrooms']),
    int_val_safe($flat['size_sqm']),
    $flat['rent_conditions']   ?? '',
    bool_val_safe($flat['heating']),
    bool_val_safe($flat['air_conditioning']),
    bool_val_safe($flat['access_control'])
  ]);
  $flat_id = $pdo->lastInsertId();

  $pdo->prepare("
        INSERT INTO flat_features (flat_id, car_parking, backyard, playground, storage)
        VALUES (?,?,?,?,?)
    ")->execute([
    $flat_id,
    bool_val_safe($flat['car_parking']),
    $flat['backyard'] ?? 'none',
    bool_val_safe($flat['playground']),
    bool_val_safe($flat['storage'])
  ]);

  if ($marketing && !empty(trim($marketing['title']))) {
    $pdo->prepare("
            INSERT INTO marketing (flat_id, title, description, url)
            VALUES (?,?,?,?)
        ")->execute([
      $flat_id,
      $marketing['title'],
      $marketing['description'],
      $marketing['url']
    ]);
  }

  $slotStmt = $pdo->prepare("
        INSERT INTO time_slots (flat_id, available_date, time)
        VALUES (?,?,?)
    ");
  foreach ($slots as $slot) {
    [$d, $t] = explode(' ', $slot);          
    $slotStmt->execute([$flat_id, $d, $t]);
  }

  $imgStmt = $pdo->prepare("
        INSERT INTO flat_images (flat_id, image_path)
        VALUES (?,?)
    ");
  foreach ($photos as $fname) {
    $imgStmt->execute([$flat_id, "images/flats/$fname"]);
  }

  $m = $pdo->query("SELECT user_id FROM users WHERE user_type='manager' LIMIT 1")->fetch();
  if ($m) {
    $pdo->prepare("
            INSERT INTO messages (recipient_id, sender_role, title, body)
            VALUES (?,'system','Flat Approval Request',?)
        ")->execute([
      $m['user_id'],
      'Owner ' . $_SESSION['user']['name'] . ' submitted flat ID ' . $flat_id . ' for approval.'
    ]);
  }

  $pdo->commit();
  $success = true;

  unset(
    $_SESSION['offer_flat'],
    $_SESSION['offer_flat_marketing'],
    $_SESSION['offer_flat_photos'],
    $_SESSION['offer_flat_slots'],
    $_SESSION['offer_flat_phone']
  );
} catch (Throwable $e) {
  $pdo->rollBack();
  $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Flat Submission</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <?php include('../includes/header.php'); ?>
  <div class="page-layout">
    <?php include('../includes/nav.php'); ?>
    <main>
      <h2>Flat Submission Result</h2>
      <?php if ($success): ?>
        <p class="success-msg">Your flat has been submitted successfully and is now awaiting manager approval.</p>
      <?php else: ?>
        <p class="error-msg">Submission failed: <?= htmlspecialchars($error) ?></p>
      <?php endif; ?>
      <a href="../pages/home.php">Return to Home</a>
    </main>
  </div>
  <?php include('../includes/footer.php'); ?>
</body>

</html>