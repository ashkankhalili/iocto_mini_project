<?php
session_start();
// Set Title of the page 
$title = "Logout";
// Include Page Parts
include 'header.php';
include 'body.php';
// Set Title of the page 
$title = "Logout";
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

?>
<div class="container mt-6">
	<div class="col-md-12">
		<section class="content">
			<div class="container-fluid">
                <div class="row">
					<section>
						<div class="card">
							<div class="card-body">
							<h1>Logout Confirmation</h1>
							<p>Please confirm if you want to logout:</p>
							<button class="btn btn-danger" onclick="confirmLogout()">Logout</button> 
							<!-- if user clicks on Logout button then will be redirected to logout_handler using javascript added in header.php-->
							<a class="btn btn-success" href="index.php">Cancel</a>
							</div>
						</div>
					</section>
				</div>
			</div>
		</section>
	</div>
</div>
<?php include 'footer.php';?>


