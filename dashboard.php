<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Include the database connection
include 'config/db_connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tree species data from the database
$query = "SELECT * FROM tree_records";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error); // Add error handling
}

// Initialize the trees array and count variable
$trees = [];
$treeCount = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $trees[] = $row;
    }
    $treeCount = count($trees); // Count the number of trees
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalasan Mapping - Dashboard</title>

    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    
    <!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
    <link href="assets/css/custom-dashboard.css" rel="stylesheet" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        /* Add your styles here */
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div class="sidebar" data-color="white" data-active-color="danger">
            <div class="logo">
                <a href="#" class="simple-text logo-mini">
                    <img src="assets/img/logo-small.png">
                </a>
                <a href="#" class="simple-text logo-normal">Kalasan</a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li class="active">
                        <a href="dashboard.php">
                            <i class="nc-icon nc-bank"></i>
                            <p>Home</p>
                        </a>
                    </li>
                    <li>
                        <a href="./map.php">
                            <i class="nc-icon nc-pin-3"></i>
                            <p>Map</p>
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
                        <a class="navbar-brand" href="javascript:;">Home</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-end" id="navigation">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="nc-icon nc-single-02"></i>
                                    <span><?php echo $_SESSION['username']; ?></span>
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

            <!-- Main Content Section -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-content">
                                <header class="main-header clearfix">
                                    <div style="float: left;">
                                        <input type="text" placeholder="Species" />
                                        <input type="text" placeholder="Location" />
                                        <button>Go</button>
                                    </div>
                                    <div class="stats" style="float: right;">
                                        <div class="stat-item">Validated: <span>0</span></div>
                                        <div class="stat-item">Species: <span><?php echo count(array_unique(array_column($trees, 'species'))); ?></span></div>
                                        <div class="stat-item">Trees Planted: <span><?php echo $treeCount; ?></span></div>
                                    </div>
                                </header>

                                <div class="card clearfix">
                                    <div class="card-header">
                                        <h5 class="card-title">Tree Mapping</h5>
                                        <p class="card-category">Map of Planted Trees</p>
                                    </div>
                                    <div class="card-body clearfix">
                                        <div id="dashboard-map" class="map-container"></div>

                                        <div class="view-toggle">
                                            <button id="grid-view" class="btn btn-primary">Grid View</button>
                                            <button id="list-view" class="btn btn-secondary">List View</button>
                                        </div>

                                        <div class="tree-table-container">
                                            <div id="tree-display" class="tree-table">
                                                <?php if ($treeCount > 0): ?>
                                                    <table class="tree-table list-view">
                                                        <thead>
                                                            <tr>
                                                                <th>Image</th>
                                                                <th>Location</th>
                                                                <th>Date Time</th>
                                                                <th>Date Uploaded</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($trees as $tree): ?>
                                                                <tr>
                                                                    <td><img src="uploads/<?php echo htmlspecialchars($tree['image']); ?>" alt="<?php echo htmlspecialchars($tree['image']); ?>" style="width: 100px;"></td>
                                                                    <td><?php echo htmlspecialchars($tree['address']); ?></td>
                                                                    <td><?php echo htmlspecialchars($tree['date_time']); ?></td>
                                                                    <td><?php echo htmlspecialchars($tree['created_at']); ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                <?php else: ?>
                                                    <p>No trees found.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div id="tree-grid" class="tree-grid" style="display: none;">
                                            <?php if ($treeCount > 0): ?>
                                                <?php foreach ($trees as $tree): ?>
                                                    <div class="tree-grid-item">
                                                        <img src="uploads/<?php echo htmlspecialchars($tree['image']); ?>" alt="<?php echo htmlspecialchars($tree['image']); ?>" style="width: 100%;">
                                                        <h6><?php echo htmlspecialchars($tree['species']); ?></h6>
                                                        <p>Location: <?php echo htmlspecialchars($tree['address']); ?></p>
                                                        <p>Uploaded: <?php echo htmlspecialchars($tree['created_at']); ?></p>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p>No trees found.</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <nav>
                        <ul>
                            <li>
                                <a href="https://www.creative-tim.com">
                                    Creative Tim
                                </a>
                            </li>
                            <li>
                                <a href="https://blog.creative-tim.com">
                                    Blog
                                </a>
                            </li>
                            <li>
                                <a href="https://www.creative-tim.com/license">
                                    Licenses
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </footer>
        </div>
    </div>

    <!-- JS Files -->
    <script src="assets/js/core/jquery.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        // Initialize map
        var map = L.map('dashboard-map').setView([<?php echo isset($trees[0]) ? $trees[0]['latitude'] : '7.8706'; ?>, <?php echo isset($trees[0]) ? $trees[0]['longitude'] : '125.1093'; ?>], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data &copy; OpenStreetMap contributors'
        }).addTo(map);

        <?php if ($treeCount > 0): ?>
            <?php foreach ($trees as $tree): ?>
                L.marker([<?php echo htmlspecialchars($tree['latitude']); ?>, <?php echo htmlspecialchars($tree['longitude']); ?>])
                    .bindPopup('<b><?php echo htmlspecialchars($tree['species']); ?></b><br />Location: <?php echo htmlspecialchars($tree['address']); ?>')
                    .addTo(map);
            <?php endforeach; ?>
        <?php endif; ?>
    </script>

    <script>
        // Toggle between grid and list views
        document.getElementById('grid-view').addEventListener('click', function() {
            document.getElementById('tree-display').style.display = 'none';
            document.getElementById('tree-grid').style.display = 'block';
        });

        document.getElementById('list-view').addEventListener('click', function() {
            document.getElementById('tree-display').style.display = 'block';
            document.getElementById('tree-grid').style.display = 'none';
        });
    </script>
    <script>
    // Set default map center (Manolo Fortich coordinates)
    var defaultLat = 7.8706;
    var defaultLng = 125.1093;
    
    // If tree data exists, use the first tree's location as the map's center
    var mapCenterLat = <?php echo isset($trees[0]['latitude']) ? $trees[0]['latitude'] : 'defaultLat'; ?>;
    var mapCenterLng = <?php echo isset($trees[0]['longitude']) ? $trees[0]['longitude'] : 'defaultLng'; ?>;

    // Initialize the Leaflet map
    var map = L.map('dashboard-map').setView([mapCenterLat, mapCenterLng], 12);

    // Load OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; OpenStreetMap contributors'
    }).addTo(map);

    // Add markers for each tree with valid latitude and longitude
    <?php if ($treeCount > 0): ?>
        <?php foreach ($trees as $tree): ?>
            // Check if the tree has valid latitude and longitude before adding marker
            <?php if (!empty($tree['latitude']) && !empty($tree['longitude'])): ?>
                L.marker([<?php echo htmlspecialchars($tree['latitude']); ?>, <?php echo htmlspecialchars($tree['longitude']); ?>])
                    .bindPopup('<b><?php echo htmlspecialchars($tree['species']); ?></b><br />Location: <?php echo htmlspecialchars($tree['address']); ?>')
                    .addTo(map);
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</script>


</body>

</html>
