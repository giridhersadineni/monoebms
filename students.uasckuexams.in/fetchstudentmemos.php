<?php
// fetch_results.php

// Include the database configuration file
include "config.php";

// Check if the "hallticket" parameter is provided
if (isset($_GET['hallticket'])) {
    // Sanitize and get the provided hallticket from the URL
    $selectedHallticket = $_GET['hallticket'];

    // Check connection
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch results based on the selected hallticket
    $sql = "SELECT DISTINCT(EXAMID) AS EXAMID,HALLTICKET, EXAMNAME FROM RESULTS 
            LEFT JOIN examsmaster ON RESULTS.EXAMID = examsmaster.EXID
            WHERE HALLTICKET = '$selectedHallticket'
            ORDER BY EXAMID";
    $result = $conn->query($sql);

    // Check if results are found
    if ($result->num_rows > 0) {
        // Generate the HTML for the table rows containing the results
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td><a href="printoldgradesheet.php?exid=' . $row['EXAMID'] . '&ht=' . $row["HALLTICKET"] . '" class="btn btn-success" target="_BLANK"><i class="fas fa-print"></i> Print</td>';
            echo '<td>' . $row['HALLTICKET'] . '</td>';
            echo '<td>' . $row['EXAMID'] . '</td>';
            echo '<td>' . $row['EXAMNAME'] . '</td>';
            echo '<td><a href="studentgpaupdate.php?exid=' . $row['EXAMID'] . '&ht=' . $row["HALLTICKET"] . '" class="btn btn-success" target="_BLANK"><i class="fas fa-print"></i> UPDATE GPA</td>';
            echo '</tr>';
        }
    } else {
        // If no results are found, display a message
        echo '<tr><td colspan="5">No results found.</td></tr>';
    }
} else {
    // If the "hallticket" parameter is not provided, display an error message
    echo '<tr><td colspan="5">Error: Hallticket not provided.</td></tr>';
}
