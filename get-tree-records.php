<?php
include 'db_connection.php';

$sql = "SELECT tr.id, tr.date_time, tr.created_at, tr.latitude, tr.longitude, tr.image_path, 
               ts.species_name, tr.address, tr.species_id
        FROM tree_records tr
        LEFT JOIN tree_species ts ON tr.species_id = ts.id";

$result = $conn->query($sql);
$trees = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $trees[] = $row;
    }
}

echo json_encode($trees);
$conn->close();
?>
