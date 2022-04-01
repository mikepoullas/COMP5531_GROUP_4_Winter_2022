<?php

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

if (isStudent()) {
    $query = "SELECT g.*, u.*, s.section_name, c.course_name FROM student_group as g
                JOIN member_of_group as mg ON mg.group_id = g.group_id
                JOIN student as st ON st.student_id = mg.student_id
                JOIN users as u ON u.user_id = st.user_id
                JOIN group_of_course as gc ON gc.group_id = g.group_id
                JOIN course as c ON c.course_id = gc.course_id
                JOIN section as s ON s.course_id = c.course_id
                JOIN user_course_section as ucs ON ucs.section_id = s.section_id AND  ucs.user_id = u.user_id
                WHERE u.user_id = $user_id
                ORDER BY g.group_id ASC";
} else {
    $query = "SELECT g.*, u.*, s.section_name, c.course_name FROM student_group as g
                JOIN member_of_group as mg ON mg.group_id = g.group_id
                JOIN student as st ON st.student_id = mg.student_id
                JOIN users as u ON u.user_id = st.user_id
                JOIN group_of_course as gc ON gc.group_id = g.group_id
                JOIN course as c ON c.course_id = gc.course_id
                JOIN section as s ON s.course_id = c.course_id
                JOIN user_course_section as ucs ON ucs.section_id = s.section_id AND  ucs.user_id = u.user_id
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
                    <th>Section</th>
                    <th>Course</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($group as $row) {
                    $group_id = $row['group_id'];
                    $group_name = $row['group_name'];
                    $group_leader_sid = $row['group_leader_sid'];
                    $group_leader_name = $row['first_name'] . " " . $row['last_name'];
                    $section_name = $row['section_name'];
                    $course_name = $row['course_name'];
                ?>
                    <tr>
                        <td><?php echo $group_name ?></td>
                        <td><?php echo $group_leader_sid ?></td>
                        <td><?php echo $group_leader_name ?></td>
                        <td><?php echo $section_name ?></td>
                        <td><?php echo $course_name ?></td>
                        <td><a href="?page=group-home&discussion_view=true&group_id=<?= $group_id ?> ">View</a></td>
                        <td><a href="?page=group-discussion&group_id=<?= $group_id ?> ">Manage</a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($_GET['discussion_view'])) { ?>

        <hr>

        <?php

        $group_id = $_GET['group_id'];

        $query = "SELECT * FROM discussion as d
                    JOIN student_group as g ON g.group_id = d.group_id
                    JOIN group_of_course as gc ON gc.group_id = g.group_id
                    JOIN course as c ON c.course_id = gc.course_id
                    JOIN users as u ON u.user_id = d.posted_by_uid
                    WHERE g.group_id = '$group_id'
                    ORDER BY d.discussion_id DESC";
        $discussion = mysqli_query($conn, $query);

        $group_name = mysqli_fetch_assoc($discussion)['group_name'];

        ?>
        <div class="discussion-content">
            <h3><?= $group_name ?> Discussions</h3>
            <br>
            <?php foreach ($discussion as $row) { ?>
                <ul>
                    <li>
                        <b><a href='?page=group-comment&discussion_id=<?= $row['discussion_id'] ?>'><?= $row['title'] ?></a></b>
                    </li>
                    <li><?= $row['content'] ?></li>
                    <li>&emsp;by <b><?= $row['first_name'] . " " . $row['last_name'] ?></b> </li>
                    <li>&emsp;<?= date_convert($row['posted_on']) ?></li>
                    <li>&emsp;<?= $row['group_name'] ?> | <?= $row['course_name'] ?></li>
                </ul><br>
            <?php } ?>
        </div>

    <?php } else { ?>

        <hr>

        <?php
        $query = "SELECT * FROM discussion as d
                    JOIN student_group as g ON g.group_id = d.group_id
                    JOIN group_of_course as gc ON gc.group_id = g.group_id
                    JOIN course as c ON c.course_id = gc.course_id
                    JOIN member_of_group as mg ON mg.group_id = g.group_id
                    JOIN users as u ON u.user_id = d.posted_by_uid
                    ORDER BY d.discussion_id DESC LIMIT 5";
        $discussion_all = mysqli_query($conn, $query);
        $group_name = mysqli_fetch_assoc($discussion_all)['group_name'];
        ?>

        <div class="discussion-content">
            <h3>Top 5 Recent Discussions</h3>
            <br>
            <?php foreach ($discussion_all as $row) { ?>
                <ul>
                    <li>
                        <b><a href='?page=group-comment&discussion_id=<?= $row['discussion_id'] ?>'><?= $row['title'] ?></a></b>
                    </li>
                    <li><?= $row['content'] ?></li>
                    <li>&emsp;by <b><?= $row['first_name'] . " " . $row['last_name'] ?></b> </li>
                    <li>&emsp;<?= date_convert($row['posted_on']) ?></li>
                    <li>&emsp;<?= $row['group_name'] ?> | <?= $row['course_name'] ?></li>
                </ul><br>
            <?php } ?>
        </div>

    <?php } ?>

</div>