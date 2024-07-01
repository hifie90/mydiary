<?php
#session_start();
require_once('../config/db.php');
include("cache.php");
?>

<?php
// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Query to fetch the full texts from the database
$query = "SELECT id, user_id, title, description FROM diaries WHERE user_id = $user_id";

// Execute the query
$result = mysqli_query($con, $query);

// Check if the query was successful
if ($result) {
    // Open the container and row before the loop
    echo '<div class="container">';
    echo '<div class="row justify-content-center mt-5">';
    
    // Fetch the data row by row
    while ($row = mysqli_fetch_assoc($result)) {
        // Accessing individual fields
        $id = $row['id'];
        $title = $row['title'];
        $description = $row['description'];
    
        // Output the card for each diary entry
        echo '<div class="col-md-4 diary-entry" data-id="'. $id .'">';
        echo '<div class="card mb-3">';
        echo '<div class="card-body">';
        echo '<div class="card-header">';
        echo '<h5 class="card-title editable" data-id="'. $id .'" data-field="title">'. $title .'</h5>';
        echo '<div class="dropdown">';
        echo '<button class="dropbtn">...</button>';
        echo '<div class="dropdown-content">';
        echo '<a href="#" class="edit" data-id="'. $id .'">Edit</a>';
        echo '<a href="removediary.php?id='.urlencode($id).'" class="delete">Delete</a>';
        echo '</div>';
        echo '</div>'; // Close dropdown
        echo '</div>'; // Close card-header
        echo '<p class="card-text editable" data-id="'. $id .'" data-field="description">'.$description.'</p>';
        echo '<a href="entries.php?diary_id='.urlencode($id).'" class="btn btn-primary">Enter to your secrets</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    // Close the row and container after the loop
    echo '</div>';
    echo '</div>';
} else {
    // Handle the case when the query fails
    echo "Error: " . mysqli_error($con);
}

mysqli_close($con);
?>

<link rel="stylesheet"href="../style/diaries.css">
<script src="../scripts/diaries.js"></script>
