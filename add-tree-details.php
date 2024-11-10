<?php 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dev_kalasan_db";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch tree data based on ID
$tree_id = isset($_GET['id']) ? $_GET['id'] : null;
$tree = null;
$user = null;

if ($tree_id) {
    $stmt = $conn->prepare("SELECT t.*, u.username, u.email FROM tree_planted t 
                            JOIN users u ON t.user_id = u.id WHERE t.id = ?");
    $stmt->bind_param("i", $tree_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tree = $result->fetch_assoc();
    $stmt->close();
}

// Update tree data if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date_time = $_POST['date_time'];
    $species_name = $_POST['species_name'];
    $scientific_name = $_POST['scientific_name'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    // Handle image upload if a new image is uploaded
    if ($_FILES['tree_image']['error'] == 0) {
        $image_path = 'uploads/' . basename($_FILES['tree_image']['name']);
        move_uploaded_file($_FILES['tree_image']['tmp_name'], $image_path);
    } else {
        // If no image is uploaded, use the existing one
        $image_path = $tree['image_path'];
    }

    // Prepare the SQL update statement
    $stmt = $conn->prepare("UPDATE tree_planted 
                            SET date_time = ?, species_name = ?, scientific_name = ?, description = ?, category = ?, image_path = ? 
                            WHERE id = ?");
    $stmt->bind_param("ssssssi", $date_time, $species_name, $scientific_name, $description, $category, $image_path, $tree_id);
    
    // Execute the update and handle success or failure
    if ($stmt->execute()) {
        // Redirect with an alert message after successful update
        header("Location: manage-record.php?alert=" . urlencode("Tree data updated successfully."));
        exit();
    } else {
        echo "<p>Error updating tree data: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Tree Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Include Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
<div class="container">
    <div class="row">
        <!-- Map Section -->
        <div class="col-md-6">
            <h4>Map</h4>
            <div id="map" style="height: 400px;"></div>
        </div>

        <!-- Form Section -->
        <div class="col-md-6">
            <h2>Update Tree Details</h2>
            
            <!-- Display the current image -->
            <div class="form-group">
                <label>Current Image</label><br>
                <?php if ($tree['image_path']): ?>
                    <img src="<?= htmlspecialchars($tree['image_path']) ?>" alt="Tree Image" width="200" />
                <?php else: ?>
                    <p>No image available</p>
                <?php endif; ?>
            </div>

            <!-- Display the user who uploaded the plant -->
            <div class="form-group">
                <label>Uploaded by</label>
                <p><?= htmlspecialchars($tree['username']) ?> (<?= htmlspecialchars($tree['email']) ?>)</p>
            </div>

            <?php if ($tree): ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="species_name">Species Name</label>
                        <input type="text" class="form-control" id="species_name" name="species_name" value="<?= htmlspecialchars($tree['species_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="scientific_name">Scientific Name</label>
                        <input type="text" class="form-control" id="scientific_name" name="scientific_name" value="<?= htmlspecialchars($tree['scientific_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" name="description" value="<?= htmlspecialchars($tree['description']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($tree['category']) ?>" required>
                    </div>

                    <!-- Input for new image -->
                    <div class="form-group">
                        <label for="tree_image">Upload New Image (optional)</label>
                        <input type="file" class="form-control" id="tree_image" name="tree_image">
                    </div>

                    <button type="submit" class="btn btn-primary">Update Tree</button>
                    <a href="./manage-record.php" class="btn btn-secondary">Back to List</a>
                </form>
            <?php else: ?>
                <p>Tree not found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    // Initialize the map
    var map = L.map('map').setView([<?php echo $tree['latitude']; ?>, <?php echo $tree['longitude']; ?>], 13);

    // Add OpenStreetMap tiles to the map
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Add a marker for the tree location
    var marker = L.marker([<?php echo $tree['latitude']; ?>, <?php echo $tree['longitude']; ?>]).addTo(map);
    marker.bindPopup("<b>Tree Location</b>").openPopup();
</script>

</body>
</html>
