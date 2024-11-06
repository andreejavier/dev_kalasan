<?php
include 'cofeig/db_connection.php';

$sql = "SELECT id, species_name AS name, scientific_name, category FROM tree_species";
$result = $conn->query($sql);
$species = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $species[] = $row;
    }
}

echo json_encode($species);
$conn->close();
?>
