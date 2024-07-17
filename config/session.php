<?php

session_start();

if(!isset($_SESSION["loggedIn"])) {
    $_SESSION["loggedIn"] = "no";
}
if(!isset($_SESSION["isAdmin"])) {
    $_SESSION["isAdmin"] = "no";
}
if(!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}
if(!isset($_SESSION["addressID"])) {
    $_SESSION["addressID"] = null;
}