year
<?php include 'header.php'?>

<!--Php start here-->

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$sql = "select * from students where stid=".$_GET['stid'];
$result = $conn->query($sql);
$row=mysqli_fetch_array($result);
}
?>

<!--Php end here-->
<div class="page-wrapper">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-12">
<div class="card">
<div class="card-body">

<form  action="updatestudent.php?id=<?php echo $_GET["stid"] ?>" method="post">
<h1>Update Information</h1>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Student Name</h4></p>
 <input type="text"  class="form-control" name="sname" id="sname" value="<?php echo $row['sname']; ?>">

</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Father Name</h4></p>
<input type="text" class="form-control" value="<?php echo $row['fname']; ?>" id="fname" name="fname">
</div></div></div>
 
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Mother Name</h4></p>
<input type="text" class="form-control" value="<?php echo $row['mname']; ?>" id="mname" name="mname">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Email</h4></p>
<input type="email" class="form-control" name="email" id="email" value="<?php echo $row['email']; ?>">
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Dob</h4></p>
<input type="date" class="form-control" name="dob" id="dob" value="<?php echo $row['dob']; ?>" readonly>
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Gender</h4></p>
<input type="text" class="form-control" name="gender" id="gender" value="<?php echo $row['gender']; ?>" readonly>
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Phone Number</h4></p>
<input type="text" class="form-control" name="phone" id="phone" value="<?php echo $row['phone']; ?>">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Group</h4></p>
<input type="text" class="form-control" name="group"  id="group" value="<?php echo $row['group']; ?>" readonly>
</div></div></div>


<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Hallticket</h4></p>
<input type="text" class="form-control" name="haltckt"  id="haltckt"  value="<?php echo $row['haltckt']; ?>" readonly>
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Semester</h4></p>
<input type="text" class="form-control" name="semester"  id="semester" value="<?php echo $row['sem']; ?>" readonly>
</div></div></div>


<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Year</h4></p>
<input type="text" class="form-control " name="year" id="year" value="<?php echo $row['curryear']; ?>" readonly>
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Aadhar</h4></p>
<input type="text" class="form-control" name="aadhar" id="aadhar" value="<?php echo $row['aadhar']; ?>">
</div></div></div>


<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Address</h4></p>
<input type="text" class="form-control" name="address" id="address" value="<?php echo $row['address']; ?>" >
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Area</h4></p>
<input type="text" class="form-control" name="address2" id="address2" value="<?php echo $row['address2']; ?>">
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Manadal</h4></p>
<input type="text" class="form-control" name="mandal" id="mandal" value="<?php echo $row['mandal']; ?>">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>City</h4></p>
<input type="text" class="form-control" name="city" id="city" value="<?php echo $row['city']; ?>"  required>
</div></div></div>


<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>State</h4></p>
<input type="text" class="form-control " name="state" id="state" value="<?php echo $row['state']; ?>"  required>
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Pincode</h4></p>
<input type="text" class="form-control" name="pincode" id="pincode" value="<?php echo $row['pincode']; ?>"  required>
</div></div></div>
  <center>
  <input type="submit" class="btn btn-primary" name="submit" value="Update">
</center>
 </form>
 </div>
</div>
     </div>
 </div>
 
                <!-- End PAge Content -->
 </div>
<?php include 'footer.php'?>
