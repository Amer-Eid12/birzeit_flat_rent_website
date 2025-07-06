<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $national_id = trim($_POST['national_id']);
    $name = trim($_POST['name']);
    $dob         = $_POST['dob'];
    $address = trim($_POST['address']);
    $mobile      = trim($_POST['mobile']);
    $telephone   = trim($_POST['telephone']);
    $bank_name = trim($_POST['bank_name']);
    $bank_branch = trim($_POST['bank_branch']);
    $account_number = trim($_POST['account_number']);

    if (!$national_id || !$name || !$address || !$bank_name || !$bank_branch || !$account_number) {
        $error = 'Please fill in all required fields.';
    } else {
        $_SESSION['register_owner'] = [
            'national_id' => $national_id,
            'name' => $name,
            'dob'         => $dob,
            'address' => $address,
            'mobile' => $mobile,
            'telephone' => $telephone,
            'bank_name' => $bank_name,
            'bank_branch' => $bank_branch,
            'account_number' => $account_number
        ];
        header('Location: register_owner_step2.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Owner Registration - Step 1</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="page-layout">
  <?php include('../includes/nav.php'); ?>
  <main>
    <h2>Owner Registration – Step 1: Personal & Bank Info</h2>
    <?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
    <form method="post">
      <label>National ID:</label>
      <input type="text" name="national_id" required>
      <label>Name:</label>
      <input type="text" name="name" required>
      <label for="dob">Date of Birth:</label>
      <input type="date" id="dob" name="dob" required>
      <label>Address:</label>
      <input type="text" name="address" required>
      <label>Mobile Number:</label>
      <input type="text" name="mobile" required>
      <label>Telephone Number:</label>
      <input type="text" name="telephone" required>
      <label>Bank Name:</label>
      <input type="text" name="bank_name" required>
      <label>Bank Branch:</label>
      <input type="text" name="bank_branch" required>
      <label>Account Number:</label>
      <input type="text" name="account_number" required>
      <button type="submit">Next Step</button>
    </form>
  </main>
</div>
<?php include('../includes/footer.php'); ?>
</body>
</html>