<?php
$title = "Manage Projects (Admin)";
session_start();
// Include database connection 
include 'db_config.php';
//declare variable for message 
$message = '';

// Check if the user is an admin, and if not, redirect to a different page or show an error message.
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php"); // Redirect to index.php if the user is not an admin
    exit();
}
// Create a new project
if (isset($_POST['create_project'])) {
    $pr_name = $_POST['pr_name'];
    $pr_summary = $_POST['pr_summary'];

    // Check if a project with the same name already exists
    $checkQuery = "SELECT * FROM projects WHERE pr_name='$pr_name'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        $message = "A project with the same name already exists.";
    } else {
        // Insert the new project into the projects table
        $insertProjectQuery = "INSERT INTO projects (pr_name, pr_summary) VALUES ('$pr_name', '$pr_summary')";

        if (mysqli_query($conn, $insertProjectQuery)) {
            $project_id = mysqli_insert_id($conn); // Get the ID of the newly created project

            // Handle user access
            if (!empty($_POST['users'])) {
                $users = $_POST['users'];
                foreach ($users as $user_id) {
                    // Insert user-project association into project_users table
                    $insertUserProjectQuery = "INSERT INTO project_users (project_id, user_id) VALUES ('$project_id', '$user_id')";
                    mysqli_query($conn, $insertUserProjectQuery);
                }
            }

            $message = "Project created successfully.";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}

// Edit an existing project
if (isset($_POST['edit_project'])) {
    $pr_id = $_POST['pr_id'];
    $pr_name = $_POST['pr_name'];
    $pr_summary = $_POST['pr_summary'];

    // Update the project details in the projects table
    $updateProjectQuery = "UPDATE projects SET pr_name='$pr_name', pr_summary='$pr_summary' WHERE id='$pr_id'";

    if (mysqli_query($conn, $updateProjectQuery)) {
        // Handle user access
        if (!empty($_POST['users'])) {
            $users = $_POST['users'];

            // Delete existing user-project associations for this project
            $deleteUserProjectQuery = "DELETE FROM project_users WHERE project_id='$pr_id'";
            mysqli_query($conn, $deleteUserProjectQuery);

            // Insert updated user-project associations into project_users table
            foreach ($users as $user_id) {
                $insertUserProjectQuery = "INSERT INTO project_users (project_id, user_id) VALUES ('$pr_id', '$user_id')";
                mysqli_query($conn, $insertUserProjectQuery);
            }
        }

        $message = "Project updated successfully.";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Delete a project and its associated test cases
if (isset($_GET['delete_project'])) {
    $pr_id = $_GET['delete_project'];

    // Delete associated test cases first
    $deleteTestCasesQuery = "DELETE FROM test_cases WHERE project_id='$pr_id'";
    if (mysqli_query($conn, $deleteTestCasesQuery)) {
        // Then, delete the associated project users
        $deleteProjectUsersQuery = "DELETE FROM project_users WHERE project_id='$pr_id'";
        if (mysqli_query($conn, $deleteProjectUsersQuery)) {
            // Finally, delete the project
            $deleteProjectQuery = "DELETE FROM projects WHERE id='$pr_id'";
            
            if (mysqli_query($conn, $deleteProjectQuery)) {
                $message = "Project and associated test cases deleted successfully.";
            } else {
                $message = "Error deleting project: " . mysqli_error($conn);
            }
        } else {
            $message = "Error deleting project users: " . mysqli_error($conn);
        }
    } else {
        $message = "Error deleting associated test cases: " . mysqli_error($conn);
    }
}

// Fetch all projects from the database with assigned users
$projectQuery = "SELECT projects.*, GROUP_CONCAT(users.username SEPARATOR ', ') AS assigned_users
                 FROM projects
                 LEFT JOIN project_users ON projects.id = project_users.project_id
                 LEFT JOIN users ON project_users.user_id = users.id
                 GROUP BY projects.id";
$projectsResult = mysqli_query($conn, $projectQuery);

// Fetch users for populating the form
$userQuery = "SELECT * FROM users";
$usersResult = mysqli_query($conn, $userQuery);
// Include theme Parts
include 'header.php';
include 'body.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h2 class="card-title">Add Projects (Admin)</h3>
                    </div>
                    <!-- Display a message if there was a success or error -->
                    <?php if (!empty($message)): ?>
                        <p style="color:red;padding:20px;font-size:25px;"><?php echo $message; ?></p>
                    <?php endif; ?>
                    <!-- Create a new project form -->
                    <div class="card-body">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <div class="form-group">
                                <input class="form-control" type="text" name="pr_name" placeholder="Project Name" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="pr_summary" placeholder="Project Summary" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="users">Select Users:</label>
                                <select name="users[]" class="custom-select rounded-0" id="users" multiple>
                                    <?php while ($user = mysqli_fetch_assoc($usersResult)): ?>
                                        <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                    </div>
					<div class="card-footer">
                            <input type="submit" name="create_project" value="Create project" class="btn btn-primary">
					</div>
						</form>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-success">
                    <div class="card-header">
                        <h2 class="card-title">Existing Projects</h3>
                    </div>
					<div class="card-body">
						<table class="table table-bordered">
							<thead>
							<tr>
								<th>Project Name</th>
								<th>Project Summary</th>
								<th>Assigned Users</th>
								<th>Operations</th>
								
							</tr>
							</thead>
							<?php while ($row = mysqli_fetch_assoc($projectsResult)): ?>
							<tbody>
								<tr>
									<td><?php echo $row['pr_name']; ?></td>
									<td><?php echo $row['pr_summary']; ?></td>
									<td><?php echo $row['assigned_users']; ?></td>
									<td>
									<a class="btn btn-primary" href="edit_project.php?id=<?php echo $row['id']; ?>">Edit</a>
									<a class="btn btn-danger" href="#" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</a>
									</td>
								</tr>
							</tbody>
							<?php endwhile; ?>
						</table>
					</div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
function confirmDelete(projectId) {
    var confirmDelete = confirm("Are you sure you want to delete this project?\n!!!All Related Test Cases will be deteled!!!!");
    if (confirmDelete) {
        // If the user confirms, redirect to the delete URL
        window.location.href = "?delete_project=" + projectId;
    }
}
</script>
<?php include 'footer.php';?>
