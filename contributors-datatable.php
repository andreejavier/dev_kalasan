<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: LogIn.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dev_kalasan_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch contributor count
$sql = "SELECT COUNT(*) AS contributor_count FROM users";
$result = $conn->query($sql);
$contributor_count = ($result->num_rows > 0) ? $result->fetch_assoc()['contributor_count'] : 0;

// Fetch planted tree count
$planted_tree_sql = "SELECT COUNT(*) AS planted_tree FROM tree_planted";
$tree_result = $conn->query($planted_tree_sql);
$planted_tree = ($tree_result->num_rows > 0) ? $tree_result->fetch_assoc()['planted_tree'] : 0;

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Contributors and Admins - Dashboard</title>
  <meta name='viewport' content='width=device-width, initial-scale=1.0' />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />
  <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="./assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>

<body>
<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar" data-color="white" data-active-color="danger">
      <div class="logo">
        <a href="#" class="simple-text logo-mini">
          <img src="assets/img/pngtree-banyan-tree-logo-design-vector-png-image_6131481.png" alt="Logo">
        </a>
        <a href="#" class="simple-text logo-normal">Kalasan</a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="active">
            <a href="./home.php">
              <i class="nc-icon nc-bank"></i>
              <p>Home</p>
            </a>
          </li>
          <li>
            <a href="./map.php">
              <i class="nc-icon nc-pin-3"></i>
              <p>Maps</p>
            </a>
          </li>
          <li>
            <a href="./manage-record.php">
              <i class="nc-icon nc-cloud-upload-94"></i>
              <p>Manage Records</p>
            </a>
          </li>
          <li>
            <a href="./tree-species-form.php">
              <i class="nc-icon nc-cloud-upload-94"></i>
              <p>Tree Species</p>
            </a>
          </li>
          <li>
            <a href="./contributors-datatable.php">
              <i class="nc-icon nc-tile-56"></i>
              <p>Manage User</p>
            </a>
          </li>
          <li>
            <a href="./validate-records.php">
              <i class="nc-icon nc-tile-56"></i>
              <p>Rree Records</p>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Main Panel -->
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Contributors and Admins</a>
          </div>
          <div class="collapse navbar-collapse justify-content-end">
            <form>
              <div class="input-group no-border">
                <input type="text" class="form-control" placeholder="Search...">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <i class="nc-icon nc-zoom-split"></i>
                  </div>
                </div>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="profile.php">
                  <i class="nc-icon nc-single-02"></i>
                  <p>Profile</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logout.php">
                  <i class="nc-icon nc-button-power"></i>
                  <p>Logout</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>

      <!-- Main content -->
      <div class="content">
        <!-- Contributors List -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Contributors List</h5>
              </div>
              <div class="card-body">
                <table id="contributorsTable" class="display" style="width:100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Username</th>
                      <th>Email</th>
                      <th>Date Joined</th>
                      <th>Profile Picture</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, username, email, created_at, profile_picture FROM users";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["username"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["created_at"] . "</td>";
                            echo "<td><img src='" . $row["profile_picture"] . "' width='50' height='50' alt='Profile Picture'></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No contributors found</td></tr>";
                    }

                    $conn->close();
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Admin List -->
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Admin List</h5>
              </div>
              <div class="card-body">
                <table id="adminTable" class="display" style="width:100%">
                  <thead>
                    <tr>
                      <th>Admin ID</th>
                      <th>Name</th>
                      <th>Username</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th>Date Created</th>
                      <th>Profile Picture</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $admin_sql = "SELECT admin_id, admin_name, username, email, status, created_at, profile_picture FROM admin";
                    $admin_result = $conn->query($admin_sql);

                    if ($admin_result->num_rows > 0) {
                        while ($admin_row = $admin_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $admin_row["admin_id"] . "</td>";
                            echo "<td>" . $admin_row["admin_name"] . "</td>";
                            echo "<td>" . $admin_row["username"] . "</td>";
                            echo "<td>" . $admin_row["email"] . "</td>";
                            echo "<td>" . ucfirst($admin_row["status"]) . "</td>";
                            echo "<td>" . $admin_row["created_at"] . "</td>";
                            echo "<td><img src='" . $admin_row["profile_picture"] . "'
width='50' height='50' alt='Admin Profile Picture'></td>"; echo "</tr>"; } } else { echo "<tr><td colspan='7'>No admins found</td></tr>"; }

                $conn->close();
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="footer" style="position: absolute; bottom: 0; width: 100%;">
    <div class="container-fluid">
      <div class="row">
        <div class="credits ml-auto">
          <span>&copy; 2024 Kalasan Project</span>
        </div>
      </div>
    </div>
  </footer>
</div>
</div> <!-- JS Files --> <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> <script> $(document).ready(function () { $('#contributorsTable').DataTable(); $('#adminTable').DataTable(); }); </script> </body> </html> ```