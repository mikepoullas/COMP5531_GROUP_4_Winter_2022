<?php

$session_user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

//DOWNLOAD
if (isset($_GET['download_file'])) {
    download_file($_GET['download_file']);
}

if (!isProfessor()) {
    $query = "SELECT c.*, u.*, s.section_name FROM course as c
    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
    LEFT JOIN section as s ON s.section_id = ucs.section_id
    JOIN users as u ON u.user_id = ucs.user_id
    WHERE u.user_id = $session_user_id
    ORDER BY u.user_id ASC";
} else {
    $query = "SELECT * FROM course as c
    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
    JOIN users as u ON u.user_id = ucs.user_id
    WHERE u.user_id = $session_user_id
    ORDER BY u.user_id ASC";
}
$course_info = mysqli_query($conn, $query);

?>

<div class="content-body">

    <?php
    display_success();
    display_error();
    ?>

    <div class="course-content">

        <h2>Courses</h2>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Course Number</th>
                    <?php if (!isProfessor()) { ?>
                        <th>Section</th>
                    <?php } ?>
                    <th colspan="2">Forum</th>
                    <th>Task</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($course_info as $row) {
                    $course_id = $row['course_id'];
                    $course_name = $row['course_name'];
                    $course_number = $row['course_number'];
                    if (!isProfessor()) {
                        if ($row['section_name'] == null) {
                            $section_name = "All";
                        } else {
                            $section_name = $row['section_name'];
                        }
                    }
                ?>
                    <tr>
                        <td><?= $course_name ?></td>
                        <td><?= $course_number ?></td>
                        <?php if (!isProfessor()) { ?>
                            <td><?= $section_name ?></td>
                        <?php } ?>
                        <td><a href="?page=course-home&forum_view=true&course_id=<?= $course_id ?>">View</a></td>
                        <td><a href="?page=course-forum&course_id=<?= $course_id ?>">Manage</a></td>
                        <td><a href="?page=course-task&course_id=<?= $course_id ?>">Manage</a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($_GET['forum_view'])) { ?>

        <hr>
        <?php
        $course_id = $_GET['course_id'];

        $query = "SELECT * FROM forum as f
        JOIN course as c ON c.course_id = f.course_id
        JOIN users as u ON u.user_id = f.posted_by_uid
        WHERE c.course_id = '$course_id'
        ORDER BY f.forum_id ASC";

        $forum = mysqli_query($conn, $query);

        if (mysqli_num_rows($forum) > 0) {
            $course_name = mysqli_fetch_assoc($forum)['course_name'];
        } else {
            $course_name = "No";
        }

        ?>
        <div class="forum-content">
            <h3><?= $course_name ?> Forum</h3>
            <br>
            <?php
            foreach ($forum as $row) {
                $forum_id = $row['forum_id'];
                $forum_title = $row['forum_title'];
                $forum_content = $row['forum_content'];
                $posted_by = $row['first_name'] . " " . $row['last_name'];
                $posted_on = date_convert($row['posted_on']);
                $course_name = $row['course_name'];
            ?>
                <ul>
                    <li>
                        <b><a href='?page=course-reply&forum_id=<?= $forum_id ?>'><?= $forum_title ?></a></b>
                    </li>
                    <li><?= $forum_content ?></li>
                    <li>&emsp;<?= $posted_on ?></li>
                    <li>&emsp;by <b><?= $posted_by ?></b> | <?= $course_name ?></li>
                </ul><br>
            <?php } ?>
        </div>

    <?php } else { ?>

        <hr>

        <?php
        $query = "SELECT f.*, c.*, s.*, u.*, fl.* FROM forum as f
        JOIN course as c ON c.course_id = f.course_id
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
        LEFT JOIN section as s ON s.section_id = ucs.section_id
        LEFT JOIN files as fl ON fl.file_id = f.file_id
        JOIN users as u ON u.user_id = f.posted_by_uid
        JOIN users as us ON us.user_id = ucs.user_id
        WHERE us.user_id = '$session_user_id'
        ORDER BY f.forum_id ASC LIMIT 10";
        $forum_all = mysqli_query($conn, $query);

        ?>

        <h3>Top 10 Recent Forums</h3>
        <div class="list-content">
            <br>
            <?php
            if (mysqli_num_rows($forum_all) > 0) {
                foreach ($forum_all as $row) {
                    $forum_id = $row['forum_id'];
                    $forum_title = $row['forum_title'];
                    $forum_content = $row['forum_content'];
                    $posted_by = $row['first_name'] . " " . $row['last_name'];
                    $posted_on = date_convert($row['posted_on']);
                    $course_name = $row['course_name'];
                    $section_name = $row['section_name'];
                    $file_id = $row['file_id'];
                    $file_name = $row['file_name'];
            ?>
                    <ul>
                        <li>
                            <b><a href='?page=course-reply&forum_id=<?= $forum_id ?>'><?= $forum_title ?></a></b>
                        </li>
                        <li><?= $forum_content ?></li>
                        <?php if ($file_id != '') { ?>
                            <li>
                                <a href="?page=course-home&download_file=<?= $file_id ?>">[ <b><?= $file_name ?></b> ]</a>
                            </li>
                        <?php } ?>
                        <li>&emsp;<?= $posted_on ?></li>
                        <li>&emsp;by <b><?= $posted_by ?></b> | <?= $course_name ?>
                            <?php if (isStudent()) echo " | " . $section_name; ?>
                        </li>
                    </ul><br>
                <?php } ?>
            <?php } else { ?>
                <p>No forums</p>
            <?php } ?>
        <?php } ?>