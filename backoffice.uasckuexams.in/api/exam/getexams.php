<?php include "config.php";

$year=$_GET["year"];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
} else {
    
    $sql = "SELECT * FROM examsmaster where YEAR=".$year ." ORDER BY EXAMNAME,YEAR";

    $result = $conn->query($sql);
    //$aresult = $result;
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $resultset[] = array(
                'EXID' =>$row["EXID"],
                'SEMESTER'=> $row["SEMESTER"],
                'EXAMNAME' => $row['EXAMNAME'],
                'MONTH' => $row['MONTH'],
                'YEAR' => $row['YEAR'],
                'EXAMTYPE' => $row["EXAMTYPE"],

            );

        }
        echo json_encode($resultset);
    } else {
        echo "No Exams Fetched";
        echo $sql;

    }
}

?>