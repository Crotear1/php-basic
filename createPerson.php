<?php 
include("stores/checkAdmin.php");
include("stores/checkLoggedIn.php");
include("stores/validation.php");
include("components/navigation.php");
include("config/db.php");

checkAdmin();
checkLoggedIn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $psw = test_input($_POST["psw"]);
    $fn = test_input($_POST["fn"]);
    $ln = test_input($_POST["ln"]);
    $street = test_input($_POST["street"]);
    $housenumber = test_input($_POST["housenumber"]);
    $zip = test_input($_POST["zip"]);
    $city = test_input($_POST["city"]);
    $isAdmin = test_input($_POST["isAdmin"]);

    if (!empty($_POST['email']) && !empty($_POST['psw']) && !empty($_POST['fn']) && !empty($_POST['ln']) 
    && !empty($_POST['street']) && !empty($_POST['housenumber']) && !empty($_POST['zip']) 
    && !empty($_POST['city']) && in_array($_POST["isAdmin"], [0, 1])) {
        try {
        $sql = "INSERT INTO Address (street, housenumber, zip, city) VALUES (?,?,?,?)";
        $stmt= $db->prepare($sql);
        $stmt->bind_param("ssss", $street, $housenumber, $zip, $city);
        if($stmt->execute()) {
            $addressId = $db->insert_id;
            // echo $addressId;
            $sql = "INSERT INTO Persons (email, passwordHash, firstname, lastname, isAdmin, addressID) VALUES (?,?,?,?,?,?)";
            $stmt= $db->prepare($sql);

            $hashedPassword = password_hash($psw, PASSWORD_DEFAULT);
            
            if($stmt->bind_param("ssssii", $email, $hashedPassword, $fn, $ln, $isAdmin, $addressId) && $stmt->execute()) {
                echo htmlspecialchars("Benutzer Erfolgreich erstellt");
            } else {
                echo htmlspecialchars("Benutzer konnte nicht erstellt werden.");
            }
        } else {
            echo htmlspecialchars("Benutzer konnte nicht erstellt werden.");
        } }
        catch(mysqli_sql_exception $e) {
            echo htmlspecialchars($e->getMessage());
        }

    } else {
        echo htmlspecialchars("Einige Daten fehlen");
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
      <title>Benutzer erstellen.</title>

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
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="border:1px solid #ccc">
  <div class="container">
    <h1>Benutzer erstellen.</h1>
    <hr>

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Email eingeben" name="email" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

    <label for="fn"><b>Vorname</b></label>
    <input type="text" placeholder="Vorname" name="fn" required>

    <label for="ln"><b>Nachname</b></label>
    <input type="text" placeholder="Nachname" name="ln" required>

    <label for="street"><b>Straße</b></label>
    <input type="text" placeholder="Straße" name="street" required>

    <label for="housenumber"><b>Hausnummer</b></label>
    <input type="text" placeholder="Hausnummer" name="housenumber" required>

    <label for="zip"><b>Postleitzahl</b></label>
    <input type="text" placeholder="Postleitzahl" name="zip" required>

    <label for="city"><b>Stadt</b></label>
    <input type="text" placeholder="Stadt" name="city" required>


    <label for="isAdmin">Ist Admin</label>

    <select name="isAdmin" id="isAdmin">
        <option value="0">Nein</option>
        <option value="1" selected>Ja</option>
    </select>

    <div class="clearfix">
      <button type="submit" class="button signupbtn">Sign Up</button>
    </div>
  </div>
</form>
</body>

</html>