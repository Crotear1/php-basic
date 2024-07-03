<?php

session_start();

if (!isset($_SESSION["loggedIn"])) {
    $_SESSION["loggedIn"] = 'notLoggedIn';
}

if (!isset($_SESSION["shoppingCart"])) {
    $_SESSION["shoppingCart"] = [];
}

if (!isset($_SESSION["isAdmin"])) {
    $_SESSION["isAdmin"] = 'notAdmin';
}