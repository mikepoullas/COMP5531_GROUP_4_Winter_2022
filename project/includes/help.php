<?php

unset($_REQUEST);

$username = $_SESSION['username'];
$role_name = $_SESSION['role_name'];
$session_user_id = $_SESSION['user_id'];
$role_id = $_SESSION['role_id'];

?>

<div class="content-body">
    <h2>Help Page</h2>
    <hr>

    <?php if (isAdmin()) { ?>
        <div class="admin-content">
            <h3>Admin Help</h3>
            <br>
            <ul>
                <li>You have all access!</li>
            </ul>
        </div>
    <?php } ?>

    <?php if (!isAdmin()) { ?>
        <div class="basics-content">
            <h3>Basics</h3>
            <br>
            <ul>
                <li>This help page will guide you through the permissions your role has.</li>
            </ul>
        </div>
        <br>
    <?php } ?>

    <?php if (isProfessor()) { ?>
        <div class="professor-content">
            <h3>Professor Help</h3>
            <br>
            <ul>
                <li>- You can view courses you are professor of.</li><br>
                <li>- You can view / manage / update / delete courses</li><br>
                <li>- You can view / manage / update / delete groups.</li><br>
                <li>- You can view / upload /download / update / delete course tasks.</li><br>
                <li>- You can view / download group solutions.</li><br>
                <li>- You can view / add / delete grades to group solutions.</li><br>
                <li>- You can add / update / delete announcements.</li><br>
                <li>- You can add / update / delete students, teaching assistants and group members.</li>
            </ul>
        </div>
    <?php } ?>

    <?php if (isTA()) { ?>
        <div class="ta-content">
            <h3>TA Help</h3>
            <br>
            <ul>
                <li>- You can view courses and sections you are TA of.</li><br>
                <li>- You can view / download tasks and solutions.</li><br>
                <li>- You can add / reply to course forums.</li><br>
                <li>- You can view / comment to group, task, solution - discussions.</li>
            </ul>
        </div>
    <?php } ?>

    <?php if (isStudent()) { ?>
        <div class="student-content">
            <h3>Student Help</h3>
            <br>
            <ul>
                <li>- You can view courses and sections you are enrolled in.</li><br>
                <li>- You can view groups you are joined in.</li><br>
                <li>- You can view / download course tasks.</li><br>
                <li>- If you are group leader, you can view / upload / download / update / delete group solutions.</li><br>
                <li>- You can add / reply to course forums.</li><br>
                <li>- You can view / comment to group, task, solution - discussions.</li>
                <li>- You can send / update/ delete (last message only!) private messages and files to group members.</li>
            </ul>
        </div>
    <?php } ?>

</div>