<?php

// UPLOAD FILE
if (isset($_POST['upload_file'])) {

    // receive all input values from the form
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // name of the uploaded file
    $file_name = $_FILES['file']['name'];

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
            // move the uploaded (temporary) file to the specified destination
            if (move_uploaded_file($file, $destination)) {
                $query = "INSERT INTO files (file_name, content, type, size, uploaded_by_uid, uploaded_on, downloads)
                            VALUES('$file_name', '$content', '$extension', $size, $user_id, NOW(), 0)";
                if (mysqli_query($conn, $query)) {
                    // array_push($success, "File uploaded successfully");
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
        // header('Content-Transfer-Encoding: Binary');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));
        // header('Expires: 0');
        // header('Cache-Control: must-revalidate');
        // header('Pragma: public');
        readfile($filepath);

        ignore_user_abort(true);
        unlink($filepath);

        // Now update downloads count
        $count_download = $file['downloads'] + 1;
        $update_count = "UPDATE files SET downloads=$count_download WHERE file_id=$id";
        mysqli_query($conn, $update_count);
        exit();
    }
}

// DELETE FILE
if (isset($_GET['delete_file'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_file']);
    $delete = "DELETE FROM files WHERE file_id='$id'";
    if (mysqli_query($conn, $delete)) {
        array_push($success, "Delete successful");
        header("location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}
