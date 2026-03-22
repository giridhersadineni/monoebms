<?php include "config.php";

$examid=$_GET['eid'];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
} else {
    
    $sql = "SELECT EXAMID FROM examenrollments where ID=".$_GET['eid'];
    //echo $sql;

    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['EXAMID'];
            break;
        }
        //echo json_encode($resultset);
    } else {
        echo "NA";
        //echo $sql;

    }
}

?>