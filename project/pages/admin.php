<?php require("../includes/header.php"); ?>

<main>

    <div class="main-body">
        <section>
            <div class="col-left">
                <div class="main-menu">
                    <h2>Manage</h2>
                    <ul class="menu-list">
                        <li><a href="?page=roles">Roles</a></li>
                        <li><a href="?page=users">Users</a></li>
                        <li><a href="?page=courses">Courses</a></li>
                        <li><a href="?page=sections">Sections</a></li>
                        <li><a href="?page=groups">Groups</a></li>
                        <li><a href="?page=announcements">Announcements</a></li>
                        <li><a href="?page=forums">Forum</a></li>
                        <li><a href="?page=reply">Reply</a></li>
                        <li><a href="?page=discussions">Discussions</a></li>
                        <li><a href="?page=comments">Comments</a></li>
                        <li><a href="?page=messages">Messages</a></li>
                        <li><a href="?page=task">Tasks</a></li>
                        <li><a href="?page=solution">Solutions</a></li>
                        <li><a href="?page=grades">Grades</a></li>
                        <li><a href="?page=files">Files</a></li>
                    </ul>

                    <h2>Assign</h2>
                    <ul class="menu-list">
                        <li><a href="?page=assign-professors">Professors</a></li>
                        <li><a href="?page=assign-tas">Teaching Assistants</a></li>
                        <li><a href="?page=assign-students">Students</a></li>
                        <!-- <li><a href="?page=assign-group">Group Members</a></li> -->
                    </ul>
                </div>
            </div>
        </section>

        <section>
            <div class="col-right">

                <?php
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    if (file_exists("../includes/" . $page . ".php")) {
                        include("../includes/" . $page . ".php");
                    }
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