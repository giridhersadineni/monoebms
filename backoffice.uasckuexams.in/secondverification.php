<?php

include 'config.php';
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$examid=$_POST['examid'];

    $sql = "SELECT * FROM examenrollments  WHERE EXAMID='$examid'";
 echo $sql;
        
     $duplicate=mysqli_query($conn,"select * from examenrollments where EXAMID=".$row['EXID']);
        if (mysqli_num_rows($duplicate)>0)
                {
               header("Location: enrollederror.php?error=duplicate");
                     } 
        // $enrolled="select * from examenrollments where EXAMID=".$row['EXID'];
        else{
         header("Location: enrollregular.php?error=duplicate");
        }

    $conn->close();
    

?>