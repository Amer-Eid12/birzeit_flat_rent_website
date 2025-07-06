<?php
session_start();
require_once '../dbconfig.inc.php';

$stmt = $pdo->query("
  SELECT f.flat_id, f.reference_number, f.location, f.address, f.price,
         (SELECT image_path FROM flat_images WHERE flat_id = f.flat_id LIMIT 1) AS first_photo
  FROM   flats f
  WHERE  f.approved = 1 AND NOT (f.available_from IS NULL AND f.available_to IS NULL)
  ORDER  BY f.flat_id DESC
  LIMIT  3
");
$latest = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Birzeit Flat Rent - Home</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>

<section>
  <h2>Welcome to Birzeit Flat Rent</h2>
  <p><strong>Discover your next flat in Ramallah, Birzeit, and surrounding areas. Browse listings,
     request appointments, and manage your rentals — all in one place.</strong></p>
</section>

<section>
  <h3>What Makes Birzeit Flat Rent Unique?</h3>
  <ul>
    <li><strong>Advanced search & filtering</strong> – find flats by price, location, size, and availability in seconds.</li>
    <li><strong>Instant preview booking</strong> – reserve viewing slots online and receive real-time confirmations.</li>
    <li><strong>Paper-free contracts</strong> – complete the entire rental process digitally with secure e-signatures.</li>
    <li><strong>Role-based dashboards</strong> – tailored interfaces for customers, owners, and managers.</li>
    <li><strong>Smart notifications</strong> – automatic alerts for approvals, messages, and expiring leases.</li>
  </ul>
</section>


<section>
  <h3>Recently Added Flats</h3>

  <?php if ($latest): ?>
    <div class="card-grid">
      <?php foreach ($latest as $f): ?>
        <div class="flat-card">
          <img src="../<?= $f['first_photo'] ?: 'images/no-image.png' ?>" alt="Flat photo">
          <div class="info">
            <h4><?= htmlspecialchars($f['reference_number']) ?></h4>
            <p><?= htmlspecialchars($f['location']) ?></p>
            <p><?= htmlspecialchars($f['address']) ?></p>
            <p><?= htmlspecialchars($f['price']) ?> JD / month</p>

            <div class="actions">
              <a href="../scripts/flat_detail.php?flat_id=<?= $f['flat_id'] ?>">View</a>
              <?php if (isset($_SESSION['user']) && $_SESSION['user']['user_type']==='customer'): ?>
                <a href="../scripts/rent_flat.php?flat_id=<?= $f['flat_id'] ?>">Rent</a>
                <a href="../scripts/request_preview.php?flat_id=<?= $f['flat_id'] ?>">Preview</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p>No flats added yet.</p>
  <?php endif; ?>
</section>

<?php if (isset($_SESSION['user']) && $_SESSION['user']['user_type']==='owner'): ?>
  <section>
    <h3>Ready to list your flat?</h3>
    <p>As a verified owner, you can add new flats to the platform.</p>
    <a href="../scripts/offer_flat_step1.php" class="internal">Offer a Flat</a>
  </section>
<?php endif; ?>

</main></div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
