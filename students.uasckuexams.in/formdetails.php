<?php include 'config.php';

//db connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//initialize message variable
$msg ="";

//if upload button is clicked..

//Get Image name.
$image = $_FILES['image']['name'];

  	// image file directory
 $target = "images/".basename($image);

 //Allow certain file formats
 if($image != "jpg" && $image= "png" && $image= "jpeg"
 && $image != "gif" ) {
     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
     $uploadOk = 0;
 }

// Check file size
if ($_FILES["image"]["size"] > 1000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

 $sql = "INSERT INTO students (image) VALUES ('$image')";
 
  	// execute query
 mysqli_query($conn,$sql);

  if (move_uploaded_file($_FILES['image']['tmp_name'], $target))
   {
  		$msg = "Image uploaded successfully";
  }else{
  		$msg = "Failed to upload image";
  	}
?>


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
$sem=$_POST["semester"];
$year=$_POST["year"];
$aadhar=$_POST["aadhaar"];
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
$sql = "INSERT INTO `students` ( `sname`, `fname`, `mname`, `email`, `dob`,`gender`, `phone`, `group`, `haltckt`, `sem`,`year`,`aadhar`, `address`,`address2`,  `mandal`, `city`, `pincode`, `state`) 
VALUES ('".$sname."','".$fname."','".$mname."','".$email."','".$dob."','".$gender."','".$phone."','".$group."','".$haltckt."','".$sem."','".$year."','".$aadhar."','".$address."','".$address2."','".$mandal."','".$city."','".$pincode."','".$state."')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} 
else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

