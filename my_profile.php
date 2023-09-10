<?php
session_start();
$title = "My Profile";
// Include database connection 
include 'db_config.php';
include 'header.php';
include 'body.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if the user is not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Retrieve user profile data from the user_profile table
$profileQuery = "SELECT first_name, last_name, contact_number, date_of_birth FROM user_profile WHERE user_id = $user_id";
$profileResult = mysqli_query($conn, $profileQuery);

if ($profileResult) {
    $profileData = mysqli_fetch_assoc($profileResult);
} else {
    // Handle the query error
    $profileData = null;
}
?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
			</div>
			<div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h2 class="card-title">User Profile</h2>
                    </div>
                    <div class="card-body">
                        <?php if ($profileData): ?>
                            <p><strong>First Name:</strong> <?php echo $profileData['first_name']; ?></p>
                            <p><strong>Last Name:</strong> <?php echo $profileData['last_name']; ?></p>
                            <p><strong>Contact Number:</strong> <?php echo $profileData['contact_number']; ?></p>
                            <p><strong>Date of Birth:</strong> <?php echo $profileData['date_of_birth']; ?></p>
                        <?php else: ?>
                            <p>No profile data available.</p>
                        <?php endif; ?>
					</div>
					<div class="card-footer">
						<div  class="col-md-3">
							<a href='edit_profile.php'><button class='btn bg-info float-and text-white w-100'>Edit Profile</button>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
include 'footer.php';
?>
