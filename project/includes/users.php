<?php

$query_body = "SELECT * FROM users as u JOIN roles as r ON u.role_id = r.role_id ORDER BY user_id ASC";
$results_body = mysqli_query($conn, $query_body);

?>

<div class="content-body">
    <p><b>Users</b></p>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Date of Birth</th>
                <th>Email</th>
                <th>Username</th>
                <th>Created On</th>
                <th>Role Name</th>
                <th colspan="2">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($users = mysqli_fetch_assoc($results_body)) {
                $user_id = $users['user_id'];
                $first_name = $users['first_name'];
                $last_name = $users['last_name'];
                $dob = $users['dob'];
                $email = $users['email'];
                $username = $users['username'];
                $created_on = $users['created_on'];
                $role_name = $users['role_name'];
            ?>
                <tr>
                    <td><?php echo $user_id ?></td>
                    <td><?php echo $first_name ?></td>
                    <td><?php echo $last_name ?></td>
                    <td><?php echo $dob ?></td>
                    <td><?php echo $email ?></td>
                    <td><?php echo $username ?></td>
                    <td><?php echo $created_on ?></td>
                    <td><?php echo $role_name ?></td>
                    <td><a href="?page=update&update=<?= $user_id ?>">Update</a></td>
                    <td><a href="?page=delete&delete=<?= $user_id ?>">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

    <a href="?page=register">
        <button>Add User</button>
    </a>

</div>