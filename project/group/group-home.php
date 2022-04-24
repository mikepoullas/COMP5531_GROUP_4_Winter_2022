<?php

$session_user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

//DOWNLOAD
if (isset($_GET['download_file'])) {
    download_file($_GET['download_file']);
}

if (isStudent()) {
    $query = "SELECT g.*, st.*, u.*, s.section_name, c.* FROM student_groups as g
    JOIN member_of_group as mg ON mg.group_id = g.group_id
    JOIN student as st ON st.student_id = mg.student_id
    JOIN users as u ON u.user_id = st.user_id
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN section as s ON s.course_id = c.course_id
    JOIN user_course_section as ucs ON ucs.section_id = s.section_id AND ucs.user_id = u.user_id
    WHERE u.user_id = '$session_user_id'
    ORDER BY g.group_id ASC";
} else {
    $query = "SELECT g.*, u.*, s.section_name, c.* FROM student_groups as g
    JOIN group_of_course as gc ON gc.group_id = g.group_id
    JOIN course as c ON c.course_id = gc.course_id
    JOIN user_course_section as ucs ON ucs.course_id = c.course_id
    LEFT JOIN section as s ON s.section_id = ucs.section_id
    JOIN users as u ON u.user_id = ucs.user_id
    WHERE u.user_id = '$session_user_id'
    ORDER BY g.group_id ASC";
}

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
                        <th>Solution</th>
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
                    if (!isProfessor()) {
                        if ($row['section_name'] == null) {
                            $section_name = "All";
                        } else {
                            $section_name = $row['section_name'];
                        }
                    }
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
                        <td><u><?= $group_leader_name ?></u></td>
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

        if (mysqli_num_rows($discussion) > 0) {
            $group_name = mysqli_fetch_assoc($discussion)['group_name'];
        } else {
            $group_name = "No";
        }

        ?>
        <div class="list-content">
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
        $query = "SELECT d.*, u.*, c.course_name, g.group_name, fl.* FROM discussion as d
        JOIN student_groups as g ON g.group_id = d.group_id
        JOIN users as u ON u.user_id = d.posted_by_uid
        JOIN group_of_course as gc ON gc.group_id = g.group_id
        JOIN course as c ON c.course_id = gc.course_id
        JOIN user_course_section as ucs ON ucs.course_id = c.course_id
        LEFT JOIN files as fl ON fl.file_id = d.file_id
        JOIN users as us ON us.user_id = ucs.user_id
        WHERE us.user_id = '$session_user_id'
        ORDER BY d.discussion_id ASC LIMIT 10";
        $discussion_all = mysqli_query($conn, $query);

        if (mysqli_num_rows($discussion_all) > 0) {
            $group_name = mysqli_fetch_assoc($discussion_all)['group_name'];
        } else {
            $group_name = "No";
        }

        ?>

        <h3>Top 10 Recent Discussions</h3>
        <br>
        <div class="list-content">
            <?php

            if (mysqli_num_rows($discussion_all) > 0) {
                foreach ($discussion_all as $row) {
                    $discussion_id = $row['discussion_id'];
                    $title = $row['discussion_title'];
                    $content = $row['discussion_content'];
                    $posted_by = $row['first_name'] . " " . $row['last_name'];
                    $posted_on = date_convert($row['posted_on']);
                    $group_name = $row['group_name'];
                    $course_name = $row['course_name'];
                    $file_id = $row['file_id'];
                    $file_name = $row['file_name'];
            ?>
                    <ul>
                        <li>
                            <b><a href='?page=group-comment&discussion_id=<?= $discussion_id ?>'><?= $title ?></a></b>
                        </li>
                        <li><?= $content ?></li>
                        <?php if ($file_id != '') { ?>
                            <li>
                                <a href="?page=group-home&download_file=<?= $file_id ?>">[ <b><?= $file_name ?></b> ]</a>
                            </li>
                        <?php } ?>
                        <li>&emsp;by <b><?= $posted_by ?></b> | <?= $group_name ?> | <?= $course_name ?></li>
                        <li>&emsp;<?= $posted_on ?></li>
                    </ul><br>
                <?php } ?>

            <?php } else { ?>
                <p>No Discussions</p>
            <?php } ?>
        </div>
    <?php } ?>

</div>