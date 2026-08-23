<?php
if (isset($_POST["submit"])) {
    $subs = $_POST['subs'];
    $subjectcount = sizeof($subs);

    $subsarray = $subs;
    for ($i = 0; $i < (10 - $subjectcount); $i++) {
        array_push($subsarray, "null");
    }
    if (isset($_POST["E1"])) {
        $e1 = $_POST["E1"];
    } else {
        $e1 = "null";
    }
    if (isset($_POST["E2"])) {
        $e2 = $_POST["E2"];
    } else {
        $e2 = "null";
    }
    if (isset($_POST["E3"])) {
        $e3 = $_POST["E3"];
    } else {
        $e3 = "null";
    }
    if (isset($_POST["E4"])) {
        $e4 = $_POST["E4"];
    } else {
        $e4 = "null";
    }
    if (isset($_POST["E5"])) {
        $e5 = $_POST["E5"];
    } else {
        $e5 = "null";
    }


    include "config.php";
    $examid=$_POST['examid'];
     //$stid=$_POST['stid'];
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if ($conn->connect_error) {
        die("Server Down Please retry after Some Time");
    } else {
        
        $result = $conn->query("select * from students where haltckt='" . $_COOKIE['userid'] . "'");
        if ($result->num_rows > 0) {
            $student = mysqli_fetch_assoc($result);
        }
    }
        $enroll='select * from examenrollments where EXAMID='.$examid.' and STUDENTID='.$student['stid'];
        $exam=mysqli_query($conn,$enroll);
        if(mysqli_num_rows($exam)>0){
        
              header("location:enrollederror.php?error=duplicateentry");
            
        }   
         else{
    $subsquery = implode("','",$subsarray );
    $values = "'" . $subsquery . "','" . $e1 . "','" . $e2 . "','" . $e3 . "','" . $e4 . "','". $e5 . "','";;
    $sql = "INSERT INTO examenrollments (HALLTICKET, EXAMID, STUDENTID, S1, S2, S3, S4, S5, S6, S7, S8, S9, S10, E1, E2, E3, E4,E5,FEEAMOUNT,ENROLLEDDATE) VALUES ('".$_POST['HALLTICKET'] ."',". $_POST['examid'] . "," . $student['stid'] . "," . $values . $_POST["fee"] . "', CURRENT_TIMESTAMP)";

    echo $sql;
    if ($conn->query($sql)) {
        header("location:registrationsuccess.php?id=" . $conn->insert_id);
    } else {
        echo "Unable to register now" . $conn->error;
    }
 }
}