<?php
// Database connection settings
$host = 'localhost';
$db = 'proj-kalasan_db';
$user = 'root';
$pass = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['record_id'])) {
    $recordId = intval($_POST['record_id']);

    try {
        // Establish the connection
        $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Update the validation status
        $sql = "UPDATE tree_planted SET validated = 1 WHERE id = :plnted_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':planted_id', $recordId, PDO::PARAM_INT);
        $stmt->execute();

        echo "Record validated successfully.";

    } catch (PDOException $e) {
        echo "Failed to validate record: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
