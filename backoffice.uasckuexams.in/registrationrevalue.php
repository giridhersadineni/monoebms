<?php
print_r($_POST);
include "config.php";
   $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
   
   if ($conn->connect_error) {
       die("Server Down Please retry after Some Time");
   } else {
       $result = $conn->query("select * from students where haltckt='" . $_COOKIE['userid'] . "'");
      // echo $result;
       if ($result->num_rows > 0) {
           $student = mysqli_fetch_assoc($result);
       }

$subject =implode($_POST['subjects'],"','");
$values =     "'".$subject."','" . $_POST['e1'] . "','" . $_POST['e2'] . "','" .$_POST['e3'] . "','" . $_POST['e4']."'," ;
$sql = "INSERT INTO revaluationenrollments ( EXAMID, STUDENTID,HALLTICKET, S1, S2, S3, S4, S5, S6, S7, S8, S9, S10, E1, E2, E3, E4,FEEAMOUNT,EXTYPE,STATUS) VALUES (" . $_POST['examid'] . "," . $student['stid'] . "," . $student['haltckt'] . "," . $values . $_POST["fee"] .",'" . $_POST['extype'] ."','OPEN')";

//echo $sql;
if ($conn->query($sql)) {
    header("location:challanrev.php?id=" . $conn->insert_id);

} else {
    echo "Unable to register now" . $conn->error;
}
}
?>
