<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$defaultUserImage = "../images/default-user.png";

$userLoggedIn = isset($_SESSION['user']);
$userName     = $userLoggedIn ? $_SESSION['user']['name']        : null;
$userPhoto    = $userLoggedIn && $_SESSION['user']['photo'] ? $_SESSION['user']['photo'] : $defaultUserImage;
$userType     = $userLoggedIn ? $_SESSION['user']['user_type']   : null;

$userCardClass = $userType === 'customer' ? 'customer-card'
               : ($userType === 'owner'   ? 'owner-card'
               : ($userType === 'manager' ? 'manager-card' : ''));
?>
<header>
  <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;">

    <div style="display:flex;align-items:center;gap:1rem;">
      <img src="../images/logo.png" alt="Logo" style="height:70px;">
      <h2 style="margin:0;">Birzeit Flat Rent</h2>
    </div>

    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">

      <span>
        <a href="../pages/about_us.php" class="internal">About Us</a> |
        <?php if (!$userLoggedIn): ?>
          <a href="../scripts/login.php">Login</a> |
          <a href="../scripts/register_customer_step1.php">Customer Register</a> |
          <a href="../scripts/register_owner_step1.php">Owner Register</a>
        <?php else: ?>
          <a href="../scripts/logout.php">Logout</a>
        <?php endif; ?>
      </span>

      <?php if ($userLoggedIn): ?>
        <div class="user-card <?= $userCardClass ?>" style="display:flex;align-items:center;gap:.5rem;">
          <a href="../scripts/profile.php">
            <img src="<?= htmlspecialchars($userPhoto) ?>" alt="User" style="height:40px;border-radius:50%;">
          </a>
          <div style="line-height:1;">
            <strong><?= htmlspecialchars($userName) ?></strong><br>
            <a href="../scripts/profile.php">Profile</a>
          </div>
        </div>

        <?php if ($userType === 'customer'): ?>
          <a href="../scripts/basket.php" class="internal" title="Shopping Basket"><img src="../images/shopping-cart.png" alt="Shopping-Cart" height="50" width="50"></a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</header>
