<?php

// initializing variables
$id = $file_name = $file_type = $file_size = $file_downloads = "";

$user_id = $_SESSION['user_id'];

// UPLOAD
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
                    array_push($success, "File uploaded successfully");
                    // header('location: ?page=files');
                }
            } else {
                array_push($errors, "Failed to upload file" . mysqli_error($conn));
            }
        }
    }
}

// DOWNLOAD
if (isset($_GET['download_id'])) {

    $id = $_GET['download_id'];

    // fetch file to download from database
    $query = "SELECT * FROM files WHERE file_id='$id'";
    $result = mysqli_query($conn, $query);

    $file = mysqli_fetch_assoc($result);
    $filepath = '../files/' . $file['file_name'];

    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize('../files/' . $file['file_name']));
        readfile('../files/' . $file['file_name']);

        // Now update downloads count
        $count_download = $file['downloads'] + 1;
        $update_count = "UPDATE files SET downloads=$count_download WHERE file_id=$id";
        mysqli_query($conn, $update_count);
        exit;
    }
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete = "DELETE FROM files WHERE file_id='$id'";
    if (mysqli_query($conn, $delete)) {
        array_push($success, "Delete successful");
        // header('location: ?page=files');
    } else {
        array_push($errors, "Delete error: " . mysqli_error($conn));
    }
}

?>

<div class="content-body">
    <?php

    display_success();
    display_error();

    $query = "SELECT * FROM files as f
                JOIN users as u ON u.user_id = f.uploaded_by_uid 
                ORDER BY file_id ASC";
    $results = mysqli_query($conn, $query);

    ?>
    <h2>Files</h2>
    <hr>
    <table>
        <thead>
            <tr>
                <?php isAdmin() ? print '<th>File ID</th>' : ''; ?>
                <th>Name</th>
                <th>Content</th>
                <th>Type</th>
                <th>Size</th>
                <th>Uploaded by</th>
                <th>Uploaded on</th>
                <th>Downloads</th>
                <?php isAdmin() ? print '<th colspan="2">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {
                $id = $row['file_id'];
                $file_name = $row['file_name'];
                $content = $row['content'];
                $type = $row['type'];
                $size = $row['size'];
                $uploaded_by_uid = $row['username'];
                $uploaded_on = date_convert($row['uploaded_on']);
                $downloads = $row['downloads'];
            ?>
                <tr>
                    <?php if (isAdmin()) {
                        echo '<td>' . $id . '</td>';
                    } ?>
                    <td><?= $file_name ?></td>
                    <td><?= $content ?></td>
                    <td><?= $type ?></td>
                    <td><?= $size ?></td>
                    <td><?= $uploaded_by_uid ?></td>
                    <td><?= $uploaded_on ?></td>
                    <td><?= $downloads ?></td>
                    <?php if (isAdmin()) {
                        echo '<td><a href="?page=files&download_id=' . $id . '">Download</a></td>';
                        echo '<td><a href="?page=files&delete_id=' . $id . '">Delete</a></td>';
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=files&add_view=true">
            <button>Add File</button>
        </a>

        <?php if (isset($_GET['add_view'])) { ?>
            <hr>
            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST">

                    <h3>Upload File</h3>
                    <div class="form-input">
                        <label>File Description</label>
                        <span><input type="text" name="content"></span>
                    </div>
                    <div class="form-input">
                        <label>Select file</label>
                        <span><input type="file" name="file"> </span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="upload_file" value="Upload">
                    </div>
                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>