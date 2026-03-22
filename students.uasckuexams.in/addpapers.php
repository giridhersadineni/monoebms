<?php include 'header.php'?>

<head>
<style>
h3
{
    color:blue;
}
</style>
 <div class="page-wrapper">
<!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Add Paper </h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
<li class="breadcrumb-item active">Add paper</li>
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
<h4 class="m-b-0 text-white">ADD PAPER </h4>
</div>
</center>
<div class="card-body">
<div class="basic-form">
<form action="sendpapers.php" method="POST">

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Course</h4></p>
<input type="text" class="form-control input-focus" placeholder="Enter Course" name="course">
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Paper Code</h4></p>
<input type="text" class="form-control input-focus "placeholder="Enter Paper Code" name="papercode">
</div></div></div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Specialization</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter Specialization" name="specialization"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Paper Name</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter Paper Name" name="papername">
</div></div></div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Group Code</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter Code" name="code"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Semester</h4></p>
<input type="text" class="form-control input-focus" placeholder="Enter Semester" name="semester">
</div> </div>
</div>
<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Year</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter Year"  name="year"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Paper Type</h4></p>
<input type="text" class="form-control input-focus" placeholder="Enter Type"  name="papertype">
</div> </div>
</div>
<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Medium</h4></p>
<select name="medium" class="form-control input-focus" id="medium">
<option  name="medium" value="null">--Select Medium--</option>
<option  name="medium" value="EM">EM</option>
<option  name="medium" value="TM">TM</option>
</select>
</div></div>


<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Description</h4></p>
<input type="text" class="form-control input-focus" placeholder="Enter Description"  name="description">
</div> </div>
</div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Elective group</h4></p>
<input type="text" class="form-control input-focus " placeholder="Select Elective Group"  name="egroup"></div></div></div>
<!-- Buttons-->
<p align="Center"> <input type="submit" class="btn btn-success" value="Add Paper"></p>

<p align="Center"> <a href="papers.php" class="btn btn-primary" value="Back">Back</a></p>
</div>
</div>
</div>
</div>
<!-- /# card -->
</div>
<!-- /# column -->
</div>
<!--end of page content-->

<?php include "datatablefooter.php";?>
