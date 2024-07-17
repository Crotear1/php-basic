<?php
include("stores/validation.php");
include("components/navigation.php");
include("config/db.php");

$sql = "SELECT * FROM Products WHERE isDeleted=? AND available=?"; // SQL with parameters
$stmt = $db->prepare($sql); 
$isDeleted = 0;
$available = 1;
$stmt->bind_param("ii", $isDeleted, $available);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$products = $result->fetch_all(MYSQLI_ASSOC);; // fetch data  

function addSummary() {
      $sum = 0;

      foreach ($_SESSION["cart"] as $product) {
            $sum = $sum + $product["price"];
            // echo gettype($product["price"]);
      }
      return $sum;
}

$sum = addSummary();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $productID = test_input($_POST["productID"]); 
      $price = test_input($_POST["price"]);
      $productName = test_input($_POST["productName"]);

      $product = array(
            "productID" => $productID,
            "price" =>  floatval($price),
            "productName" => $productName,
      );

      array_push($_SESSION["cart"], $product);

      $sum = addSummary();
}

?>


<!DOCTYPE html>

<html lang="en">
    
<head>
      <!-- Information about website and creator -->
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <!-- Defines the compatibility of version with browser -->
      <meta name="viewport" content="width=device-width, 
                   initial-scale=0.7" />
      <title>GeeksforGeeks</title>

      <style>
table {
  border-collapse: collapse;
  border-spacing: 0;
  border: 1px solid #ddd;
  width: 100%;
}

th, td {
  text-align: left;
  padding: 16px;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

.open-button {
  background-color: #555;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  left: 28px;
  width: 280px;
}

/* The popup chat - hidden by default */
.chat-popup {
  display: none;
  position: fixed;
  bottom: 0;
  left: 15px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
}

/* Full-width textarea */
.form-container textarea {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
  resize: none;
  min-height: 200px;
}

/* When the textarea gets focus, do something */
.form-container textarea:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/send button */
.form-container .btn {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}
      </style>
</head>

<body>

<table>
  <tr>
    <th>Produktname</th>
    <th>Preis</th>
    <th>Beschreibung</th>
    <th>Bild</th>
    <th>Aktionen</th>
  </tr>
    <?php foreach($products as $value): ?>
        <tr>
            <td><?php echo htmlspecialchars($value["productName"]) ?></td>
            <td><?php echo htmlspecialchars($value["price"]) ?></td>
            <td><?php echo htmlspecialchars($value["description"]) ?></td>
            <td><img src="pictures/<?php echo htmlspecialchars($value["imageID"]) ?>" style="width: 100px; height: 100px;"></td>
            <td>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <input type="hidden" name="productID" value="<?php echo htmlspecialchars($value["productID"]) ?>" >
                    <input type="hidden" name="price" value="<?php echo htmlspecialchars($value["price"]) ?>" >
                    <input type="hidden" name="productName" value="<?php echo htmlspecialchars($value["productName"]) ?>" >
                    <button type="submit" >Warenkorb hinzufügen</button>
                </form>
                <form action="product.php" method="GET">
                    <input type="hidden" name="productID" value="<?php echo htmlspecialchars($value["productID"]) ?>" >
                    <button type="submit" >Anschauen</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<button class="open-button" onclick="openForm()">Warenkorb</button>

<div class="chat-popup" id="myForm">
      <div class="form-container">
            <?php foreach($_SESSION["cart"] as $value): ?>
                  <div>
                        <?php echo htmlspecialchars($value["productName"]) ?>
                        €<?php echo htmlspecialchars($value["price"]) ?>
                  </div>
            <?php endforeach; ?>
            €<?php echo htmlspecialchars($sum); ?>
            <form action="payWithCard.php" method="post">
                  <button type="submit" class="btn">Karte bezahlen</button>
            </form>
            <form action="payWithBill.php" method="post">
                  <button type="submit" class="btn">Rechnung bezahlen</button>
            </form>
            <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
      </div>
</div>

<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}
</script>
</body>

</html>