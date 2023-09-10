<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to the dashboard if logged in
    exit();
}
//set page title 
$title = "Registration";
// Include Header for CSS/JS files
include 'header.php';

// Include database connection configuration
include 'db_config.php';

// Declaring message variable
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $registrationTimestamp = date("Y-m-d H:i:s"); // Get the current timestamp

    // Check if the email address is a valid email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address. Please enter a valid email.";
    } else {
        // Check if the username contains only allowed characters (., _, -)
        if (!preg_match('/^[A-Za-z0-9\.\-\_]+$/', $username)) {
            $message = "<p style='padding-top:25px;text-align:center'>Invalid username. </br>Username can only contain</br> letters, numbers, '.', '_', or '-'.</p>";
        } else {
            // Check if the email or username already exists in the database
            $checkEmailQuery = "SELECT * FROM users WHERE email='$email' OR username='$username'";
            $result = mysqli_query($conn, $checkEmailQuery);

            if (mysqli_num_rows($result) > 0) {
                $message = "Email address or username is already in use. Please choose a different email or username.";
            } else {
                // Insert user data into the users table
                $sql = "INSERT INTO users (username, email, password, reg_date, first_name, last_name) VALUES ('$username', '$email', '$password', '$registrationTimestamp', '$firstName', '$lastName')";

                if (mysqli_query($conn, $sql)) {
                    // User registration successful
                    $user_id = mysqli_insert_id($conn); // Get the user's ID from the inserted record

                    // Insert user data into the user_profile table
                    $insertProfileQuery = "INSERT INTO user_profile (user_id, first_name, last_name) VALUES ('$user_id', '$firstName', '$lastName')";

                    if (mysqli_query($conn, $insertProfileQuery)) {
                        // User profile data insetred successfully
                        $message = "Registration successful.";
                        $message .= "<br/><a href='login.php'><button class='btn bg-secondary text-white w-100'>Login Here</button></a>";
                    } else {
                        // Error occurred during user profile data insertion
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    // Error occurred during user registration
                    echo "Error: " . mysqli_error($conn);
                }

                mysqli_close($conn);
            }
        }
    }
}
?>

<div id="reg_login">
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <div class="card p-5 text-light bg-dark mb-5">
            <div class="card-header">
                <h3>Register</h3>
            </div>
            <div class="card-body w-100">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="input-group form-group mt-3">
                        <div class="bg-secondary rounded-start">
                            <span class="m-3"><i class="fas fa-user mt-2"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="First Name" name="first_name">
                    </div>
                    <div class="input-group form-group mt-3">
                        <div class="bg-secondary rounded-start">
                            <span class="m-3"><i class="fas fa-user mt-2"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Last Name" name="last_name">
                    </div>
                    <div class="input-group form-group mt-3">
                        <div class="bg-secondary rounded-start">
                            <span class="m-3"><i class="fas fa-user mt-2"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Username" name="username" required>
                    </div>
                    <div class="input-group form-group mt-3">
                        <div class="bg-secondary rounded-start">
                            <span class="m-3"><i class="fas fa-envelope mt-2"></i></span>
                        </div>
                        <input type="email" class="form-control" placeholder="Email" name="email" required>
                    </div>
                    <div class="input-group form-group mt-3">
                        <div class="bg-secondary rounded-start">
                            <span class="m-3"><i class="fas fa-key mt-2"></i></span>
                        </div>
                        <input type="password" class="form-control" placeholder="Password" name="password" required>
                    </div>
                    <div class="form-group mt-3">
                        <input type="submit" value="Register" class="btn bg-secondary float-end text-white w-100" name="login-btn">
                    </div>
                </form>
                <?php if (!empty($message)): ?>
                    <div class="text-danger"><?php echo $message; ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
