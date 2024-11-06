<?php
include 'config/db_connection.php';

$data = json_decode(file_get_contents("php://input"), true);
$tree_id = $data['tree_id'] ?? null;
$species_id = $data['species_id'] ?? null;

if ($tree_id && $species_id) {
    $stmt = $conn->prepare("UPDATE tree_records SET species_id = ? WHERE id = ?");
    $stmt->bind_param("ii", $species_id, $tree_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database update failed']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
}

$conn->close();
?>
