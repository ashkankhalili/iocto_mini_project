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

// Handle deletion when Delete button is clicked
    if (isset($_POST['deleteTestCase'])) {
        $delete_id = $_POST['delete_id'];
        $confirmDelete = $_POST['confirm_delete'];

        if ($confirmDelete === 'yes') {
            // Perform the deletion based on $delete_id
            $deleteSql = "DELETE FROM test_cases WHERE id = '$delete_id'";

            if (mysqli_query($conn, $deleteSql)) {
                // Test case deleted successfully
                header("Location: list_test_cases.php"); // Redirect to the test cases page
                exit();
            } else {
                // Error occurred during deletion
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }


// Retrieve a list of projects for assigning test cases
$projectsSql = "SELECT id, pr_name FROM projects";
$projectsResult = mysqli_query($conn, $projectsSql);

// Retrieve a list of existing test cases
$testCasesSql = "SELECT tc.id, p.pr_name AS project_name, tc.name, tc.priority, tc.description, tc.steps, tc.status, tc.created_at
                 FROM test_cases tc
                 INNER JOIN projects p ON tc.project_id = p.id";
$testCasesResult = mysqli_query($conn, $testCasesSql);

$title = "List Test Cases";
include 'header.php';
include 'body.php';
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
			<div class="col-md-12">
				<div class="card card-success">
					<div class="card-header">
						<h2 class="card-title">Existing Test Cases</h2>
					</div>
					<div class="card-body">
						<!-- List of Existing Test Cases -->
						<h3>Details</h3>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Test Name</th>
									<th>Related Project</th>
									<th>Priority</th>
									<th>Description</th>
									<th>Steps</th>
									<th>Status</th>
									<th>Created at</th>
									<th>Action</th> 
								</tr>
							</thead>
							<tbody>
								<?php while ($row = mysqli_fetch_assoc($testCasesResult)): ?>
									<tr>
										<td><?php echo $row['name']; ?></td>
										<td><?php echo $row['project_name']; ?></td>
										<td><?php echo $row['priority']; ?></td>
										<td><?php echo $row['description']; ?></td>
										<td><?php echo $row['steps']; ?></td>
										<td><?php echo $row['status']; ?></td>
										<td><?php echo $row['created_at']; ?></td>
										<td>
											<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $row['id']; ?>">Delete</button>
											<a href="edit_test_case.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
										</td>
									</tr>
									<div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<p>Are you sure you want to delete this test case?</p>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
													<form method="post">
														<input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
														<input type="hidden" name="confirm_delete" value="yes">
														<button type="submit" name="deleteTestCase" class="btn btn-danger">Delete</button>
													</form>
												</div>
											</div>
										</div>
									</div>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php include 'footer.php';?>

