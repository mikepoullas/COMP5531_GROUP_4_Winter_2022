<?php require("../includes/header.php"); ?>

<main>

    <div class="main-body">
        <section class="col-left">
            <div class="user-info">
                <p>Role: <?= $_SESSION['role_name'] ?></p>
                <p>Student ID: <?= mysqli_fetch_assoc(get_records_where('student', 'user_id', $_SESSION['user_id']))['student_id'] ?></p>
            </div>
            <hr>
            <div class="main-menu">
                <h3>Manage</h3>
                <ul class="menu-list">
                    <li><a href="?page=group-home">Groups</a></li>
                    <li><a href="?page=group-submission">Submissions</a></li>
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