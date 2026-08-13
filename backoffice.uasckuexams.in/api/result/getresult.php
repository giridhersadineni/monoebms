<?php 
include "../config.php";
if(isset($_GET['HALLTICKET']))
{
    $hallticket=$_GET['HALLTICKET'];
    if(isset($_GET['SEM'])){
        $semester=$_GET['SEM'];
        $sql = "select * from rholdernew where HALLTICKET='$hallticket' AND SEMESTER='$semester' GRADE NOT IN ('F','AB')";
    }
    else if(isset($_GET['PART'])){
        $part=$_GET['PART'];
        $sql = "select * from rholdernew where HALLTICKET='$hallticket' AND PART='$part' GRADE NOT IN ('F','AB')";
    }
    else{
         $sql = "select * from rholdernew where HALLTICKET='$hallticket' AND GRADE NOT IN ('F','AB')";
    }
} 
else{
    echo "{[status:'Parameter error']}";
}

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
$resultset=array();
if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
}
else{
    //echo $sql;
    $result=$conn->query($sql);
    if ($result->num_rows > 0){
        while ($row = mysqli_fetch_assoc($result)) {
        array_push($resultset,$row);
        }
        echo json_encode($resultset);
    } 
    else {
       echo json_encode($result);
       echo $sql;
    }
}

?>