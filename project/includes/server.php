<?php

$user_id = $_SESSION['user_id'];

display_error();
display_success();

// UPLOAD FILE
if (isset($_POST['upload_file'])) {

    // receive all input values from the form
    // $content = mysqli_real_escape_string($conn, $_POST['content']);

    // name of the uploaded file
    $file_name = $_FILES['file']['name'];

    // unique file description based on username
    $content = $_SESSION['username'] . "_" . date('d_m_Y', time()) . "_" . $file_name;

    // destination of the file on the server
    $destination = '../files/' . $file_name;

    // get the file extension
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($content)) {
        array_push($errors, "File content is required");
    }
    if (!isset($_FILES)) {
        array_push($errors, "Please upload a file !!");
    }


    if (!in_array($extension, ['zip', 'pdf', 'docx', 'txt'])) {
        array_push($errors, "You file extension must be zip / pdf / docx / txt");
    } elseif ($_FILES['file']['size'] > 1000000) { // file shouldn't be larger than 1Megabyte
        array_push($errors, "File too large!");
    } elseif (count($errors) == 0) { {

            if (file_exists($destination)) {
                array_push($errors, "File already exists!");
            }
            // move the uploaded (temporary) file to the specified destination
            elseif (move_uploaded_file($file, $destination)) {
                $query = "INSERT INTO files (file_name, file_content, file_type, file_size, uploaded_by_uid, uploaded_on)
                            VALUES('$file_name', '$content', '$extension', $size, $user_id, NOW())";
                if (mysqli_query($conn, $query)) {
                    array_push($success, "File uploaded successfully");
                    header("location: {$_SERVER['HTTP_REFERER']}");
                    exit();
                }
            } else {
                array_push($errors, "Failed to upload file" . mysqli_error($conn));
            }
        }
    }
}









// DOWNLOAD FILE
if (isset($_GET['download_file'])) {

    $id = $_GET['download_file'];

    // fetch file to download from database
    $query = "SELECT * FROM files WHERE file_id='$id'";
    $result = mysqli_query($conn, $query);
    $file = mysqli_fetch_assoc($result);

    $filepath = '../files/' . $file['file_name'];

    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));
        // header('Content-Transfer-Encoding: Binary');
        // header('Expires: 0');
        // header('Cache-Control: must-revalidate');
        // header('Pragma: public');
        readfile($filepath);

        // Now update downloads count
        // $count_download = $file['downloads'] + 1;
        // $update_count = "UPDATE files SET downloads=$count_download WHERE file_id=$id";
        // mysqli_query($conn, $update_count);
        exit();
    }
}










// UPDATE FILE
if (isset($_POST['update_file'])) {

    $id = $_GET['update_file'];

    // receive all input values from the form
    // $content = mysqli_real_escape_string($conn, $_POST['content']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    // if (empty($content)) {
    //     array_push($errors, "File content is required");
    // }
    if (!isset($_FILES)) {
        array_push($errors, "Please upload a file !!");
    }

    // name of the uploaded file
    $file_name = $_FILES['file']['name'];

    // unique file description based on username
    $content = $_SESSION['username'] . "_" . date('d_m_Y', time()) . "_" . $file_name;

    // destination of the file on the server
    $destination = '../files/' . $file_name;

    // get the file extension
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];

    $filepath = '../files/' . $file_name;

    if (file_exists($filepath)) {
        array_push($errors, "File already exists!");
    }

    if (!in_array($extension, ['zip', 'pdf', 'docx', 'txt'])) {
        array_push($errors, "You file extension must be zip / pdf / docx / txt");
    } elseif ($_FILES['file']['size'] > 1000000) { // file shouldn't be larger than 1Megabyte
        array_push($errors, "File too large!");
    } elseif (count($errors) == 0) {

        // fetch file to download from database
        $query = "SELECT * FROM files WHERE file_id='$id'";
        $result = mysqli_query($conn, $query);
        $old_file = mysqli_fetch_assoc($result);

        $old_filepath = '../files/' . $old_file['file_name'];
        unlink($old_filepath);

        // move the uploaded (temporary) file to the specified destination
        if (move_uploaded_file($file, $destination)) {
            $query = "UPDATE files SET file_name='$file_name', file_content='$content', file_type='$extension', file_size=$size WHERE file_id=$id";
            if (mysqli_query($conn, $query)) {
                array_push($success, "File updated successfully");
                header("location: {$_SERVER['HTTP_REFERER']}");
                exit();
            }
        } else {
            array_push($errors, "Failed to update file " . mysqli_error($conn));
        }
    }
}



// DELETE FILE
if (isset($_GET['delete_file'])) {

    $id = mysqli_real_escape_string($conn, $_GET['delete_file']);

    $check = "SELECT * FROM files WHERE file_id='$id'";
    $delete = "DELETE FROM files WHERE file_id='$id'";

    if ($result = mysqli_query($conn, $check)) {
        $file_name = mysqli_fetch_assoc($result)['file_name'];
        $filepath = '../files/' . $file_name;

        if (unlink($filepath) && mysqli_query($conn, $delete)) {
            array_push($success, "Delete successful");
            header("location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }
    } else {
        array_push($errors, "Delete error " . mysqli_error($conn));
    }
}
