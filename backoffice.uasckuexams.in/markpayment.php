<?php include 'header.php';?>

<?php
include 'config.php';
  $id="";
$sname= "";
$course="";
$FEEPAID="";
$FEEAMOUNT="";
$EXAMNAME="";
if (isset($_GET['getdetails'])) {

  $STUDENTID=$_GET['studentid'];

  $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
$query="SELECT  * FROM enrolledview WHERE id=".$STUDENTID;
$result=mysqli_query($conn,$query);
if($result->num_rows>0){
  while($row=mysqli_fetch_array($result))
  {
    $id=$row['ID'];
    $sname=$row['sname'];
    $course=$row['course'];
    $FEEPAID=$row['FEEPAID'];
    $FEEAMOUNT=$row['FEEAMOUNT']; 
    $EXAMNAME=$row['EXAMNAME'];
    $aadhar=$row['aadhar'];
    $txnid=$row['TXNID'];
  }
  mysqli_close($conn);
}


else
{   
   $id="";
    $sname= "";
    $course="";
    $FEEPAID="";
    $FEEAMOUNT="";
    $EXAMTYPE=$row['EXAMTYPE'];

    echo "<script>alert('Details Not Found');</script>";
    mysqli_close($conn);
}
}

?>

<div class="page-wrapper">
<!-- Bread crumb -->
  <div class="row page-titles">
                 <div class="col-md-5 align-self-center">
        <h3 class="text-primary">Mark Fee Payment </h3>
    </div>
        <div class="col-md-7 align-self-center">
             <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
           <li class="breadcrumb-item active">mark fee Payment</li>
           </ol>
      </div>
</div>
<!-- End Bread crumb -->

<!-- Container fluid  -->
<div class="container-fluid">
<!-- Start Page Content -->
<div class="row">
<div class="col-lg-12">
<div class="card card-outline-primary">        
<center>
<div class="card-header">
<h4 class="m-b-0 text-white">MARK FEE PAYMENT </h4>
</div>
</center>
<br><br>
<div class="card-body">
<div class="basic-form">
    
<?php 
if(isset($_POST['update'])){
  $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
  if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
  }
  $sql='UPDATE examenrollments SET FEEPAID="'.$_POST['feepaid'].'",CHALLANRECBY="'.$_POST['challanrecd'].'", TXNID="'. $_POST['TXNID'] . '", CHALLANSUBMITTEDON="'.$_POST['CHALLANSUBDATE'].'" WHERE ID=' . $_POST['enrollid'];
  echo $sql;
 $currentDateTime = date('Y-m-d H:i:s');
  if ($conn->query($sql) === TRUE) {
      echo " <h2 class='text-success'>Payment Marked</h2>";
      echo "<h2 class='text-primary'> $currentDateTime</h2>";
  } 
  else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
  
  $conn->close();

}
else{

}


?>

<style>
.img1{
 float:right;
 margin-right:10%;
}
</style>
<form action="markpayment.php" method="GET">
<div class="col-md-6">
    <?php print_r($_POST); ?>
<div class="input-group mb-3">
<input type="text" class="form-control" name="studentid"  placeholder="Enter Registration Number">
  <div class="input-group-append">
    <input class="btn btn-warning" type="submit" name="getdetails" id="getdetails" value="Get Details">
  </div>
  </div>
</div>
</form>

<input type="hidden" name="id" value="<?php echo $id;?>">

<div class="img1">
<img src="../students/upload/images/<?php echo $aadhar;?>.jpg" alt="12345" width="100px"/><br>
<img src="../students/upload/signatures/<?php echo $aadhar;?>.jpg"  alt="12345" width="100px"/>

</div>
<form action="markpayment.php" method="POST">
<table width="60%">
<input type="hidden" name="id" value="<?php echo $id;?>">

<tbody>
<h4>1.Candidate details</h4>
<tr>
<td>Student Name:</td>
<td><center><h3><?php echo $sname; ?></h3></center></td>
</tr>
<tr>
<td>Course:</td>
<td><center><h3><?php echo $course; ?></h3></center></td>
</tr>
<tr>
<td>Exam Name:</td>
<td><center><h3><?php echo $EXAMNAME; ?></h3></center></td>
</tr>
<tr>
<td>Fee Amount:</td>
<td><center><h3><?php echo $FEEAMOUNT; ?></h3></center></td>
</tr>
<tr>
    
<td>Fee Paid:</td>
<td><center><h3 class="text-success"><?php if ($FEEPAID==1){echo 'FEE PAID';}else{echo 'NOT PAID';}?></h3></center></td>
</tr>
</form>
<form action="markpayment.php" method="POST">
<tr>
<td>Challan Number:</td>
<td><input type="text" class="form-control" placeholder="" name="TXNID" value="<?=$record['TXNID']?>"></td>
</tr>

<input type="hidden" name="enrollid" value="<?php echo $id;?>">
<tr>
<td>Challan Date:</td>
<td><input type="" class="form-control" placeholder="" name="CHALLANSUBDATE" value="<?php echo  date("d/m/Y")?>"></td>
</tr>
<tr>
<td><h4>Fee Paid:<h4></td>
<td><input type="checkbox" name="feepaid" value="1"><strong>Tick this to update </strong>
</tr>
<input type="hidden" name="challanrecd" value="<?php echo $_COOKIE['userid']; ?>">  
</tbody>
</table>
<br>
<p align="center"><input type="submit" class="btn btn-primary" name="update" value="Mark Payment"></p>

</form>

</div>
</div>
</div>
</div>
</div>
</div>
<?php include 'datatablefooter.php'?>







































