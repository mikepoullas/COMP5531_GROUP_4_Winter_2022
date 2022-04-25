<?php

unset($_REQUEST);

$username = $_SESSION['username'];
$role_name = $_SESSION['role_name'];
$session_user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

if (!isAdmin()) {

    $query = "SELECT * FROM users as u
    JOIN user_course_section as ucs ON ucs.user_id = u.user_id
    JOIN course as c ON c.course_id = ucs.course_id
    LEFT JOIN section as s ON s.section_id = ucs.section_id
    WHERE u.user_id = '$session_user_id'
    ORDER BY u.user_id ASC";
    $course_info = mysqli_query($conn, $query);

    $query = "SELECT g.*, st.*, u.*, s.section_name, c.course_name FROM student_groups as g
    JOIN member_of_group as mg ON mg.group_id = g.group_id
    JOIN student as st ON st.student_id = mg.student_id
    JOIN users as u ON u.user_id = st.user_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN section as s ON s.course_id = c.course_id
    JOIN user_course_section as ucs ON ucs.section_id = s.section_id AND ucs.user_id = u.user_id
    WHERE u.user_id = '$session_user_id'
    ORDER BY g.group_id ASC";
    $group_info = mysqli_query($conn, $query);

    $query = "SELECT a.*, u.*, c.course_name, s.section_name FROM announcement as a
    JOIN course as c ON c.course_id = a.course_id
    JOIN users as u ON u.user_id = a.posted_by_uid
    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
    LEFT JOIN section as s ON s.section_id = ucs.section_id
    JOIN users as us ON us.user_id = ucs.user_id
    WHERE us.user_id = '$session_user_id'
    ORDER BY a.announcement_id ASC";
    $announcements = mysqli_query($conn, $query);
}

?>

<div class="content-body">
    <h2>Home Page</h2>
    <hr>
    <?php if (isAdmin()) { ?>
        <h3>Database Entry</h3><br>
        <div class="admin-content">
            <ul>
                <li><a href="?page=roles">Roles<br><br><b><?= mysqli_num_rows(get_table_array('roles')) ?></b></a></li>
                <li><a href="?page=users">Users<br><br><b><?= mysqli_num_rows(get_table_array('users')) ?></b></a></li>
                <li><a href="?page=courses">Courses<br><br><b><?= mysqli_num_rows(get_table_array('course')) ?></b></a></li>
                <li><a href="?page=sections">Sections<br><br><b><?= mysqli_num_rows(get_table_array('section')) ?></b></a></li>
                <li><a href="?page=groups">Groups<br><br><b><?= mysqli_num_rows(get_table_array('student_groups')) ?></b></a></li>
                <li><a href="?page=announcements">Announcements<br><br><b><?= mysqli_num_rows(get_table_array('announcement')) ?></b></a></li>
                <li><a href="?page=forums">Forums<br><br><b><?= mysqli_num_rows(get_table_array('forum')) ?></b></a></li>
                <li><a href="?page=reply">Reply<br><br><b><?= mysqli_num_rows(get_table_array('reply')) ?></b></a></li>
                <li><a href="?page=discussions">Discussions<br><br><b><?= mysqli_num_rows(get_table_array('discussion')) ?></b></a></li>
                <li><a href="?page=comments">Comments<br><br><b><?= mysqli_num_rows(get_table_array('comment')) ?></b></a></li>
                <li><a href="?page=messages">Messages<br><br><b><?= mysqli_num_rows(get_table_array('messages')) ?></b></a></li>
                <li><a href="?page=task">Tasks<br><br><b><?= mysqli_num_rows(get_table_array('task')) ?></b></a></li>
                <li><a href="?page=solution">Solutions<br><br><b><?= mysqli_num_rows(get_table_array('solution')) ?></b></a></li>
                <li><a href="?page=grades">Grades<br><br><b><?= mysqli_num_rows(get_table_array('grades')) ?></b></a></li>
                <li><a href="?page=files">Files<br><br><b><?= mysqli_num_rows(get_table_array('files')) ?></b></a></li>
                <li><a href="?page=assign-students">Students<br><br><b><?= mysqli_num_rows(get_table_array('student')) ?></b></a></li>
                <li><a href="?page=assign-tas">Teaching Assistants<br><br><b><?= mysqli_num_rows(get_table_array('ta')) ?></b></a></li>
                <li><a href="?page=assign-professors">Professors<br><br><b><?= mysqli_num_rows(get_table_array('professor')) ?></b></a></li>
                <li><a href="?page=assign-group">Group Members<br><br><b><?= mysqli_num_rows(get_table_array('member_of_group')) ?></b></a></li>
            </ul>
        </div>
    <?php } ?>

    <?php if (!isAdmin()) { ?>
        <div class="course-info-content">
            <h3>Course Info</h3>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Course Number</th>
                        <?php if (!isProfessor()) { ?>
                            <th>Section</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($course_info as $row) {
                        $course_name = $row['course_name'];
                        $course_number = $row['course_number'];
                        if ($row['section_name'] == null) {
                            $section_name = "All";
                        } else {
                            $section_name = $row['section_name'];
                        }
                    ?>
                        <tr>
                            <td><?= $course_name ?></td>
                            <td><?= $course_number ?></td>
                            <?php if (!isProfessor()) { ?>
                                <td><?= $section_name ?></td>
                            <?php } ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <hr>
        </div>
    <?php } ?>

    <?php if (isStudent()) { ?>
        <div class="group-info-content">
            <h3>Group Info</h3>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Course Number</th>
                        <th>Section Name</th>
                        <th>Group Leader</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($group_info as $row) {
                        $group_name = $row['group_name'];
                        $course_name = $row['course_name'];
                        $section_name = $row['section_name'];
                    ?>
                        <tr>
                            <td><?= $group_name ?></td>
                            <td><?= $course_name ?></td>
                            <td><?= $section_name ?></td>
                            <?php
                            if ($row['group_leader_sid'] == $row['student_id']) {
                                echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                            } else {
                                $query = "SELECT * FROM users as u
                                JOIN student as st ON u.user_id = st.user_id
                                WHERE st.student_id = " . $row['group_leader_sid'];
                                $row = mysqli_fetch_assoc(mysqli_query($conn, $query));
                                $group_leader_name = $row['first_name'] . " " . $row['last_name'];
                                echo "<td>" . $group_leader_name . "</td>";
                            }
                            ?>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <hr>
        </div>
    <?php } ?>

    <?php if (!isAdmin()) { ?>
        <div class="announcement-content">
            <h3>Announcements</h3><br>
            <?php
            if (mysqli_num_rows($announcements) > 0) {
                foreach ($announcements as $row) {
                    $announcement_title = $row['announcement_title'];
                    $announcement_content = $row['announcement_content'];
                    $posted_on = $row['posted_on'];
                    $posted_by = $row['first_name'] . " " . $row['last_name'];
                    $course_name = $row['course_name'];
                    $section_name = $row['section_name'];

            ?>
                    <ul>
                        <li> <b><?= $announcement_title ?></b> </li>
                        <li> <?= $announcement_content ?></li>
                        <li>&emsp;<?= $posted_on ?></li>
                        <li>&emsp;by <b><?= $posted_by ?></b> | <?= $course_name ?>
                            <?php if (!isProfessor()) echo " | " . $section_name; ?>
                        </li>
                    </ul><br>
                <?php } ?>

            <?php } else { ?>
                <p>No Announcements</p>
            <?php } ?>

        </div>
    <?php } ?>

</div>