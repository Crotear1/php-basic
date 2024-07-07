<?php 
include '../config/dbConncetion.php';
include '../components/navigation.php';

if($_SESSION["isAdmin"] !== "yes") {
  header("Location: index.php");
}

$errorMessage = null;
$successMessage = null;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{  
    $target_dir = "/php-basic/images/";
    $target_file = $_SERVER['DOCUMENT_ROOT'] . $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $errorMessage = "File is not an image.";
        $uploadOk = 0;
    }
    
    // Check if file already exists
    if (file_exists($target_file)) {
        $errorMessage = "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $errorMessage = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

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
      $price = str_replace(',', '.', $_POST["price"]);
      $price = filter_var($price, FILTER_VALIDATE_FLOAT);
    } else {
        $errorMessage = "Bitte geben Sie einen gültigen Preis ein.";
        return;
    }     
    
    if (isset($_POST["stock"]) && !empty($_POST["stock"])) {
        $stock = filter_var($_POST["stock"], FILTER_SANITIZE_STRING);
    } else {
        $errorMessage = "Bitte geben Sie eine gültige Anzahl ein.";
        return;
    }   

    if (isset($_POST["available"]) && !empty($_POST["available"])) {
        $available = filter_var($_POST["available"], FILTER_SANITIZE_NUMBER_INT);
        echo $available;
    } else {
        return $errorMessage = "Bitte wähle eine Option";
    }      

    if ($uploadOk == 0 && $errorMessage !== null) {
      exit;
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

            $stmt = $conn->prepare("INSERT INTO Products(articleNumber, productName, price, stock, imageID, description, available) VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("isdissi", $articleNumber, $productName, $price, $stock, $_FILES["fileToUpload"]["name"], $description, $available);
        
            $stmt->execute();

            $successMessage = "Produkt erfolgreich erstellt";

        } else {
            $errorMessage = "Sorry, there was an error uploading your file.";
        }
    }

}

?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data">
  <div class="container">
    <h1>Registrieren</h1>
    <hr>

    <?php if ($errorMessage): ?>
        <div class="error-message"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <?php if ($successMessage): ?>
        <div class="success-message"><?php echo $successMessage; ?></div>
    <?php endif; ?>

    <label for="articleNumber"><b>Artikelnummer</b></label><br>
    <input type="number" placeholder="Artikelnummer" name="articleNumber" id="articleNumber" >

    <label for="productName"><b>Produktname</b></label>
    <input type="text" placeholder="Produktname" name="productName" id="productName" >

    <label for="description"><b>Beschreibung</b></label>
    <input type="text" placeholder="Beschreibung" name="description" id="description" >

    <label for="price"><b>Preis</b></label>
    <input type="number" step=".01" placeholder="Preis" name="price" id="price" >

    <label for="stock"><b>Stückzahl</b></label>
    <input type="number" placeholder="Stückzahl" name="stock" id="stock" >

    
    <label for="available"><b>Anzeigen</b></label><br>
    <select name="available" required>
      <option value="null">Hier auswählen</option>
      <option value="0">Anzeigen</option>
      <option value="1">Verstecken</option>
    </select>
    <br>
    <br>


    <label for="fileToUpload"><b>Produtk Bild</b></label>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <hr>

    <button type="submit" class="registerbtn">Registrieren</button>
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