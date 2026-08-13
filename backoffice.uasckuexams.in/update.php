
<?php include "header.php";?>

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
}
</style>
 <div class="page-wrapper bg-white">
            <!-- Bread crumb -->
<div class="row page-titles">

<div class="col-md-5 align-self-center">
<h3 class="text-primary">Student Details</h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Students</a></li>
<li class="breadcrumb-item active">My Details</li>
</ol>
</div>
</div>
<!-- End Bread crumb -->
<!-- Container fluid  -->
<div class="container">
<div class="row">
<div class="col-md-12  toppad  pull-right col-md-offset-6 ">  
<div class="panel panel-info">

<div class="panel-body">

<div class="row">
<div class="img">
<div class="col-md-3 col-lg-3 " align="center"><img src='' alt="user pic" class="img-rounded" style="width: 150px;"/>
 
 </div>

<br>


<!--button-->
<div class="container">
  <!-- Button to Open the Modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
    Uploads
</button>
<!-- The Modal -->
<div class="modal" id="myModal">
<div class="modal-dialog">
<div class="modal-content">
<!-- Modal Header -->
<div class="modal-header">
<h4 class="modal-title">Select Photo</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<!-- Modal body -->
<form action="<?php echo '"update.php?id='.$_GET['id'].'"';?>" method="POST" enctype="multipart/form-data">
<div class="modal-body">
<input type="file" id="profilepic" name="profilepic">
</div>      
<!-- Modal footer -->
<div class="modal-footer">
<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
<input type="submit" class="btn btn-success" value="Upload Photo" name="picsubmitted">
</form>

</div>       
</div>
</div>
</div>
</div>
</div>



<div class=" col-md-9 col-lg-9 "> 

<table class="table">

<tbody>
<tr>
<td>Full Name:</td>
<td><center><?php //echo $row["sname"]?></center></td>
</tr>
<tr>
<td>Father Name:</td>
<td><center><?php //echo $row["fname"]?></center></td>
</tr>
<tr>
<td>Date of Birth:</td>
<td><center></center></td>
</tr>
<tr>
<td>Gender:</td>
<td></td>
</tr>
<tr>
<td>Home Address:</td>
<td><center><?php //echo $row["address"]." ".$row["address2"]?></center></td>
</tr>
<tr>
<td>Email:</td>
<td><center><?php //echo $row["email"]?></center></td>
</tr>
<td>Phone Number:</td>
<td><center><?php //echo $row["phone"]?></center></td>
</tr>                    
</tbody>
</table>
<br>


<p align="right"> <input type="submit" class="button" value="Update ">
</div>
</div>
</div>
</div>
</div>
</div>
<!--end of page content-->

<?php include "footer.php";?>