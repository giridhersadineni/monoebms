<?php 
/*
session_start();
if(!(isset($_SESSION['login'])))
{
    header("location:index.php?sessionexpired");
}*/
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php 
include 'config.php';
include 'projectorm.php';

// $checkaadhar="select * from students where aadhar=".$_GET['aadhar'];
// $rows=get_result($checkaadhar);
// var_dump($rows);
// if($rows->num_rows>0){
//     echo '"<script> 
//         swal("Aadhar Number already exists in the system")
//         .then((value) => {
//             window.location.href = "editdetails.php";
//         });
//         </script>"';
// }


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
$caste=$_POST['caste'];
$subcaste=$_POST['subcaste'];
$challenged_quota=$_POST['challenged_quota'];
$gender=$_POST['gender'];
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn){
    die("connection failed:".mysqli_connect_error());
}
echo $sql= 'UPDATE students SET onboarding_complete=1, aadhar="'.$aadhar.'", sname="'.$sname.'",fname="'.$fname.'", mname="'.$mname.'", gender="'.$gender.'", email="'.$email.'",dob="'.$dob.'",gender="'.$gender.'",`group`="'.$group.'",sem="'.$sem.'",haltckt="'.$haltckt.'",phone="'.$phone.'", curryear='.$curryear.', address="'.$address.'",address2="'.$address2.'",mandal="'.$mandal.'", city="'.$city.'",state="'.$state.'", caste="'.$caste.'",subcaste="'.$subcaste.'", pincode='.$pincode. ', challenged_quota="'.$challenged_quota.'" WHERE stid='.$_COOKIE["stid"];

if ($conn->query($sql) === TRUE) {


    header("Location:welcome.php?action=updatedinfosuccess");

} 
else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
