<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $slots = $_POST['slot'] ?? [];

    if (!$phone || count($slots) === 0) {
        $error = 'Please provide phone number and at least one time slot.';
    } else {
        $_SESSION['offer_flat_phone'] = $phone;
        $_SESSION['offer_flat_slots'] = $slots;
        header("Location: offer_flat_submit.php");
        exit;
    }
}

$daysAhead  = 7;                       
$baseDate   = new DateTime('tomorrow'); 
$slotTimes  = ['10:00', '14:00', '17:00'];  

$futureSlots = [];                     
for ($d = 0; $d < $daysAhead; $d++) {
    $day = clone $baseDate;
    $day->modify("+$d day");
    foreach ($slotTimes as $t) {
        $futureSlots[] = [$day->format('Y-m-d'), $t];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Offer Flat – Step 3</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>

<h2>Step 3 – Preview Time Slots</h2>
<?php if ($error) echo "<p style='color:red'>$error</p>"; ?>

<form method="post">
  <label>Contact Phone:
    <input name="phone" required>
  </label>

  <p>Select at least one future slot:</p>

  <?php foreach ($futureSlots as [$date,$time]): ?>
      <label style="display:block;">
        <input type="checkbox"
               name="slot[]"
               value="<?= $date.' '.$time ?>">
        <?= date('M j', strtotime($date)) ?> – <?= $time ?>
      </label>
  <?php endforeach; ?>

  <button type="submit">Submit Flat</button>
</form>

</main></div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
