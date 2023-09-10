<?php
session_start();
$title = "Add User";
// Include database connection configuration
include 'db_config.php';
//include theme parts
include 'header.php';
include 'body.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php"); // Redirect to index.php if not an admin
    exit();
}

// Declare a message variable for action result
$message = '';

// Handle form submissions to add a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addUser'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user_role = $_POST['user_role'];
        $first_name = $_POST['first_name']; 
        $last_name = $_POST['last_name']; 
		$registrationTimestamp = date("Y-m-d H:i:s");

        // Check if the username or email already exists
        $checkQuery = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // If Username or email already exists
            $message = "Username or email already registered.";
        } else {
            // username contains only allowed characters (., _, -)
            if (!preg_match('/^[A-Za-z0-9\.\-\_]+$/', $username)) {
                $message = "Invalid username. Username can only contain letters, numbers, '.', '_', or '-'.";
            } else {
                // Insert a new user into the database
                $insertQuery = "INSERT INTO users (username, email, password, user_role, first_name, last_name, reg_date) VALUES ('$username', '$email', '$password', '$user_role', '$first_name', '$last_name', '$registrationTimestamp')";

                if (mysqli_query($conn, $insertQuery)) {
                    // Get the user ID
                    $user_id = mysqli_insert_id($conn);

                    // Insert the user profile data into the user_profile table
                    $insertProfileQuery = "INSERT INTO user_profile (user_id, first_name, last_name) VALUES ('$user_id', '$first_name', '$last_name')";

                    if (mysqli_query($conn, $insertProfileQuery)) {
                        // User and profile added successfully
                        $message = "User created successfully.";
                    } else {
                        // Error occurred during user profile insertion
                        $message = "Error: " . mysqli_error($conn);
                    }
                } else {
                    // Error occurred during user insertion
                    $message = "Error: " . mysqli_error($conn);
                }
            }
        }

        mysqli_close($conn);
    }
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
                        <h2 class="card-title">Add User</h2>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email address:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name:</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                            <div class="form-group">
                                <label for="user_role">User Role:</label>
                                <select class="form-control" id="user_role" name="user_role" required>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" name="addUser" class="btn btn-info">Add User</button>
                            <!-- Display a message if user creation was successful or if there was an error -->
                            <?php if (!empty($message)): ?>
                                <p style="color:red; font-weight:bold;"><?php echo $message; ?></p>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>
