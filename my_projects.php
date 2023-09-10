<?php
session_start();
$title = "My Projects";
// Include database connection 
include 'db_config.php';
include 'header.php';
include 'body.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if the user is not logged in
    exit();
}

// Fetch the projects assigned to the current user from the database based on their user ID
$user_id = $_SESSION['user_id'];

$projectsQuery = "SELECT projects.* FROM projects
                  INNER JOIN project_users ON projects.id = project_users.project_id
                  WHERE project_users.user_id = $user_id";

$projectsResult = mysqli_query($conn, $projectsQuery);
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php if (mysqli_num_rows($projectsResult) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($projectsResult)): ?>
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h2 class="card-title"><?php echo $row['pr_name']; ?></h2>
                            </div>
                            <div class="card-body">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h3 class="card-title">Project Description</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body" style="display: block;">
                                        <?php echo $row['pr_summary']; ?>
                                    </div>
                                </div>
                                <div class="card card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Test Case Details</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body" style="display: block;">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Test Case Name</th>
                                                <th>Priority</th>
                                                <th>Description</th>
                                                <th>Steps</th>
                                                <th>Status</th>
                                                <th>Operation</th>
                                            </tr>
                                            <?php
                                            $project_id = $row['id'];
                                            $testCasesQuery = "SELECT * FROM test_cases WHERE project_id = $project_id";
                                            $testCasesResult = mysqli_query($conn, $testCasesQuery);
                                            while ($testCase = mysqli_fetch_assoc($testCasesResult)):
                                            ?>
                                                <tr>
                                                    <td><?php echo $testCase['name']; ?></td>
                                                    <td><?php echo $testCase['priority']; ?></td>
                                                    <td><?php echo $testCase['description']; ?></td>
                                                    <td><?php echo $testCase['steps']; ?></td>
                                                    <td><?php echo $testCase['status']; ?></td>
                                                    <td>
                                                        <a href="update_test_status.php?id=<?php echo $testCase['id']; ?>" class="btn btn-primary">Edit</a>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <p style="color:red;Font-size:25px;">No projects assigned to you.</p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include 'footer.php'; ?>
