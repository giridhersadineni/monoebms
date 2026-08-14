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
$res = "select * from  students where haltckt=".$_GET['ht'];
$result = $conn->query($res);

$exam="select * from examsmaster where EXID=".$_GET['examid'];

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
<br>
<table style="min-width:20cm;max-width:20cm;margin-top:1.5px;">
<tr>
  <td style="padding-left:3cm;"><?php echo strtoupper($ex['EXAMNAME']) ?></td>
  <td colspan='2'  style="padding-left:6cm;padding:4px;"><?php echo date("d/m/Y");?></td>
</tr>
<tr>
  <td style="padding-left:3cm;"><?php echo $row['sname'] ?> </td>
   <td colspan='2' style="padding-left:6cm;padding:3px;"><?php echo $row['haltckt'] ?></td>
</tr>
<tr>
  <td style="padding-left:3cm;"><?php echo $row['mname'] ?></td>
    <td colspan='2' style="padding-left:6cm;  padding:1.5px;"><?php echo $row['aadhar']?></td>
</tr>
<tr>
  <td style="padding-left:3cm; "><?php echo $row['fname'] ?></td>
</tr>
</table>
<br>
<br>
<br>

<table style="width:19cm;margin-top:1px;max-height:3cm;min-height:3cm;" >
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
$sql = "select * from  rholdernew where EID=".$_GET['id'];
//echo $sql;
$gpt=0;
 $totalcredit=0;
 $passed=1;
 $examtype="";
$result = $conn->query($sql);

if($result->num_rows>0){

while($row=$result->fetch_assoc())
 { 
   

        echo "<tr style='vertical-align:center;padding:4px;'>";
        echo "<td style=padding-right:4cm;font-size:12px; margin-bottom:0px;>".strtoupper($row['PAPERNAME'])."</td>";
     echo "<td style='width:3cm;font-size:12px;margin-bottom:0px;text-align:center;' >" . $row['CREDITS'] . "</td>";
       echo  "<td style='font-size:12px;margin-bottom:0px; text-align:center;' >" . $row['GRADE'] . "</td>";
     
   $gpt+=$row['GPC'];
   $totalcredit+=$row['CREDITS'];
   if($row['RESULT']=="F" || $row['RESULT']=="AB"){
       $passed=0;
    }
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
<style>
    .result{
        /*border:1px solid;*/
        position:absolute;
        top:10cm;
       
    }
    
</style>
<table  class='result' style="max-height:0cm;min-height:0cm;">
  
<?php

echo "<tr><td colspan='3'style='font-size:12px;padding-left:1cm;'>"; 
if($passed==1){ 
    echo "PASSED";
    } else {
        echo "PROMOTOED";
    }";</td></tr>";
    echo "<br>";
 echo "<tr><td colspan='3'style='font-size:12px;padding-left:1cm;'>";
 
 echo round($gpt/$totalcredit,PHP_ROUND_HALF_UP);
 
 echo "</td></tr>";

?>    
<?php
mysqli_close($conn);
?>
</table>
</body>

</html>