<?php require("../includes/header.php"); ?>

<main>

    <div class="main-body">
        <section>
            <div class="col-left">
                <div class="user-info">
                    <p>Role: <?= $_SESSION['role_name'] ?></p>
                </div>
                <hr>
                <div class="main-menu">
                    <h3>Manage</h3>
                    <ul class="menu-list">
                        <li><a href="?page=users">Users</a></li>
                        <li><a href="?page=roles">Roles</a></li>
                        <li><a href="?page=courses">Courses</a></li>
                        <li><a href="?page=sections">Sections</a></li>
                        <li><a href="?page=groups">Groups</a></li>
                        <li><a href="?page=announcements">Announcements</a></li>
                        <li><a href="?page=discussions">Discussions</a></li>
                        <li><a href="?page=comments">Comments</a></li>
                        <li><a href="?page=files">Files</a></li>
                    </ul>

                    <h3>Assign</h3>
                    <ul class="menu-list">
                        <li><a href="?page=professors">Professors</a></li>
                        <li><a href="?page=tas">Teaching Assistants</a></li>
                        <li><a href="?page=students">Students</a></li>
                    </ul>
                </div>
            </div>
        </section>

        <section>
            <div class="col-right">

                <?php

                if (isset($_GET['page'])) {

                    $page = $_GET['page'];
                    if (file_exists("../CRUD/" . $page . ".php")) {
                        include("../CRUD/" . $page . ".php");
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