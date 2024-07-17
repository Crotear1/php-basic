<?php 
include("stores/checkAdmin.php");
include("stores/checkLoggedIn.php");
include("stores/validation.php");
include("components/navigation.php");
include("config/db.php");

checkAdmin();
checkLoggedIn();

$sql = "SELECT * FROM Products WHERE isDeleted=?"; // SQL with parameters
$stmt = $db->prepare($sql); 
$isDeleted = 0;
$stmt->bind_param("i", $isDeleted);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$products = $result->fetch_all(MYSQLI_ASSOC);; // fetch data  

?>

<!DOCTYPE html>

<html lang="en">
    
<head>
      <!-- Information about website and creator -->
      <meta charset="UTF-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <!-- Defines the compatibility of version with browser -->
      <meta name="viewport" content="width=device-width, 
      initial-scale=0.4" />
      <title>GeeksforGeeks</title>

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
</head>

<body>

<a href="createProduct.php">Produkt erstellen</a>

<table>
  <tr>
    <th>Artikelnummer</th>
    <th>Produktname</th>
    <th>Preis</th>
    <th>Beschreibung</th>
    <th>Verfügbar</th>
    <th>Bild</th>
    <th>Aktionen</th>
  </tr>
    <?php foreach($products as $key=>$value): ?>
        <tr>
            <td><?php echo htmlspecialchars($value["articleNumber"]) ?></td>
            <td><?php echo htmlspecialchars($value["productName"]) ?></td>
            <td><?php echo htmlspecialchars($value["price"]) ?></td>
            <td><?php echo htmlspecialchars($value["description"]) ?></td>
            <td>
                <?php if(htmlspecialchars($value["available"] === 1)) : ?>
                    Ist Verfügbar
                <?php else : ?>
                    Nicht Verfügbar
                <?php endif; ?>    
            </td>
            <td><img src="pictures/<?php echo htmlspecialchars($value["imageID"]) ?>" style="width: 100px; height: 100px;"></td>
            <td>
                <form action="editProduct.php" method="GET">
                    <input type="hidden" name="productID" value="<?php echo htmlspecialchars($value["productID"]) ?>" >
                    <button type="submit" >Bearbeiten</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>

</html>