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

// return user array from their username
function get_user_array()
{
    global $conn;
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);
    return $result;
}

// return role array
function get_role_array()
{
    global $conn;
    $query = "SELECT * FROM roles";
    $result = mysqli_query($conn, $query);
    return $result;
}

// return record from table using key-value
function get_record($table, $key, $value)
{
    global $conn;
    $query = "SELECT * FROM $table WHERE $key='$value'";
    $result = mysqli_query($conn, $query);
    return $result;
}
