<?php 
include("stores/checkAdmin.php");
include("stores/checkLoggedIn.php");
include("stores/validation.php");
include("components/navigation.php");
include("config/db.php");

// checkAdmin();
// checkLoggedIn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $psw = test_input($_POST["psw"]);

    if(!isset($email) || empty($email) || !isset($psw) || empty($psw) ) {
        echo htmlspecialchars("Etwas ist schiefgelaufen");
    } {
        $sql = "SELECT * FROM Persons WHERE email=?"; // SQL with parameters
        $stmt = $db->prepare($sql); 
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result
        $person = $result->fetch_assoc(); // fetch data

        if(password_verify($psw, $person["passwordHash"])) {
            $_SESSION["loggedIn"] = "yes";

            $_SESSION["addressID"] = $person["addressID"];

            if($person["isAdmin"] === 1) {
                $_SESSION["isAdmin"] = "yes";
            }
            header("Location: index.php");
        } else {
            echo htmlspecialchars("Passwort oder Email ist falsch");
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
                   initial-scale=1.0" />
      <title>Benutzer erstellen.</title>

      <style>
/* Bordered form */
form {
  border: 3px solid #f1f1f1;
}

/* Full-width inputs */
input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

/* Set a style for all buttons */
button {
  background-color: #04AA6D;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

/* Add a hover effect for buttons */
button:hover {
  opacity: 0.8;
}

/* Add padding to containers */
.container {
  padding: 16px;
}

/* The "Forgot password" text */
span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
    display: block;
    float: none;
  }
  .cancelbtn {
    width: 100%;
  }
}
      </style>
</head>

<body>
<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
  <div class="container">
    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Email" name="email" required>

    <label for="psw"><b>Passwort</b></label>
    <input type="password" placeholder="Passwort" name="psw" required>

    <button type="submit">Login</button>
  </div>
</form>

</body>

</html>