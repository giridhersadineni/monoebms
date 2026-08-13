<?php

if(isset($_POST['submit'])){
    
if($_FILES['csvfile']['name']){

$servername = "localhost";
$dbuser = "atech_ebmsarts";
$dbpwd = "giridhersadineni";
$dbname = "atech_ebms";

$con=mysqli_connect($servername,$dbuser,$dbpwd,$dbname);
// Check connection
if (mysqli_connect_errno())
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$arrFileName = explode('.',$_FILES['csvfile']['name']);

//print_r($arrFileName);

if($arrFileName[1] == 'csv'){

$handle = fopen($_FILES['csvfile']['tmp_name'], "r");


while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

$item1 = mysqli_real_escape_string($con,$data[0]);
$item2 = mysqli_real_escape_string($con,$data[1]);
$item3 = mysqli_real_escape_string($con,$data[2]);
$item4 = mysqli_real_escape_string($con,$data[3]);
$item5 = mysqli_real_escape_string($con,$data[4]);
$item6 = mysqli_real_escape_string($con,$data[5]);
$item7 = mysqli_real_escape_string($con,$data[6]);
$item8 = mysqli_real_escape_string($con,$data[7]);
$item9 = mysqli_real_escape_string($con,$data[8]);
$item10 = mysqli_real_escape_string($con,$data[9]);
$item11 = mysqli_real_escape_string($con,$data[10]);
$item12 = mysqli_real_escape_string($con,$data[11]);
$item13 = mysqli_real_escape_string($con,$data[12]);
$item14 = mysqli_real_escape_string($con,$data[13]);
$item15 = mysqli_real_escape_string($con,$data[14]);
$item16 = mysqli_real_escape_string($con,$data[15]);
$item17 = mysqli_real_escape_string($con,$data[16]);
$item18= mysqli_real_escape_string($con,$data[17]);

$import="INSERT into rholdernew(RHID,EXAMID,PAPERCODE,HALLTICKET,EID,CODE,
EXT,ETOTAL,INT,ITOTAL,RESULT,CREDITS,MARKS,TOTALMARKS,PERCENTAGE,GRADE,GPV,GPC) values('$item1','$item2','$item3','$item4','$item5',
'$item6','$item7','$item8','$item9','$item10','$item11','$item12','$item13','$item14','$item15,'$item16,'$item17','$item18')";

mysqli_query($con,$import);
}
fclose($handle);
header("location:csvfiles.php?id=success");
}
else{
    echo "ERROR IN UPLOADING";
    
}
}
}
?>
