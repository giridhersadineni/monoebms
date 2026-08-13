<?php 
include "../config.php";
$hallticket=$_GET['hallticket'];
//$sql = "select * from rholdernew where HALLTICKET='$hallticket' AND GRADE NOT IN ('F','AB')";
$getsumofcredits="select sum(CREDITS) as TOTALCREDITS from rholdernew where HALLTICKET='$hallticket' and GRADE NOT IN ('F','AB')";
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
}
else{
    //echo $getsumofcredits;
    $result=$conn->query($getsumofcredits);
    $data=mysqli_fetch_assoc($result);
    $totalcredits=$data['TOTALCREDITS'];
    echo $totalcredits;
}
?>