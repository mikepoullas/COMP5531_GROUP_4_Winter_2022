<?php

$session_user_id = $_SESSION['user_id'];

// UPLOAD FILE
function upload_file($table)
{
    global $conn, $session_user_id, $errors, $success;

    // name of the uploaded file with extension
    $file_name = $_FILES['file']['name'];

    // name of the uploaded file
    $name = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);

    // unique file description based on username
    $content = $table . "_" . $name;
    // date('d_m_Y', time())

    // destination of the file on the server
    $destination = '../files/' . $file_name;

    // get the file extension
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);

    // the physical file on a temporary uploads directory on the server
    $file = $_FILES['file']['tmp_name'];
    $size = $_FILES['file']['size'];

    if ($_FILES['file']['error'] == 4) {
        array_push($errors, "Please upload a file !!");
    } elseif (!in_array($extension, ['zip', 'pdf', 'docx', 'txt'])) {
        array_push($errors, "You file extension must be zip / pdf / docx / txt");
    } elseif ($_FILES['file']['size'] > 5000000) { // file shouldn't be larger than 5 Megabyte
        array_push($errors, "File too large!");
    } elseif (count($errors) == 0) {
        if (file_exists($destination)) {
            array_push($errors, "File already exists!");
        }
        // move the uploaded (temporary) file to the specified destination
        elseif (move_uploaded_file($file, $destination)) {
            $query = "INSERT INTO files (file_name, file_content, file_type, file_size, uploaded_by_uid, uploaded_on)
                                VALUES('$file_name', '$content', '$extension', $size, $session_user_id, NOW())";
            if (mysqli_query($conn, $query)) {
                array_push($success, "File uploaded successfully");
                header("location: {$_SERVER['HTTP_REFERER']}");
                return $conn->insert_id;
                exit();
            }
        } else {
            array_push($errors, "Failed to upload file" . mysqli_error($conn));
        }
    }
}

// DOWNLOAD FILE
function download_file($id)
{
    global $conn;

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
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');

        ob_clean();
        flush();
        readfile($filepath);
        exit();
    }
}




// UPDATE FILE
function update_file($table, $id)
{
    global $conn, $errors, $success;

    if (!isset($_FILES)) {
        array_push($errors, "Please upload a file !!");
    }

    // name of the uploaded file with extension
    $file_name = $_FILES['file']['name'];

    // name of the uploaded file
    $name = pathinfo($_FILES['file']['name'], PATHINFO_FILENAME);

    // unique file description based on username
    $content = $table . "_" . $name;
    // date('d_m_Y', time())

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
function delete_file($id)
{
    global $conn, $errors, $success;

    $check = "SELECT * FROM files WHERE file_id='$id'";
    $delete = "DELETE FROM files WHERE file_id='$id'";

    if ($result = mysqli_query($conn, $check)) {
        $file_name = mysqli_fetch_assoc($result)['file_name'];
        $filepath = '../files/' . $file_name;

        if (mysqli_query($conn, $delete)) {
            unlink($filepath);
            array_push($success, "Delete successful");
            header("location: {$_SERVER['HTTP_REFERER']}");
            exit();
        }
    } else {
        array_push($errors, "Delete error " . mysqli_error($conn));
    }
}
