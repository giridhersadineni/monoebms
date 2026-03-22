<?php include "config.php";

$examid=$_GET['eid'];
$papercode=$_GET['pc'];
$hallticket=$_GET['ht'];
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
} else {
    
    $sql = "SELECT `INT` as MARKS FROM tempresult where EXAMID=$examid and PAPERCODE='$papercode' and HALLTICKET=$hallticket";
    //echo $sql;

    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['MARKS'];
            break;
        }
        //echo json_encode($resultset);
    } else {
        echo "NA";
        //echo $sql;

    }
}

?>