<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "your_password";
$dbname = "proj-kalasan_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch validation logs
$sql = "SELECT v.id, v.validated_by AS admin_id, v.tree_record_id AS tree_id, 
               v.status, v.validation_date AS validated_at, v.comments AS remarks
        FROM validations v
        JOIN tree_records tr ON v.tree_record_id = tr.id
        JOIN tree_species ts ON tr.tree_species_id = ts.id
        WHERE ts.id IS NOT NULL"; // Only fetch records with valid species

$result = $conn->query($sql);

$logs = [];

if ($result->num_rows > 0) {
    // Fetch all log records
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}

// Return the logs as JSON
header('Content-Type: application/json');
echo json_encode($logs);

// Close connection
$conn->close();
?>
