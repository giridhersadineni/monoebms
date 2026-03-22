<?php include "header.php";?>
<div class="container ">
    <br>
    <div class="page-wrapper p-3 bg-light">
    <br>
    <div class="row">
        <div class="col-md-12">
            
        <?php
        
        if(isset($_GET['action'])){
            if($_GET['action']=="updatedinfosuccess"){
                echo "<div class='alert alert-success text-dark'>Information Updated Successfully, Please Change Photo and Signature.</div>";
            }
        
        }
        ?>
        </div>
    </div>
    <div class="row">
                  <div class="card bg-dark text-white w-100">
                    <div class="card-header bg-warning">
                    <h2 class="text-light">Update Fee Payments - Urgent
                    </h2>
                </div>
                
                <div class="card-body p-3">
                    <h3 class="text-light">All the Students need to upload fee payment receipt / Challan / PhonePe / Google Pay Screenshots the Fee payment details .  </h3> 
                    
                    <p style="text-right">
                        <a href="enrollments.php" class="btn btn-success">Update Payment Details</a>
                    </p>
                    
                </div>
                </div>
                
                <div class="card w-100">
                        <div class="card-header"> 
                            <h2>
                                Exam Results 
                            </h2>
                            </div>
                            <div class="card-body p-3">
                            <h3>All Results are live now. You can see your Exam Results in Exam Registrations.</h3> 
                            <p style="text-right"> <a href="results.php" class="btn btn-success">View Results</a> </p>
                        </div>
                  </div>
    
    
              
                <!--<div class="card bg-dark text-white w-100">-->
                <!--    <div class="card-header bg-danger">-->
                <!--    <h2 class="text-light">Update Caste Details-->
                <!--    </h2>-->
                <!--</div>-->
                
                <!--<div class="card-body p-3">-->
                <!--    <h3 class="text-light">All the Students need to update the Caste Details for Scholarship purpose. </h3> -->
                <!--    <p style="text-right">-->
                <!--        <a href="editdetails.php" class="btn btn-success">Update Details</a>-->
                <!--    </p>-->
                    
                <!--</div>-->
          </div>
          
          
          
         
          
  </div>
    </div>
    </div>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-5755477602321907",
    enable_page_level_ads: true
  });
</script>

<?php include "footer.php";?>

