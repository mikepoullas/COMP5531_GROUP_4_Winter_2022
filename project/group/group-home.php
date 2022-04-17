<?php

$session_user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

$query = "SELECT g.*, c.*, u.*, s.section_name FROM student_groups as g
JOIN group_of_course as gc ON gc.group_id = g.group_id
JOIN course as c ON c.course_id = gc.course_id
JOIN user_course_section as ucs ON ucs.course_id = c.course_id
LEFT JOIN section as s ON s.section_id = ucs.section_id
JOIN users as u ON u.user_id = ucs. user_id 
WHERE u.user_id = '$session_user_id'
ORDER BY g.group_id ASC";
$group = mysqli_query($conn, $query);

?>

<div class="content-body">

    <?php
    display_success();
    display_error();
    ?>

    <div class="group-content">

        <h2>Groups</h2>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Group Name</th>
                    <th>Group Leader SID</th>
                    <th>Group Leader</th>
                    <?php if (!isProfessor()) { ?>
                        <th>Section</th>
                    <?php } ?>
                    <th>Course</th>
                    <?php if (isStudent()) { ?>
                        <th colspan="2">Discussion</th>
                        <th colspan="2">Solution</th>
                    <?php } else { ?>
                        <th>Discussion</th>
                        <th>Solution</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($group as $row) {
                    $group_id = $row['group_id'];
                    $group_name = $row['group_name'];
                    $group_leader_sid = $row['group_leader_sid'];
                    $section_name = $row['section_name'];
                    $course_id = $row['course_id'];
                    $course_name = $row['course_name'];

                    $query = "SELECT * FROM users as u
                    JOIN student as st ON u.user_id = st.user_id
                    WHERE st.student_id = '$group_leader_sid'";
                    $row = mysqli_fetch_assoc(mysqli_query($conn, $query));
                    $group_leader_name = $row['first_name'] . " " . $row['last_name'];
                ?>
                    <tr>
                        <td><?= $group_name ?></td>
                        <td><?= $group_leader_sid ?></td>
                        <td><?= $group_leader_name ?></td>
                        <?php if (!isProfessor()) { ?>
                            <td><?= $section_name ?></td>
                        <?php } ?>
                        <td><?= $course_name ?></td>
                        <?php if (isStudent()) { ?>
                            <td><a href="?page=group-home&discussion_view=true&group_id=<?= $group_id ?>">View</a></td>
                            <td><a href="?page=group-discussion&group_id=<?= $group_id ?>">Manage</a></td>
                            <td><a href="?page=group-solution&course_id=<?= $course_id ?>&group_id=<?= $group_id ?>">Manage</a></td>
                        <?php } else { ?>
                            <td><a href="?page=group-home&discussion_view=true&group_id=<?= $group_id ?>">View</a></td>
                            <td><a href="?page=group-solution&course_id=<?= $course_id ?>&group_id=<?= $group_id ?>">Manage</a></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($_GET['discussion_view'])) { ?>

        <hr>

        <?php

        $group_id = $_GET['group_id'];

        $query = "SELECT * FROM discussion as d
        JOIN student_groups as g ON g.group_id = d.group_id
        JOIN group_of_course as gc ON gc.group_id = g.group_id
        JOIN course as c ON c.course_id = gc.course_id
        JOIN users as u ON u.user_id = d.posted_by_uid
        WHERE g.group_id = '$group_id'
        ORDER BY d.discussion_id ASC";
        $discussion = mysqli_query($conn, $query);

        $group_name = mysqli_fetch_assoc($discussion)['group_name'];

        ?>
        <div class="discussion-content">
            <h3><?= $group_name ?> Discussions</h3>
            <br>
            <?php
            foreach ($discussion as $row) {
                $discussion_id = $row['discussion_id'];
                $title = $row['discussion_title'];
                $content = $row['discussion_content'];
                $posted_by = $row['first_name'] . " " . $row['last_name'];
                $posted_on = date_convert($row['posted_on']);
                $group_name = $row['group_name'];
                $course_name = $row['course_name'];
            ?>
                <ul>
                    <li>
                        <b><a href='?page=group-comment&discussion_id=<?= $discussion_id ?>'><?= $title ?></a></b>
                    </li>
                    <li><?= $content ?></li>
                    <li>&emsp;<?= $posted_on ?></li>
                    <li>&emsp;by <b><?= $posted_by ?></b> | <?= $group_name ?> | <?= $course_name ?></li>
                </ul><br>
            <?php } ?>
        </div>

    <?php } else { ?>

        <hr>

        <?php
        $query = "SELECT d.*, u.*, c.course_name, g.group_name FROM discussion as d
        JOIN student_groups as g ON g.group_id = d.group_id
        JOIN users as u ON u.user_id = d.posted_by_uid
        JOIN group_of_course as gc ON gc.group_id = g.group_id
        JOIN course as c ON c.course_id = gc.course_id
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
        JOIN users as us ON us.user_id = ucs.user_id
        WHERE us.user_id = '$session_user_id'
        ORDER BY d.discussion_id ASC LIMIT 10";
        $discussion_all = mysqli_query($conn, $query);

        $group_name = mysqli_fetch_assoc($discussion_all)['group_name'];
        ?>

        <div class="discussion-content">
            <h3>Top 10 Recent Discussions</h3>
            <br>
            <?php
            foreach ($discussion_all as $row) {
                $discussion_id = $row['discussion_id'];
                $title = $row['discussion_title'];
                $content = $row['discussion_content'];
                $posted_by = $row['first_name'] . " " . $row['last_name'];
                $posted_on = date_convert($row['posted_on']);
                $group_name = $row['group_name'];
                $course_name = $row['course_name'];
            ?>
                <ul>
                    <li>
                        <b><a href='?page=group-comment&discussion_id=<?= $discussion_id ?>'><?= $title ?></a></b>
                    </li>
                    <li><?= $content ?></li>
                    <li>&emsp;<?= $posted_on ?></li>
                    <li>&emsp;by <b><?= $posted_by ?></b> | <?= $group_name ?> | <?= $course_name ?></li>
                </ul><br>
            <?php } ?>
        </div>

    <?php } ?>

</div>