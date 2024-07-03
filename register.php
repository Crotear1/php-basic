<?php
include 'config/dbConncetion.php';
include 'components/navigation.php';

if($_SESSION["loggedIn"] === "isLoggedIn") {
  header("Location: index.php");
}

$errorMessage = null;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{  
    if (isset($_POST["email"]) && !empty($_POST["email"])) {
        $email = $_POST["email"]; 
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
        } else {
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        }
    } else {
        $errorMessage = "Bitte geben Sie Ihre E-Mail-Adresse ein.";
        return;
    }    
    
    if (isset($_POST["psw"]) && !empty($_POST["psw"]) && isset($_POST["psw-repeat"]) && !empty($_POST["psw-repeat"] && $_POST["psw"] === $_POST["psw-repeat"])) {
        $hashedPassword = password_hash($_POST["psw"], PASSWORD_DEFAULT);
    } else {
        return $errorMessage = "Passwörter stimmen nicht überein";
    }

    if (isset($_POST["fn"]) && !empty($_POST["fn"])) {
        $fn = filter_var($_POST["fn"], FILTER_SANITIZE_STRING);
    } else {
        $errorMessage = "Bitte geben Sie Ihren Vornamen ein.";
        return;
    }     
    
    if (isset($_POST["ln"]) && !empty($_POST["ln"])) {
        $ln = filter_var($_POST["ln"], FILTER_SANITIZE_STRING);
    } else {
        $errorMessage = "Bitte geben Sie Ihren Nachnamen ein.";
        return;
    }      
    
    if (isset($_POST["housenumber"]) && !empty($_POST["housenumber"])) {
        $housenumber = filter_var($_POST["housenumber"], FILTER_SANITIZE_STRING);
    } else {
        $errorMessage = "Bitte geben Sie Ihre Hausnummer ein.";
        return;
    }  

    if (isset($_POST["street"]) && !empty($_POST["street"])) {
        $street = filter_var($_POST["street"], FILTER_SANITIZE_STRING);
    } else {
        $errorMessage = "Bitte geben Sie Ihre Straße ein.";
        return;
    }     
    
    if (isset($_POST["city"]) && !empty($_POST["city"])) {
        $city = filter_var($_POST["city"], FILTER_SANITIZE_STRING);
    } else {
        $errorMessage = "Bitte geben Sie Ihre Stadt ein.";
        return;
    }      
    
    if (isset($_POST["zip"]) && !empty($_POST["zip"])) {
        $zip = filter_var($_POST["zip"], FILTER_SANITIZE_STRING);
    } else {
        $errorMessage = "Bitte geben Sie Ihre Postleitzahl ein.";
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

    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email" id="email" >

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" id="psw" >

    <label for="psw-repeat"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" >

    <label for="fn"><b>Vorname</b></label>
    <input type="text" placeholder="Vorname" name="fn" id="fn" >

    <label for="ln"><b>Nachname</b></label>
    <input type="text" placeholder="Nachname" name="ln" id="ln" >
    
    <label for="housenumber"><b>Hausnummer</b></label>
    <input type="text" placeholder="Hausnummer" name="housenumber" id="housenumber" >    
    
    <label for="street"><b>Straße</b></label>
    <input type="text" placeholder="Straße" name="street" id="street" >    
    
    <label for="city"><b>Stadt</b></label>
    <input type="text" placeholder="Stadt" name="city" id="city" >    
    
    <label for="zip"><b>Postleitzahl</b></label>
    <input type="text" placeholder="Postleitzahl" name="zip" id="zip" >

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