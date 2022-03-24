<?php

$errors = array();
$success = array();

// show errors
function display_error()
{
    global $errors;
    if (count($errors) > 0) {
        echo '<div class="error">';
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
        echo '<div class="success">';
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

function isAdmin()
{
    if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
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

function pre_print($var)
{
    echo '<pre>';
    print_r($var);
    echo '</pre>';
}
