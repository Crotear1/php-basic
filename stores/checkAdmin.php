<?php

function checkAdmin() {
    if($_SESSION["isAdmin"] !== "yes") {
        header("Location: index.php");
    }
}