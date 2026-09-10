<?php include "header.php";?>

<style>
label{
    color:black;
}

</style>


<div class="page-wrapper">
<!-- Bread crumb -->



<!-- Container fluid  -->
<div class="container">

<div class="card">

<div class="card-header">
<label>Change Password </label>
</div>
 <br>

<div class="card-body">
<div class="basic-form">
<form action="#" method="POST">
    <center>
<div class="form-group" >
<div class="col-md-6">
<label>Current Password:</label>

<input type="text" class="form-control input-focus " placeholder="Enter password" name="cpswd">
</div></div>

<div class="form-group">
<div class="col-md-6">
<label>New Password:</label>
<input type="text" class="form-control input-focus " placeholder="New password" name="npswd">
</div></div>

<div class="form-group">
<div class="col-md-6">
<label>Re-enter Password:</label>
<input type="text" class="form-control input-focus " placeholder="Re-enter password" name="rpswd">
</div></div>
</center>



</div>
</div>
</div>
<!--end of page content-->
<!-- footer -->

<?php include "footer.php";?>

</body>

</html>