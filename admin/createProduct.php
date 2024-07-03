<?php 
include '../config/dbConncetion.php';
include '../components/navigation.php';

if($_SESSION["isAdmin"] !== "yes") {
  header("Location: index.php");
}

$errorMessage = null;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{  
    if (isset($_POST["articleNumber"]) && !empty($_POST["articleNumber"])) {
        $articleNumber = filter_var($_POST["articleNumber"], FILTER_SANITIZE_NUMBER_INT);
    } else {
        $errorMessage = "Bitte geben Sie eine gültige Artikelnummer ein.";
        return;
    }     
    
    if (isset($_POST["productName"]) && !empty($_POST["productName"])) {
        $productName = filter_var($_POST["productName"], FILTER_SANITIZE_STRING);
    } else {
        $errorMessage = "Bitte geben Sie einen gültigen Produktnamen ein.";
        return;
    }      
    
    if (isset($_POST["description"]) && !empty($_POST["description"])) {
        $description = filter_var($_POST["description"], FILTER_SANITIZE_STRING);
    } else {
        $errorMessage = "Bitte geben Sie eine gültige Beschreibung ein.";
        return;
    }  

    if (isset($_POST["price"]) && !empty($_POST["price"])) {
        $price = filter_var($_POST["price"], FILTER_SANITIZE_NUMBER_FLOAT);
    } else {
        $errorMessage = "Bitte geben Sie einen gültigen Preis ein.";
        return;
    }     
    
    if (isset($_POST["stock"]) && !empty($_POST["stock"])) {
        $stock = filter_var($_POST["$stock"], FILTER_SANITIZE_STRING);
    } else {
        $errorMessage = "Bitte geben Sie eine gültige Anzahl ein.";
        return;
    }      
    
    if (isset($_POST["imageID"]) && !empty($_POST["imageID"])) {
       
    } else {
        return;
    }  

    $stmt = $conn->prepare("INSERT INTO Users(email, password_hash, firstname, lastname, housenumber, street, city, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssss", $email, $hashedPassword, $fn, $ln, $housenumber, $street, $city, $zip);

    $stmt->execute();

    header("Location: login.php");

}

?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <div class="container">
    <h1>Registrieren</h1>
    <hr>

    <p> <?php echo $errorMessage; ?> </p>

    <label for="articleNumber"><b>Artikelnummer</b></label><br>
    <input type="number" placeholder="Artikelnummer" name="articleNumber" id="articleNumber" >

    <label for="productName"><b>Produktname</b></label>
    <input type="text" placeholder="Produktname" name="productName" id="productName" >

    <label for="description"><b>Beschreibung</b></label>
    <input type="text" placeholder="Beschreibung" name="description" id="description" >

    <label for="price"><b>Preis</b></label>
    <input type="number" placeholder="Preis" name="price" id="price" >

    <label for="stock"><b>Stückzahl</b></label>
    <input type="number" placeholder="Stückzahl" name="stock" id="stock" >

    <label for="imageID"><b>Produtk Bild</b></label>
    <input type="file" placeholder="Produkt Bild" name="imageID" id="imageID" >    

    <hr>

    <button type="submit" class="registerbtn">Registrieren</button>
  </div>

  <div class="container signin">
    <p>Hast du schon einen Account? <a href="#">Einloggen</a>.</p>
  </div>
</form>

<style>
    * {box-sizing: border-box}

/* Add padding to containers */
.container {
  padding: 16px;
}

/* Full-width input fields */
input[type=text], input[type=password], input[type=number] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Overwrite default styles of hr */
hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

/* Set a style for the submit/register button */
.registerbtn {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity:1;
}

/* Add a blue text color to links */
a {
  color: dodgerblue;
}

/* Set a grey background color and center the text of the "sign in" section */
.signin {
  background-color: #f1f1f1;
  text-align: center;
}
</style>