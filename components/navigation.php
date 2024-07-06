<?php
include $_SERVER['DOCUMENT_ROOT'].'/php-basic/config/session.php';

?>

<!DOCTYPE html>
<html>
<head>
<style>
body {
  background-color:white;
  padding: 0;
  margin: 0;
}
ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: #38444d;
}

li {
  float: left;
}

li a, .dropbtn {
  display: inline-block;
  color: white;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
}

li a:hover, .dropdown:hover .dropbtn {
  background-color: red;
}

li.dropdown {
  display: inline-block;
}

.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #3e8e41;
}
</style>
</head>
<body>

<ul>
  <li><a href="/php-basic/index.php">Startseite</a></li>
  <?php if($_SESSION["loggedIn"] === "notLoggedIn") : ?>
    <li><a href="/php-basic/register.php">Register</a></li>
    <li><a href="/php-basic/login.php">Login</a></li>
  <?php elseif($_SESSION["isAdmin"] === "yes" && $_SESSION["loggedIn"] === "isLoggedIn") : ?>
    <li><a href="/php-basic/admin/products.php">Produktliste</a></li>
  <?php endif; ?>

  <?php if($_SESSION["loggedIn"] === "isLoggedIn") : ?>
    <li>
      <form action="/php-basic/functions/logout.php" method="post">
        <input type="submit" value="Ausloggen" />
      </form>
    </li>
  <?php endif; ?>

</ul>

</body>
</html>