<?php 
include("stores/checkAdmin.php");
include("stores/checkLoggedIn.php");
include("stores/validation.php");
include("components/navigation.php");
include("config/db.php");

checkAdmin();
checkLoggedIn();

$productID = test_input($_GET["productID"]);

$sql = "SELECT * FROM Products WHERE productID=?"; // SQL with parameters
$stmt = $db->prepare($sql); 
$stmt->bind_param("i", $productID);
$stmt->execute();
$result = $stmt->get_result(); // get the mysqli result
$product = $result->fetch_assoc(); // fetch data

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
    $articleNumber = test_input($_POST["articleNumber"]);
    $productIDState = test_input($_POST["productID"]);
    $productName = test_input($_POST["productName"]);
    $price = test_input($_POST["price"]);
    $description = test_input($_POST["description"]);
    $available = test_input($_POST["available"]);
    $imageID = test_input($_POST["imageID"]);
    $fileToUpload = test_input($_FILES["fileToUpload"]["name"]);

    if(!isset($articleNumber) || empty($articleNumber) 
    || !isset($productName) || empty($productName) ||
     !isset($price) || empty($price) ||
      !isset($description) || empty($description) ||
           !isset($available)){
                // echo $isAdmin . " " . $email . " " . $psw . " " . $fn . " " . $ln . " " . $street . " " . $housenumber . " " . $zip . " " . $city;
                echo htmlspecialchars("Etwas ist schiefgelaufen");
            } else {
                if(isset($fileToUpload) && !empty($fileToUpload)) {
                    $target_dir = "pictures/";
                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                    if($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }
    
                    // Check if file already exists
                    if (file_exists($target_file)) {
                    echo "Sorry, file already exists.";
                    $uploadOk = 0;
                    }
    
                    // Check file size
                    if ($_FILES["fileToUpload"]["size"] > 50000000) {
                    echo "Sorry, your file is too large.";
                    $uploadOk = 0;
                    }
    
                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                    }
    
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        echo "Sorry, your file was not uploaded.";
                        // if everything is ok, try to upload file
                        } else {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) && unlink("pictures/{$imageID}")) {
                            $sql = "UPDATE Products SET articleNumber=?, productName=?, price=?, description=?, imageID=?, available=? WHERE productID=?";
                            $stmt= $db->prepare($sql);
                            $stmt->bind_param("isdssii", $articleNumber, $productName, $price, $description, $fileToUpload, $available, $productIDState);
                            if($stmt->execute()) {
                                // echo $available;
                                header("Location: products.php");
                            } else {
                                echo htmlspecialchars("Änderungen konnten nicht gespeichert werden.");
                            }
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    }
                } else {
                    $sql = "UPDATE Products SET articleNumber=?, productName=?, price=?, description=?, available=? WHERE productID=?";
                    $stmt= $db->prepare($sql);
                    $stmt->bind_param("isdsii", $articleNumber, $productName, $price, $description, $available, $productIDState);
                    if($stmt->execute()) {
                        // echo $available;
                        header("Location: products.php");
                    } else {
                        echo htmlspecialchars("Änderungen konnten nicht gespeichert werden.");
                    }
                }
            }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $productIDState = test_input($_POST["productID"]);
    if(isset($productIDState) && !empty($productIDState)) {
        $sql = "UPDATE Products SET isDeleted=? WHERE productID=?";
        $stmt= $db->prepare($sql);
        $isDeleted = 1;
        $stmt->bind_param("ii", $isDeleted, $productIDState);
        if($stmt->execute()) {
            // echo $available;
            header("Location: products.php");
        } else {
            echo htmlspecialchars("Änderungen konnten nicht gespeichert werden.");
        }
    } else {
        echo htmlspecialchars("ID fehlt");
    }
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
      <title>Produkt bearbeiten.</title>

      <style>
        * {box-sizing: border-box}

            /* Full-width input fields */
            input[type=text], input[type=password] {
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

            hr {
            border: 1px solid #f1f1f1;
            margin-bottom: 25px;
            }

            /* Set a style for all buttons */
            .button {
            background-color: #04AA6D;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
            opacity: 0.9;
            }

            .button:hover {
            opacity:1;
            }

            /* Extra styles for the cancel button */
            .cancelbtn {
            padding: 14px 20px;
            background-color: #f44336;
            }

            /* Float cancel and signup buttons and add an equal width */
            .cancelbtn, .signupbtn {
            float: left;
            width: 50%;
            }

            /* Add padding to container elements */
            .container {
            padding: 16px;
            }

            /* Clear floats */
            .clearfix::after {
            content: "";
            clear: both;
            display: table;
            }

            /* Change styles for cancel button and signup button on extra small screens */
            @media screen and (max-width: 300px) {
            .cancelbtn, .signupbtn {
                width: 100%;
            }
            }
      </style>
</head>

<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="border:1px solid #ccc" enctype="multipart/form-data">
  <div class="container">
    <h1>Produkt bearbeiten.</h1>
    <hr>

    <label for="articleNumber"><b>Artikelnummer</b></label>
    <input type="text" placeholder="Artikelnummer" name="articleNumber" value="<?php if(!empty($product["articleNumber"])) {echo htmlspecialchars($product["articleNumber"]); } ?>" required>

    <label for="productName"><b>Produktname</b></label>
    <input type="text" placeholder="Produktname" name="productName" value="<?php if(!empty($product["productName"])) {echo htmlspecialchars($product["productName"]); } ?>"  required>

    <label for="price"><b>Preis</b></label>
    <input type="text" placeholder="Preis" name="price" value="<?php if(!empty($product["price"])) {echo htmlspecialchars($product["price"]); } ?>"  required>

    <label for="description"><b>Beschreibung</b></label>
    <input type="text" placeholder="Beschreibung" name="description" value="<?php if(!empty($product["description"])) {echo htmlspecialchars($product["description"]); } ?>"  required>

    <label for="available">Ist Verfügbar</label>

    <select name="available" id="available">
        <option value="0" <?php echo htmlspecialchars($product["available"] === 0) && !empty($product["available"]) ? 'selected' :  '';?> >Nein</option>
        <option value="1" <?php echo htmlspecialchars($product["available"] === 1) && !empty($product["available"]) ?  'selected' :  '';?> >Ja</option>
    </select>
    <br><br>

    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="hidden" id="productID" name="productID" value="<?php if(!empty($product["productID"])) {echo htmlspecialchars($product["productID"]); } ?>">
    <input type="hidden" id="imageID" name="imageID" value="<?php if(!empty($product["imageID"])) {echo htmlspecialchars($product["imageID"]); } ?>">
    <input type="hidden" id="edit" name="edit">
    <div class="clearfix">
      <button type="submit" class="button signupbtn">Bearbeiten</button>
    </div>
  </div>
</form>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="border:1px solid #ccc" enctype="multipart/form-data">
    <input type="hidden" id="productID" name="productID" value="<?php if(!empty($product["productID"])) {echo htmlspecialchars($product["productID"]); } ?>">
    <input type="hidden" id="delete" name="delete">
    <div class="clearfix">
        <button type="submit" class="button signupbtn" style="background: red; margin-left: 10px;">Produkt löschen</button>
    </div>
</form>
</body>

</html>