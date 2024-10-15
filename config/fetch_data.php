<?php
// Database connection
$host = 'localhost';
$dbname = 'landong_db';
$username = 'root';
$password = 'your_db_password';

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch data for tree species
    $speciesQuery = $pdo->prepare("SELECT address, COUNT(id) as count FROM tree_species GROUP BY address");
    $speciesQuery->execute();
    $speciesData = $speciesQuery->fetchAll(PDO::FETCH_ASSOC);

    // Query to fetch upload dates
    $uploadsQuery = $pdo->prepare("SELECT DATE(uploaded_at) as upload_date, COUNT(id) as count FROM tree_species GROUP BY upload_date ORDER BY upload_date ASC");
    $uploadsQuery->execute();
    $uploadsData = $uploadsQuery->fetchAll(PDO::FETCH_ASSOC);

    // Return the data as JSON
    echo json_encode([
        'speciesData' => $speciesData,
        'uploadsData' => $uploadsData
    ]);
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
