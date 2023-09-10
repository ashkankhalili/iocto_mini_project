<?php
session_start();
//include database configuration 
include 'db_config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT user_role FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if ($row['user_role'] !== 'admin') {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Check if the user is associated with any projects
    $checkProjectsSql = "SELECT project_id FROM project_users WHERE user_id = '$delete_id'";
    $checkProjectsResult = mysqli_query($conn, $checkProjectsSql);

    if (mysqli_num_rows($checkProjectsResult) === 0) {
        // User is not associated with any projects, safe to delete

        // Delete the user's profile data from the user_profile table first
        $deleteUserProfileSql = "DELETE FROM user_profile WHERE user_id = '$delete_id'";
        if (mysqli_query($conn, $deleteUserProfileSql)) {
            // User's profile data deleted successfully

            // Now, delete the user from the users table
            $deleteUserSql = "DELETE FROM users WHERE id = '$delete_id'";
            if (mysqli_query($conn, $deleteUserSql)) {
                // when User deleted successfully
            } else {
                // Error occurrd during user deletion
            }
        } else {
            // Error occurred during user profile data deletion
        }
    }
}

$sql = "SELECT id, username, user_role, email, reg_date FROM users";
$result = mysqli_query($conn, $sql);
$users = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}
//set title of the page
$title = "Users List";
//include theme parts.
include 'header.php';
include 'body.php';
?>

<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Users</h2>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Registration Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['user_role']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['reg_date']; ?></td>
                            <td>
                                <?php if ($user['user_role'] !== 'admin'): ?>
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $user['id']; ?>">Delete</button>
                                <?php endif; ?>
                                <!-- Check if the user is an admin to enable editing of username -->
                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                    <a class="btn btn-success" href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <div class="modal fade" id="deleteModal<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php
                                        $user_id = $user['id'];
                                        $checkProjectsSql = "SELECT project_id FROM project_users WHERE user_id = '$user_id'";
                                        $checkProjectsResult = mysqli_query($conn, $checkProjectsSql);
                                        if (mysqli_num_rows($checkProjectsResult) > 0): ?>
                                            <p>This user is related to the following project(s):</p>
                                            <ul>
                                                <?php while ($row = mysqli_fetch_assoc($checkProjectsResult)): ?>
                                                    <?php
                                                    $project_id = $row['project_id'];
                                                    $projectNameSql = "SELECT pr_name FROM projects WHERE id = '$project_id'";
                                                    $projectNameResult = mysqli_query($conn, $projectNameSql);
                                                    $projectName = mysqli_fetch_assoc($projectNameResult)['pr_name'];
                                                    ?>
                                                    <li><?php echo $projectName; ?></li>
                                                <?php endwhile; ?>
                                            </ul>
                                            <p>Please Unassign the user from related project(s) and try again.</p>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <a href="projects.php" class="btn btn-success">GoTo Projects</a>
                                        <?php else: ?>
                                            <p>Are you sure you want to delete this user?</p>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <a href="users.php?delete_id=<?php echo $user['id']; ?>" class="btn btn-danger">Delete</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
