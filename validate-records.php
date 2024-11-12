<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: LogIn.php");
    exit();
}

// Database connection settings
$host = 'localhost';
$db = 'dev_kalasan_db';
$user = 'root';
$pass = '';

try {
    // Establish the connection
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL query to fetch tree records
    $sql = "SELECT 
                tree_planted.id AS planted_id,
                tree_planted.latitude,
                tree_planted.longitude,
                tree_planted.date_time,
                tree_planted.address,
                tree_planted.image_path,
                tree_planted.validated,
                tree_planted.scientific_name,
                tree_planted.species_name,
                tree_planted.description,
                tree_planted.category
            FROM tree_planted";
    
    // Execute the query
    $stmt = $pdo->query($sql);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tree Record</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/paper-dashboard.css" rel="stylesheet">
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f4f4f4; }
        img { width: 100px; height: auto; }
        button { padding: 5px 10px; }
    </style>
    <script>
        function reviewRecord(recordId) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "Validate-record-action.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById("status_" + recordId).innerText = "Reviewed";
                    document.getElementById("button_" + recordId).disabled = true;
                }
            };

            xhr.send("planted_id=" + recordId);
        }
    </script>
</head>
<body>
    <!-- Main Panel -->
    <div class="main-panel">
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
          <li>
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
              <i class="fa fa-tasks"></i>
              <p>Manage Records</p>
            </a>
          </li>
          <li>
            <a href="./tree-species-form.php">
              <i class="fa fa-tree"></i>
              <p>Tree Species</p>
            </a>
          </li>
          <li>
            <a href="./contributors-datatable.php">
              <i class="fa fa-users"></i>
              <p>Manage User</p>
            </a>
          </li>
          <li class="active">
            <a href="./validate-records.php">
              <i class="fa fa-clipboard"></i>
              <p>Tree Records</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
        <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <a class="navbar-brand" href="javascript:;">Tree Records</a>
                </div>
                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: black;">
                                <i class="nc-icon nc-single-02"></i>
                                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="profile.php">View Profile</a>
                                <a class="dropdown-item" href="settings.php">Settings</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Table Content -->
        <div class="container mt-5 pt-5">
            <h2>Tree Records and Species Information</h2>
            <table>
                <tr>
                    <th>Record ID</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Date & Time</th>
                    <th>Address</th>
                    <th>Image</th>
                    <th>Species Name</th>
                    <th>Description</th>
                    <th>Category</th>
                </tr>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['planted_id']) ?></td>
                        <td><?= htmlspecialchars($record['latitude']) ?></td>
                        <td><?= htmlspecialchars($record['longitude']) ?></td>
                        <td><?= htmlspecialchars($record['date_time']) ?></td>
                        <td><?= htmlspecialchars($record['address']) ?></td>
                        <td>
                            <?php if (!empty($record['image_path'])): ?>
                                <img src="<?= htmlspecialchars($record['image_path']) ?>" alt="Tree Image">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($record['species_name']) ?></td>
                        <td><?= htmlspecialchars($record['description']) ?></td>
                        <td><?= htmlspecialchars($record['category']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <!-- Required JavaScript for Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
     <!-- External JS files -->
  <script src="assets/js/core/jquery.min.js"></script>
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>
</body>
</html>
