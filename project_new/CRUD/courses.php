<?php

$query_body = "SELECT * FROM course ORDER BY course_name ASC";
$results_body = mysqli_query($conn, $query_body);
?>

<div class="content-body">
    <p><b>Users</b></p>
    <table>
        <thead>
            <tr>
                <th>Course ID</th>
                <th>Course Name</th>
                <th>Course Number</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($users = mysqli_fetch_assoc($results_body)) {
                $course_id = $users['course_id'];
                $course_name = $users['course_name'];
                $course_number = $users['course_number'];
            ?>
                <tr>
                    <td><?php echo $course_id ?></td>
                    <td><?php echo $course_name ?></td>
                    <td><?php echo $course_number ?></td>
                    <td><a href="?page=courses_update&id=<?= $course_id ?>">Update</a></td>
                    <td><a href="?page=courses_delete&id=<?= $course_id ?>">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <a href="?page=courses_add">
        <button>Add Course</button>
    </a>

</div>