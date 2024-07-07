<?php 
include '../config/dbConncetion.php';
include '../components/navigation.php';

if($_SESSION["isAdmin"] !== "yes") {
  header("Location: index.php");
}

$errorMessage = null;

if(isset($_GET["productID"]) && !empty($_GET["productID"])) {
    $productID = filter_var($_GET["productID"], FILTER_VALIDATE_INT);

    $sql = "SELECT * FROM Products WHERE productID=? LIMIT 1"; // SQL with parameters
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result(); // get the mysqli result
    $product = $result->fetch_assoc(); // fetch data   

    $articleNumber = $product["articleNumber"];
    $productName = $product["productName"];
    $description = $product["description"];
    $price = $product["price"];
    $stock = $product["stock"];

}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{  
  $productID = filter_var($_POST['productID'], FILTER_VALIDATE_INT);
  if ($productID === false) {
      $errorMessage = "Invalid product ID.";
      return; 
  }  


    $target_dir = "/php-basic/images/";
    $target_file = $_SERVER['DOCUMENT_ROOT'] . $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if($_FILES["fileToUpload"]["tmp_name"]) {
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
    if ($_FILES["fileToUpload"]["size"] > 500000000) {
         $errorMessage = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $errorMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }} else {
      $uploadOk = 2;
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
        $price = filter_var($_POST["price"], FILTER_SANITIZE_NUMBER_FLOAT);
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

    if ($uploadOk == 0 && $errorMessage !== null) {
        
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $stmt = $conn->prepare("UPDATE Products SET articleNumber=?, productName=?, price=?, stock=?, imageID=?, description=? WHERE productID=?");

            echo $articleNumber, $productName, $price, $stock, $_FILES["fileToUpload"]["name"], $description, $productID;

            $stmt->bind_param("isdissi", $articleNumber, $productName, $price, $stock, $_FILES["fileToUpload"]["name"], $description, $productID);
        
            $stmt->execute();
            
            // header("Location: products.php");

        } else if($uploadOk === 2) {
          $stmt = $conn->prepare("UPDATE Products SET articleNumber=?, productName=?, price=?, stock=?, description=? WHERE productID=?");

            echo $articleNumber, $productName, $price, $stock, $description, $productID;

            $stmt->bind_param("isdisi", $articleNumber, $productName, $price, $stock, $description, $productID);
        
            $stmt->execute();
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

    <p> <?php echo $errorMessage; ?> </p>

    <label for="articleNumber"><b>Artikelnummer</b></label><br>
    <input type="number" placeholder="Artikelnummer" name="articleNumber" id="articleNumber" value="<?php echo $articleNumber ?>">

    <label for="productName"><b>Produktname</b></label>
    <input type="text" placeholder="Produktname" name="productName" id="productName" value="<?php echo $productName ?>" >

    <label for="description"><b>Beschreibung</b></label>
    <input type="text" placeholder="Beschreibung" name="description" id="description" value="<?php echo $description ?>">

    <label for="price"><b>Preis</b></label>
    <input type="number" placeholder="Preis" name="price" id="price" value="<?php echo $price ?>">

    <label for="stock"><b>Stückzahl</b></label>
    <input type="number" placeholder="Stückzahl" name="stock" id="stock" value="<?php echo $stock ?>">

    <label for="fileToUpload"><b>Produtk Bild</b></label>
    <input type="file" name="fileToUpload" id="fileToUpload" >

    <input type="hidden" name="productID" value="<?php echo $productID; ?>">
    <br>
    <img style="width: 100px; margin: 100px;" src="<?php echo "../images/" . $product["imageID"] ?>">
    <hr>

    <button type="submit" class="registerbtn">Aktualisieren</button>
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