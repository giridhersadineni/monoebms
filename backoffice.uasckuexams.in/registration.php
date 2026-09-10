<?php 
/*
session_start();
if(!(isset($_SESSION['login'])))
{
    header("location:index.php?sessionexpired");
*/
?>

<?php include "header.php"?>   

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
.container{
    
    max-width: 1500px;
    margin: auto;
    padding-left: 250px;
}
</style>

     
<!-- Container fluid  -->
<div class="container">
<!-- Start Page Content -->
<div class="row">
<div class="col-lg-12">
<div class="card card-outline-primary">   

<center>
<div class="card-header">
<h4 class="m-b-0 text-white">Exam Registration Form </h4>
</div>
</center>

<div class="card-body">
<div class="basic-form">

<form action="senddetails.php" method="POST">

<div class="row p-t-20"><div class="col-md-6"><div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4> Fullname</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter name" name="sname"></div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Father name</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter fathername" name="fname">
</div></div></div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Mother name</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter mothername" name="mname">
</div></div>
 
 <div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Email</h4></p>
<input type="email" class="form-control input-focus" placeholder="Enter your Email" name="email">
</div></div></div>
<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>DOB</h4></p>
<input type="date" class="form-control input-focus " placeholder="Enter aadhar number" name="dob">
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Gender</h4></p>
<select name="gender"  id="gender" class="form-control input-focus">
    
    <option selected>--Select--</option>
    <option name="male" value="male">Male</option>
     <option  name="female" value="female">Female</option>
    </select>
</div></div></div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Phone Number</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter phoneno" name="phneno">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Group</h4></p>

<select name="group"  id="group" class="form-control input-focus">

    <option selected>--Select--</option>
     <option name="group" value="B.com">B.COM</option>
     <option name="group" value="Bsc(computer science)">Bsc(computer science)</option>
    <option  name="group" value="BBM">BBM</option>
    </select>
</div></div></div>

 <div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Hallticket Number</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter hallticket number" name="hltckt">
</div></div>

<!-- list iteam-->
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Semester</h4></p>

<select name="semester"  id="semester" class="form-control input-focus">
    
    <option selected>--Select--</option>
    <option name="semester" value="1">1</option>
     <option name="semester" value="2">2</option>
    <option  name="semester" value="3">3</option>
    <option  name="semester" value="4">4</option>
     <option name="semester" value="5">5</option>
    <option name="semester" value="6">6</option>
    </select>
</div></div></div>

 <div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Year</h4></p>

<select name="year"  id="year" class="form-control input-focus">
    
    <option selected>--Select--</option>
    <option name="year" value="2018">2018</option>
     <option  name="year" value="2017">2017</option>
    <option  name="year" value="2016">2016</option>
    <option  name="year" value="2015">2015</option>
     <option name="year" value="2014">2014</option>
    <option name="year" value="2013">2013</option>
    </select>
</div></div>
<!-- end list iteam-->

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Aadhar Number</h4></p>
<input type="text" class="form-control input-focus " placeholder="Enter aadhar number" name="aadhar">
</div></div></div>


<!--address-->
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>ADDRESS:</h4></p>
</div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Address Line1</h4></p>
<input type="text" class="form-control input-focus" placeholder="Address Line1" name="address">              
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Street/Village/Area</h4></p>
<input type="text" class="form-control input-focus" placeholder="Street" name="address2">
</div></div>
</div>
                                       
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Mandal</h4></p>
<input type="text" class="form-control input-focus" placeholder="Mandal" name="mandal">
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>City</h4></p>
<input type="text" class="form-control input-focus" placeholder="City"name="city">
</div></div>
</div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>State</h4></p>
<input type="text" class="form-control input-focus" placeholder="State" name="state">
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Pincode</h4></p>
<input type="text" class="form-control input-focus" placeholder="Pincode" name="pincode">
</div></div>
</div>

<div class="form-group">
 Upload Photo:<input type="file" class="form-control input-focus" placeholder="upload pic" name="img"> 
 </div>  <br>

 <div class="form-group">
 Upload signature:<input type="file" class="form-control input-focus" placeholder="upload signature" name="signature"> 
 </div> 
                     
<p align="right"> <input type="submit" class="button" value="Submit">
</div>
</div>
</div>
</div>
<!-- /# card -->
</div>
<!-- /# column -->
</div>
</div>
<!--end of page content-->
<?php include"footer.php"?>