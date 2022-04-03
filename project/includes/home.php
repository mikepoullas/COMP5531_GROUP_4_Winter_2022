<?php

unset($_REQUEST);

$username = $_SESSION['username'];
$role_name = $_SESSION['role_name'];
$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

if (!isAdmin()) {

    $query = "SELECT * FROM users as u
    JOIN user_course_section as ucs ON ucs.user_id = u.user_id
    JOIN course as c ON c.course_id = ucs.course_id
    LEFT JOIN section as s ON s.section_id = ucs.section_id
    WHERE u.user_id = '$user_id'
    ORDER BY u.user_id ASC";
    $course_info = mysqli_query($conn, $query);

    $query = "SELECT g.*, st.*, u.*, s.section_name, c.course_name FROM student_group as g
    JOIN member_of_group as mg ON mg.group_id = g.group_id
    JOIN student as st ON st.student_id = mg.student_id
    JOIN users as u ON u.user_id = st.user_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN section as s ON s.course_id = c.course_id
    JOIN user_course_section as ucs ON ucs.section_id = s.section_id AND  ucs.user_id = u.user_id
    WHERE u.user_id = $user_id
    ORDER BY g.group_id ASC";
    $group_info = mysqli_query($conn, $query);

    $query = "SELECT * FROM announcement as a
    JOIN users as u ON a.posted_by_uid = u.user_id
    JOIN course as c ON c.course_id = a.course_id
    ORDER BY a.announcement_id DESC";
    $announcements = mysqli_query($conn, $query);
}

?>

<div class="content-body">
    <h2>Home Page</h2>
    <hr>
    <?php if (isAdmin()) { ?>
        <div class="admin-content">
            <h3>Database Entry</h3>
            <br>
            <?php
            echo "<ul>";
            echo '<li>Roles:<b> ' . mysqli_num_rows(get_table_array('roles')) . '</b> </li>';
            echo '<li>Users:  <b> ' . mysqli_num_rows(get_table_array('users')) . '</b> </li>';
            echo '<li>Professors: <b> ' . mysqli_num_rows(get_table_array('professor')) . '</b> </li>';
            echo '<li>TAs: <b> ' . mysqli_num_rows(get_table_array('ta')) . '</b> </li>';
            echo '<li>Students: <b> ' . mysqli_num_rows(get_table_array('student')) . '</b> </li>';
            echo '<li>Courses: <b> ' . mysqli_num_rows(get_table_array('course')) . '</b> </li>';
            echo '<li>Sections: <b> ' . mysqli_num_rows(get_table_array('section')) . '</b> </li>';
            echo '<li>Groups: <b> ' . mysqli_num_rows(get_table_array('student_group')) . '</b> </li>';
            echo '<li>Submission: <b> ' . mysqli_num_rows(get_table_array('submission')) . '</b> </li>';
            echo '<li>Grades: <b> ' . mysqli_num_rows(get_table_array('grades')) . '</b> </li>';
            echo '<br>';
            echo '<li>Announcements: <b> ' . mysqli_num_rows(get_table_array('announcement')) . '</b> </li>';
            echo '<li>Forums: <b> ' . mysqli_num_rows(get_table_array('forum')) . '</b> </li>';
            echo '<li>Reply: <b> ' . mysqli_num_rows(get_table_array('reply')) . '</b> </li>';
            echo '<li>Discussions:  <b> ' . mysqli_num_rows(get_table_array('discussion')) . '</b> </li>';
            echo '<li>Comments: <b> ' . mysqli_num_rows(get_table_array('comment')) . '</b> </li>';
            echo '<li>Files: <b> ' . mysqli_num_rows(get_table_array('files')) . '</b> </li>';
            echo "</ul>";
            ?>
            <hr>
            <h3>Key ID Legends</h3>
            <br>
            <p>
                10000 - user<br>
                20000 - student<br>
                30000 - ta<br>
                40000 - professor<br>
                50000 - course<br>
                60000 - section<br>
                70000 - groups<br>
                80000 - submission<br>
                90000 - grades<br>
                <br>
                1100000 - announcement<br>
                2200000 - forum<br>
                3300000 - reply<br>
                4400000 - discussion<br>
                5500000 - comment<br>
                6600000 - files<br>
            </p>
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
                        $section_name = $row['section_name'];
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
                                $query = "SELECT * FROM users as u JOIN student as st ON u.user_id = st.user_id WHERE st.student_id = " . $row['group_leader_sid'];
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
            <?php foreach ($announcements as $row) { ?>
                <ul>
                    <li> <b><?= $row['announcement_title'] ?></b> </li>
                    <li> <?= $row['announcement_content'] ?></li>
                    <li>&emsp;by <b><?= $row['first_name'] . " " . $row['last_name'] ?></b></li>
                    <li>&emsp;<?= $row['posted_on'] ?></li>
                    <li>&emsp;<?= $row['course_name'] ?></li>
                </ul><br>
            <?php } ?>
        </div>
    <?php } ?>

</div>