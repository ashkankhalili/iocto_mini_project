<?php
// Start the session (if not already started to prevent Conflict)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Include database connection 
include 'db_config.php';

// Check if the user has an 'admin' role for future needs
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; // Set a default value if user_id is not set

$sql = "SELECT user_role FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $user_role = $row['user_role'];
    mysqli_free_result($result);
} else {
    // Handle the query error or set a default role
    $user_role = 'user'; // Set Defult to 'user' role if there's an error
}

$user_name = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest'; // Set a default value if username is not set
?>
<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="images/logo.svg" alt="AdminLTE Logo" class="brand-image elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ST-CMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="images/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo $user_name;?></a>
        </div>
      </div>
	<!-- Sidebar Menu (different Sidebare menu for different user role, -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
		<?php
		if ($user_role === 'admin') {
		echo '
			
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Admin Menu
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./users.php" class="nav-link">
                  <i class="nav-icon far fa-circle text-warning"></i>
                  <p>Users List</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./adduser.php" class="nav-link">
                  <i class="nav-icon far fa-circle text-warning"></i>
                  <p>Add User </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./projects.php" class="nav-link">
                  <i class="nav-icon far fa-circle text-warning"></i>
                  <p>Projects</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="./test_cases.php" class="nav-link">
                  <i class="nav-icon far fa-circle text-warning"></i>
                  <p>Create Test Case</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="./list_test_cases.php" class="nav-link">
                  <i class="nav-icon far fa-circle text-warning"></i>
                  <p>Test Cases List</p>
                </a>
              </li>
            </ul>
          </li>
		  <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Main Menu
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./my_projects.php" class="nav-link">
                  <i class="nav-icon fas fa-copy"></i>
                  <p>My Projects</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./my_profile.php" class="nav-link">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>My Profile</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="./edit_profile.php" class="nav-link">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>Edit Profile</p>
                </a>
              </li>';
		  }else { echo'	
		  
		  <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Main Menu
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./my_projects.php" class="nav-link">
                  <i class="nav-icon fas fa-copy"></i>
                  <p>My Projects</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./my_profile.php" class="nav-link">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>My Profile</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="./edit_profile.php" class="nav-link">
                  <i class="nav-icon fas fa-edit"></i>
                  <p>Edit Profile</p>
                </a>
              </li>
            </ul>
          </li>';}?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  
  
  
