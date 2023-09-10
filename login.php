<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Check the user's role and redirect accordingly
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: index.php"); // Redirect admin to index.php
        exit();
    } else {
        header("Location: my_projects.php"); // Redirect user to my_projects.php
        exit();
    }
}

$title = "Login";
// Include Header for CSS/JS files
include 'header.php';

// Include database connection configuration
include 'db_config.php';

// Initialize message variable
$message = "";

// User login logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $emailOrUsername = $_POST['email_or_username'];
    $password = $_POST['password'];

    // Retrieve user data from the database based on email or username
    $sql = "SELECT * FROM users WHERE email='$emailOrUsername' OR username='$emailOrUsername'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password is correct
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_role'] = $row['user_role']; // Set the user's role in the session

            // Redirect based on user role
            if ($_SESSION['user_role'] === 'admin') {
                header("Location: index.php"); // Redirect admin to index.php
                exit();
            } else {
                header("Location: my_projects.php"); // Redirect user to my_projects.php
                exit();
            }
        } else {
            // Password is incorrect
            $message = "Invalid password. Please try again.";
        }
    } else {
        // If User not found then show register button
        $message = "<p class='text-align:center'>User not found. <br/>Please check your email/username and try again.<br/> </p>";
        $message .= "<br/><a href='registration.php'><button class='btn bg-secondary float-and text-white w-100'>Register</button></a>";
    }
}

mysqli_close($conn);
?>
<div id="reg_login">
    <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
        <div class="card p-5 text-light bg-dark mb-5">
            <div class="card-header">
                <h3>Sign In</h3>
            </div>
            <div class="card-body w-100">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="input-group form-group mt-3">
                        <div class="bg-secondary rounded-start">
                            <span class="m-3"><i class="fas fa-user mt-2"></i></span>
                        </div>
                        <input type="text" class="form-control" placeholder="Email/Username" name="email_or_username" required>
                    </div>
                    <div class="input-group form-group mt-3">
                        <div class="bg-secondary rounded-start">
                            <span class="m-3"><i class="fas fa-key mt-2"></i></span>
                        </div>
                        <input type="password" class="form-control" placeholder="password" name="password" required>
                    </div>

                    <div class="form-group mt-3">
                        <input type="submit" name="login" value="Login" class="btn bg-secondary float-end text-white w-100" name="login-btn">
                    </div>
				</form>
                <?php if (!empty($message)): ?>
                    <div class="text-danger"><?php echo $message; ?></div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center">
                    <div class="text-primary">If you are a registered user, login here.</div>
                </div>
			</div>
		</div>
    </div>
</div>
</body>
</html>
