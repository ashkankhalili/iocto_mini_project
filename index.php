<?php
session_start();

// Include database connection 
include 'db_config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

// Check the user's role
$user_id = $_SESSION['user_id'];
$sql = "SELECT user_role, username FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
$row = mysqli_fetch_assoc($result);
$user_role = $row['user_role']; //here we get the user role
$user_name = $row['username']; // Here we get the username

$title = "Dashboard"; //set title of the page.
//include theme parts.
include 'header.php';
include 'body.php';

// Display content based on the user's role
if ($user_role === 'admin') {
    // Display elements for admins
    echo '
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div class="card-header">
							<h2 class="card-title">';echo "<h1>Welcome, " . $user_name . "</h2>"; 
							echo "<p style=color:#fff> The system summary is as below";
					echo ' </div> 
					</div>
				</div>
			</div>';
						
							// Fetch and display the list of users
							$userListQuery = "SELECT id, username, user_role, email,first_name, last_name, reg_date FROM users";
							$userListResult = mysqli_query($conn, $userListQuery);

							if (mysqli_num_rows($userListResult) > 0) {
								echo '
									<div class="row">
										<div class="col-md-4">
											<div class="card card-info">
												<div class="card-header">';
													echo "<h2>Users</h2>";
											echo "</div>";
										echo '<div class="card-body">';
										echo '<table class="table table-bordered">';
										echo '
											  <tr>
												<th>Username</th>
												<th>Role</th>
												<th>Name</th>
												<th>Registration Date</th>
											  </tr>';

										while ($userRow = mysqli_fetch_assoc($userListResult)) {
											echo "<tr>";
											echo "<td>" . $userRow['username'] . "</td>";
											echo "<td>" . $userRow['user_role'] . "</td>";
											echo "<td>" . $userRow['first_name'] . " " . $userRow['last_name'] . "</td>";
											echo "<td>" . $userRow['reg_date'] . "</td>";
											echo "</tr>";
									}

									echo "</table>";
							} else {
								echo "No users found.";
							}
							
							
						echo '
						
					</div>
				</div>
			</div>';
			// Fetch and display the list of projects
							$userListQuery = "SELECT id, pr_name, pr_summary FROM projects";
							$userListResult = mysqli_query($conn, $userListQuery);

							if (mysqli_num_rows($userListResult) > 0) {
								echo '
									
										<div class="col-md-4">
											<div class="card card-info">
												<div class="card-header">';
													echo "<h2>Projects</h2>";
											echo "</div>";
										echo '<div class="card-body">';
										echo '<table class="table table-bordered">';
										echo '
											  <tr>
												<th>Id</th>
												<th>Project Name</th>
												<th>Project Summary</th>
												
											  </tr>';

										while ($userRow = mysqli_fetch_assoc($userListResult)) {
											echo "<tr>";
											echo "<td>" . $userRow['id'] . "</td>";
											echo "<td>" . $userRow['pr_name'] . "</td>";
											echo "<td>" . $userRow['pr_summary'] . "</td>";
											
											echo "</tr>";
									}

									echo "</table>";
							} else {
								echo "No project found.";
							}
						echo '
						
					</div>
				</div>
			</div>';	
			// Fetch and display the list of Test Cases
							$userListQuery = "SELECT tc.id, p.pr_name AS project_name, tc.name, tc.priority, tc.description, tc.steps, tc.status, tc.created_at
												FROM test_cases tc
												INNER JOIN projects p ON tc.project_id = p.id";
							$userListResult = mysqli_query($conn, $userListQuery);

							if (mysqli_num_rows($userListResult) > 0) {
								echo '
									
										<div class="col-md-4">
											<div class="card card-info">
												<div class="card-header">';
													echo "<h2>Test Cases</h2>";
											echo "</div>";
										echo '<div class="card-body">';
										echo '<table class="table table-bordered">';
										echo '
											  <tr>
												<th>Test Name</th>
												<th>Related Project</th>
												<th>Priority</th>
												<th>status</th>
												
											  </tr>';

										while ($userRow = mysqli_fetch_assoc($userListResult)) {
											echo "<tr>";
											echo "<td>" . $userRow['name'] . "</td>";
											echo "<td>" . $userRow['project_name'] . "</td>";
											echo "<td>" . $userRow['priority'] . "</td>";
											echo "<td>" . $userRow['status'] . "</td>";
											
											echo "</tr>";
									}

									echo "</table>";
							} else {
								echo "No project found.";
							}
						echo '
						
					</div>
				</div>
			</div>';
			echo '
		</div>
	</section>'	;
    
} else {
    // Display elements for user
   echo '
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-success">
						<div class="card-header">
							<h2 class="card-title">';echo "<h1>Welcome, " . $user_name . "</h2>"; 
					echo ' </div> 
					</div>
				</div>
			</div>';
	echo '</div>';
}

// Include the footer
include 'footer.php';
?>
