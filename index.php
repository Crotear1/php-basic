<?php 
include 'config/dbConncetion.php';
include 'components/navigation.php';

$sql = "SELECT * FROM Products"; // SQL with parameters
$stmt = $conn->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$products = $result->fetch_all(MYSQLI_ASSOC); 

function summary() {
  $sum = 0;
  foreach($_SESSION["shoppingCart"] as $cartItem)
  $sum = $sum + $cartItem["price"];

  return $sum;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["addCard"])) {
  $productID = $_POST["productID"];
  $productName = $_POST["productName"];
  $price = $_POST["price"];

  array_push($_SESSION["shoppingCart"], [
    "productID" => $productID,
    "productName" => $productName,
    "price" => $price,

  ]);
  summary();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteState"])) {
  $productID = $_POST["productID"];

  foreach ($_SESSION["shoppingCart"] as $key => $item) {
    if ($item["productID"] == $productID) {
        echo $key; 
        break;
    }
  }

  unset($_SESSION["shoppingCart"][$key]);

  summary();
}


?>

<style>
  table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}

th, td {
  text-align: left;
  padding: 16px;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}
</style>

<table>
  <tr>
    <th>ProductID</th>
    <th>Produktname</th>
    <th>Preis</th>
  </tr>
  <?php  foreach($_SESSION["shoppingCart"] as $cartItem): ?>
    <tr>
      <td><?php echo $cartItem["productID"] ?></td>
      <td><?php echo $cartItem["productName"] ?></td>
      <td><?php echo $cartItem["price"] ?></td>

      <td>
        <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
          <input type="hidden" name="productID" value="<?php echo htmlspecialchars($product["productID"]) ?>" >
          <input type="hidden" name="deleteState" value="deleteState" >
          <input type="submit" value="Entfernen">
        </form>
      </td>
    </tr>
  <?php endforeach;?>
</table>

<p>
  <?php echo summary() ?>
</p>

<?php  foreach($products as $product): ?>
    <tr>
        <td>
        <div class="card">
            <img src="<?php echo "images/" . $product["imageID"] ?>" alt="Produkt Foto" style="width:100%">
            <h1> <?php echo $product["productName"] ?> </h1>
            <p class="price"><?php echo $product["price"] ?> €</p>
            <p><?php echo $product["description"] ?></p>
            <p>
              <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>">
                <input type="hidden" name="productID" value="<?php echo htmlspecialchars($product["productID"]) ?>" >
                <input type="hidden" name="productName" value="<?php echo htmlspecialchars($product["productName"]) ?>" >
                <input type="hidden" name="price" value="<?php echo htmlspecialchars($product["price"]) ?>" >
                <input type="submit" name="addCard" value="In den Warenkorb">
              </form>
            </p>
            <p>
              <form method="GET" action="product.php">
                <input type="hidden" name="productID" value="<?php echo htmlspecialchars($product["productID"]) ?>" >
                <input type="submit" value="Anzeigen">
              </form>
            </p>
        </div>
        </td>
    </tr>
<?php endforeach;?>

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