<?php include "config.php";

$id=$_GET["id"];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "SELECT * FROM examsmaster where EXAMID=".$id;

    $result = $conn->query($sql);
    $aresult = $result;
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $resultset[] = array(
                'SEMESTER'=> $row["SEMESTER"],
                'EXAMNAME' => $row['EXAMNAME'],
                'MONTH' => $row['MONTH'],
                'YEAR' => $row['YEAR'],
                'EXAMTYPE' => $row["EXAMTYPE"],

            );

        }
        echo json_encode($resultset);
    } else {
        echo "No Subjects Fetched";
        echo $sql;

    }
}

?>