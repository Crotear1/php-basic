<?php
include("stores/checkAdmin.php");
include("stores/checkLoggedIn.php");
include("stores/validation.php");
include("components/navigation.php");
include("config/db.php");

checkAdmin();
checkLoggedIn();

$sql = "SELECT * FROM Orders WHERE orderDate >= DATE_SUB(CURDATE(), INTERVAL 4 WEEK)"; // SQL with parameters
$stmt = $db->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$orders = $result->fetch_all(MYSQLI_ASSOC);; // fetch data  

$sql = "SELECT  FROM Orders WHERE orderDate >= DATE_SUB(CURDATE(), INTERVAL 4 WEEK)"; // SQL with parameters
$stmt = $db->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$orders = $result->fetch_all(MYSQLI_ASSOC);; // fetch data  

$sql = "SELECT * FROM Orders WHERE orderDate >= DATE_SUB(CURDATE(), INTERVAL 4 WEEK)"; // SQL with parameters
$stmt = $db->prepare($sql); 
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$orders = $result->fetch_all(MYSQLI_ASSOC);; // fetch data  


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

      </style>
</head>

<body>

<table>
    <h1>Letzten 4 Wochen</h1>
  <tr>
    <th>BestellID</th>
    <th>PersonenID</th>
    <th>Preis</th>
  </tr>
    <?php foreach($orders as $value): ?>
        <tr>
            <td><?php echo htmlspecialchars($value["orderID"]) ?></td>
            <td><?php echo htmlspecialchars($value["personID"]) ?></td>
            <td><?php echo htmlspecialchars($value["price"]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<table>
    <h1>5 Beliebteste</h1>
  <tr>
    <th>BestellID</th>
    <th>PersonenID</th>
    <th>Preis</th>
  </tr>
    <?php foreach($orders as $value): ?>
        <tr>
            <td><?php echo htmlspecialchars($value["orderID"]) ?></td>
            <td><?php echo htmlspecialchars($value["personID"]) ?></td>
            <td><?php echo htmlspecialchars($value["price"]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<table>
    <h1>5 Unbeliebteste</h1>
  <tr>
    <th>BestellID</th>
    <th>PersonenID</th>
    <th>Preis</th>
  </tr>
    <?php foreach($orders as $value): ?>
        <tr>
            <td><?php echo htmlspecialchars($value["orderID"]) ?></td>
            <td><?php echo htmlspecialchars($value["personID"]) ?></td>
            <td><?php echo htmlspecialchars($value["price"]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>

</html>