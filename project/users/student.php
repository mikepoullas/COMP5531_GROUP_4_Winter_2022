<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Student</title>
</head>

<body>

    <header>
        <h1>Student</h1>
        <nav>
            <p>Welcome <b>User</b></p>
            <ul>
                <li><a href="#">Change Email</a></li>
                <li><a href="#">Change Password</a></li>
                <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>

        <div class="main-body">
            <section class="col-left">
                <div class="user-info">
                    <p>Role: User</p>
                    <p>Course: N/A</p>
                    <p>Section: N/A</p>
                </div>
                <hr>
                <div class="main-menu">
                    <h3>Manage</h3>
                    <ul class="menu-list">
                        <li><a href="#">Courses</a></li>
                        <li><a href="#">Sections</a></li>
                        <li><a href="#">Announcements</a></li>
                        <li><a href="#">Groups</a></li>
                        <li><a href="#">Assignments</a></li>
                        <li><a href="#">Projects</a></li>
                        <li><a href="#">Discussions</a></li>

                    </ul>
                </div>
            </section>

            <section class="col-right">
                <div class="content-body">
                    <p>Users</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Countries</th>
                                <th>Capitals</th>
                                <th>Population</th>
                                <th>Language</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>USA</td>
                                <td>Washington, D.C.</td>
                                <td>309 million</td>
                                <td>English</td>
                            </tr>
                            <tr>
                                <td>Sweden</td>
                                <td>Stockholm</td>
                                <td>9 million</td>
                                <td>Swedish</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section>
        </div>


    </main>

</body>

</html>