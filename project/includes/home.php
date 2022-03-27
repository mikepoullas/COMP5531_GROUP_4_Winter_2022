<?php

unset($_REQUEST);

$username = $_SESSION['username'];
$role_name = $_SESSION['role_name'];
$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

if (!isAdmin()) {

    $query = "SELECT * FROM users as u
    LEFT JOIN user_of_course as uc ON u.user_id = uc. user_id
    LEFT JOIN course as c ON uc.course_id = c.course_id
    LEFT JOIN section as s ON c.course_id = s.course_id
    WHERE u.user_id = '$user_id'
    ORDER BY u.user_id ASC";
    $user_info = mysqli_query($conn, $query);

    $query = "SELECT * FROM users as u
    LEFT JOIN student as st ON st.user_id = u.user_id
    LEFT JOIN student_of_group as sg ON sg.student_id = st.student_id
    LEFT JOIN student_group as g ON g.group_id = sg.group_id
    LEFT JOIN group_of_course as gc ON gc.group_id = g.group_id
    LEFT JOIN course as c ON c.course_id = gc.course_id
    WHERE u.user_id = '$user_id'
    ORDER BY u.user_id ASC";
    $student_info = mysqli_query($conn, $query);

    $query = "SELECT * FROM announcement as a
    LEFT JOIN users as u ON a.posted_by_uid = u.user_id
    LEFT JOIN course as c ON c.course_id = a.course_id
    ORDER BY a.announcement_id DESC";
    $announcements = mysqli_query($conn, $query);
}

?>

<div class="content-body">
    <p><b>Home Page</b></p>
    <hr>

    <?php if (isAdmin()) { ?>
        <div class="admin-content">
            <p>Database Entry</p>
            <?php
            echo "<ul>";
            echo '<li>Roles:<b> ' . mysqli_num_rows(get_table_array('roles')) . '</b> </li>';
            echo '<li>Users:  <b> ' . mysqli_num_rows(get_table_array('users')) . '</b> </li>';
            echo '<li>Courses: <b> ' . mysqli_num_rows(get_table_array('course')) . '</b> </li>';
            echo '<li>Sections: <b> ' . mysqli_num_rows(get_table_array('section')) . '</b> </li>';
            echo '<li>Groups: <b> ' . mysqli_num_rows(get_table_array('student_group')) . '</b> </li>';
            echo '<li>Announcements: <b> ' . mysqli_num_rows(get_table_array('announcement')) . '</b> </li>';
            echo '<li>Discussions:  <b> ' . mysqli_num_rows(get_table_array('discussion')) . '</b> </li>';
            echo '<li>Comments: <b> ' . mysqli_num_rows(get_table_array('comment')) . '</b> </li>';
            echo '<li>Files: <b> ' . mysqli_num_rows(get_table_array('files')) . '</b> </li>';
            echo '<li>Assignments: <b> ' . mysqli_num_rows(get_table_array('course_assignment')) . '</b> </li>';
            echo '<li>Projects: <b> ' . mysqli_num_rows(get_table_array('course_project')) . '</b> </li>';
            echo '<br>';
            echo '<li>Professors: <b> ' . mysqli_num_rows(get_table_array('professor')) . '</b> </li>';
            echo '<li>TAs: <b> ' . mysqli_num_rows(get_table_array('ta')) . '</b> </li>';
            echo '<li>Students: <b> ' . mysqli_num_rows(get_table_array('student')) . '</b> </li>';
            echo "</ul><br>";
            ?>
            <p>Key ID Legends</p>
            <hr>
            <p>10000 - user<br>
                20000 - student<br>
                30000 - ta<br>
                40000 - professor<br>
                50000 - course<br>
                60000 - section<br>
                70000 - groups<br>
                80000 - assignment<br>
                90000 - project<br>
                <br>
                1100000 - announcement<br>
                2200000 - discussion<br>
                3300000 - comment<br>
                4400000 - files
            </p>
        </div>
    <?php } ?>

    <?php if (!isAdmin()) { ?>
        <div class="user-info-content">
            <p>Course Info</p>
            <?php
            echo "<ul>";
            foreach ($user_info as $user) {
                echo "<li>Course: " . $user['course_name'] . " - " . $user['course_number'] .  " | Section: " . $user['section_name'] . "</li>";
            }
            echo "</ul><br>";
            ?>
            <hr>
        </div>
    <?php } ?>

    <?php if (isStudent()) { ?>
        <div class="user-info-content">
            <p>Group Info</p>
            <?php
            echo "<ul>";
            foreach ($student_info as $row) {
                echo "<li>Group: " . $row['group_name'] . " | Section: " . $row['section_name'] . " | Course: " . $row['course_name'] . "</li>";
                if ($row['group_leader_sid'] == $row['student_id']) {
                    echo "<li>Group leader of <b>" . $row['group_name'] . "</b></li>";
                }
            }
            echo "</ul><br>";
            ?>
            <hr>
        </div>
    <?php } ?>

    <?php if (!isAdmin()) { ?>
        <div class="announcement-content">
            <p>Announcements</p>
            <?php
            foreach ($announcements as $row) {
                echo "<ul>";
                echo '<li> <b> Title: ' . $row['title'] . '</b> </li>';
                echo '<li> <b> Content: ' . $row['content'] . ' </b> </li>';
                echo '<li> Posted by: ' . $row['username'] . '</li>';
                echo '<li> Posted on: ' . $row['posted_on'] . '</li>';
                echo '<li> Course: ' . $row['course_name'] . '</li>';
                echo "</ul><br>";
            }
            ?>
            <hr>
        </div>
    <?php } ?>

</div>