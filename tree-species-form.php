<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include 'config/db_connection.php';

// Query to fetch all tree records
$treeRecordsQuery = "SELECT * 
                     FROM tree_planted tr
                     LEFT JOIN users u ON tr.user_id = u.id";
$stmt = $conn->prepare($treeRecordsQuery);
if ($stmt) {
    $stmt->execute();
    $treeRecordsResult = $stmt->get_result();
} else {
    die("Error preparing statement: " . $conn->error);
}

// Fetch tree species for dropdown
$treeSpeciesQuery = "SELECT id, species_name, scientific_name, description, created_at, category FROM tree_planted";
$speciesStmt = $conn->prepare($treeSpeciesQuery);
if ($speciesStmt) {
    $speciesStmt->execute();
    $treeSpeciesResult = $speciesStmt->get_result();
} else {
    die("Error preparing species statement: " . $conn->error);
}

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $treePlanted = $_POST['tree_planted'];

}

$stmt->close();
$speciesStmt->close();
$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="./assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Tree Records</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
</head>

<body>
<div class="wrapper">
    <div class="sidebar" data-color="white" data-active-color="danger">
        <div class="logo">
            <a href="https://www.creative-tim.com" class="simple-text logo-normal">
                Your Logo
            </a>
        </div>
        <div class="sidebar-wrapper">
            <ul class="nav">
                <li class="active">
                    <a href="javascript:;">
                        <i class="nc-icon nc-bank"></i>
                        <p>Tree Records</p>
                    </a>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="nc-icon nc-diamond"></i>
                        <p>Second Item</p>
                    </a>
                </li>
                <li>
                    <a href="javascript:;">
                        <i class="nc-icon nc-pin-3"></i>
                        <p>Third Item</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="main-panel" style="height: 100vh;">
        <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <a class="navbar-brand" href="javascript:;">Tree Records</a>
                </div>
                <div class="collapse navbar-collapse justify-content-end" id="navigation">
                    <form>
                        <div class="input-group no-border">
                            <input type="text" value="" class="form-control" placeholder="Search...">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="nc-icon nc-zoom-split"></i>
                                </div>
                            </div>
                        </div>
                    </form>
                    <ul class="navbar-nav">
                        <li class="nav-item btn-rotate dropdown">
                            <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="nc-icon nc-bell-55"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container">
                <h3 class="mt-4">Tree Records</h3>

                <?php if ($message): ?>
                    <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>

                <div class="row">
                    <?php while ($row = $treeRecordsResult->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="<?php echo htmlspecialchars($row['image_path']); ?>" class="card-img-top" alt="Tree Image" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <p class="card-text"><strong>Name:</strong> <?php echo htmlspecialchars($row['species_name']); ?></p>
                                    <p class="card-text"><strong>Address:</strong> <?php echo htmlspecialchars($row['address']); ?></p>
                                    <p class="card-text"><strong>Date & Time:</strong> <?php echo htmlspecialchars($row['date_time']); ?></p>
                                    <p class="card-text"><strong>Uploaded By:</strong> <?php echo htmlspecialchars($row['username']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <footer class="footer" style="position: absolute; bottom: 0; width: -webkit-fill-available;">
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

<script src="./assets/js/core/jquery.min.js"></script>
<script src="./assets/js/core/popper.min.js"></script>
<script src="./assets/js/core/bootstrap.min.js"></script>
<script src="./assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<script src="./assets/js/plugins/chartjs.min.js"></script>
<script src="./assets/js/plugins/bootstrap-notify.js"></script>
<script src="./assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>
<script src="./assets/demo/demo.js"></script>
</body>
</html>
