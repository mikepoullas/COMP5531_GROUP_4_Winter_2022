<!--
CODE CONTRIBUTOR:

# COMP 5531 - GROUP 4 (Winter 2022)
Student_ID  First_Name  Last_Name   Email
40159305    shafiq      IMTIAZ      s_mtiaz@encs.concordia.ca
21917730    michael     POULLAS     m_poull@encs.concordia.ca
-->

<?php

//Database params
define('DB_HOST', 'localhost:3306');  //Add your db host
define('DB_USER', 'root'); // Add your DB root
define('DB_PASS', 'root.SQL'); //Add your 
define('DB_NAME', 'cga'); //Add your DB Name

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
