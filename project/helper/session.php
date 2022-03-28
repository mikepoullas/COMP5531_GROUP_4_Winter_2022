<?php
// Initialize the session
session_start();

function isLoggedIn()
{
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        return true;
    } else {
        return false;
    }
}
