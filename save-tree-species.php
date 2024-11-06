<?php
// Database connection
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $species_name = $_POST['species_name'] ?? '';
    $scientific_name = $_POST['scientific_name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (empty($species_name)) {
        echo json_encode(['success' => false, 'error' => 'Species name is required']);
        exit();
    }

    // Insert data into the tree_species table
    $stmt = $conn->prepare("INSERT INTO tree_species (species_name, scientific_name, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $species_name, $scientific_name, $description);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database insert failed']);
    }

    $stmt->close();
}

// Close database connection
$conn->close();
?>
