<?php
// list_trees.php
if (isset($_GET['alert'])) {
    $alertMessage = htmlspecialchars($_GET['alert']);
} else {
    $alertMessage = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of Trees</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />

    <script>
        // Function to show alert
        function showAlert(message) {
            if (message) {
                alert(message); // Display the alert message
            }
        }

        // Call showAlert function if the alert message is passed in the URL
        window.onload = function() {
            var alertMessage = "<?php echo $alertMessage; ?>"; // PHP variable passed into JS
            showAlert(alertMessage); // Show the alert message
        }
    </script>
</head>
<body>

<!-- Content of list_trees.php -->
<div class="container">
    <h2>List of Trees</h2>
    <!-- Add your tree list table or content here -->
    <p>This is where the list of trees will be displayed.</p>
</div>

</body>
</html>
