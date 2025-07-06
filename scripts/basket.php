<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$customerId = $_SESSION['user']['user_id'];

if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $removeId = intval($_GET['remove']);

    $stmt = $pdo->prepare("DELETE FROM rentals WHERE rental_id = ? AND customer_id = ? AND confirmed = 0");
    $stmt->execute([$removeId, $customerId]);
}

$stmt = $pdo->prepare("
  SELECT r.*, f.reference_number, f.location, f.address, f.price, r.rent_start, r.rent_end
  FROM rentals r
  JOIN flats f ON r.flat_id = f.flat_id
  WHERE r.customer_id = ? AND r.confirmed = 0
  ORDER BY r.rent_start DESC
");
$stmt->execute([$customerId]);
$rentals = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Basket</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>

<h2>Shopping Basket (Ongoing Rentals)</h2>

<?php if (empty($rentals)): ?>
    <p>Your basket is empty. You have no ongoing rentals.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Reference #</th>
                <th>Location</th>
                <th>Address</th>
                <th>Rental Period</th>
                <th>Price / Month (JD)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rentals as $rental): ?>
                <tr>
                    <td><?= htmlspecialchars($rental['reference_number']) ?></td>
                    <td><?= htmlspecialchars($rental['location']) ?></td>
                    <td><?= htmlspecialchars($rental['address']) ?></td>
                    <td><?= htmlspecialchars($rental['rent_start']) ?> → <?= htmlspecialchars($rental['rent_end']) ?></td>
                    <td><?= htmlspecialchars($rental['price']) ?></td>
                    <td>
                        <a href="confirm_rent.php?rental_id=<?= urlencode($rental['rental_id']) ?>">Complete Confirmation</a>
                        |
                        <a href="basket.php?remove=<?= urlencode($rental['rental_id']) ?>" 
                           onclick="return confirm('Are you sure you want to remove this rental?')">
                           Remove
                        </a>
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
