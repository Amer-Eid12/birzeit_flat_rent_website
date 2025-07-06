<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$userType = $_SESSION['user']['user_type'] ?? null;
?>

<nav class="side-nav">

  <a href="../pages/home.php" class="<?= ($currentPage == 'home.php') ? 'active' : '' ?>">Home</a>
  <a href="../scripts/search.php" class="<?= ($currentPage == 'search.php') ? 'active' : '' ?>">Flat Search</a>
  <a href="../scripts/view_messages.php" class="<?= ($currentPage == 'view_messages.php') ? 'active' : '' ?>">Messages</a>

  <?php if ($userType === 'customer'): ?>
    <a href="../scripts/view_rented_flats.php" class="<?= ($currentPage == 'view_rented_flats.php') ? 'active' : '' ?>">My Rentals</a>
  <?php endif; ?>

  <?php if ($userType === 'owner'): ?>
    <a href="../scripts/my_listed_flats.php" class="<?= ($currentPage == 'my_listed_flats.php') ? 'active' : '' ?>">My Listed Flats</a>
    <a href="../scripts/offer_flat_step1.php" class="<?= ($currentPage == 'offer_flat_step1.php') ? 'active' : '' ?>">Offer Flat</a>
    <a href="../scripts/owner_preview_requests.php" class="<?= ($currentPage == 'owner_preview_requests.php') ? 'active' : '' ?>">Preview Requests</a>
  <?php endif; ?>

  <?php if ($userType === 'manager'): ?>
    <a href="../scripts/manager_approval.php" class="<?= ($currentPage == 'manager_approval.php') ? 'active' : '' ?>">Approve Flats</a>
    <a href="../scripts/inquiry_flats.php" class="<?= ($currentPage == 'inquiry_flats.php') ? 'active' : '' ?>">Inquiry Flats</a>
  <?php endif; ?>

</nav>
