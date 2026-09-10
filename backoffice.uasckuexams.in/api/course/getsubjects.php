

<?php include "config.php";
$course = $_GET['course'];
$group = $_GET['group'];
$medium = $_GET['medium'];
$semester = $_GET['semester'];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "SELECT * FROM allpapers WHERE COURSE='" . $course . "' and GROUPCODE='" . $group . "'and MEDIUM='" . $medium . "'and sem=" . $semester . " and scheme='".$_GET['scheme']."'";

    $result = $conn->query($sql);
    $aresult = $result;
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {

            $resultset[] = array(
                'PAPERCODE' => $row['PAPERCODE'],
                'PAPERNAME' => $row['PAPERNAME'],
                'PAPERTYPE' => $row['PAPERTYPE'],
                'PAPERGROUP' => $row["EGROUP"],
                'MEDIUM' => $row["MEDIUM"],
            );

        }
        echo json_encode($resultset);
    } else {
        echo "No Subjects Fetched";
        echo $sql;

    }
}

?>