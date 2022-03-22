<?php

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM users WHERE user_id='$id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        include("CRUD/users.php");
    } else {
        echo 'Please check your Query';
    }
} else {
    include("CRUD/users.php");
}
