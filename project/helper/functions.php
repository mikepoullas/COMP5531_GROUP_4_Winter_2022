<?php

$errors = array();
$success = array();

// show errors
function display_error()
{
    global $errors;
    if (count($errors) > 0) {
        echo '<div class="error" id="notification" onclick="this.remove()">';
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
}

// show success
function display_success()
{
    global $success;
    if (count($success) > 0) {
        echo '<div class="success" id="notification" onclick="this.remove()">';
        foreach ($success as $success) {
            echo $success . '<br>';
        }
        echo '</div>';
    }
}

// return table array
function get_table_array($table)
{
    global $conn;
    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);
    return $result;
}

// return record from table using key-value
function get_records_where($table, $key, $value)
{
    global $conn;
    $query = "SELECT * FROM $table WHERE $key='$value'";
    $result = mysqli_query($conn, $query);
    return $result;
}

function isGroupLeader($student_id, $group_id)
{
    global $conn;
    $query = "SELECT * FROM student_groups WHERE group_leader_sid='$student_id' AND group_id='$group_id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        return true;
    }
    return false;
}

function isAdmin()
{
    if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
        return true;
    }
    return false;
}

function isProfessor()
{
    if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 2) {
        return true;
    }
    return false;
}

function isTA()
{
    if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3) {
        return true;
    }
    return false;
}

function isStudent()
{
    if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 4) {
        return true;
    }
    return false;
}

function isLoggedIn()
{
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        return true;
    } else {
        return false;
    }
}

function prep($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}

function date_convert($date)
{
    $date = strtotime($date);
    return date('d-M-y g:i A', $date);
}

/***************************************************************************/


// return column names from table
function get_column_names($table)
{
    global $conn;
    $query = "SHOW COLUMNS FROM $table"; // WHERE Field = $var
    $result = mysqli_query($conn, $query);
    return $result;
}

/*
$results_head = get_column_names('users');
while ($column = mysqli_fetch_assoc($results_head)) {
    $col_name = $column['Field'];
    echo '<th>' . $col_name . '</th>';
}
*/


//get page
function get_page($dir, $filename, $default = false)
{
    $root = "../";
    $path = $root . $dir;

    if (is_dir($path)) {
        if (file_exists($path . '/' . $filename . '.php')) {
            include($path . '/' . $filename . '.php');
            return true;
        }
        if (file_exists($path . '/' . $filename . '.html')) {
            include($path . '/' . $filename . '.html');
            return true;
        }
        if ($default) {
            if (file_exists($path . '/' . $default . '.php')) {
                include($path . '/' . $filename . '.php');
                return true;
            }
        }
    }
}
