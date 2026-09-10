<?php
    include "projectorm.php";
    set_time_limit(1000);
    ini_set('display_errors', 1);
    if (isset($_POST["submit"])) {
        $oldid=$_POST['EID'];
        $subs = $_POST['PAPERS'];
        $subjectcount = sizeof($subs);
        $subsarray = $subs;
        $fee=$_POST['FEE'];
    for ($i = 0; $i < (10 - $subjectcount); $i++) {
           array_push($subsarray, "null");
    }

    $conn=getconnection();
    
    $regularenrollment=get_one_assoc("select * from examenrollments where ID=$oldid");
    echo "<hr>";
    print_r($regularenrollment);
    
    $student = get_one_assoc("select * from students where stid=".$regularenrollment['STUDENTID']);
    echo "<hr>";
    print_r($student);

    $checkenroll='select * from revaluationenrollments where EXAMID='.$examid.' and STUDENTID='.$student['stid'];
    $enrollments=$conn->query($checkenroll);
    echo "<hr>";
    print_r($exam);
    if($enrollments->num_rows>0){
         header("location:enrollederror.php?error=duplicateentry");
    }   
    else{
        $subsquery = implode("','",$subsarray);
        $values = "'" . $subsquery . "','null','null','null','null',";
        $sql = "INSERT INTO revaluationenrollments(EXAMID, STUDENTID,HALLTICKET, S1, S2, S3, S4, S5, S6, S7, S8, S9, S10, E1, E2, E3, E4,FEEAMOUNT,ENROLLEDDATE) VALUES (" . $regularenrollment['EXAMID'] . "," . $regularenrollment["STUDENTID"] . ",'" . $regularenrollment["HALLTICKET"] . "',".$values . $fee. ", CURRENT_TIMESTAMP)";
        echo $sql;
        echo "<hr>";
        if($conn->query($sql)){
            header("location:registrationsuccess.php?et=reval&id=".$conn->insert_id);
        } else {
            echo "Unable to register now";
                
        }
     }
}