<?php 
include '../config/dbConncetion.php';
include '../components/navigation.php';

$sql = "SELECT * FROM Products";
$stmt = $conn->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);



?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
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
.productBox {
  background-color: #38444d;
  border: 5px solid #ddd; /* Green border */
  color: white; /* White text */
  padding: 10px 24px; /* Some padding */
  cursor: pointer; /* Pointer/hand icon */
  width: 100%; /* Set a width if needed */
  display: block; /* Make the buttons appear below each other */
} 

</style>
</head>
<body>

<form action="createProduct.php">
    <input class="productBox" type="submit" value="Produkt erstellen" />
</form>

<table>
    <tr>
        <th>Artikelnummer</th>
        <th>Produktname</th>
        <th>Preis</th>
        <th>Stückzahl</th>
        <th>Beschreibung</th>
        <th>Produktfoto</th>
        <th>Aktionen</th>
    </tr>
    <?php  foreach($rows as $row): ?>
        <tr>
            <td><?=$row["articleNumber"];?></td>
            <td><?=$row["productName"];?></td>
            <td><?=$row["price"];?></td>
            <td><?=$row["stock"];?></td>
            <td><?=$row["description"];?></td>
            <td><?php echo '<img style="width: 100px; height: 100px;" src="../images/' . $row["imageID"] . '" alt="image" />'; ?></td>
            <td>
              <form action="editProduct.php" method="GET">
                <input type="hidden" name="productID" value="<?=$row["productID"];?>" />
                  <input class="productBox" type="submit" value="Bearbeiten" />
              </form>              
              <form action="editProduct.php" method="GET">
                  <input class="productBox" type="submit" value="Löschen" />
              </form>
            </td>
        </tr>
    <?php endforeach;?>
</table>

</body>
</html>
