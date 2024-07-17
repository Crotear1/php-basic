<?php
include($_SERVER['DOCUMENT_ROOT']."/uebung/config/session.php");


?>

<div class="topnav">
  <a href="index.php">Startseite</a>
  <?php if($_SESSION["isAdmin"] === "yes") : ?>
    <a href="createPerson.php">Benutzer erstellen</a>
    <a href="products.php">Produktliste</a>
    <a href="statistic.php">Statistik</a>
  <?php endif; ?>

  <?php if($_SESSION["loggedIn"] === "yes") : ?>
    <form action="<?php echo htmlspecialchars("stores/logout.php") ?>" method="post">
      <button type="submit" >Ausloggen</button>
    </form>
  <?php else : ?>
    <a href="login.php">Einloggen</a>
  <?php endif; ?>
</div>

<style>
html body {
    padding: 0;
    margin: 0;
}


    /* Add a black background color to the top navigation */
.topnav {
  background-color: #333;
  overflow: hidden;
  max-width: 100%;
}

/* Style the links inside the navigation bar */
.topnav a {
  float: left;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

/* Change the color of links on hover */
.topnav a:hover {
  background-color: #ddd;
  color: black;
}

</style>