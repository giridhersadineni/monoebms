

<?php include "config.php";

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "SELECT * FROM `allpapers` where course='BCOM' and groupcode='CA' and MEDIUM='EM' and SEM=3 ORDER BY `ID` ASC";

    $result = $conn->query($sql);
    $aresult = $result;
    $papers = array();
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($papers, $row);
        }
        echo json_encode($papers);
    } else {
        echo "No Rows ";
        echo $sql;

    }
}

?>