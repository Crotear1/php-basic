<?php 
include("stores/checkAdmin.php");
include("stores/checkLoggedIn.php");
include("stores/validation.php");
include("components/navigation.php");
include("config/db.php");

// checkAdmin();
// checkLoggedIn();

$productID = test_input($_GET["productID"]);

$sql = "SELECT * FROM Products WHERE productID=?"; // SQL with parameters
$stmt = $db->prepare($sql); 
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$product = $result->fetch_assoc(); // fetch data

?>

<!DOCTYPE html>

<html lang="en">

<head>
      <!-- Information about website and creator -->
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <!-- Defines the compatibility of version with browser -->
      <meta name="viewport" content="width=device-width, 
                   initial-scale=1.0" />
      <title>Produkt bearbeiten.</title>
<style>
    .card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.price {
  color: grey;
  font-size: 22px;
}

.card button {
  border: none;
  outline: 0;
  padding: 12px;
  color: white;
  background-color: #000;
  text-align: center;
  cursor: pointer;
  width: 100%;
  font-size: 18px;
}

.card button:hover {
  opacity: 0.7;
}
</style>
     
</head>

<body>
<br>
<div class="card">
  <img src="<?php if(!empty($product["imageID"])) {echo htmlspecialchars("pictures/{$product['imageID']}"); } ?>" alt="Produktfoto" style="width:100%">
  <h1><?php if(!empty($product["description"])) {echo htmlspecialchars($product["productName"]); } ?></h1>
  <p class="price">$<?php if(!empty($product["description"])) {echo htmlspecialchars($product["price"]); } ?></p>
  <p class="price">Artikelnummer: <?php if(!empty($product["description"])) {echo htmlspecialchars($product["articleNumber"]); } ?></p>
  <p><?php if(!empty($product["description"])) {echo htmlspecialchars($product["description"]); } ?></p>
</div>
</body>

</html>