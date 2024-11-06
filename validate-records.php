<?php
// Database connection settings
$host = 'localhost';
$db = 'proj-kalasan_db';
$user = 'root';
$pass = ''; // Update with your password if needed

try {
    // Establish the connection
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch tree records with species information
    $sql = "SELECT 
                tree_records.id AS record_id,
                tree_records.latitude,
                tree_records.longitude,
                tree_records.date_time,
                tree_records.address,
                tree_records.image_path,
                tree_records.validated,
                tree_species.species_name
            FROM tree_records
            LEFT JOIN tree_species ON tree_records.tree_species_id = tree_species.id";
    
    // Execute the query
    $stmt = $pdo->query($sql);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tree Record Validation</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #f4f4f4; }
        button { padding: 5px 10px; }
    </style>
    <script>
        // Function to validate a record using AJAX
        function validateRecord(recordId) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "validate_record_action.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById("status_" + recordId).innerText = "Yes";
                    document.getElementById("button_" + recordId).disabled = true;
                }
            };

            xhr.send("record_id=" + recordId);
        }
    </script>
</head>
<body>
    <h1>Tree Records - Validation</h1>
    <table>
        <tr>
            <th>Record ID</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Date & Time</th>
            <th>Address</th>
            <th>Image Path</th>
            <th>Species Name</th>
            <th>Validated</th>
            <th>Action</th>
        </tr>
        <?php foreach ($records as $record): ?>
            <tr>
                <td><?= htmlspecialchars($record['record_id']) ?></td>
                <td><?= htmlspecialchars($record['latitude']) ?></td>
                <td><?= htmlspecialchars($record['longitude']) ?></td>
                <td><?= htmlspecialchars($record['date_time']) ?></td>
                <td><?= htmlspecialchars($record['address']) ?></td>
                <td><?= htmlspecialchars($record['image_path']) ?></td>
                <td><?= htmlspecialchars($record['species_name']) ?></td>
                <td id="status_<?= $record['record_id'] ?>"><?= $record['validated'] ? 'Yes' : 'No' ?></td>
                <td>
                    <?php if (!$record['validated']): ?>
                        <button id="button_<?= $record['record_id'] ?>" onclick="validateRecord(<?= $record['record_id'] ?>)">Validate</button>
                    <?php else: ?>
                        <button disabled>Validated</button>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
