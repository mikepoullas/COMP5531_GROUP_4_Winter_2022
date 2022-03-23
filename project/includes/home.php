<?php

$query = "SELECT title, username, posted_on, content
FROM announcement as a
LEFT JOIN users as u
ON a.posted_by_uid = u.user_id";
$announcements = mysqli_query($conn, $query);

$user_info = $_SESSION['user_info'];

pre_print($user_info);

?>

<div class="content-body">
    <p><b>Home Page</b></p>
    <hr>
    <div class="user-info-content">
        <p>User Info</p>
        <?php
        while ($row = mysqli_fetch_assoc($user_info)) {
            echo "<ul>";
            echo "<li>Course number: " . $row['course_number'] . "</li>";
            echo "<li>Course name: " . $row['course_name'] . "</li>";
            echo "<li>Section name: " . $row['section_name'] . "</li>";
            if ($_SESSION['role_name'] == 'Student') {
                echo "<li>Group name: " . $row['group_name'] . "</li>";
                if ($row['group_leader_sid'] == $row['student_id']) {
                    echo "<li>Group leader of " . $row['group_name'] . "</li>";
                }
            }
            echo "</ul>";
        }
        ?>
    </div>
    <br>
    <hr>
    <div class="announcement-content">
        <p>Announcements</p>
        <?php
        while ($row = mysqli_fetch_assoc($announcements)) {
            echo "<ul>";
            echo '<li> Title: ' . $row['title'] . '</li>';
            echo '<li> Posted by: ' . $row['username'] . '</li>';
            echo '<li> Posted on: ' . $row['posted_on'] . '</li>';
            echo '<li> Posted by: ' . $row['content'] . '</li>';
            echo "</ul>";
        }
        ?>
    </div>
    <br>
    <hr>
</div>