<!--
CODE CONTRIBUTOR:

# COMP 5531 - GROUP 4 (Winter 2022)
Student_ID  First_Name  Last_Name   Email
40159305    shafiq      IMTIAZ      s_mtiaz@encs.concordia.ca
21917730    michael     POULLAS     m_poull@encs.concordia.ca
-->

<?php require("../includes/header.php"); ?>

<main>

    <div class="main-body">
        <section class="col-left">
            <div class="user-info">
                <p>Role: <?= $_SESSION['role_name'] ?></p>
            </div>
            <hr>
            <div class="main-menu">
                <div class="menu-list">
                    <h2><a href="?page=course-home">Courses</a></h2>
                </div>
                <div class="menu-list">
                    <h2><a href="?page=group-home">Groups</a></h2>
                </div>
                <br>
                <h2>Manage</h2>
                <ul class="menu-list">
                    <li><a href="?page=groups">Groups</a></li>
                    <li><a href="?page=announcements">Announcements</a></li>
                    <li><a href="?page=grades">Grades</a></li>
                </ul>
                <br>
                <h2>Assign</h2>
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
                    if (file_exists("../assigns/" . $page . ".php")) {
                        include("../assigns/" . $page . ".php");
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