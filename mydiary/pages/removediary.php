<?php
// Start the session if not already started
session_start();
require_once('../config/db.php');

// Check if the ID parameter is set and display its value
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    var_dump($id); 
} else {
    echo "Missing diary ID parameter";
    exit;
}

// Validate ID (assuming it's an integer)
if (!is_numeric($id) || $id <= 0) {
    // Invalid ID, redirect or handle error
    echo "Invalid diary ID";
    exit;
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Delete diary entry from database
$query = "DELETE FROM diaries WHERE id = $id AND user_id = $user_id";

$result = mysqli_query($con, $query);

if ($result) {
    // Check if any rows were affected
    if (mysqli_affected_rows($con) > 0) {
        header("Location: index.php");
    } else {
        echo "Diary entry not found or you do not have permission to delete it.";
    }
} else {
    echo "Error deleting entry: " . mysqli_error($con);
}

// Close database connection
mysqli_close($con);
?>
