<?php include "config.php";

$examid=$_GET['examid'];
$papercode=$_GET['papercode'];
$hallticket=$_GET['hallticket'];
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
} else {
    
    $sql = "SELECT * FROM RESULTS WHERE PAPERCODE='$papercode' AND HALLTICKET = '$hallticket'";
    // $sql = "SELECT `INT` as MARKS FROM RESULTS where EXAMID in($examid) and $examid and PAPERCODE='$papercode' and HALLTICKET=$hallticket";
    //echo $sql;

    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['INT'] . " ";
       //     break;
        }
        //echo json_encode($resultset);
    } else {
        echo "NA";
        //echo $sql;

    }
}

?>