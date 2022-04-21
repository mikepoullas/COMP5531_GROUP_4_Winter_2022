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
                <li></li>
            </ul>
        </div>
    <?php } ?>

    <?php if (!isAdmin()) { ?>
        <div class="basics-content">
            <h3>Basics</h3>
            <br>
            <ul>
                <li>Basics HELP !!</li>
            </ul>
        </div>
    <?php } ?>

    <hr>

    <?php if (isProfessor()) { ?>
        <div class="professor-content">
            <h3>Professor Help</h3>
            <br>
            <ul>
                <li>-You can view and manage courses</li>
                <li>-You can view/manage/update/delete groups.</li> 
                <li>-You can add/delete/update announcements.</li>
                <li>-You can view/delete grades.</li>
                <li>-You can add/delete/update students, teaching assistants, and group members.</li>
            </ul>
        </div>
    <?php } ?>

    <?php if (isTA()) { ?>
        <div class="ta-content">
            <h3>TA Help</h3>
            <br>
            <ul>
                <li>-You can view and add to courses, groups, and discussions.</li>
            </ul>
        </div>
    <?php } ?>

    <?php if (isStudent()) { ?>
        <div class="student-content">
            <h3>Student Help</h3>
            <br>
            <ul>
                <li>-You can view courses you are enrolled in.</li>
                <li>-You can view groups and join them.</li>
                <li>-You can view/add/update discussions, comments, and assignment solutions.</li>
            </ul>
        </div>
    <?php } ?>

</div>