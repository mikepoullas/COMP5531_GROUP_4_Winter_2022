<!--
CODE CONTRIBUTOR:

# COMP 5531 - GROUP 4 (Winter 2022)
Student_ID  First_Name  Last_Name   Email
40159305    shafiq      IMTIAZ      s_mtiaz@encs.concordia.ca
21917730    michael     POULLAS     m_poull@encs.concordia.ca
-->

<?php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();
$_REQUEST = array();

// Destroy the session.
session_destroy();

// Redirect to login page
header("location: login.php");
exit;
