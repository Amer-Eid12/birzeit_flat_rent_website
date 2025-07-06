<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $required = ['location', 'address', 'rent', 'from_date', 'to_date', 'bedrooms', 'bathrooms', 'size'];
  foreach ($required as $field) {
    if (empty($_POST[$field])) {
      $error = 'Please fill in all required fields.';
      break;
    }
  }
  if (!$error) {
    $_SESSION['offer_flat'] = [
      'location' => $_POST['location'],
      'address' => $_POST['address'],
      'price' => $_POST['rent'],
      'available_from' => $_POST['from_date'],
      'available_to' => $_POST['to_date'],
      'num_bedrooms' => $_POST['bedrooms'],
      'num_bathrooms' => $_POST['bathrooms'],
      'size_sqm' => $_POST['size'],
      'rent_conditions' => $_POST['rent_conditions'],
      'heating' => isset($_POST['heating']),
      'air_conditioning' => isset($_POST['ac']),
      'access_control' => isset($_POST['access_control']),
      'car_parking' => isset($_POST['car_parking']),
      'backyard' => $_POST['backyard'] ?? 'none',
      'playground' => isset($_POST['playground']),
      'storage' => isset($_POST['storage'])
    ];
    header("Location: offer_flat_step2.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Offer Flat – Step 1</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>

<h2>Step 1 – Flat Details</h2>

<?php if ($error) echo "<p style='color:red'>$error</p>"; ?>

<form method="post">
  <label>Location: <input name="location" required></label>
  <label>Address: <input name="address" required></label>
  <label>Rent/month: <input type="number" name="rent" required></label>
  <label>Available From: <input type="date" name="from_date" required></label>
  <label>Available To: <input type="date" name="to_date" required></label>
  <label>Bedrooms: <input type="number" name="bedrooms" required></label>
  <label>Bathrooms: <input type="number" name="bathrooms" required></label>
  <label>Size (m²): <input type="number" name="size" required></label>
  <label>Rent Conditions: <textarea name="rent_conditions"></textarea></label>

  <fieldset>
    <legend>Features</legend>
    <label><input type="checkbox" name="heating"> Heating</label>
    <label><input type="checkbox" name="ac"> Air Conditioning</label>
    <label><input type="checkbox" name="access_control"> Access Control</label>
  </fieldset>

  <fieldset>
    <legend>Extra Features</legend>
    <label><input type="checkbox" name="car_parking"> Car Parking</label>
    <label>Backyard: 
      <select name="backyard">
        <option value="none">None</option>
        <option value="individual">Individual</option>
        <option value="shared">Shared</option>
      </select>
    </label>
    <label><input type="checkbox" name="playground"> Playground</label>
    <label><input type="checkbox" name="storage"> Storage</label>
  </fieldset>

  <button type="submit">Next Step</button>
</form>

</main></div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
