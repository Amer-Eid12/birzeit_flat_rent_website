<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user'])) {
  $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
  header("Location: login.php");
  exit;
}

$userId = $_SESSION['user']['user_id'];

if (isset($_GET['read']) && is_numeric($_GET['read'])) {
  $msgId = intval($_GET['read']);
  
  $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE message_id = ? AND recipient_id = ?");
  $stmt->execute([$msgId, $userId]);
  
  $stmt = $pdo->prepare("SELECT * FROM messages WHERE message_id = ? AND recipient_id = ?");
  $stmt->execute([$msgId, $userId]);
  $fullMessage = $stmt->fetch();
} else {
  $fullMessage = null;
}

$stmt = $pdo->prepare("SELECT * FROM messages WHERE recipient_id = ? ORDER BY sent_date DESC");
$stmt->execute([$userId]);
$messages = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Messages</title>
  <link rel="stylesheet" href="../style.css">
  <style>
    .unread { font-weight: bold; background-color: #f9f9f9; }
    .msg-icon { color: #4794B8; margin-right: 0.5rem; }
    .msg-full { background: #f4f4f4; border: 1px solid #ccc; padding: 1rem; margin-top: 1rem; }
  </style>
</head>
<body>

<?php include('../includes/header.php'); ?>
<div class="page-layout">
<?php include('../includes/nav.php'); ?>
<main>
  <h2>My Messages</h2>

  <?php if (empty($messages)): ?>
    <p>You have no messages.</p>
  <?php else: ?>
    <table class="msg-table" style="width:100%; border-collapse: collapse;">
      <thead>
        <tr>
          <th></th>
          <th>Title</th>
          <th>Sender</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($messages as $msg): ?>
          <tr class="<?= $msg['is_read'] ? '' : 'unread' ?>">
            <td><?php if (!$msg['is_read']): ?><span class="msg-icon">📩</span><?php endif; ?></td>
            <td><a href="view_messages.php?read=<?= $msg['message_id'] ?>"><?= htmlspecialchars($msg['title']) ?></a></td>
            <td><?= htmlspecialchars(ucfirst($msg['sender_role'])) ?></td>
            <td><?= htmlspecialchars($msg['sent_date']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>

  <?php if ($fullMessage): ?>
    <div class="msg-full">
      <h3><?= htmlspecialchars($fullMessage['title']) ?></h3>
      <p><strong>From:</strong> <?= htmlspecialchars($fullMessage['sender_role']) ?></p>
      <p><strong>Date:</strong> <?= htmlspecialchars($fullMessage['sent_date']) ?></p>
      <hr>
      <p><?= nl2br(htmlspecialchars($fullMessage['body'])) ?></p>
    </div>
  <?php endif; ?>
</main>
</div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
