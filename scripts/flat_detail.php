<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_GET['flat_id'])) {
    header("Location: search.php");
    exit;
}

$flatId = intval($_GET['flat_id']);

$stmt = $pdo->prepare("SELECT f.*, u.name AS owner_name FROM flats f JOIN users u ON f.owner_id = u.user_id WHERE f.flat_id = ? AND f.approved = 1");
$stmt->execute([$flatId]);
$flat = $stmt->fetch();

if (!$flat) {
    echo "Flat not found or not approved yet.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM flat_features WHERE flat_id = ?");
$stmt->execute([$flatId]);
$features = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM marketing WHERE flat_id = ?");
$stmt->execute([$flatId]);
$marketing = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM flat_images WHERE flat_id = ?");
$stmt->execute([$flatId]);
$images = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Flat Details</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body>

    <?php include('../includes/header.php'); ?>
    <div class="page-layout">
        <?php include('../includes/nav.php'); ?>
        <main>

            <h2>Flat Details</h2>

            <h3>Basic Information</h3>
            <p><b>Reference:</b> <?= htmlspecialchars($flat['reference_number']) ?></p>
            <p><b>Location:</b> <?= htmlspecialchars($flat['location']) ?></p>
            <p><b>Address:</b> <?= htmlspecialchars($flat['address']) ?></p>
            <p><b>Price:</b> <?= htmlspecialchars($flat['price']) ?> JD/month</p>
            <p><b>Size:</b> <?= htmlspecialchars($flat['size_sqm']) ?> sqm</p>
            <p><b>Bedrooms:</b> <?= htmlspecialchars($flat['num_bedrooms']) ?></p>
            <p><b>Bathrooms:</b> <?= htmlspecialchars($flat['num_bathrooms']) ?></p>
            <p><b>Rent Conditions:</b> <?= htmlspecialchars($flat['rent_conditions']) ?></p>

            <h3>Facilities</h3>
            <ul>
                <li>Heating: <?= $flat['heating'] ? 'Yes' : 'No' ?></li>
                <li>AC: <?= $flat['air_conditioning'] ? 'Yes' : 'No' ?></li>
                <li>Access Control: <?= $flat['access_control'] ? 'Yes' : 'No' ?></li>

                <?php if ($features): ?>
                    <li>Car Parking: <?= $features['car_parking'] ? 'Yes' : 'No' ?></li>
                    <li>Backyard: <?= htmlspecialchars($features['backyard']) ?></li>
                    <li>Playground: <?= $features['playground'] ? 'Yes' : 'No' ?></li>
                    <li>Storage: <?= $features['storage'] ? 'Yes' : 'No' ?></li>
                <?php else: ?>
                    <li>No extra features provided.</li>
                <?php endif; ?>
            </ul>


            <?php if ($marketing): ?>
                <h3>Marketing Info</h3>
                <p><b><?= htmlspecialchars($marketing['title']) ?></b></p>
                <p><?= htmlspecialchars($marketing['description']) ?></p>
                <?php if (!empty($marketing['url'])): ?>
                    <p><a href="<?= htmlspecialchars($marketing['url']) ?>" target="_blank">More info</a></p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($images)): ?>
                <h3>Photos</h3>
                <div style="display:flex;gap:10px;">
                    <?php foreach ($images as $img): ?>
                        <img src="../<?= htmlspecialchars($img['image_path']) ?>" alt="Flat Image" width="200">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <h3>Actions</h3>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] === 'customer'): ?>
                <a href="rent_flat.php?flat_id=<?= $flatId ?>">Rent this flat</a> |
                <a href="request_preview.php?flat_id=<?= $flatId ?>">Request Preview</a>
            <?php else: ?>
                <?php
                $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
                ?>
                <p>
                    <a href="login.php">Login as customer</a>
                    to rent or request preview.
                </p>
            <?php endif; ?>


        </main>
    </div>
    <?php include('../includes/footer.php'); ?>
</body>

</html>