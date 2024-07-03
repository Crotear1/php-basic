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
</style>
</head>
<body>

<a href="createProduct.php"> Neues Produkt anlegen </a>

<table>
    <tr>
        <th>Artikelnummer</th>
        <th>Produktname</th>
        <th>Preis</th>
        <th>Stückzahl</th>
        <th>Beschreibung</th>
        <th>Produktfoto</th>
    </tr>
    <?php  foreach($rows as $row): ?>
        <tr>
            <td><?=$row["articleNumber"];?></td>
            <td><?=$row["productName"];?></td>
            <td><?=$row["price"];?></td>
            <td><?=$row["stock"];?></td>
            <td><?=$row["description"];?></td>
            <td><?=$row["imageID"];?></td>
        </tr>
    <?php endforeach;?>
</table>

</body>
</html>
