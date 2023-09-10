<?php
session_start();
$title = "Edit Project";
// Include database connection configuration
include 'db_config.php';

// Initialize the message variable
$message = '';

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
//include Theme Parts.
include 'header.php';
include 'body.php';
// Check if the project ID is provided in the URL
if (isset($_GET['id'])) {
    $project_id = $_GET['id'];

    // Fetch the project details from the database using $project_id
    $query = "SELECT * FROM projects WHERE id = $project_id";
    $result = mysqli_query($conn, $query);

    // Check if the project exists
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        // if Project not found 
        echo "Project not found.";
        exit;
    }
} else {
    // Project ID should be provided in the URL, otherwise user gets message.
    echo "Project ID not provided.";
    exit;
}

// Fetch the list of users from the database
$userQuery = "SELECT * FROM users";
$usersResult = mysqli_query($conn, $userQuery);

// Fetch the list of users associated with the project
$projectUsersQuery = "SELECT user_id FROM project_users WHERE project_id = $project_id";
$projectUsersResult = mysqli_query($conn, $projectUsersQuery);
$projectUserIds = [];
while ($projectUser = mysqli_fetch_assoc($projectUsersResult)) {
    $projectUserIds[] = $projectUser['user_id'];
}

// Handle form submission to update project details
if (isset($_POST['update_project'])) {
    $pr_id = $_POST['pr_id'];
    $pr_name = $_POST['pr_name'];
    $pr_summary = $_POST['pr_summary'];
    $selectedUsers = isset($_POST['users']) ? $_POST['users'] : [];

    // Check if a project with the same name already exists (excluding the current project)
    $checkQuery = "SELECT * FROM projects WHERE pr_name='$pr_name' AND id != '$pr_id'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // A project with the same name already exists, display an error message
        $message = "A project with the same name already exists.";
    } else {
        // Update the project in the database
        $updateQuery = "UPDATE projects SET pr_name='$pr_name', pr_summary='$pr_summary' WHERE id='$pr_id'";

        if (mysqli_query($conn, $updateQuery)) {
            // Update user-project associations for the project
            $deleteUserProjectQuery = "DELETE FROM project_users WHERE project_id='$pr_id'";
            mysqli_query($conn, $deleteUserProjectQuery);

            foreach ($selectedUsers as $user_id) {
                $insertUserProjectQuery = "INSERT INTO project_users (project_id, user_id) VALUES ('$pr_id', '$user_id')";
                mysqli_query($conn, $insertUserProjectQuery);
            }

            // Set the success message
            $message = "Project Updated.";
        } else {
            // Set the error message
            $message = "Error updating project: " . mysqli_error($conn);
        }
    }
}

?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
			<div class="col-md-3">
			</div>
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h2 class="card-title">Edit Project</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($message)): ?>
                            <script>
                                alert("<?php echo $message; ?>");
                                window.location.href = "./projects.php";
                            </script>
                        <?php endif; ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $project_id; ?>" method="post">
                            <input type="hidden" name="pr_id" value="<?php echo $row['id']; ?>">
                            <div class="form-group">
                                <label for="pr_name">Project Name:</label>
                                <input class="form-control" type="text" name="pr_name" value="<?php echo $row['pr_name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="pr_summary">Project Summary:</label>
                                <textarea class="form-control" name="pr_summary" required><?php echo $row['pr_summary']; ?></textarea>
                            </div>
                            <div>
                                <label for="users">Select Users:</label>
                                <select class="form-control" name="users[]" class="custom-select rounded-0" id="users" multiple>
                                    <?php while ($user = mysqli_fetch_assoc($usersResult)): ?>
                                        <option value="<?php echo $user['id']; ?>" <?php if (in_array($user['id'], $projectUserIds)) echo 'selected'; ?>><?php echo $user['username']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <input class="btn btn-primary" type="submit" name="update_project" value="Update Project">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'footer.php';?>
