<?php
// get_plant_data.php
header('Content-Type: application/json');

// Database credentials
$servername = "localhost";
$username = "root"; // Update with your username
$password = ""; // Update with your password
$dbname = "landong_db"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Prepare and execute the SQL query (consistent field names)
$sql = "SELECT `id`, `image`, `latitude`, `longitude`, `date_time`, `address`, `user_id` FROM newtree_species";
$result = $conn->query($sql);

$plants = [];

if ($result && $result->num_rows > 0) {
    // Loop through the result set and add each row to the $plants array
    while ($row = $result->fetch_assoc()) {
        $plants[] = [
            "id" => (int)$row['id'],
            "image" => htmlspecialchars($row['image']),
            "latitude" => (float)$row['latitude'],
            "longitude" => (float)$row['longitude'],
            "date_time" => htmlspecialchars($row['date_time']),
            "address" => htmlspecialchars($row['address']),
            "user_id" => (int)$row['user_id'], // Include user ID
        ];
    }
} else {
    http_response_code(404);
    echo json_encode(["message" => "No plant data found"]);
    exit;
}

$conn->close();

// Output the plant data as JSON
echo json_encode($plants, JSON_PRETTY_PRINT);
?>
