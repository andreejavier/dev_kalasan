<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: LogIn.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kalasan Dashboard & Analytics</title>
  <!-- External CSS -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/paper-dashboard.css" rel="stylesheet">
  <link href="assets/css/custom-dashboard.css" rel="stylesheet">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a href="./manage_record.php">
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

    <!-- Main Panel -->
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Dashboard & Analytics</a>
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
                <a class="nav-link" href="./logout.php">
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
            $dbname = "landong_db";     // Replace with your DB name

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
            $sql = "SELECT COUNT(*) AS planted_tree FROM newtree_species WHERE id IS NOT NULL";
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

        <div class="content">
        <div class="row">
          <div class="col-md-12">
            <h3 class="description">Analytics Overview</h3>

            <!-- Chart Container -->
            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Tree Species Data</h5>
              </div>
              <div class="card-body">
                <canvas id="treeSpeciesChart" width="400" height="150"></canvas>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h5 class="card-title">Uploads Over Time</h5>
              </div>
              <div class="card-body">
                <canvas id="uploadsChart" width="400" height="150"></canvas>
              </div>
            </div>

          </div>
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <div class="row">
            <div class="credits ml-auto">
              <span class="copyright">
                © 2024, Kalasan Project
              </span>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <!-- JavaScript to render charts -->
<script>
  // Fetch data from the backend
  fetch('config/fetch_data.php')
    .then(response => response.json())
    .then(data => {
      // Extract species data
      const speciesLabels = data.speciesData.map(item => item.address);
      const speciesCounts = data.speciesData.map(item => item.count);

      // Tree species chart
      const treeSpeciesCtx = document.getElementById('treeSpeciesChart').getContext('2d');
      const treeSpeciesChart = new Chart(treeSpeciesCtx, {
        type: 'bar',
        data: {
          labels: speciesLabels,
          datasets: [{
            label: '# of Trees',
            data: speciesCounts,
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });

      // Extract uploads data
      const uploadLabels = data.uploadsData.map(item => item.upload_date);
      const uploadCounts = data.uploadsData.map(item => item.count);

      // Uploads over time chart
      const uploadsCtx = document.getElementById('uploadsChart').getContext('2d');
      const uploadsChart = new Chart(uploadsCtx, {
        type: 'line',
        data: {
          labels: uploadLabels,
          datasets: [{
            label: '# of Uploads',
            data: uploadCounts,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    })
    .catch(error => console.error('Error fetching data:', error));
</script>
</body>
</html>
