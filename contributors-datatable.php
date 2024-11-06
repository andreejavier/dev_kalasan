<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Contributors List - Dashboard
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="./assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
  
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="./assets/demo/demo.css" rel="stylesheet" />
</head>

<body class="">
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
            <a href="./validate.html">
              <i class="nc-icon nc-bank"></i>
              <p>Validation Logs</p>
            </a>
          </li>
          <li>
            <a href="./map.php">
              <i class="nc-icon nc-pin-3"></i>
              <p>Maps</p>
            </a>
          </li>
          <li>
            <a href="./">
              <i class="nc-icon nc-cloud-upload-94"></i>
              <p>Manage Records</p>
            </a>
          </li>
          <li>
            <a href="./contributors_datatable.php">
              <i class="nc-icon nc-tile-56"></i>
              <p>Manage User</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
      <!-- End Navbar -->

       <!-- Main Panel -->
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">DataTable</a>
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
        <div class="row">
          <!-- PHP Code for Counting Contributors and Planted Trees -->
          <?php
            // Database connection details
            $servername = "localhost";  // Replace with your DB server
            $username = "root";         // Replace with your DB username
            $password = "";             // Replace with your DB password
            $dbname = "proj-kalasan_db";     // Replace with your DB name

            // Create a connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check the connection
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to count users with id (Contributors)
            $sql = "SELECT COUNT(*) AS contributor_count FROM users WHERE id IS NOT NULL";
            $result = $conn->query($sql);
            $contributor_count = 0;
            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $contributor_count = $row['contributor_count'];
            }

            // SQL query to count trees planted
            $sql = "SELECT COUNT(*) AS planted_tree FROM tree_records WHERE id IS NOT NULL";
            $result = $conn->query($sql);
            $planted_tree = 0;
            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $planted_tree = $row['planted_tree'];
            }

            // Close the connection
            $conn->close();
          ?>

         <!-- Dashboard Card for Contributors -->
<div class="col-lg-3 col-md-6 col-sm-6">
  <div class="card card-stats">
    <div class="card-body">
      <div class="row">
        <div class="col-5 col-md-4">
          <div class="icon-big text-center icon-warning">
            <i class="nc-icon nc-globe text-warning"></i>
          </div>
        </div>
        <div class="col-7 col-md-8">
          <p class="card-category">Contributors</p>
          <p class="card-title"><?php echo $contributor_count; ?></p>
        </div>
      </div>
    </div>
    <div class="button-footer">
      <button class="btn btn-primary" onclick="window.location.href='contributors_datatable.php';">
        <i class="fa fa-eye"></i> View List
      </button>
    </div>
  </div>
</div>

          <!-- Dashboard Card for Planted Trees -->
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-planet text-success"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <p class="card-category">Planted Trees</p>
                    <p class="card-title"><?php echo $planted_tree; ?></p>
                  </div>
                </div>
              </div>
              <div class="button-footer">
                   <button class="btn btn-primary" onclick="window.location.href='home.php';">
                       <i class="fa fa-eye"></i> View Analytics
                   </button>
              </div>
              </div>
            </div>
          </div>
        </div>

      <div class="content">
        <div class="row">
          <div class="col-md-12">
            <!-- Card for Contributors List -->
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
                    <!-- PHP Code to Fetch Data from the Database -->
                    <?php
                      // Database connection
                      $servername = "localhost";
                      $username = "root";
                      $password = "";
                      $dbname = "proj-kalasan_db";
                      $conn = new mysqli($servername, $username, $password, $dbname);
                      if ($conn->connect_error) {
                          die("Connection failed: " . $conn->connect_error);
                      }

                      // SQL query to fetch contributors' data
                      $sql = "SELECT id, username, email, created_at, profile_picture FROM users";
                      $result = $conn->query($sql);

                      if ($result->num_rows > 0) {
                          while($row = $result->fetch_assoc()) {
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
      </div>
      
      <footer class="footer" style="position: absolute; bottom: 0; width: 100%;">
        <div class="container-fluid">
          <div class="row">
            <nav class="footer-nav">
              <ul>
                <li><a href="https://www.creative-tim.com" target="_blank">Creative Tim</a></li>
                <li><a href="https://www.creative-tim.com/blog" target="_blank">Blog</a></li>
                <li><a href="https://www.creative-tim.com/license" target="_blank">Licenses</a></li>
              </ul>
            </nav>
            <div class="credits ml-auto">
              <span class="copyright">
                © 2020, made with <i class="fa fa-heart heart"></i> by Creative Tim
              </span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  
  <!-- Core JS Files -->
  <script src="./assets/js/core/jquery.min.js"></script>
  <script src="./assets/js/core/popper.min.js"></script>
  <script src="./assets/js/core/bootstrap.min.js"></script>
  <script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

  <!-- DataTables Initialization Script -->
  <script>
    $(document).ready(function() {
      $('#contributorsTable').DataTable({
        "paging": true,
        "searching": true,
        "ordering": true,
        "columnDefs": [
          { "orderable": false, "targets": 4 }
        ],
        "lengthMenu": [5, 10, 25],
        "pageLength": 5,
        "language": {
          "search": "Filter records:",
          "lengthMenu": "Show _MENU_ contributors per page",
          "info": "Showing _START_ to _END_ of _TOTAL_ contributors"
        }
      });
    });
  </script>
  
</body>

</html>
