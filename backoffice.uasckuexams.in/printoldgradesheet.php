<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->

<style>
*{
    margin:0px;
    padding:0px;
    font-family:arial;
    font-weight:bold;
    font-size:11.2px;
}
table{
   width: 100%;
/*border:1px solid;*/
}
table tr{
    vertical-align:top;
}

/*td{*/
/*    border:1px solid;*/
   
/*}*/

</style>
</head>
<?php

include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$res = "select * from  students where haltckt='".$_GET['ht']."'";
$result = $conn->query($res);


$exam="select * from examsmaster where EXID=".$_GET['exid'];

$examname=mysqli_query($conn,$exam);
//echo $exam;

$examtype="";
if($result->num_rows>0){

while($row=$result->fetch_assoc())
 { 
     
     
  break;
 }
}
if($examname->num_rows>0){
while($ex=$examname->fetch_assoc())
 { 
  break;
 }
}
}
?>
  
<body style='min-width:20cm;max-width:20cm;margin-left:auto;margin-right:auto;margin-top:0px;padding:0px;'>

<table style="min-width:20cm;max-width:20cm;margin-top:0.55cm;">
<tr>
  <td style="padding-left:2.7cm;width:13cm;vertical-align:top;">
      <?php 
      echo '<p style="padding-bottom:0.1cm;">'.$ex['EXAMNAME']."</p>";
      echo '<p style="padding-bottom:0.1cm;">'.$row['sname'].'<p>';
      echo '<p style="padding-bottom:0.1cm;">'.$row['mname'].'<p>';
      echo '<p>'.$row['fname'].'<p>';
      ?>
     </td>

<td style="vertical-align:top;">
    <?php 
    echo '<p style="padding-bottom:0.1cm;">';
    echo date("d/m/Y");
    echo "</p>";
    echo '<p style="padding-bottom:0.1cm;">'.$row['haltckt']."</p>";
    echo '<p>'.$row['aadhar']."</p>";
    ?>
    </td>
</tr>
</table>
<br>
<br>
<br>
<table style="width:19cm;margin-top:4px;max-height:3cm;min-height:3cm;vertical-align:top;" >
<tr>
<?php 
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$sql = "select * from RESULTS where EXAMID=".$_GET['exid']." and HALLTICKET='".$_GET['ht']."'";
//echo $sql;

$studentgpa="select * from gpas where HALLTICKET='".$_GET['ht']."' and EXAMID=".$_GET['exid']."";
//echo $studentgpa;
$getgpa=$conn->query($studentgpa);
$gpa=mysqli_fetch_assoc($getgpa);
//print_r($gpa);

 $examtype="";
$result = $conn->query($sql);

if($result->num_rows>0){

while($row=$result->fetch_assoc())
 { 
   
   // print_r($row);
    //echo "<br>";
        echo "<tr style='vertical-align:center;padding:4px;'>";
        echo "<td style=padding-right:4cm;font-size:12px; margin-bottom:0px;>".strtoupper($row['PAPERNAME'])."</td>";
     echo "<td style='width:3cm;font-size:12px;margin-bottom:0px;text-align:center;' >" . $row['CREDITS'] . "</td>";
       echo  "<td style='font-size:12px;margin-bottom:0px; text-align:center;' >" ;
       
       if($row['GRADE']=='AB'){
            echo $row['GRADE'];
        }
        else if($gpa['RESULT']=='M'){
            echo "MP";
        }
        else{
            echo $row['GRADE'];
        }
        
       
       echo  "</td>";
     
  
     echo "</tr>";
}
}
}
?>
<?php
mysqli_close($conn);
?>
</tr>
</table>

<!--<br><br><br><br><br><br><br><br>-->
<!--<table style="margin-top:30px;max-height:0cm;min-height:0cm;">-->
 <style>
    .result{
        /*border:1px solid;*/
        position:absolute;
        top:10.6cm;
       
    }
    
</style>
<table  class='result' style="max-height:0cm;min-height:0cm;"> 
<?php


echo "<tr><td colspan='3'style='font-size:12px;padding-left:1.5cm;'>"; 
if($gpa['RESULT']=='P'){ 
    echo "PASSED";
} 
else if($gpa['RESULT']=='R'){ 
    echo "PROMOTED";
} 
else if($gpa['RESULT']=='M'){ 
    echo "MALPRACTICE";
} 
else{
    echo "<script>alert('Error Please contact Administrator');</script>";
}

    echo "</td></tr>";
    echo "<br>";
 echo "<tr><td colspan='3'style='font-size:12px;padding-left:1.5cm;'>";

if($gpa['RESULT']=='P'){ 
    echo $gpa['SGPA'];
} 
 
 echo "</td></tr>";

?>    
<?php
mysqli_close($conn);
?>
</table>
</body>

</html>