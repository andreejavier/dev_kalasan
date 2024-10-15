<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db_connection.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = trim($_POST['admin_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $date_assigned = $_POST['date_assigned'];
    $status = 'active'; // Default status is active

    // Validate that passwords match
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT admin_id FROM `admin` WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Username or email already taken.";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare to insert the new admin record
            $stmt = $conn->prepare("INSERT INTO `admin` (admin_name, username, email, password_hash, status, date_assigned) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $admin_name, $username, $email, $hashedPassword, $status, $date_assigned);

            if ($stmt->execute()) {
                $success = "Admin account created successfully. <a href='LogIn.php'>Login here</a>";
            } else {
                $error = "Error creating admin account: " . $conn->error;
            }
        }
        $stmt->close();
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
    <title>Kalasan Admin Registration</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">

    <!-- CSS Files -->
    <link href="assettss/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assettss/css/paper-kit.css?v=2.2.0" rel="stylesheet" />

    <!-- Demo CSS -->
    <link href="assettss/demo/demo.css" rel="stylesheet" />
</head>

<body class="register-page sidebar-collapse">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-transparent " color-on-scroll="300">
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
                        <h3 class="title mx-auto">Admin Registration</h3>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        <form class="register-form" action="register-page.php" method="POST">
                            <label>Full Name</label>
                            <input type="text" name="admin_name" class="form-control" placeholder="Full Name" required>

                            <label>Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Username" required>

                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>

                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" required>

                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>

                            <label>Date Assigned</label>
                            <input type="date" name="date_assigned" class="form-control" required>

                            <button type="submit" class="btn btn-danger btn-block btn-round">Register</button>
                        </form>
                        <div class="forgot">
                            <a href="http://localhost/dev_kalasan/LogIn.php" class="btn btn-link btn-danger">Log In</a>
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
