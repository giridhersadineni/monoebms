<?php 
include 'config.php';
$ID=$_GET['id'];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} 
else {
    $getgrade="SELECT * FROM grades where ID='$ID'";
    $graderesult=$conn->query($getgrade);
    $grade=mysqli_fetch_assoc($graderesult);
    $getstudent="SELECT * FROM students where haltckt='".$grade['HALLTICKET']."'";
    //echo $getstudent." ".$getresult;
    $sres=$conn->query($getstudent);
    $student=mysqli_fetch_assoc($sres);
//  echo print_r($student);
//   echo print_r($grade);
if($student['course']=="BA"){
         $course="BACHELOR OF ARTS";
 }
else if($student['course']=="BCOM"){
          $course="BACHELOR OF COMMERCE";
}
  else if($student['course']=="BSC"){
  $course="BACHELOR OF SCIENCE";
}
     
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
<style>
    
</style>
</head>
<body>

    <div class="container">
        <p>
            This is to certify that <span class="underlined"><?=$student['sname']?></span> 
            Son/Daughter of.<span class="underlined"><?=$student['fname']?></span> 
            has passed the <span class="underlined"><?=$course?></span> 
            examination held in <span class="underlined"><?=$grade['ALL_YOP']?></span> 
            in/with <span class="underlined">CGPA <?=$grade['P1CGPA']?></span>
        </p>
    </div>

</body>

</html>