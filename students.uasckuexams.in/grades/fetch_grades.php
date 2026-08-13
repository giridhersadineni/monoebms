<?php
include("../config.php");

// Create connection
$conn = new mysqli($servername, $dbuser, $dbpwd, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from grades table
$sql = "SELECT * FROM grades";
$result = $conn->query($sql);

// Initialize an empty array to store the data
$data = array();

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Fetch associative array of each row
    while ($row = $result->fetch_assoc()) {
        // Add each row to the data array
        array_push($data,$row);
    }
}

// Close the database connection
$conn->close();


// Return the data as JSON
echo json_encode($data);
?>
