<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Include the database connection
include 'config/db_connection.php';

// Fetch the user ID from the session
$user_id = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kalasan - List of Trees</title>
  <!-- External CSS files -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
  <link href="assets/css/custom-dashboard.css" rel="stylesheet" />
</head>

<body class="">
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar" data-color="white" data-active-color="danger">
      <div class="logo">
        <a href="./" class="simple-text logo-mini">
          <div class="logo-image-small">
            <img src="assets/img/pngtree-banyan-tree-logo-design-vector-png-image_6131481.png" alt="Logo">
          </div>
        </a>
        <a href="#" class="simple-text logo-normal">Kalasan</a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav"> 
          <li><a href="./Home.php"><i class="nc-icon nc-bank"></i><p>Home</p></a></li>
          <li><a href="./map.php"><i class="nc-icon nc-pin-3"></i><p>Maps</p></a></li>
          <li><a href="./upload-plant.php"><i class="nc-icon nc-cloud-upload-94"></i><p>Plant</p></a></li>
          <li class="active"><a href="./stats.php"><i class="nc-icon nc-tile-56"></i><p>List</p></a></li>
        </ul>
      </div>
    </div>

    <!-- Main Panel -->
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:;">List Of Trees</a>
          </div>
        </div>
      </nav>

      <!-- Main content -->
      <div class="content">
        <div class="row">
          <!-- JS to fetch and display plant data -->
          <script>
            document.addEventListener('DOMContentLoaded', function() {
              fetch('config/get_plant_data.php')
                .then(response => response.json())
                .then(plants => {
                  document.getElementById('plantsPlantedCount').textContent = plants.length;

                  const plantsContainer = document.getElementById('plantsContainer');
                  const fragment = document.createDocumentFragment();

                  plants.forEach(plant => {
                    const plantCard = document.createElement('div');
                    plantCard.className = 'card stats-box2-sub'; 

                    const locationAddress = plant.location_address ? plant.location_address : 'Address not available';

                    plantCard.innerHTML = `
                      <div class="card-header">
                        <h5 class="card-title">Planted on ${plant.date_time}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">${locationAddress}</h6>
                      </div>
                      <div class="card-body">
                        <img src="${plant.image}" alt="Plant Image" onerror="this.onerror=null; this.src='assets/img/default-plant.jpg';">
                        <p class="card-text">Latitude: ${plant.latitude}, Longitude: ${plant.longitude}</p>
                      </div>
                    `;
                    fragment.appendChild(plantCard);
                  });

                  plantsContainer.appendChild(fragment);
                })
                .catch(error => console.error('Error fetching plant data:', error));
            });
          </script>

          <!-- Cards to display plant information -->
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-money-coins text-success"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Plants Uploaded</p>
                      <p class="card-title" id="plantsPlantedCount">0</p> <!-- Updated via JS -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="plantsContainer" class="stats-box2"></div>
        </div>
      </div>

      <!-- Footer -->
      <footer class="footer">
        <div class="container-fluid">
          <div class="copyright">
            &copy; 2024 <a href="">Kalasan Team</a>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <!-- External JS files -->
  <script src="assets/js/core/jquery.min.js"></script>
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>

</body>
</html>
