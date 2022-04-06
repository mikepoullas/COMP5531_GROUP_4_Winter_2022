<?php

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

if (isProfessor()) {
    $query = "SELECT * FROM course as c
                JOIN user_course_section as ucs ON ucs.course_id = c.course_id
                JOIN users as u ON u.user_id = ucs.user_id
                WHERE u.user_id = $user_id
                ORDER BY u.user_id ASC";
} else {
    $query = "SELECT * FROM course as c
                JOIN section as s ON s.course_id = c.course_id
                JOIN user_course_section as ucs ON ucs.section_id = s.section_id
                JOIN users as u ON u.user_id = ucs.user_id
                WHERE u.user_id = $user_id
                ORDER BY u.user_id ASC;";
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
                    <th colspan="2">Task</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($course_info as $row) {
                    $course_id = $row['course_id'];
                    $course_name = $row['course_name'];
                    $course_number = $row['course_number'];
                    if (!isProfessor()) {
                        $section_name = $row['section_name'];
                    }
                ?>
                    <tr>
                        <td><?= $course_name ?></td>
                        <td><?= $course_number ?></td>
                        <?php if (!isProfessor()) { ?>
                            <td><?= $section_name ?></td>
                        <?php } ?>
                        <td><a href="?page=course-home&forum_view=true&course_id=<?= $course_id ?> ">View</a></td>
                        <td><a href="?page=course-forum&course_id=<?= $course_id ?> ">Manage</a></td>
                        <td><a href="?page=course-home&task_view=true&course_id=<?= $course_id ?> ">View</a></td>
                        <td><a href="?page=course-task&course_id=<?= $course_id ?> ">Manage</a></td>
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
        $course_name = mysqli_fetch_assoc($forum)['course_name'];
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

    <?php } elseif (isset($_GET['task_view'])) { ?>

        <hr>
        <?php
        $course_id = $_GET['course_id'];
        $query = "SELECT t.*, f.*, c.*, s.*, u.* FROM task as t
                    JOIN files as f ON f.file_id = t.file_id
                    JOIN course as c ON c.course_id = t.course_id
                    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
                    LEFT JOIN section as s ON s.section_id = ucs.section_id
                    JOIN users as u ON u.user_id = f.uploaded_by_uid
                    JOIN users as us ON us.user_id = ucs.user_id
                    WHERE c.course_id = '$course_id' AND us.user_id = '$user_id' AND t.task_type = 'Assignment'
                    ORDER BY t.task_id ASC";
        $assignment = mysqli_query($conn, $query);

        $query = "SELECT t.*, f.*, c.*, s.*, u.* FROM task as t
                    JOIN files as f ON f.file_id = t.file_id
                    JOIN course as c ON c.course_id = t.course_id
                    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
                    LEFT JOIN section as s ON s.section_id = ucs.section_id
                    JOIN users as u ON u.user_id = f.uploaded_by_uid
                    JOIN users as us ON us.user_id = ucs.user_id
                    WHERE c.course_id = '$course_id' AND us.user_id = '$user_id' AND t.task_type = 'Project'
                    ORDER BY t.task_id ASC";
        $project = mysqli_query($conn, $query);
        ?>

        <div class="task-content">
            <h3>Course Tasks</h3>
            <br>
            <h4><?= mysqli_fetch_assoc($assignment)['task_type'] ?></h4><br>
            <?php
            foreach ($assignment as $row) {
                $task_id = $row['task_id'];
                $task_type = $row['task_type'];
                $task_content = $row['task_content'];
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
                $task_deadline = date_convert($row['task_deadline']);
                $course_name = $row['course_name'];
                $section_name = $row['section_name'];
                $uploaded_by = $row['first_name'] . " " . $row['last_name'];
            ?>
                <ul>
                    <li><a href='?page=group-discussion&task_id=<?= $task_id ?>'><b><?= $task_content ?></b></a></li>
                    <li>
                        <a href='?page=course-home&download_file=<?= $file_id ?>'>[<?= $file_name ?>]</a>
                    </li>
                    <li>&emsp;Deadline: <b><?= $task_deadline ?></b></li>
                    <li>&emsp;by <b><?= $uploaded_by ?></b> | <?= $course_name ?> | <?= $section_name ?></li>
                </ul><br>
            <?php } ?>
            <h4><?= mysqli_fetch_assoc($project)['task_type'] ?></h4><br>
            <?php
            foreach ($project as $row) {
                $task_id = $row['task_id'];
                $task_type = $row['task_type'];
                $task_content = $row['task_content'];
                $file_name = $row['file_name'];
                $task_deadline = date_convert($row['task_deadline']);
                $course_name = $row['course_name'];
                $section_name = $row['section_name'];
                $uploaded_by = $row['first_name'] . " " . $row['last_name'];
            ?>
                <ul>
                    <li><a href='?page=group-discussion&task_id=<?= $task_id ?>'><b><?= $task_content ?></b></a></li>
                    <li>
                        <a href='?page=course-home&download_file=<?= $file_id ?>'>[<?= $file_name ?>]</a>
                    </li>
                    <li>&emsp;Deadline: <b><?= $task_deadline ?></b></li>
                    <li>&emsp;by <b><?= $uploaded_by ?></b> | <?= $course_name ?> | <?= $section_name ?></li>
                </ul><br>
            <?php } ?>
        </div>

    <?php } else { ?>

        <hr>

        <?php
        $query = "SELECT f.*, c.*, s.*, u.* FROM forum as f
                    JOIN course as c ON c.course_id = f.course_id
                    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
                    LEFT JOIN section as s ON s.section_id = ucs.section_id
                    JOIN users as u ON u.user_id = f.posted_by_uid
                    JOIN users as us ON us.user_id = ucs.user_id
                    WHERE us.user_id = '$user_id'
                    ORDER BY f.forum_id ASC LIMIT 10";
        $forum_all = mysqli_query($conn, $query);

        $query = "SELECT t.*, f.*, c.*, s.*, u.* FROM task as t
                    JOIN files as f ON f.file_id = t.file_id
                    JOIN course as c ON c.course_id = t.course_id
                    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
                    LEFT JOIN section as s ON s.section_id = ucs.section_id
                    JOIN users as u ON u.user_id = f.uploaded_by_uid
                    JOIN users as us ON us.user_id = ucs.user_id
                    WHERE us.user_id = '$user_id' AND t.task_type = 'Assignment'
                    ORDER BY t.task_id ASC";
        $assignment = mysqli_query($conn, $query);

        $query = "SELECT t.*, f.*, c.*, s.*, u.* FROM task as t
                    JOIN files as f ON f.file_id = t.file_id
                    JOIN course as c ON c.course_id = t.course_id
                    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
                    LEFT JOIN section as s ON s.section_id = ucs.section_id
                    JOIN users as u ON u.user_id = f.uploaded_by_uid
                    JOIN users as us ON us.user_id = ucs.user_id
                    WHERE us.user_id = '$user_id' AND t.task_type = 'Project'
                    ORDER BY t.task_id ASC";
        $project = mysqli_query($conn, $query);

        ?>

        <div class="task-content">
            <h3>Course Tasks</h3>
            <br>
            <h4><?= mysqli_fetch_assoc($assignment)['task_type'] ?></h4><br>
            <?php
            foreach ($assignment as $row) {
                $task_id = $row['task_id'];
                $task_content = $row['task_content'];
                $file_id = $row['file_id'];
                $file_name = $row['file_name'];
                $task_deadline = date_convert($row['task_deadline']);
                $course_name = $row['course_name'];
                $section_name = $row['section_name'];
                $uploaded_by = $row['first_name'] . " " . $row['last_name'];
            ?>
                <ul>
                    <li><a href='?page=group-discussion&task_id=<?= $task_id ?>'><b><?= $task_content ?></b></a></li>
                    <li>
                        <a href='?page=course-home&download_file=<?= $file_id ?>'>[<?= $file_name ?>]</a>
                    </li>
                    <li>&emsp;Deadline: <b><?= $task_deadline ?></b></li>
                    <li>&emsp;by <b><?= $uploaded_by ?></b> | <?= $course_name ?> | <?= $section_name ?></li>
                </ul><br>
            <?php } ?>
            <h4><?= mysqli_fetch_assoc($project)['task_type'] ?></h4><br>
            <?php
            foreach ($project as $row) {
                $task_id = $row['task_id'];
                $task_type = $row['task_type'];
                $task_content = $row['task_content'];
                $file_name = $row['file_name'];
                $task_deadline = date_convert($row['task_deadline']);
                $course_name = $row['course_name'];
                $section_name = $row['section_name'];
                $uploaded_by = $row['first_name'] . " " . $row['last_name'];
            ?> <ul>
                    <li><a href='?page=group-discussion&task_id=<?= $task_id ?>'><b><?= $task_content ?></b></a></li>
                    <li>
                        <a href='?page=course-home&download_file=<?= $file_id ?>'>[<?= $file_name ?>]</a>
                    </li>
                    <li>&emsp;Deadline: <b><?= $task_deadline ?></b></li>
                    <li>&emsp;by <b><?= $uploaded_by ?></b> | <?= $course_name ?> | <?= $section_name ?></li>
                </ul><br>
            <?php } ?>
        </div>

        <hr>

        <div class="forum-content">
            <h3>Top 10 Recent Forums</h3>
            <br>
            <?php
            foreach ($forum_all as $row) {
                $forum_id = $row['forum_id'];
                $forum_title = $row['forum_title'];
                $forum_content = $row['forum_content'];
                $posted_by = $row['first_name'] . " " . $row['last_name'];
                $posted_on = date_convert($row['posted_on']);
                $course_name = $row['course_name'];
                $section_name = $row['section_name'];
            ?>
                <ul>
                    <li>
                        <b><a href='?page=course-reply&forum_id=<?= $forum_id ?>'><?= $forum_title ?></a></b>
                    </li>
                    <li><?= $forum_content ?></li>
                    <li>&emsp;<?= $posted_on ?></li>
                    <li>&emsp;by <b><?= $posted_by ?></b> | <?= $course_name ?> | <?= $section_name ?></li>
                </ul><br>
            <?php } ?>
        </div>

    <?php } ?>

</div>