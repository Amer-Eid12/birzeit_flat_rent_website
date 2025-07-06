<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - Birzeit Flat Rent</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

<?php include('../includes/header.php'); ?>

<div class="page-layout">
  <?php include('../includes/nav.php'); ?>

  <main>
    <section>
      <h2>The Agency</h2>
      <article>
        <p>Birzeit Flat Rent is a modern real estate agency founded in 2021, specializing in affordable residential flat rentals across Birzeit and surrounding cities. We have quickly built a reputation for professionalism and reliability.</p>
        <p>Our management team includes certified property consultants and experienced IT professionals ensuring a seamless digital rental experience. Over the past 3 years, we’ve received local recognition for innovation in rental services.</p>
      </article>
    </section>

    <section>
      <h2>The City</h2>
      <article>
        <p>Birzeit is a small but vibrant town in the West Bank, known for its university, cultural heritage, and growing economy. It offers a mix of urban convenience and traditional charm, making it ideal for students, families, and professionals.</p>
        <ul>
          <li>Population: ~7,000 residents</li>
          <li>Known for: <a href="https://www.birzeit.edu" target="_blank" class="external">Birzeit University</a>, scenic views, and olive groves</li>
          <li>Nearby Attractions: Ramallah, Jifna village, local markets</li>
        </ul>
        <p>Learn more about Birzeit on this <a href="https://en.wikipedia.org/wiki/Birzeit" target="_blank" class="external">Wikipedia page</a>.</p>
      </article>
    </section>

    <section>
      <h2>Main Business Activities</h2>
      <article>
        <ul>
          <li>Flat listing and promotion</li>
          <li>Rental agreement processing</li>
          <li>Customer and owner registration</li>
          <li>Rental appointment booking</li>
          <li>Digital messaging and notification system</li>
          <li>Rental status tracking</li>
        </ul>
      </article>
    </section>
  </main>
</div>

<?php include('../includes/footer.php'); ?>

</body>
</html>
