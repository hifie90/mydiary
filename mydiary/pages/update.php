<?php
require_once('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    // Sanitize the input
    $id = mysqli_real_escape_string($con, $id);
    $field = mysqli_real_escape_string($con, $field);
    $value = mysqli_real_escape_string($con, $value);

    // Update the database
    $query = "UPDATE diaries SET $field = '$value' WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "Success";
    } else {
        echo "Error: " . mysqli_error($con);
    }

    mysqli_close($con);
}
?> 
