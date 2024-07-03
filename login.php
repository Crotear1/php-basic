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
    
    if (!isset($_POST["psw"]) && empty($_POST["psw"])) {
        $errorMessage = "Bitte Passwort setzen";
        return;
    } else {
        $psw = filter_input(INPUT_POST, "psw", FILTER_SANITIZE_STRING);
    }

    if($errorMessage === null) {
        $sql = "SELECT userID, isAdmin, password_hash, email FROM Users WHERE email=?";
        $stmt = $conn->prepare($sql); 
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if(password_verify($psw, $user["password_hash"])) {
            $_SESSION['loggedIn'] = "isLoggedIn";
            if($user["isAdmin"] === "yes") {
                $_SESSION["isAdmin"] = "yes";
            }
            header("Location: index.php");
        } else {
            $errorMessage = "Falsches Passwort";
        }
        

        // if(password_verify($psw, $row["password_hash"])) {
        //     echo "test";
        // }

        // while ($row = $result->fetch_assoc()) {
        //     echo $row['name'];
        // }
    }

    
}

?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  <div class="container">
    <p> <?php echo htmlspecialchars($errorMessage); ?> </p>
    <label for="email"><b>Email</b></label>
    <input type="text" placeholder="Email" name="email" required>

    <label for="psw"><b>Passwort</b></label>
    <input type="password" placeholder="Passwort" name="psw" required>

    <button type="submit">Login</button>
  </div>

</form>

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

/* Extra style for the cancel button (red) */
.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
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