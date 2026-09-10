<?php include "header.php";?>
<div class="container-fluid">
  <div class="page-wrapper p-3  w-100">

    <div class="row">
      <div class="col-md-12">

        <?php

        if (isset($_GET['action'])) {
          if ($_GET['action'] == "updatedinfosuccess") {
            echo "<div class='alert alert-success text-dark'>Information Updated Successfully, Please Change Photo and Signature.</div>";
          }
        }
        ?>
      </div>
    </div>

     <!--Edit Details Card-->
    <div class="row">
    <div class="card col-sm-9 bg-dark text-white ">
      <div class="card-header bg-danger">
        <h2 class="text-light">Update your Details !!!
        </h2>
      </div>
      <div class="card-body p-3">
        <h3 class="text-light"> Students joined in 2025 must update the details and upload their Photo and Signature urgently </h3>
        <p class="text-left">
          <a href="editdetails.php" class="btn btn-success">Update Details</a>
        </p>

      </div>
    </div>
  </div>
   <!--End of edit details card-->



<!--First Row-->
    <div class="row text-center">

      <div class="card col-sm-3 m-1">
        <a href="selectexam.php" aria-expanded="false" class="stretched-link">
          <i class="fa fa-edit fa-3x"></i>
         <span class="text-large"> <br> Exam Registration </span> </a>
      </div>

      <div class="card col-sm-3 m-1">
        <a href="enrollments.php" aria-expanded="false" class="stretched-link">
          <i class="fa fa-address-book fa-3x"></i>
        <span class="text-large"> <br> Registered Exams </span> </a>
      </div>
    
    <div class="card col-sm-3 m-1"> <a href="newimageupload.php" aria-expanded="false" class="stretched-link">
          <i class="fa fa-user fa-3x"></i>
          <span class="text-large"> <br> Change Photo / Signature</span> </a>
      </div>
    </div>
<!--End of First Row-->

<!--Second Row-->
    
  </div>
<!--End of Second Row-->

<?php include "footer.php";?>

