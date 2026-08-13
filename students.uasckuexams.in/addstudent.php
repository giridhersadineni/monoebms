<?php 

?>



<?php include "header.php";?>
<head>
<style>
.button
{
    background-color:#008CBA;
    color:white
}
h3
{
    color:blue;
}
</style>
 <div class="page-wrapper">
<!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Student Registration </h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
<li class="breadcrumb-item active">student Registration</li>
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
<h4 class="m-b-0 text-white">STUDENT REGISTRATION </h4>
</div>
</center>
<div class="card-body">
<div class="basic-form">

<form action="checkpost.php" method="POST">
<div class="row p-t-20"><div class="col-md-6"><div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Student Firstname</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter student firstname" name="firstname"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Student Lastname</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter student lastname" name="lastname">
</div></div></div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Father Name</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter Father's name" name="fathername"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Father Contact Number</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter Father's Contact" name="fatherphone">
</div></div></div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Contact Number</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter contact" name="phone"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Branch id</h4></p>
<input type="text" class="form-control input-focus" placeholder="Enter Branch id" name="branchid">
</div> </div>
</div>
<!--student name close-->

<div class="row p-t-20">
<div class="col-md-12">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Email</h4></p>
<input type="email" class="form-control input-focus" placeholder="enter your mail-id" name="mailid">
</div></div>
 </div>
<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Date Of Birth</h4></p>
<input type="date" class="form-control input-focus" placeholder="Enter Date of birth" name="dob">
</div> 
</div> <br><br>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4> Gender</h4></p>
<input type="radio" name="gender" value="Male">Male</input><br>
<input type="radio" name="gender" value="Female">Female</input>
</div>
</div>
  </div>
  <p class="text-muted m-b-15 f-s-12"><h4>ADDRESS:</h4></p>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
 <p class="text-muted m-b-15 f-s-12"><h4>Address Line1</h4></p>
 <input type="text" class="form-control input-focus" placeholder="Address Line1" name="addressline1">
</div>
</div>
     <div class="col-md-6">
 <div class="form-group">
     <p class="text-muted m-b-15 f-s-12"><h4>Street/Village/Area</h4></p>
           <input type="text" class="form-control input-focus" placeholder="Street" name="addressline2">
   </div>
</div>
 </div>                                       
      <div class="row">
            <div class="col-md-6">
         <div class="form-group">
       <p class="text-muted m-b-15 f-s-12"><h4>Mandal</h4></p>
            <input type="text" class="form-control input-focus" placeholder="Mandal" name="mandal">
      </div>
    </div>
         <div class="col-md-6">
             <div class="form-group">
          <p class="text-muted m-b-15 f-s-12"><h4>City</h4></p>
           <input type="text" class="form-control input-focus" placeholder="City" name="city">
 </div>
</div>
</div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>State</h4></p>
<input type="text" class="form-control input-focus" placeholder="State" name="state">
</div>
</div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Pincode</h4></p>
<input type="text" class="form-control input-focus" placeholder="Pincode" name="pincode">
</div>
</div>
</div>
<br>                     
<p align="right"> <input type="submit" class="button" value="Add "></p>
</div>
</div>
</div>
</div>
<!-- /# card -->
</div>
<!-- /# column -->
</div>
<!--end of page content-->

<?php include "footer.php";?>