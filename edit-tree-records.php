<?php
session_start();
include 'config/db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Get tree record ID from URL
$tree_record_id = $_GET['id'];

// Fetch the existing tree record
$query = "SELECT * FROM tree_records WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $tree_record_id);
$stmt->execute();
$result = $stmt->get_result();
$tree_record = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update the tree record
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $address = $_POST['address'];
    $date_time = $_POST['date_time'];

    // Update query
    $update_query = "UPDATE tree_records SET latitude = ?, longitude = ?, address = ?, date_time = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('ddssi', $latitude, $longitude, $address, $date_time, $tree_record_id);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('Record updated successfully.'); window.location.href='home.php';</script>";
    } else {
        echo "<script>alert('Error updating record.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Tree Record</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2>Update Tree Record</h2>
    <form action="" method="POST">
        <div class="form-group">
            <label for="latitude">Latitude</label>
            <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo htmlspecialchars($tree_record['latitude']); ?>" required>
        </div>
        <div class="form-group">
            <label for="longitude">Longitude</label>
            <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo htmlspecialchars($tree_record['longitude']); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($tree_record['address']); ?>" required>
        </div>
        <div class="form-group">
            <label for="date_time">Date and Time</label>
            <input type="datetime-local" class="form-control" id="date_time" name="date_time" value="<?php echo date('Y-m-d\TH:i', strtotime($tree_record['date_time'])); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Record</button>
    </form>
    <a href="home.php" class="btn btn-secondary mt-3">Cancel</a>
</div>

<!-- External JS files -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
