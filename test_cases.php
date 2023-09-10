<?php
session_start();
include 'db_config.php';

// Check if the user is logged in and has the necessary privileges
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT user_role FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($row['user_role'] !== 'admin' && $row['user_role'] !== 'project_manager') {
    header("Location: index.php");
    exit();
}

// Handle form submissions to add new test cases
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addTestCase'])) {
        $project_id = $_POST['project_id'];
        $name = $_POST['name'];
        $priority = $_POST['priority'];
        $description = $_POST['description'];
        $steps = $_POST['steps'];
        $status = $_POST['status'];

        // Insrt the new test case into the database
        $insertSql = "INSERT INTO test_cases (project_id, name, priority, description, steps, status)
                      VALUES ('$project_id', '$name', '$priority', '$description', '$steps', '$status')";

        if (mysqli_query($conn, $insertSql)) {
            // if Test case added successfully
            header("Location: test_cases.php"); // Redirect to the test cases page
            exit();
        } else {
            // Error occurred during insertion
            $error = "Error: " . mysqli_error($conn);
        }
    }

    // Handle deletion when Delete button is clicked
    if (isset($_POST['deleteTestCase'])) {
        $delete_id = $_POST['delete_id'];
        $confirmDelete = $_POST['confirm_delete'];

        if ($confirmDelete === 'yes') {
            // Perform the deletion based on $delete_id
            $deleteSql = "DELETE FROM test_cases WHERE id = '$delete_id'";

            if (mysqli_query($conn, $deleteSql)) {
                // when Test case deleted successfully
                header("Location: test_cases.php"); // Redirect to the test cases page
                exit();
            } else {
                // Error occurred during deletion
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Retrieve a list of projects for assigning test cases
$projectsSql = "SELECT id, pr_name FROM projects";
$projectsResult = mysqli_query($conn, $projectsSql);

// Retrieve a list of existing test cases
$testCasesSql = "SELECT tc.id, p.pr_name AS project_name, tc.name, tc.priority, tc.description, tc.steps, tc.status
                 FROM test_cases tc
                 INNER JOIN projects p ON tc.project_id = p.id";
$testCasesResult = mysqli_query($conn, $testCasesSql);

$title = "Test Cases";
include 'header.php';
include 'body.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
			<div class="col-md-12">
				<div class="card card-primary">
					<div class="card-header">
						<h2 class="card-title">Add New Test Case</h2>
					</div>
					<div class="card-body">
						<!-- Add New Test Case Form -->
						<form method="post">
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label for="project_id">Project:</label>
										<select class="form-control" id="project_id" name="project_id" required>
											<option value="" disabled selected>Select a project</option>
											<?php while ($row = mysqli_fetch_assoc($projectsResult)): ?>
												<option value="<?php echo $row['id']; ?>"><?php echo $row['pr_name']; ?></option>
											<?php endwhile; ?>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="name">Name:</label>
										<input type="text" class="form-control" id="name" name="name" required>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="priority">Priority:</label>
										<select class="form-control" id="priority" name="priority" required>
											<option value="High">High</option>
											<option value="Normal">Normal</option>
											<option value="Low">Low</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="status">Status:</label>
										<select class="form-control" id="status" name="status" required>
											<option value="Untested">Untested</option>
											<option value="Passed">Passed</option>
											<option value="Failed">Failed</option>
											<option value="On Process">On Process</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="description">Description:</label>
										<textarea class="form-control" id="description" name="description" rows="3"></textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="steps">Steps:</label>
										<textarea class="form-control" id="steps" name="steps" rows="3"></textarea>
									</div>
								</div>
							</div>
							<button type="submit" name="addTestCase" class="btn btn-primary">Add Test Case</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include 'footer.php';?>
