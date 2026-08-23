
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
$sql = "select * from enrolledview where id=".$_GET['id'];
$result = $conn->query($sql);
$row=mysqli_fetch_array($result);
}
?>

<!--Php end here-->
<div class="page-wrapper">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card">
<div class="card-body">
      
<form  action="updateenrolled.php?id=<?php echo $_GET["id"] ?>" method="post">

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Student Name</h4></p>
 <input type="text"  class="form-control" name="sname" id="sname" value="<?php echo $row['sname']; ?>">

</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4> Hallticket</h4></p>
<input type="text" class="form-control" value="<?php echo $row['haltckt']; ?>" id="haltckt" name="haltckt">
</div></div></div>
 
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Group </h4></p>
<input type="text" class="form-control" value="<?php echo $row['group']; ?>" id="group" name="group">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Subject-1</h4></p>
<input type="text" class="form-control" name="S1" id="S1" value="<?php echo $row['S1']; ?>">
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Subject-2</h4></p>
<input type="text" class="form-control" name="S2" id="S2" value="<?php echo $row['S2']; ?>">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Subject-3</h4></p>
<input type="text" class="form-control" name="S3" id="S3" value="<?php echo $row['S3']; ?>">
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Subject-4</h4></p>
<input type="text" class="form-control" name="S4" id="S4" value="<?php echo $row['S4']; ?>">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Subject-5</h4></p>
<input type="text" class="form-control" name="S5"  id="S5" value="<?php echo $row['S5']; ?>">
</div></div></div>


<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Subject-6</h4></p>
<input type="text" class="form-control" name="S6"  id="S6"  value="<?php echo $row['S6']; ?>">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Subject-7</h4></p>
<input type="text" class="form-control" name="S7"  id="S7" value="<?php echo $row['S7']; ?>">
</div></div></div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Subject-8</h4></p>
<input type="text" class="form-control" name="S8"  id="S8"  value="<?php echo $row['S8']; ?>">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Subject-9</h4></p>
<input type="text" class="form-control" name="S9"  id="S9" value="<?php echo $row['S9']; ?>">
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Subject-10</h4></p>
<input type="text" class="form-control" name="S10"  id="S10"  value="<?php echo $row['S10']; ?>">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Elective-1</h4></p>
<input type="text" class="form-control" name="E1"  id="E1" value="<?php echo $row['E1']; ?>">
</div></div></div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Elective-2</h4></p>
<input type="text" class="form-control" name="E2"  id="E2"  value="<?php echo $row['E2']; ?>">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Elective-3</h4></p>
<input type="text" class="form-control" name="E3"  id="E3" value="<?php echo $row['E3']; ?>">
</div></div></div>


<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Elective-4</h4></p>
<input type="text" class="form-control" name="E4"  id="E4"  value="<?php echo $row['E4']; ?>">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Enrolled Date</h4></p>
<input type="text" class="form-control" name="ENROLLEDDATE"  id="ENROLLEDDATE" value="<?php echo $row['ENROLLEDDATE']; ?>">
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>CHALLAN NUMBER</h4></p>
<input type="text" class="form-control" name="CHALLANNUMBER"  id="CHALLANNUMBER"  value="<?php echo $row['CHALLANNUMBER']; ?>">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>FEEPAID</h4></p>
<input type="text" class="form-control" name="FEEPAID"  id="FEEPAID" value="<?php echo $row['FEEPAID']; ?>">
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
