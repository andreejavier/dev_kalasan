<?php
session_start();
include 'config/db_connection.php'; // Ensure this points to your DB connection file

// Check if id is set
if (!isset($_GET['id'])) {
    header("Location: home.php");
    exit();
}

$tree_id = $_GET['id'];
$admin_id = $_SESSION['admin_id']; // Assuming admin is logged in and admin_id is stored in the session

// Fetch tree planting details
$sql = "SELECT t.*, u.username, u.profile_picture, COUNT(t2.id) AS observations_count
        FROM `tree_planted` t
        JOIN `users` u ON t.user_id = u.id
        LEFT JOIN `tree_planted` t2 ON t2.user_id = u.id
        WHERE t.id = ? 
        GROUP BY u.id";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Query preparation failed: ' . $conn->error);
}
$stmt->bind_param("i", $tree_id);
$stmt->execute();
$result = $stmt->get_result();
$tree = $result->fetch_assoc();
$stmt->close();

// Fetch all reviews for this tree planting
$reviews_sql = "SELECT r.*, a.admin_name FROM `reviews` r JOIN `admin` a ON r.review_by = a.admin_id WHERE r.tree_planted_id = ?";
$stmt = $conn->prepare($reviews_sql);
$stmt->bind_param("i", $tree_id);
$stmt->execute();
$reviews_result = $stmt->get_result();
$stmt->close();

// Handle review submission by admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $status = $_POST['status'];
    $comments = $_POST['comments'];
    
    $insert_review_sql = "INSERT INTO `reviews` (tree_planted_id, review_by, status, comments) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_review_sql);
    $stmt->bind_param("iiss", $tree_id, $admin_id, $status, $comments);
    
    if ($stmt->execute()) {
        header("Location: view-tree.php?id=$tree_id");
        exit();
    } else {
        echo "Error submitting review: " . $conn->error;
    }
    
    $stmt->close();
}

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Tree Details</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <!-- CSS Files -->
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
    <!-- CSS for Leaflet Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    
    <style>
        #map { height: 250px; width: 100%; margin-top: 20px; border: 1px solid #ddd; }
        .tree-image { width: 100%; height: auto; }
        .species-details { font-style: italic; color: #555; }
        .user-info { display: flex; align-items: center; gap: 10px; margin-top: 20px; }
        .user-info img { border-radius: 50%; width: 40px; height: 40px; }
        .review-form { margin-top: 30px; }
        .review-item { border-bottom: 1px solid #ddd; padding: 10px 0; }
        .review-item:last-child { border-bottom: none; }
    </style>
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
              <p>tree Species</p>
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
              <p>Validate Records</p>
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;">Tree Details</a>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->

      <!-- Main Content -->
      <div class="content">
        <div class="container">
          <div class="header-section">
            <div>
              <h2><?php echo htmlspecialchars($tree['species_name']); ?></h2>
              <p class="species-details">(<?php echo htmlspecialchars($tree['scientific_name']); ?>)</p>
            </div>
          </div>

          <!-- Display Tree and User Information -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="row">
              <div class="col-md-6">
  <?php if (!empty($tree['image_path'])): ?>
      <img src="<?php echo htmlspecialchars($tree['image_path']); ?>" alt="Tree Image" class="tree-image" id="treeImage" onclick="openFullscreen();">
  <?php else: ?>
      <p>No image available.</p>
  <?php endif; ?>
</div>

                <div class="col-md-6">
                  <div class="user-info mt-4">
                    <img src="<?php echo !empty($tree['profile_picture']) ? htmlspecialchars($tree['profile_picture']) : 'assets/img/user-avatar.png'; ?>" alt="User Avatar">
                    <div>
                      <p><strong>Uploaded by:</strong> <a href="#"><?php echo htmlspecialchars($tree['username']); ?></a></p>
                    </div>
                  </div>
                  <div id="map"></div>
                  <div class="map-details">
                    <p><strong>Location Map:</strong> <?php echo htmlspecialchars($tree['address']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($tree['description']); ?></p>
                  </div>
                </div>
              </div>
              <div class="observation-details mt-3">
                <p><strong>Location:</strong> <?php echo htmlspecialchars($tree['address']); ?></p>
                <p><strong>Date Observed:</strong> <?php echo htmlspecialchars($tree['date_time']); ?></p>
              </div>
            </div>
          </div>

          <!-- Admin Review Form -->
          <div class="review-form">
            <h3>Leave a Review</h3>
            <form action="view-tree.php?id=<?php echo $tree_id; ?>" method="POST">
              <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control" id="status">
                  <option value="agree">Agree</option>
                  <option value="disagree">Disagree</option>
                </select>
              </div>
              <div class="form-group">
                <label for="comments">Comments</label>
                <textarea name="comments" id="comments" class="form-control" rows="4" placeholder="Leave your comments here..."></textarea>
              </div>
              <button type="submit" name="submit_review" class="btn btn-success">Submit Review</button>
            </form>
          </div>

          <!-- Display Existing Reviews -->
          <div class="reviews mt-5">
            <h3>Reviews</h3>
            <?php if ($reviews_result->num_rows > 0): ?>
                <?php while ($review = $reviews_result->fetch_assoc()): ?>
                    <div class="review-item">
                        <h4>Review by <?php echo htmlspecialchars($review['admin_name']); ?> - <small><?php echo htmlspecialchars($review['status']); ?></small></h4>
                        <p><?php echo htmlspecialchars($review['comments']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No reviews yet.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
      </div>
      </div>
    </div>
</div>

<script>
  // Get the image element
  var elem = document.getElementById("treeImage");

  // Function to open the image in full-screen mode
  function openFullscreen() {
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) { // Firefox
      elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) { // Chrome, Safari, Opera
      elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { // IE/Edge
      elem.msRequestFullscreen();
    }
  }
</script>


<!-- JS Scripts -->
<script src="./assets/js/core/jquery.min.js"></script>
<script src="./assets/js/core/popper.min.js"></script>
<script src="./assets/js/core/bootstrap.min.js"></script>

<!-- Leaflet Map JS -->
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
  var map = L.map('map').setView([<?php echo htmlspecialchars($tree['latitude']); ?>, <?php echo htmlspecialchars($tree['longitude']); ?>], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
  }).addTo(map);
  L.marker([<?php echo htmlspecialchars($tree['latitude']); ?>, <?php echo htmlspecialchars($tree['longitude']); ?>]).addTo(map);
</script>
</body>
</html>
