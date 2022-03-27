<?php require("../includes/header.php"); ?>

<main>

    <div class="main-body">
        <section class="col-left">
            <div class="user-info">
                <p>Role: <?= $_SESSION['role_name'] ?></p>
                <p>Professor ID: <?= mysqli_fetch_assoc(get_records_where('professor', 'user_id', $_SESSION['user_id']))['professor_id'] ?></p>
            </div>
            <hr>
            <div class="main-menu">
                <h3>Groups</h3>
                <ul class="menu-list">
                    <li><a href="?page=group-home">Home</a></li>
                    <li><a href="?page=group-submission">Submissions</a></li>
                </ul>
                <br>
                <h3>Manage</h3>
                <ul class="menu-list">
                    <li><a href="?page=groups">Groups</a></li>
                    <li><a href="?page=announcements">Announcements</a></li>
                </ul>
                <br>
                <h3>Assign</h3>
                <ul class="menu-list">
                    <li><a href="?page=assign-students">Students</a></li>
                    <li><a href="?page=assign-tas">Teaching Assistants</a></li>
                </ul>
            </div>
        </section>

        <section class="col-right">

            <div class="content-body">
                <?php

                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    if (file_exists("../group/" . $page . ".php")) {
                        include("../group/" . $page . ".php");
                    }
                    if (file_exists("../CRUD/" . $page . ".php")) {
                        include("../CRUD/" . $page . ".php");
                    }
                    if (file_exists("../includes/" . $page . ".php")) {
                        include("../includes/" . $page . ".php");
                    }
                } else {
                    include("../includes/home.php");
                }
                ?>

            </div>
        </section>
    </div>

</main>

<?php require("../includes/footer.php"); ?>