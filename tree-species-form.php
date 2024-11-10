<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'config/db_connection.php';

// Ensure the database connection is established
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set upload directory and ensure it exists
$uploads_dir = 'uploads/trees';
if (!is_dir($uploads_dir)) {
    mkdir($uploads_dir, 0777, true);
}

// Fetch all tree records with additional images and uploader details
$query = "SELECT tr.*, ti.image_path AS additional_image, u.username AS uploader_name, u.profile_picture 
          FROM tree_planted tr
          LEFT JOIN tree_images ti ON tr.id = ti.tree_planted_id
          LEFT JOIN users u ON tr.user_id = u.id";
$stmt = mysqli_prepare($conn, $query);

if ($stmt === false) {
    die("Error preparing statement: " . mysqli_error($conn));
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$trees = [];
while ($row = mysqli_fetch_assoc($result)) {
    $tree_id = $row['id'];
    if (!isset($trees[$tree_id])) {
        $trees[$tree_id]['details'] = $row;
    }
    if ($row['additional_image']) {
        $trees[$tree_id]['images'][] = $row['additional_image'];
    }
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Kalasan Mapping - All Planted Trees</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/paper-dashboard.css" rel="stylesheet">
    <link href="assets/css/custom-dashboard.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f0f0f0; /* Setting the background to gray */
        }

        .wrapper {
            display: flex;
            width: 100%;
            height: 100%;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #f8f9fa;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow-y: auto;
        }

        .content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
        }

        .main-content {
            margin-top: 80px; /* Adjust for navbar height */
            padding: 20px;
        }

        .tree-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .additional-images img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin: 5px;
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        @media screen and (max-width: 768px) {
            .content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
        }

        /* Navbar adjustments */
        .navbar {
            position: fixed;
            top: 0;
            left: 250px; /* Aligns with sidebar */
            width: calc(100% - 250px);
            z-index: 1001;
            background-color: #ffffff;
        }

        .navbar .nav-link {
            color: #333;
        }

        .navbar .dropdown-menu {
            right: 0;
            left: auto;
        }

        @media screen and (max-width: 768px) {
            .navbar {
                left: 0;
                width: 100%;
            }
        }
    </style>
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
    
<!-- Navbar with Search Inputs -->
<nav class="navbar navbar-expand-lg fixed-top navbar-light" color-on-scroll="300">
    <div class="container-fluid">
        <div class="navbar-translate">
            <a class="navbar-brand" href="#">Kalasan Dashboard</a>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <form class="form-inline">
                <div class="searchwrapper">
                    <input id="speciesSearch" class="form-control" placeholder="Search Species" type="text" onkeyup="filterTrees()" />
                </div>
                <div class="searchwrapper">
                    <input id="locationSearch" class="form-control" placeholder="Search Location" type="text" onkeyup="filterTrees()" />
                </div>
                <button class="btn btn-default btn-inat btn-focus show-btn" type="button" onclick="filterTrees()">
                    <i class="fa fa-search"></i>
                </button>
                <ul class="navbar-nav">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: black;">
            <i class="nc-icon nc-single-02"></i>
            <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
            <a class="dropdown-item" href="profile.php" style="color: black;">View Profile</a>
            <a class="dropdown-item" href="settings.php" style="color: black;">Settings</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="logout.php" style="color: black;">Logout</a>
        </div>
    </li>
</ul>

            </form>
        </div>
    </div>
</nav>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="main-content">
            <h3>All Planted Trees</h3>

            <div class="row" id="treeContainer">
                <?php foreach ($trees as $tree): ?>
                    <div class="col-md-4 tree-card">
                        <div class="card">
                            <a href="view-tree.php?id=<?php echo htmlspecialchars($tree['details']['id']); ?>">
                                <img src="<?php echo htmlspecialchars($tree['details']['image_path']); ?>" alt="Tree Image" />
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($tree['details']['species_name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($tree['details']['address']); ?></p>
                                <img class="profile-pic" src="<?php echo htmlspecialchars($tree['details']['profile_picture']); ?>" alt="Profile Picture" />
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
function filterTrees() {
    let speciesInput = document.getElementById("speciesSearch").value.toLowerCase();
    let locationInput = document.getElementById("locationSearch").value.toLowerCase();
    let cards = document.querySelectorAll(".tree-card");

    cards.forEach(card => {
        let speciesName = card.querySelector(".card-title").innerText.toLowerCase();
        let location = card.querySelector(".card-text").innerText.toLowerCase();
        if (speciesName.includes(speciesInput) && location.includes(locationInput)) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    });
}
</script>

<!-- Bootstrap JS -->
<script src="assets/js/core/jquery.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
</body>
</html>
