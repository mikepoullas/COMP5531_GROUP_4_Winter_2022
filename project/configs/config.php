<?php

//Database params
define('DB_HOST', 'localhost:3306');  //Add your db host
define('DB_USER', 'root'); // Add your DB root
define('DB_PASS', 'root.SQL'); //Add your 
define('DB_NAME', 'cga'); //Add your DB Name

//APPROOT
define('APPROOT', dirname(dirname(__FILE__)));

//URLROOT (Dynamic links)
define('URLROOT', 'http://localhost/CGA/fresh/');

//Sitename
define('SITENAME', 'CGA');

/* Attempt to connect to MySQL database */
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("ERROR: Could not connect DB " . mysqli_connect_error());
}

$select_db = mysqli_select_db($conn, DB_NAME);
if (!$select_db) {
    die("ERROR: Could not select DB " . mysqli_error($conn));
}
