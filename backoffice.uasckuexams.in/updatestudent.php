<?php 
/*
session_start();
if(!(isset($_SESSION['login'])))
{
    header("location:index.php?sessionexpired");
}*/
?>
<?php include 'config.php';
// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// exit;
$sname=$_POST["sname"];
$fname=$_POST["fname"];
$mname=$_POST["mname"];
$email=$_POST["email"];
$dob=$_POST["dob"];
$gender=$_POST["gender"];
$phone=$_POST["phone"];

$group=$_POST["group"];
$haltckt=$_POST["haltckt"];
$sem=$_POST["semester"];
$curryear=$_POST["year"];
$aadhar=$_POST["aadhar"];

$address=$_POST["address"];

$address2=$_POST["address2"];

$mandal=$_POST["mandal"];
$city=$_POST["city"];
$pincode=$_POST["pincode"];
$state=$_POST["state"];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn){
    die("connection failed:".mysqli_connect_error());
}
$sql= 'UPDATE  students SET sname="'.$sname.'",fname="'.$fname.'",mname="'.$mname.'",email="'.$email.'",dob="'.$dob.'",gender="'.$gender.'",`group`="'.$group.'",sem="'.$sem.'",haltckt="'.$haltckt.'",phone="'.$phone.'", aadhar="'.$aadhar.'", curryear='.$curryear.', address="'.$address.'",address2="'.$address2.'",mandal="'.$mandal.'", city="'.$city.'",state="'.$state.'", pincode='.$pincode.' WHERE stid='.$_GET["id"];

if ($conn->query($sql) === TRUE) {


    header("Location:viewstudents.php?record=true");

} 
else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
