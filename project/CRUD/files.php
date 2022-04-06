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
                <?php isAdmin() ? print '<th colspan="3">Action</th>' : ''; ?>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($results as $row) {
                $id = $row['file_id'];
                $file_name = $row['file_name'];
                $content = $row['file_content'];
                $type = $row['file_type'];
                $size = $row['file_size'];
                $uploaded_by_uid = $row['username'];
                $uploaded_on = date_convert($row['uploaded_on']);
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
                    <?php if (isAdmin()) {
                        echo "<td><a href='?page=files&download_file=" . $id . "'>Download</a></td>";
                        echo "<td><a href='?page=files&update_view=true&update_file=" . $id . "'>Update</a></td>";
                        echo "<td><a href='?page=files&delete_file=" . $id . "' onclick='return confirm(&quot;Are you sure you want to delete?&quot;)'>Delete File</a></td>";
                    } ?>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <?php if (isAdmin()) { ?>
        <a href="?page=files&upload_view=true">
            <button>Upload File</button>
        </a>

        <?php if (isset($_GET['upload_view'])) { ?>
            <hr>
            <div class="form-container">
                <form class="form-body" action="" enctype="multipart/form-data" method="POST">

                    <h3>Upload File</h3>

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

        <?php if (isset($_GET['update_view'])) { ?>

            <?php
            $id = mysqli_real_escape_string($conn, $_GET['update_file']);
            $query = "SELECT * FROM files WHERE file_id='$id'";

            $results = mysqli_query($conn, $query);

            foreach ($results as $row) {
                $id = $row['file_id'];
                $file_name = $row['file_name'];
                $content = $row['file_content'];
            }

            ?>

            <hr>
            <div class="form-container">
                <form class="form-body" action="?update_file=<?= $id ?>" enctype="multipart/form-data" method="POST">
                    <h3>Update File</h3>
                    <!-- <div class="form-input">
                        <label>File Name</label>
                        <span><?= $file_name ?></span>
                    </div> -->
                    <div class="form-input">
                        <label>Select file</label>
                        <span><input type="file" name="file"> </span>
                    </div>
                    <div class="form-submit">
                        <input type="submit" name="update_file" value="Update">
                    </div>
                </form>
            </div>

        <?php } ?>

    <?php } ?>

</div>