<?php include 'config.php';

$sname=$_POST["sname"];
$fname=$_POST["fname"];
$mname=$_POST["mname"];
$email=$_POST["email"];
$dob=$_POST["dob"];
$gender=$_POST["gender"];
$phone=$_POST["phneno"];
$group=$_POST["group"];
$haltckt=$_POST["hltckt"];
$semester=$_POST["semester"];
$year=$_POST["year"];
$aadhar=$_POST["aadhar"];
$address=$_POST["address"];
$address2=$_POST["address2"];
$mandal=$_POST["mandal"];
$city=$_POST["city"];
$pincode=$_POST["pincode"];
$state=$_POST["state"];
$image=$_POST["img"];
$signature=$_POST["signature"];


$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn){
    die("connection failed:".mysqli_connect_error());
}
$sql = "INSERT INTO `students` ( `sname`, `fname`, `mname`, `email`, `dob`,`gender`, `phone`, `group`, `haltckt`,`semester`, `year`,`aadhar`, `address`,`address2`,  `mandal`, `city`, `pincode`, `state`, `image`,`signature`) 
VALUES ('".$sname."','".$fname."','".$mname."','".$email."','".$dob."','".$gender."','".$phone."','".$group."','".$haltckt."','".$semester."','".$year."','".$aadhar."','".$address."','".$address2."','".$mandal."','".$city."','".$pincode."','".$state."','".$image."','".$signature."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} 
else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
