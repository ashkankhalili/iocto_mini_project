<?php
session_start();
$title = "Edit Profile";
// Include database connection
include 'db_config.php';

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

// Handle form submissions to edit the user's profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['editProfile'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $contact_number = $_POST['contact_number'];
        $date_of_birth = $_POST['date_of_birth'];

        // Update the user's profile in the user_profile table
        $updateProfileSql = "UPDATE user_profile
                             SET first_name = '$first_name', last_name = '$last_name', contact_number = '$contact_number', date_of_birth = '$date_of_birth'
                             WHERE user_id = $user_id";

        if (mysqli_query($conn, $updateProfileSql)) {
            // Profile updated successfully
            header("Location: my_profile.php"); // Redirect to the user's profile page
            exit();
        } else {
            // Error occurred during update
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
//include theme parts
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
                        <h2 class="card-title">Edit Profile</h2>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name"
                                    value="<?php echo $profileData['first_name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name"
                                    value="<?php echo $profileData['last_name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Contact Number:</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number"
                                    value="<?php echo $profileData['contact_number']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth:</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                    value="<?php echo $profileData['date_of_birth']; ?>">
                            </div>
                            <button type="submit" name="editProfile" class="btn btn-info">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
//include footer
include 'footer.php';
?>
