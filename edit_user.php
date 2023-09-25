<?php
$title = "Edit User";
session_start();
include 'db_config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if the user is not logged in
    exit();
}

// Check if the user is an admin, and if not, redirect to index.php
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php"); // Redirect to index.php if the user is not an admin
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch the user's information from the database using $user_id
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "User not found.";
        exit;
    }
} else {
    echo "User ID not provided.";
    exit;
}

// Handle form submission to update user details
if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $newEmail = $_POST['new_email'];
    $newPassword = $_POST['new_password'];

    // Construct the initial update query
    $updateQuery = "UPDATE users SET email='$newEmail'";

    // Check if a new password is provided; otherwise, keep the old password
    if (!empty($newPassword)) {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateQuery .= ", password='$passwordHash'";
    }

    // Only update the username if the user is an admin
    if ($_SESSION['user_role'] === 'admin') {
        $newUsername = $_POST['new_username'];

        // Check if the new username is already in use by another user
        $checkUsernameQuery = "SELECT * FROM users WHERE username='$newUsername' AND id != $user_id";
        $usernameResult = mysqli_query($conn, $checkUsernameQuery);
        if (mysqli_num_rows($usernameResult) > 0) {
            // Username is already in use
            $message = "Username '$newUsername' is already in use by another user and cannot be updated.";
        } else {
            $updateQuery .= ", username='$newUsername'";
        }
    }

    // Add a WHERE clause to update only the specific user
    $updateQuery .= " WHERE id = $user_id";

    if (!isset($message) && mysqli_query($conn, $updateQuery)) {
        // User details updated successfully
        header("Location: users.php"); // Redirect back to the user list page
        exit;
    }
}
include 'header.php';
include 'body.php';
?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
            </div>
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h2 class="card-title">Edit User</h2>
                    </div>
                    <div class="card-body">
                        <?php if (isset($message)): ?>
                            <div class="alert alert-danger"><?php echo $message; ?></div>
                        <?php endif; ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $user_id; ?>" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <div class="form-group">
                                <label for="new_username">Username:</label>
                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                    <input class="form-control" type="text" name="new_username"
                                        value="<?php echo $user['username']; ?>">
                                <?php else: ?>
                                    <input type="text" name="new_username" value="<?php echo $user['username']; ?>"
                                        readonly>
                                <?php endif; ?>
                            </div>
                            <div class="form-group">
                                <label for="new_email">Email:</label>
                                <input class="form-control" type="email" name="new_email"
                                    value="<?php echo $user['email']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="new_password">New Password:</label>
                                <input class="form-control" type="password" name="new_password">
                            </div>
                            <div>
                                <input class="btn btn-success" type="submit" name="update_user" value="Update User">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>
