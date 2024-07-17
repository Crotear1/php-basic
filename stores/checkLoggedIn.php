<?php

function checkLoggedIn() {
    if($_SESSION["loggedIn"] === "no") {
        header("Location: login.php");
    }
}