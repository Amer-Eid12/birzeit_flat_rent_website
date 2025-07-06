<?php
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $national_id = trim($_POST['national_id']);
    $name        = trim($_POST['name']);
    $dob         = $_POST['dob'];
    $address     = trim($_POST['address']);
    $mobile      = trim($_POST['mobile']);
    $telephone   = trim($_POST['telephone']);

    if (!$national_id || !$name || !$dob || !$address || !$mobile || !$telephone) {
        $error = 'Please fill in all required fields.';
    } elseif (!preg_match('/^\d{9}$/', $national_id)) {
        $error = 'National ID must be exactly 9 digits.';
    } elseif (!preg_match('/^\d{10}$/', $mobile)) {
        $error = 'Mobile number must be exactly 10 digits.';
    } elseif (!preg_match('/^\d{7,15}$/', $telephone)) {
        $error = 'Telephone number must be between 7 and 15 digits.'; 
    } else {
        $_SESSION['register_customer'] = [
            'national_id' => $national_id,
            'name'        => $name,
            'dob'         => $dob,
            'address'     => $address,
            'mobile'      => $mobile,
            'telephone'   => $telephone
        ];
        header('Location: register_customer_step2.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Customer Registration - Step 1</title>
  <link rel="stylesheet" href="../style.css">
</head>

<body>

  <?php include('../includes/header.php'); ?>

  <div class="page-layout">
    <?php include('../includes/nav.php'); ?>

    <main>
      <h2>Customer Registration – Step 1: Personal Information</h2>

      <?php if ($error): ?>
        <p style="color: red; font-weight: bold;"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" action="register_customer_step1.php">
        <label for="national_id">National ID:</label>
        <input type="text" id="national_id" name="national_id" required>

        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required>

        <label for="mobile">Mobile Number:</label>
        <input type="text" id="mobile" name="mobile" required>

        <label for="telephone">Telephone Number:</label>
        <input type="text" id="telephone" name="telephone" required>

        <button type="submit">Next Step</button>
      </form>
    </main>
  </div>

  <?php include('../includes/footer.php'); ?>

</body>

</html>