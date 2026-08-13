<?php
include "config.php";

$course = $_GET["course"];
$medium = $_GET['medium'];

$resultset = array();

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {

    $sql = "SELECT GROUPCODE,SPECIALIZATION,MEDIUM FROM courses WHERE COURSE='" . $course . "' and MEDIUM='" . $medium . "'AND SEM=" . $_GET['s'];

    $results = $conn->query($sql);
    while ($row = mysqli_fetch_assoc($results)) {

        $resultset[] = array(
            'courseid' => $row['GROUPCODE'],
            'coursename' => $row['SPECIALIZATION'],
            'coursemedium' => $row['MEDIUM'],
        );
    }

    echo json_encode($resultset);
}
