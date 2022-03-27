<?php

$user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

// initializing variables
$id = $group_name = $group_leader_sid = $section_name = $course_name = "";

if (isStudent()) {
    $query = "SELECT g.*, u.*, cs.section_name, c.course_name FROM student_groups as g
                JOIN member_of_group as mg ON mg.group_id = g.group_id
                JOIN student as s ON s.student_id = mg.student_id
                JOIN users as u ON u.user_id = s.user_id
                JOIN section_groups as sg ON sg.group_id = g.group_id
                JOIN course_section as cs ON cs.section_id = sg.section_id
                JOIN course as c ON c.course_id = cs.course_id
                WHERE u.user_id = $user_id
                ORDER BY g.group_id ASC";
} else {
    $query = "SELECT g.*, u.*, cs.section_name, c.course_name FROM student_groups as g
                JOIN member_of_group as mg ON mg.group_id = g.group_id
                JOIN student as s ON s.student_id = mg.student_id
                JOIN users as u ON u.user_id = s.user_id
                JOIN section_groups as sg ON sg.group_id = g.group_id
                JOIN course_section as cs ON cs.section_id = sg.section_id
                JOIN course as c ON c.course_id = cs.course_id
                ORDER BY g.group_id ASC";
}
$group_info = mysqli_query($conn, $query);

?>

<div class="content-body">

    <?php
    display_success();
    display_error();
    ?>

    <div class="group-content">

        <p><b>Groups</b></p>
        <hr><br>
        <table>
            <thead>
                <tr>
                    <th>Group Name</th>
                    <th>Group Leader SID</th>
                    <th>Section</th>
                    <th>Course</th>
                    <th colspan="2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($group_info as $group) {
                    $id = $group['group_id'];
                    $group_name = $group['group_name'];
                    $group_leader_sid = $group['group_leader_sid'];
                    $section_name = $group['section_name'];
                    $course_name = $group['course_name'];
                ?>
                    <tr>
                        <td><?php echo $group_name ?></td>
                        <td><?php echo $group_leader_sid ?></td>
                        <td><?php echo $section_name ?></td>
                        <td><?php echo $course_name ?></td>
                        <td><a href="?page=group-home&discussion_view=true&group_id=<?= $id ?> ">View</a></td>
                        <td><a href="?page=group-discussion&group_id=<?= $id ?> ">Goto</a></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($_GET['discussion_view'])) { ?>
        <br>
        <hr>

        <?php

        $group_id = $_GET['group_id'];

        $query = "SELECT * FROM discussion as d
                    JOIN student_groups as g ON g.group_id = d.group_id
                    JOIN section_groups as sg ON sg.group_id = g.group_id
                    JOIN users as u ON u.user_id = d.posted_by_uid
                    WHERE g.group_id = $group_id
                    ORDER BY d.discussion_id DESC";
        $group_discussion = mysqli_query($conn, $query);

        $group_name = mysqli_fetch_assoc($group_discussion)['group_name'];

        ?>
        <div class="discussion-content">
            <p><?= $group_name ?> Discussions</p>
            <?php

            foreach ($group_discussion as $disccussion) {
                echo "<ul>";
                echo '<li> <b> Title: ' . $disccussion['title'] . '</b> </li>';
                echo '<li> <b> Content: ' . $disccussion['content'] . ' </b> </li>';
                echo '<li> Posted by: ' . $disccussion['username'] . '</li>';
                echo '<li> Posted on: ' . $disccussion['posted_on'] . '</li>';
                echo '<li> <b> Group: ' . $disccussion['group_name'] . '</b> </li>';
                echo "</ul><br>";
            }
            ?>
            <hr>
        </div>
    <?php } else { ?>

        <br>
        <hr>

        <?php
        $query = "SELECT * FROM discussion as d
              JOIN student_groups as g ON g.group_id = d.group_id
              JOIN section_groups as sg ON sg.group_id = g.group_id
              JOIN users as u ON u.user_id = d.posted_by_uid
              ORDER BY d.discussion_id DESC LIMIT 5";
        $group_discussion = mysqli_query($conn, $query);
        $group_name = mysqli_fetch_assoc($group_discussion)['group_name'];
        ?>

        <div class="discussion-content">
            <p>Top 5 Recent Discussions</p>
            <?php
            foreach ($group_discussion as $disccussion) {
                echo "<ul>";
                echo '<li> <b> Title: ' . $disccussion['title'] . '</b> </li>';
                echo '<li> <b> Content: ' . $disccussion['content'] . ' </b> </li>';
                echo '<li> Posted by: ' . $disccussion['username'] . '</li>';
                echo '<li> Posted on: ' . $disccussion['posted_on'] . '</li>';
                echo '<li> <b> Group: ' . $disccussion['group_name'] . '</b> </li>';
                echo "</ul><br>";
            }
            ?>
            <hr>
        </div>

    <?php } ?>

</div>