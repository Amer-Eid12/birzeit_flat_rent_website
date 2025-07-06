<?php
session_start();
require_once '../dbconfig.inc.php';

$location = $_GET['location'] ?? '';
$bedrooms = $_GET['bedrooms'] ?? '';
$keyword  = $_GET['keyword']  ?? '';

$allowedCols = ['reference_number','location','address','num_bedrooms'];
$sortCol = $_GET['sort'] ?? ($_COOKIE['search_sort_col'] ?? 'reference_number');
$sortDir = $_GET['dir']  ?? ($_COOKIE['search_sort_dir']  ?? 'ASC');

if (!in_array($sortCol,$allowedCols)) $sortCol = 'reference_number';
$sortDir = ($sortDir === 'DESC') ? 'DESC' : 'ASC';

if (isset($_GET['sort'])) {
    setcookie('search_sort_col', $sortCol, time()+60*60*24*30, '/');
    setcookie('search_sort_dir', $sortDir, time()+60*60*24*30, '/');
}

$sql = "SELECT f.*, u.name AS owner_name
        FROM flats f
        JOIN users u ON f.owner_id = u.user_id
        WHERE f.approved = 1 AND NOT (f.available_from IS NULL AND f.available_to IS NULL)" ;
$params=[];

if ($location) { $sql.=" AND f.location LIKE :loc";  $params[':loc']="%$location%"; }
if ($bedrooms){ $sql.=" AND f.num_bedrooms=:beds";  $params[':beds']=$bedrooms;    }
if ($keyword) { $sql.=" AND f.address   LIKE :kw";  $params[':kw']="%$keyword%";   }

$sql .= " ORDER BY $sortCol $sortDir";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

function headerLink($col,$curCol,$curDir,$query)
{
    $nextDir = ($col==$curCol && $curDir==='ASC') ? 'DESC' : 'ASC';
    $icon    = ($col==$curCol) ? ($curDir==='ASC'?'▲':'▼').' ' : '';
    $base = htmlspecialchars($_SERVER['PHP_SELF'].'?'.$query);
    return "<a href=\"{$base}&sort={$col}&dir={$nextDir}\">{$icon}".headerLabel($col)."</a>";
}
function headerLabel($col){
    return match($col){
        'reference_number'=>'Ref #',
        'location'        =>'Location',
        'address'         =>'Address',
        'num_bedrooms'    =>'Bedrooms',
        default           =>$col
    };
}

$filterQuery = http_build_query([
    'location'=>$location,
    'bedrooms'=>$bedrooms,
    'keyword' =>$keyword
]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Flat Search – Birzeit Flat Rent</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
<?php include('../includes/header.php'); ?>
<div class="page-layout"><?php include('../includes/nav.php'); ?><main>

<section class="search-grid">
  <form class="search-form" method="get" action="search.php">
    <label>Location:
      <input name="location" value="<?= htmlspecialchars($location) ?>">
    </label>
    <label>Bedrooms:
      <select name="bedrooms">
        <option value="">Any</option>
        <?php for($i=1;$i<=4;$i++): ?>
          <option value="<?= $i ?>" <?= $bedrooms==$i?'selected':'' ?>><?= $i ?></option>
        <?php endfor; ?>
      </select>
    </label>
    <label>Keyword (address):
      <input name="keyword" value="<?= htmlspecialchars($keyword) ?>">
    </label>
    <button type="submit">Search</button>
  </form>

  <div>
    <h3>Search Results (<?= count($rows) ?>)</h3>
    <?php if ($rows): ?>
      <table>
        <thead>
          <tr>
            <th><?= headerLink('reference_number',$sortCol,$sortDir,$filterQuery) ?></th>
            <th><?= headerLink('location',        $sortCol,$sortDir,$filterQuery) ?></th>
            <th><?= headerLink('address',         $sortCol,$sortDir,$filterQuery) ?></th>
            <th><?= headerLink('num_bedrooms',    $sortCol,$sortDir,$filterQuery) ?></th>
            <th>Owner</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $f): ?>
          <tr>
            <td><?= htmlspecialchars($f['reference_number']) ?></td>
            <td><?= htmlspecialchars($f['location']) ?></td>
            <td><?= htmlspecialchars($f['address']) ?></td>
            <td style="text-align:center;"><?= $f['num_bedrooms'] ?></td>
            <td><?= htmlspecialchars($f['owner_name']) ?></td>
            <td>
              <a href="flat_detail.php?flat_id=<?= $f['flat_id'] ?>">View</a>
              <?php if(isset($_SESSION['user']) && $_SESSION['user']['user_type']=='customer'): ?>
                | <a href="rent_flat.php?flat_id=<?= $f['flat_id'] ?>">Rent</a>
                | <a href="request_preview.php?flat_id=<?= $f['flat_id'] ?>">Preview</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No flats match your criteria.</p>
    <?php endif; ?>
  </div>
</section>

</main></div>
<?php include('../includes/footer.php'); ?>
</body>
</html>
