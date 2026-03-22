<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/arts.png">
    <title>University Arts & Science College</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>


<?php

if (isset($_GET['error'])) {
    if ($_GET['error'] == "duplicateaadhar") {
        echo '<script>alert("Student Already Registered");</script>';

    }
}

include 'config.php';
$conn = mysqli_connect($servername, $dbuser, $dbpwd, "atech_ebms2020");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_POST['Submit'])) {
    $aadhar = $_POST['aadhar'];

    $sql = "SELECT * FROM students WHERE aadhar='$aadhar'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {

    }
}
?>
<!--Profile Pic-->
<?php
$imageupload = 0;
$signupload = 0;

if (isset($_POST['Submit'])) {

    //FILE UPLOAD CODE
    $target_dir = "/home/atech/uascku.ac.in/ebms/students/upload/signatures/";
    $target_file = $target_dir . basename($_FILES["signature"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (move_uploaded_file($_FILES["signature"]["tmp_name"], $target_dir . $_POST['aadhar'] . ".jpg")) {

        $imageupload = 1;

    } else {
        echo "Sorry, there was an error uploading your file.";
    }

}

?>
<?php
if (isset($_POST['Submit'])) {
    //FILE UPLOAD CODE
    $target_dir = "/home/atech/uascku.ac.in/ebms/students/upload/images/";
    $target_file = $target_dir . basename($_FILES["images"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


    if (move_uploaded_file($_FILES["images"]["tmp_name"], $target_dir . $_POST['aadhar'] . ".jpg")) {
        $signupload = 1;

    } else {
        echo "Sorry, there was an error uploading your file.";

    }

}

?>


<?php
include 'config.php';

if (isset($_POST['Submit']) and $imageupload == 1 and $signupload == 1) {
    $sname = $_POST["sname"];
    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $email = $_POST["val-email"];
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $phone = $_POST["val-phoneus"];
    $medium = $_POST["medium"];
    $group = $_POST["group"];
    $course = $_POST['course'];
    $haltckt = $_POST["haltckt"];
    $sem = $_POST["semester"];
    $year = $_POST["year"];
    $aadhar = $_POST["aadhar"];
    $address = $_POST["address"];
    $address2 = $_POST["address2"];
    $mandal = $_POST["mandal"];
    $city = $_POST["city"];
    $state = $_POST["state"];
    $pincode = $_POST["pincode"];
    $scheme=$_POST["scheme"];

    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    }
    $sql = "INSERT INTO `students` ( `sname`, `fname`, `mname`, `email`, `dob`,`gender`, `phone` , `medium`,`group`, `course`,`haltckt`, `sem`,`curryear`,`aadhar`, `address`,`address2`,  `mandal`, `city`, `pincode`, `state`,`SCHEME`)
VALUES ('" . $sname . "','" . $fname . "','" . $mname . "','" . $email . "','" . $dob . "','" . $gender . "','" . $phone . "','" . $medium . "','" . $group . "','" . $course . "','" . $haltckt . "','" . $sem . "','" . $year . "','" . $aadhar . "','" . $address . "','" . $address2 . "','" . $mandal . "','" . $city . "','" . $pincode . "','" . $state. "','" . $scheme."')";

    if ($conn->query($sql) === true) {
        header("Location:dashboard.php?accountcreated=true");

    } else {
        header("Location:regerror.php?error=aadharduplicate");
    }

    $conn->close();

}

?>
<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {

    $coursequery = "select * from students";

    $courseresults = $conn->query($coursequery);
}
?>

<!-- tooltip script-->
<script>
   (function () {
        $("[data-toggle = 'tooltip']").tooltip();
         });
</script>

<!-- validation script-->
<script>
function check(){
    var aadhar=document.getElementById('aadhar');
    aadharcleaned=aadhar.value.replace(/\s/g,'');
    aadhar.value=aadharcleaned;
    var aadharvalue = parseInt(aadhar.value);
    var url='../api/course/getaadhar.php'
    var msg=document.getElementById('message');



   
    if(isNaN(aadhar.value)){
       alert("Don't give any Spaces and Special Characters")
        msg.innerHTML="Not a valid aadhar";
   
    }
    else{
        if(aadharvalue>100000000000 && aadharvalue <=999999999999){

            msg.innerHTML="Valid Aadhar";
        }
        else{
            msg.innerHTML="Not a valid aadhar please check all digits";
           

        }
 }
    return true;
}
</script>


<style>

h2{
    background-color:#9999ff;
}
</style>




 <body>

<div class="container" style="box-shadow:0px 0px 10px grey; background:#fff;">
<div class="header-middle">
<div class="row">
<div class="col-sm-12">
<div class="bgtop">
<div class="logo">
    <img src="images/arts.png" alt="" width="85" height="85" style="float:left;"/>
     <h1><span>University Arts & Science College<br><h4>An Autonomous Institute under Kakatiya Universtiy</h4></span></h1>
</div>
</div>
</div>
</div>
</div>
<br>
<div class="bodyContainer">
<center>
<h2>  Student Registration Form<h2>
</center>
</div><br><br>


<form action="registerstudent.php" method="POST"  class="form-valide" enctype="multipart/form-data">

<div class="row">
 <div class="col-xs-9 col-sm-3 ">
       <p class="text-muted m-b-15 f-s-12"><h4>Aadhaar Number <span class="text-danger">*</span></h4></p>
         </div>
<div class="col-md-9">
        <div class="form-group">
    <input type="text" name="aadhar" id="aadhar" onchange="check()" title="Enter AADHAR NUMBER" placeholder="Enter Aadhar" class="form-control" required>

     <p id="message" class="text-center text-success" ></p>
           </div>
     </div>
</div><br>

	<fieldset>

<legend class="subHeader"> Basic Details </legend>

<fieldset>

<legend> Personal Details </legend>
<span style="color:red" >(*)REQUIRED FIELDS</span><br>
<br>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<label class="text-muted m-b-15 f-s-12"><h4> Fullname <span class="text-danger">*</span></h4></label>
<input type="text" class="form-control" onkeyup="this.value = this.value.toUpperCase();" id="sname"
 placeholder="Enter name"  data-toggle="tooltip" title="Enter your Name!"  name="sname"  required>
 <span class="message"></span>
</div></div>


<div class="col-md-6">
<div class="form-group">
<label class="text-muted m-b-15 f-s-12" ><h4>Father name <span class="text-danger">*</span></h4></label>
<input type="text" class="form-control " required onkeypress="return isAlpha(event);"title="Enter Father's Name"  onkeyup="this.value = this.value.toUpperCase();" placeholder="Enter fathername" name="fname" required>
</div></div></div>



<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4> Mother Name <span class="text-danger">*</span></h4></p>
<input type="text" class="form-control" required onkeypress="return isAlpha(event);"title="Enter Mother's Name"
onkeyup="this.value = this.value.toUpperCase();" placeholder="Enter name" name="mname"  required>
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12" for="val-email"><h4>Email <span class="text-danger">*</span></h4></p>
<input type="email" class="form-control " id="val-email" required onkeypress="return isAlpha(event);"title="Enter Email"  placeholder="Enter email" name="val-email" required>
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4> Date of birth <span class="text-danger">*</span></h4></p>
<input type="date" class="form-control" onkeypress="return isAlpha(event);"title="Enter Date of birth" placeholder="Enter date" name="dob" required>
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Gender <span class="text-danger">*</span></h4></p>
<select name="gender"  id="gender" class="form-control" required>
    <option name="gender" value="">--Please Select--</option>
    <option name="gender" value="male">Male</option>
     <option  name="gender" value="female">Female</option>
    </select>
</div></div></div>


<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12" for="val-phoneus"><h4>Phone Number<span class="text-danger">*</span> </h4></p>
<input type="text" class="form-control" id="val-phoneus"   onkeypress="return isAlpha(event);"title="Enter Phone number" placeholder="Enter number" name="val-phoneus" required>
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12" for=""><h4>Hallticket <span class="text-danger">*</span></h4></p>
<input type="text" class="form-control"   onkeypress="return isAlpha(event);"title="Enter Hallticket Number" placeholder="Enter hallticket" name="haltckt" required>
</div></div>
</div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Current Year <span class="text-danger">*</span></h4></p>

<select name="year"  id="year"  class="form-control" required>
    <option name="year" value="" >--Please Select Year--</option>
    <option name="year" value="1">I Year</option>
     <option  name="year" value="2">II Year</option>
     <option  name="year" value="3">III Year</option>
    </select>

</div>
</div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Semester <span class="text-danger">*</span></h4></p>
<select name="semester"  id="semester"  class="form-control" required>
    <option name="semester" value="" >--Please Select Semester--</option>
    <option name="semester" value="1">I SEM -1</option>
     <option  name="semester" value="2">II SEM -2</option>
     <option  name="semester" value="3">III SEM -3</option>
     <option name="semester" value="4">IV SEM -4</option>
     <option  name="semester" value="3">V SEM -5</option>
     <option name="semester" value="4">VI SEM -6</option>
    </select>
</div></div></div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Medium <span class="text-danger">*</span></h4></p>
<select name="medium"   id="medium" class="form-control" required>
    <option name="english" value="" >--Please Select Medium--</option>
    <option name="english" value="EM">English</option>
    <option name="telugu" value="TM">Telugu</option>
 </select>
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Scheme <span class="text-danger">*</span></h4></p>
<select name="scheme"   id="scheme" class="form-control" required>
    <option name="scheme" value="" >--Please Select Shceme--</option>
    <option name="scheme" value="2016">2016</option>
    <option name="scheme" value="2017">2017</option>
    <option name="scheme" value="2018">2018</option>
    <option name="scheme" value="2019">2019</option>
    <option name="scheme" value="2020">2020</option>
    <option name="scheme" value="2021">2021</option>
 </select>
 </div>
 
</div>
</div>


<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Course <span class="text-danger">*</span></h4></p>

<select name="course"  id="course"  class="form-control" required>
        <option name="course" value="" >--Please Select Course-- </option>
        <option name="course"value="BA">BA</option>
        <option name="course"value="BCOM">BCOM</option>
        <option name="course"value="BSC">BSC</option>

</select>

</div>
</div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Group <span class="text-danger">*</span></h4></p>
<select name="group"   id="group" class="form-control" placeholder="Select group" required>
    <option name="group" value="" >--Please Select Group--</option>


    </select>

</div>
</div>
</div>
<script>
 function updatecourse()
      {
        var group = document.getElementById("course");
        var course = group.options[group.selectedIndex].value;
        var sem=document.getElementById("semester");
        var semester=sem.options[sem.selectedIndex].value;
        var url = '../api/course/getcourses.php?course=' + course+'&medium='+document.getElementById('medium').options[medium.selectedIndex].value+"&s="+semester;
        console.log("sending request"+url);

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function () {

          if(xhr.readyState == 4 && xhr.status == 200)
           {
      console.log(xhr.responseText);
    document.getElementById('group').innerHTML="";

        var course=JSON.parse(xhr.responseText);
        course.forEach(element => {

        document.getElementById('group').innerHTML+='<option value="'+element.courseid+'">'+element.coursename+'</option>"';
            });
 }
   }
        xhr.send();
      }
      var group = document.getElementById("course");
      group.addEventListener("change",  updatecourse);

</script>





</fieldset>

<fieldset>
<legend class="subHeader"> Address Details  </legend>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Address Line1 <span class="text-danger">*</span></h4></p>
<input type="text" class="form-control"  onkeyup="this.value = this.value.toUpperCase();" placeholder="Address Line1" name="address" required>
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Street/Village/Area</h4></p>
<input type="text" class="form-control"   onkeyup="this.value = this.value.toUpperCase();" placeholder="Street" name="address2" required>
</div></div>
</div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Mandal</h4></p>
<input type="text" class="form-control"   onkeyup="this.value = this.value.toUpperCase();" placeholder="Mandal" name="mandal" required>
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>City</h4></p>
<input type="text" class="form-control"  onkeyup="this.value = this.value.toUpperCase();" placeholder="City"name="city" required>
</div></div>
</div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>State</h4></p>
<input type="text" class="form-control"  onkeyup="this.value = this.value.toUpperCase();" placeholder="State" name="state" required>
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Pincode</h4></p>
<input type="text" class="form-control"  placeholder="Pincode" name="pincode" required>
</div></div>
</div>
</fieldset>

<fieldset>
<legend class="subHeader"> Photograph and Signature </legend>
<div class="row">
  </div>
<div style="height: 15px;"></div>
	<p>To be uploaded during the submission of online application
			form</p>
	<p>Photograph Size should be  [3.5<sup>cm</sup>x4.5<sup>cm</sup>] and size should not exceed 100 KB</p>
<p>Signature of the candidate shall be scanned [3.5<sup>cm</sup>x1.5<sup>cm</sup>] separately and uploaded here. </p>


<table class="table table-hover">
	<thead>
		<tr>
			<th>File Format</th>
			<th>File Size</th>
			<th>Dimension</th>
			<th>Sample</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Photograph of Candidate JPEG format</td>
			<td>100 KB</td>
			<td>3.5cm x 4.5cm</td>
<td>		<img src="images/samplephoto.jpg" alt="" width="85" height="85" style="float:left;"/></td>
		</tr>
		<tr>
			<td>Signature of Candidate JPEG format</td>
			<td>50 KB</td>
			<td>3.5cm x 1.5cm</td>
			<td>		<img src="images/signature.jpg" alt="" width="85" height="85" style="float:left;"/></td>
		</tr>
	</tbody>

</table>

<div class="form-group">
<label>
 Upload Photo: <span class="text-danger">*</span></label>
<input type="file"  class="form-control" id="image" onchange="validate();" name="images" required>
<br>


 <div class="form-group">
 Upload signature:<span class="text-danger">*</span>
 <input type="file" class="form-control" placeholder="upload signature" id="signature" name="signature" required >
 </div>

<p align="center"> <input type="submit" class="btn btn-primary"  value="Submit" name="Submit">
</div>



</div>
</div>

</form>
<!--SCRIPT START HERE-->




<script language="javascript">

document.getElementById('image').onchange = function(){
        var photosize = document.getElementById('image').files[0].size;
        if(photosize>100000){
        alert("Photo size should be less than 100KB: "+"size is "+photosize);
        document.getElementById('image').value="";
        }
}

document.getElementById('signature').onchange = 
    function(){
        var filesize = document.getElementById('signature').files[0].size;
        if(filesize>50000){
                alert("signature size should be less than 50kb");
                document.getElementByID('signature').value="";
        }
    }


/*
function validate()
{
var image = document.getElementById('images');
var fileName = image.value;
var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
if(ext == "gif" || ext == "GIF" || ext == "JPEG" || ext == "jpeg" || ext == "jpg" || ext == "JPG" || ext == "doc")
{

return true;
}
else
{
alert("Upload Gif or Jpg images only");
image.focus();

return false;
}
}*/
</script>


            <!-- footer -->

            <!-- End footer -->
    <!-- All Jquery -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>

    <!-- Form validation
    <script src="js/lib/form-validation/jquery.validate.min.js"></script>
    <script src="js/lib/form-validation/jquery.validate-init.js"></script>-->
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/scripts.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>


</body>


</html>