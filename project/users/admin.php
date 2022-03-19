<?php include("../includes/header.php"); ?>

<main>

    <div class="main-body">
        <section class="col-left">
            <div class="user-info">
                <p>Role: <?= $_SESSION['role_name'] ?></p>
            </div>
            <hr>
            <div class="main-menu">
                <h3>Manage</h3>
                <ul class="menu-list">
                    <li><a href="#">Users</a></li>
                    <li><a href="#">Professors</a></li>
                    <li><a href="#">Teaching Assistants</a></li>
                    <li><a href="#">Students</a></li>
                    <li><a href="#">Courses</a></li>
                    <li><a href="#">Sections</a></li>
                    <li><a href="#">Groups</a></li>
                    <li><a href="#">Announcements</a></li>
                    <li><a href="#">Discussions</a></li>
                    <li><a href="#">Comments</a></li>
                    <li><a href="#">Files</a></li>
                </ul>
            </div>
        </section>

        <section class="col-right">

            <?php include("../includes/register.php") ?>
            <hr>
            <?php include("../includes/users.php") ?>

        </section>
    </div>

</main>

<?php include("../includes/footer.php"); ?>