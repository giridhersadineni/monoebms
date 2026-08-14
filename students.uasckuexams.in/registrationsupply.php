<?php
    print_r($_POST);
    include "config.php";
    $examid=$_POST['examid'];
   $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
   
   if ($conn->connect_error) {
       die("Server Down Please retry after Some Time");
   } else {
       $result = $conn->query("select * from students where haltckt='" . $_COOKIE['userid'] . "'");
     
       if ($result->num_rows > 0) {
           $student = mysqli_fetch_assoc($result);
       }
         
         echo $enroll='select * from examenrollments where EXAMID='.$examid.' and STUDENTID='.$student['stid'];
         $exam=mysqli_query($conn,$enroll);
        if(mysqli_num_rows($exam)>0){
              header("location:enrollederror.php?error=duplicateentry");
            
        }   
         else{
       
        echo "<pre>";
        var_dump($_POST);
        echo "</pre>";
        $subject = implode( "','" , $_POST['subjects']);
        $values =     "'".$subject."','" . $_POST['e1'] . "','" . $_POST['e2'] . "','" .$_POST['e3'] . "','" . $_POST['e4']."'," ;
        $sql = "INSERT INTO examenrollments (HALLTICKET, EXAMID, STUDENTID, S1, S2, S3, S4, S5, S6, S7, S8, S9, S10, E1, E2, E3, E4,FEEAMOUNT,EXTYPE) VALUES ('".$_POST['HALLTICKET']."'," . $_POST['examid'] . "," . $student['stid'] . "," . $values . $_POST["fee"] .",'" . $_POST['extype'] ."')";

        echo $sql;
        if ($conn->query($sql)) {
            header("location:supplychallan.php?id=" . $conn->insert_id);
        
        } else {
            echo "Unable to register now" . $conn->error;
        }
        }
        }
?>
