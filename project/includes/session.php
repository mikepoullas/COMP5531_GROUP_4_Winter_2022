<?php
// Initialize the session

if (!isset($_SESSION)) {
    session_start();
}

require_once('../configs/db.php');
include('../functions/functions.php');
