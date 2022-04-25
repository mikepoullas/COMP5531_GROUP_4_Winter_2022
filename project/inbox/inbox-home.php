<?php

$session_user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

//DOWNLOAD
if (isset($_GET['download_file'])) {
    download_file($_GET['download_file']);
}

?>

<div class="content-body">

    <?php
    display_success();
    display_error();
    ?>

    <?php if (isStudent()) {

        $student_id = mysqli_fetch_assoc(get_records_where('student', 'user_id', $session_user_id))['student_id'];

        $groups = get_records_where('member_of_group', 'student_id', $student_id);

        echo "<h2>Group Members</h2><hr>";

        foreach ($groups as $row) {
            $query = "SELECT g.*, c.*, st.*, u.* FROM student_groups as g
            JOIN member_of_group as mg ON mg.group_id = g.group_id
            JOIN student as st ON st.student_id = mg.student_id
            JOIN users as u ON u.user_id = st.user_id
            JOIN group_of_course as gc ON gc.group_id = g.group_id
            JOIN course as c ON c.course_id = gc.course_id
            WHERE g.group_id = '$row[group_id]'
            ORDER BY g.group_id ASC";
            $group_info = mysqli_query($conn, $query);
    ?>

            <div class="group-content">
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Group Name</th>
                            <th>Course Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($group_info as $row) {
                            $user_id = $row['user_id'];
                            $group_id = $row['group_id'];
                            $group_name = $row['group_name'];
                            $student_name = $row['first_name'] . " " . $row['last_name'];
                            $course_id = $row['course_id'];
                            $course_name = $row['course_name'];
                            $student_id = $row['student_id'];
                        ?>
                            <tr>
                                <?php if (isGroupLeader($student_id, $group_id)) { ?>
                                    <td><u><?= $student_name ?></u></td>
                                <?php } else { ?>
                                    <td><?= $student_name ?></td>
                                <?php } ?>
                                <td><?= $group_name ?></td>
                                <td><?= $course_name ?></td>
                                <?php if ($user_id != $session_user_id) { ?>
                                    <td><a href="?page=inbox-messages&receiver_id=<?= $user_id ?>">Message</a></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <br>

        <?php } ?>

    <?php } ?>

</div>