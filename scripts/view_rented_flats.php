<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'customer') {
  header("Location: login.php");
  exit;
}

$customerId = $_SESSION['user']['user_id'];

$stmt = $pdo->prepare("SELECT r.*, f.reference_number, f.location, f.address, f.price, f.num_bedrooms, f.num_bathrooms
                       FROM rentals r
                       JOIN flats f ON r.flat_id = f.flat_id
                       WHERE r.customer_id = ? AND r.confirmed = 1
                       ORDER BY r.rent_start DESC");
$stmt->execute([$customerId]);
$rentals = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Rentals</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include('../includes/header.php'); ?>
<div class="page-layout">
<?php include('../includes/nav.php'); ?>
<main>
  <h2>My Rentals</h2>

  <?php if (empty($rentals)): ?>
    <p>You have no confirmed rentals.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Reference #</th>
          <th>Location</th>
          <th>Address</th>
          <th>Bedrooms</th>
          <th>Bathrooms</th>
          <th>Price/Month</th>
          <th>Rent Period</th>
          <th>Total Paid</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rentals as $rental): 
          $months = max(1, ceil((strtotime($rental['rent_end']) - strtotime($rental['rent_start'])) / (30 * 86400)));
          $total = $months * $rental['price'];
        ?>
          <tr>
            <td><?= htmlspecialchars($rental['reference_number']) ?></td>
            <td><?= htmlspecialchars($rental['location']) ?></td>
            <td><?= htmlspecialchars($rental['address']) ?></td>
            <td><?= htmlspecialchars($rental['num_bedrooms']) ?></td>
            <td><?= htmlspecialchars($rental['num_bathrooms']) ?></td>
            <td><?= htmlspecialchars($rental['price']) ?> JD</td>
            <td><?= htmlspecialchars($rental['rent_start']) ?> → <?= htmlspecialchars($rental['rent_end']) ?></td>
            <td><?= $total ?> JD</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

</main></div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
