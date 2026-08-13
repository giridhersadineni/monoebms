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
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>


    <!-- Main wrapper  -->
<div id="main-wrapper">
<!-- header header  -->
<div class="header">
<nav class="navbar top-navbar navbar-expand-md navbar-light">
<a class="navbar-brand" >
<!-- Logo icon -->
<div class="header-middle">
<div class="row">
<div class="col-sm-12">
<div class="bgtop">
<div class="logo">
            <img src="images/arts.png" alt="" width="85" height="85" style="float:left;"/> 
       <h1><span>University Arts & Science College<br><h4>An Autonomous Institute under Kakatiya Universtiy,Subedari,Hanamkonda,Warangal 506001</h4></span></h1>
 </div>
</div>
</div>		
</div>
</div>
</a>   
<br>                 
</div>             
                <!-- End Logo -->



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

h2{
    background-color:#9999ff;
}
</style>

     
<!-- Container fluid  -->
<div class="container-fluid">
<!-- Start Page Content -->
<div class="row justify-content-center">
<div class="col-lg-12">
<div class="card card-outline-primary">   

<center>
<h2>  Student Registration Form<h2>
</center>


<div class="card-body">
<div class="basic-form">

<form action="senddetails.php" method="POST" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4> Fullname</h4></p>
<input type="text"onkeypress="return isAlpha(event);"
 class="form-control input-focus " placeholder="Enter name" name="sname">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Father name</h4></p>
<input type="text" class="form-control input-focus "  placeholder="Enter fathername" name="fname">
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
    <option name="year" value="1">1</option>
     <option  name="year" value="2">2</option>
    <option  name="year" value="3">3</option>
    
    </select>
</div></div>
<!-- end list iteam-->

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>Aadhar Number</h4></p>
<input type="text" class="form-control input-focus " onkeypress="javascript:if(this.value.length==12){alert('You are entering EID')};return isNumberKey(event); " placeholder="Enter aadhar number" name="aadhar">
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
 Upload Photo:<input type="file" class="form-control input-focus" placeholder="upload pic" name="image"> 
 </div>  <br>

 <div class="form-group">
 Upload signature:<input type="file" class="form-control input-focus" placeholder="upload signature" name="signature"> 
 </div> 
                     
<p align="right"> <input type="submit" class="button" value="Submit" name="Submit">
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