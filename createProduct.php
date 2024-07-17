<?php 
include("stores/checkAdmin.php");
include("stores/checkLoggedIn.php");
include("stores/validation.php");
include("components/navigation.php");
include("config/db.php");

checkAdmin();
checkLoggedIn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $articleNumber = test_input($_POST["articleNumber"]);
    $productName = test_input($_POST["productName"]);
    $price = test_input($_POST["price"]);
    $description = test_input($_POST["description"]);
    $available = test_input($_POST["available"]);
    $fileToUpload = test_input($_FILES["fileToUpload"]["name"]);

    if(!isset($articleNumber) || empty($articleNumber) 
    || !isset($productName) || empty($productName) ||
     !isset($price) || empty($price) ||
      !isset($description) || empty($description) ||
           !isset($available)){
                // echo $isAdmin . " " . $email . " " . $psw . " " . $fn . " " . $ln . " " . $street . " " . $housenumber . " " . $zip . " " . $city;
                echo htmlspecialchars("Etwas ist schiefgelaufen");
            } else {

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
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $sql = "INSERT INTO Products (articleNumber, productName, price, description, imageID, available, isDeleted) VALUES (?,?,?,?,?,?,?)";
                        $stmt= $db->prepare($sql);
                        $isDeleted = 0;
                        $stmt->bind_param("isdssii", $articleNumber, $productName, $price, $description, $fileToUpload, $available, $isDeleted);
                        if($stmt->execute()) {
                            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                            echo htmlspecialchars("Produkt wurde erfolgreich erstellt");
                        } else {
                            echo htmlspecialchars("Etwas ist schiefgelaufen");
                        }
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                }
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
      <title>Produkt erstellen.</title>

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
    <h1>Produkt erstellen.</h1>
    <hr>

    <label for="articleNumber"><b>Artikelnummer</b></label>
    <input type="text" placeholder="Artikelnummer" name="articleNumber" required>

    <label for="productName"><b>Produktname</b></label>
    <input type="text" placeholder="Produktname" name="productName" required>

    <label for="price"><b>Preis</b></label>
    <input type="text" placeholder="Preis" name="price" required>

    <label for="description"><b>Beschreibung</b></label>
    <input type="text" placeholder="Beschreibung" name="description" required>

    <label for="available">Ist Verfügbar</label>

    <select name="available" id="available">
        <option value="2">Bitte auswählen</option>
        <option value="0">Nein</option>
        <option value="1">Ja</option>
    </select>
    <br><br>

    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">

    <div class="clearfix">
      <button type="submit" class="button signupbtn">Erstellen</button>
    </div>
  </div>
</form>
</body>

</html>