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



// Check if the test case ID is provided in the URL
if (isset($_GET['id'])) {
    $test_case_id = $_GET['id'];

    // Retieve the test case details based on the provided ID
    $testCaseSql = "SELECT tc.id, p.pr_name AS project_name, tc.name, tc.priority, tc.description, tc.steps, tc.status
                     FROM test_cases tc
                     INNER JOIN projects p ON tc.project_id = p.id
                     WHERE tc.id = '$test_case_id'";

    $testCaseResult = mysqli_query($conn, $testCaseSql);
    $testCase = mysqli_fetch_assoc($testCaseResult);

    if (!$testCase) {
        // Test case with the provided ID does not exist
        header("Location: test_cases.php");
        exit();
    }
} else {
    // Test case ID is not providde in the URL
    header("Location: test_cases.php");
    exit();
}

// Handle form submissions to edit the test case
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editTestCase'])) {
        $name = $_POST['name'];
        $priority = $_POST['priority'];
        $description = $_POST['description'];
        $steps = $_POST['steps'];
        $status = $_POST['status'];

        // Update the test case in the database
        $updateSql = "UPDATE test_cases
                      SET name = '$name', priority = '$priority', description = '$description', steps = '$steps', status = '$status'
                      WHERE id = '$test_case_id'";

        if (mysqli_query($conn, $updateSql)) {
            // when Test case updated successfully
            header("Location: my_projects.php"); // Redirect to the test cases page
            exit();
        } else {
            // Error occurred during update
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

$title = "Edit Test Case";
include 'header.php';
include 'body.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h2 class="card-title">Edit Test Case</h2>
                    </div>
                    <div class="card-body">
                        <!-- Edit Test Case Form -->
                        <form method="post">
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="name">Name:</label>
										<input type="text" class="form-control" id="name" name="name"
											value="<?php echo $testCase['name']; ?>" readonly>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="priority">Priority:</label>
										<input type="text" class="form-control" id="priority" name="priority"
											value="<?php echo $testCase['priority']; ?>" readonly>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="status">Status:</label>
										<select class="form-control" id="status" name="status" required>
											<option value="Untested" <?php if ($testCase['status'] === 'Untested') echo 'selected'; ?>>Untested</option>
											<option value="Passed" <?php if ($testCase['status'] === 'Passed') echo 'selected'; ?>>Passed</option>
											<option value="Failed" <?php if ($testCase['status'] === 'Failed') echo 'selected'; ?>>Failed</option>
											<option value="On Process" <?php if ($testCase['status'] === 'On Process') echo 'selected'; ?>>On Process</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="description">Description:</label>
										<textarea class="form-control" id="description" name="description"
											rows="3" readonly><?php echo $testCase['description']; ?></textarea>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="steps">Steps:</label>
										<textarea class="form-control" id="steps" name="steps"
											rows="3" readonly><?php echo $testCase['steps']; ?></textarea>
									</div>
								</div>
							</div>
                            <button type="submit" name="editTestCase" class="btn btn-info">Update Test Case</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'footer.php';?>
