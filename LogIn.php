<?php
session_start();
include 'config/db_connection.php';  // Ensure this file contains your database connection details

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to prevent SQL injection
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username and password are required.";
    } else {
        // Prepare SQL query to check admin's credentials
        $stmt = $conn->prepare("SELECT admin_id, password_hash FROM `admin` WHERE username = ? AND status = 'active'");
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($admin_id, $password_hash);
                $stmt->fetch();

                // Verify password
                if (password_verify($password, $password_hash)) {
                    // Successful login
                    $_SESSION['admin_id'] = $admin_id;
                    $_SESSION['username'] = $username;

                    // Update last login time
                    $updateStmt = $conn->prepare("UPDATE `admin` SET last_login = NOW() WHERE admin_id = ?");
                    if ($updateStmt) {
                        $updateStmt->bind_param("i", $admin_id);
                        $updateStmt->execute();
                        $updateStmt->close();
                    }

                    // Redirect to admin dashboard
                    header("Location: home.php");
                    exit();
                } else {
                    $error = "Invalid password. Please try again.";
                }
            } else {
                $error = "Invalid username or account is inactive.";
            }
            $stmt->close();
        } else {
            $error = "Database error. Please try again later.";
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Kalasan Admin Login</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  
  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  
  <!-- CSS Files -->
  <link href="assettss/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assettss/css/paper-kit.css?v=2.2.0" rel="stylesheet" />
</head>

<body class="login-page sidebar-collapse">
  
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top navbar-transparent" color-on-scroll="300">
    <div class="container">
      <div class="navbar-translate">
        <a class="navbar-brand" href="#" rel="tooltip" title="Welcome to Kalasan Admin" data-placement="bottom">
          Kalasan Admin
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-bar bar1"></span>
          <span class="navbar-toggler-bar bar2"></span>
          <span class="navbar-toggler-bar bar3"></span>
        </button>
      </div>
      <div class="collapse navbar-collapse justify-content-end" id="navigation">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="./landing_page.php" class="nav-link"><i class="nc-icon nc-layout-11"></i> Home</a>
          </li>
          <li class="nav-item">
            <a href="about.html" class="nav-link"><i class="nc-icon nc-book-bookmark"></i> About</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->

  <!-- Page Header -->
  <div class="page-header" style="background-image: url('assets/img/login-image.jpg');">
    <div class="filter"></div>
    <div class="container">
      <div class="row">
        <div class="col-lg-4 ml-auto mr-auto">
          <div class="card card-register">
            <h3 class="title mx-auto">Log In</h3>
            <form class="register-form" action="LogIn.php" method="POST"> <!-- Fixed action URL -->
              <label>Username</label> <!-- Changed label to Username -->
              <input type="text" name="username" class="form-control" placeholder="Username" required>

              <label>Password</label>
              <input type="password" name="password" class="form-control" placeholder="Password" required>

              <?php if (!empty($error)): ?> <!-- Display error messages if any -->
                <div class="alert alert-danger"><?php echo $error; ?></div>
              <?php endif; ?>

              <button type="submit" class="btn btn-danger btn-block btn-round">Log In</button>
            </form>
            <div class="forgot">
              <a href="./register-page.php" class="btn btn-link btn-danger">Register</a> <!-- Corrected the link to registration -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="footer register-footer text-center">
      <h6>©
        <script>
          document.write(new Date().getFullYear())
        </script>, Northern Bukidnon State College
      </h6>
    </div>
  </div>

  <!-- Core JS Files -->
  <script src="assets/js/core/jquery.min.js" type="text/javascript"></script>
  <script src="assets/js/core/popper.min.js" type="text/javascript"></script>
  <script src="assets/js/core/bootstrap.min.js" type="text/javascript"></script>

  <!-- Plugins -->
  <script src="assets/js/plugins/bootstrap-switch.js"></script>
  <script src="assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
  <script src="assets/js/plugins/moment.min.js"></script>
  <script src="assets/js/plugins/bootstrap-datepicker.js"></script>

  <!-- Control Center -->
  <script src="assets/js/paper-kit.js?v=2.2.0" type="text/javascript"></script>
</body>

</html>
