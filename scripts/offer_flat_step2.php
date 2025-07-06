<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['marketing_title']);
  $desc = trim($_POST['marketing_desc']);
  $url = trim($_POST['marketing_url']);
  $photos = $_FILES['photos'];

  if (count(array_filter($photos['name'])) < 3) {
    $error = 'You must upload at least 3 photos.';
  } else {
    $uploaded = [];
    $targetDir = '../images/flats/';

    foreach ($photos['name'] as $i => $name) {
      $tmp = $photos['tmp_name'][$i];
      $ext = pathinfo($name, PATHINFO_EXTENSION);
      $newName = uniqid('flat_') . "." . $ext;
      $path = $targetDir . $newName;
      move_uploaded_file($tmp, $path);
      $uploaded[] = $newName; 
    }

    $_SESSION['offer_flat_marketing'] = [
      'title' => $title,
      'description' => $desc,
      'url' => $url
    ];
    $_SESSION['offer_flat_photos'] = $uploaded;

    header("Location: offer_flat_step3.php");
    exit;
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Offer Flat – Step 2</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>

<h2>Step 2 – Marketing Information</h2>

<?php if ($error) echo "<p style='color:red'>$error</p>"; ?>

<form method="post" enctype="multipart/form-data">
  <label>Marketing Title: <input name="marketing_title"></label>
  <label>Short Description: <textarea name="marketing_desc"></textarea></label>
  <label>URL (optional): <input name="marketing_url"></label>
  <label>Upload at least 3 photos: <input type="file" name="photos[]" multiple accept="image/*" required></label>
  <button type="submit">Next Step</button>
</form>

</main></div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
