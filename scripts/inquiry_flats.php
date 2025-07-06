<?php
session_start();
require_once '../dbconfig.inc.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['user_type'] !== 'manager') {
    header("Location: login.php"); exit;
}

$loc        = $_GET['location']   ?? '';
$ownerEmail = $_GET['owner']      ?? '';
$custEmail  = $_GET['customer']   ?? '';
$fromDate   = $_GET['from']       ?? '';
$toDate     = $_GET['to']         ?? '';
$onDate     = $_GET['ondate']     ?? '';

$allowed = ['reference_number','location','price','available_from','available_to'];
$col = $_GET['sort'] ?? ($_COOKIE['inq_col'] ?? 'reference_number');
$dir = $_GET['dir']  ?? ($_COOKIE['inq_dir'] ?? 'ASC');
if (!in_array($col,$allowed)) $col='reference_number';
$dir = ($dir==='DESC')?'DESC':'ASC';
if (isset($_GET['sort'])) {
  setcookie('inq_col',$col,time()+2592000,'/');
  setcookie('inq_dir',$dir,time()+2592000,'/');
}

$sql = "
SELECT f.*, ow.name AS owner_name, ow.user_id AS owner_id,
       r.rent_start, r.rent_end, cu.name AS cust_name, cu.user_id AS cust_id
FROM   flats f
JOIN   users ow            ON  f.owner_id = ow.user_id
LEFT   JOIN rentals r      ON  r.flat_id  = f.flat_id
LEFT   JOIN users   cu     ON  cu.user_id = r.customer_id
WHERE  1=1 ";
$p=[];

if ($loc)        { $sql.=" AND f.location LIKE :loc";      $p[':loc'] = "%$loc%"; }
if ($ownerEmail) { $sql.=" AND ow.email   = :oe";          $p[':oe']  = $ownerEmail; }
if ($custEmail)  { $sql.=" AND cu.email   = :ce";          $p[':ce']  = $custEmail; }
if ($fromDate)   { $sql.=" AND f.available_from >= :frm";  $p[':frm'] = $fromDate; }
if ($toDate)     { $sql.=" AND f.available_to   <= :to";   $p[':to']  = $toDate; }
if ($onDate)     { $sql.=" AND :od BETWEEN f.available_from AND f.available_to";
                   $p[':od'] = $onDate; }

$sql .= " ORDER BY $col $dir";

$stmt=$pdo->prepare($sql); $stmt->execute($p);
$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

function hdr($label,$c,$cur,$dir,$q){
  $next=($c==$cur&&$dir==='ASC')?'DESC':'ASC';
  $icon=($c==$cur)?($dir==='ASC'?'▲ ':'▼ '):'';
  return "<a href=\"?{$q}&sort=$c&dir=$next\">$icon$label</a>";
}
$qstr = http_build_query(['location'=>$loc,'owner'=>$ownerEmail,'customer'=>$custEmail,
                          'from'=>$fromDate,'to'=>$toDate,'ondate'=>$onDate]);
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><title>Flats Inquiry</title>
<link rel="stylesheet" href="../style.css"></head><body>
<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>

<section class="search-grid">

<form class="search-form" method="get">
  <label>Location: <input name="location" value="<?=htmlspecialchars($loc)?>"></label>
  <label>Available&nbsp;From: <input type="date" name="from" value="<?=$fromDate?>"></label>
  <label>To: <input type="date" name="to" value="<?=$toDate?>"></label>
  <label>On&nbsp;Date: <input type="date" name="ondate" value="<?=$onDate?>"></label>
  <label>Owner&nbsp;Email: <input name="owner" value="<?=htmlspecialchars($ownerEmail)?>"></label>
  <label>Customer&nbsp;Email: <input name="customer" value="<?=htmlspecialchars($custEmail)?>"></label>
  <button type="submit">Search</button>
</form>

<h3>Flats (<?=count($rows)?>)</h3>
<?php if($rows): ?>
<table>
 <thead><tr>
  <th><?= hdr('Ref #','reference_number',$col,$dir,$qstr) ?></th>
  <th><?= hdr('Monthly JD','price',$col,$dir,$qstr) ?></th>
  <th><?= hdr('Avail-From','available_from',$col,$dir,$qstr) ?></th>
  <th><?= hdr('Avail-To','available_to',$col,$dir,$qstr) ?></th>
  <th><?= hdr('Location','location',$col,$dir,$qstr) ?></th>
  <th>Owner</th><th>Customer</th>
 </tr></thead>
 <tbody>
 <?php foreach($rows as $r): ?>
  <tr>
   <td>
     <a target="_blank"
        href="flat_detail.php?flat_id=<?=$r['flat_id']?>"
        style="padding:2px 6px;background:#007BFF;color:#fff;border-radius:4px;">
        <?=htmlspecialchars($r['reference_number']??'Pending')?>
     </a>
   </td>
   <td><?=htmlspecialchars($r['price'])?></td>
   <td><?=htmlspecialchars($r['available_from'])?></td>
   <td><?=htmlspecialchars($r['available_to'])?></td>
   <td><?=htmlspecialchars($r['location'])?></td>
   <td><a target="_blank" href="user_card.php?user_id=<?=$r['owner_id']?>"><?=htmlspecialchars($r['owner_name'])?></a></td>
   <td><?= $r['cust_id'] ?
          '<a target="_blank" href="user_card.php?user_id='.$r['cust_id'].'">'.
          htmlspecialchars($r['cust_name']).'</a>' : '—' ?></td>
  </tr>
 <?php endforeach;?>
 </tbody>
</table>
<?php else: ?><p>No flats match those criteria.</p><?php endif; ?>

</section>
</main></div>
<?php include('../includes/footer.php'); ?>
</body></html>
