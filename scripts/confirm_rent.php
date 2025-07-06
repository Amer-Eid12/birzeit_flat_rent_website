<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$customerId = $_SESSION['user']['user_id'];

if (!isset($_GET['rental_id']) || !is_numeric($_GET['rental_id'])) {
    header("Location: shopping_basket.php");
    exit;
}

$rentalId = intval($_GET['rental_id']);

$stmt = $pdo->prepare("
  SELECT r.*, f.reference_number, f.location, f.address, f.price 
  FROM rentals r 
  JOIN flats f ON r.flat_id = f.flat_id 
  WHERE r.rental_id = ? AND r.customer_id = ? AND r.confirmed = 0
");
$stmt->execute([$rentalId, $customerId]);
$rental = $stmt->fetch();

if (!$rental) {
    echo "Rental not found or already confirmed.";
    exit;
}

$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cc_number = trim($_POST['credit_card']);
    $cc_expiry = trim($_POST['expiry_date']);
    $cc_name = trim($_POST['card_name']);

    if (!preg_match('/^\d{9}$/', $cc_number)) {
        $error = 'Credit card number must be exactly 9 digits.';
    } elseif (empty($cc_expiry) || empty($cc_name)) {
        $error = 'Please fill in all credit card fields.';
    } else {
        $stmt = $pdo->prepare("
            UPDATE rentals 
            SET credit_card_number = ?, credit_card_expiry = ?, credit_card_name = ?, confirmed = 1 
            WHERE rental_id = ?
        ");
        $stmt->execute([$cc_number, $cc_expiry, $cc_name, $rentalId]);

        $stmt = $pdo->prepare("SELECT owner_id FROM flats WHERE flat_id = ?");
        $stmt->execute([$rental['flat_id']]);
        $ownerId = $stmt->fetchColumn();

        if ($ownerId) {
            $stmt = $pdo->prepare("INSERT INTO messages (recipient_id, sender_role, title, body) VALUES (?, 'system', ?, ?)");
            $stmt->execute([
                $ownerId,
                'Flat Rented',
                'Flat Reference: ' . $rental['reference_number'] . ' has been rented by customer ID ' . $customerId
            ]);
        }

        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Rent</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>

<h2>Confirm Rental</h2>

<?php if ($success): ?>
    <p style="color:green;">Rental confirmed successfully!</p>
    <a href="view_rented_flats.php">View My Rentals</a>
<?php else: ?>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <h3>Rental Summary</h3>
    <p>Reference #: <?= htmlspecialchars($rental['reference_number']) ?></p>
    <p>Location: <?= htmlspecialchars($rental['location']) ?></p>
    <p>Address: <?= htmlspecialchars($rental['address']) ?></p>
    <p>Rental Period: <?= htmlspecialchars($rental['rent_start']) ?> → <?= htmlspecialchars($rental['rent_end']) ?></p>
    <p>Monthly Price: <?= htmlspecialchars($rental['price']) ?> JD</p>

    <h3>Enter Payment Details</h3>
    <form method="post">
        <label>Credit Card Number (9 digits):</label><br>
        <input type="text" name="credit_card" pattern="\d{9}" required><br><br>

        <label>Expiry Date (MM/YY):</label><br>
        <input type="text" name="expiry_date" placeholder="MM/YY" required><br><br>

        <label>Name on Card:</label><br>
        <input type="text" name="card_name" required><br><br>

        <button type="submit">Confirm Rent</button>
    </form>

<?php endif; ?>

</main></div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
