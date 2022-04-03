<?php require("../includes/header.php"); ?>

<main>

    <div class="main-body">
        <section class="col-left">
            <div class="user-info">
                <p>Role: <?= $_SESSION['role_name'] ?></p>
                <p>TA ID: <?= mysqli_fetch_assoc(get_records_where('ta', 'user_id', $_SESSION['user_id']))['ta_id'] ?></p>
            </div>
            <hr>
            <div class="main-menu">
                <h2>Course</h2>
                <ul class="menu-list">
                    <li><a href="?page=course-home">Forum</a></li>
                    <li><a href="?page=course-submission">Submissions</a></li>
                </ul>
                <h2>Groups</h2>
                <ul class="menu-list">
                    <li><a href="?page=group-home">Group Home</a></li>
                </ul>
            </div>
        </section>

        <section class="col-right">

            <div class="content-body">
                <?php

                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    if (file_exists("../includes/" . $page . ".php")) {
                        include("../includes/" . $page . ".php");
                    }
                    if (file_exists("../CRUD/" . $page . ".php")) {
                        include("../CRUD/" . $page . ".php");
                    }
                    if (file_exists("../course/" . $page . ".php")) {
                        include("../course/" . $page . ".php");
                    }
                    if (file_exists("../group/" . $page . ".php")) {
                        include("../group/" . $page . ".php");
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