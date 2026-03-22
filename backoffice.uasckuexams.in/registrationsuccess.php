
<?php include "header.php";?>
        <div class="page-wrapper">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Exam Registration Regular</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Exams</a></li>
                        <li class="breadcrumb-item active">Enrollment</li>
                    </ol>
                </div>
            </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">
                <!-- Start Page Content -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="table-responsive">
                           <!--COntent-->
    <h1>Registration Successfull Click on Generate Challan </h1>

    
    
    
        
        <?php 
        $examtype=$_GET['et'];
        
       
        
        if($examtype=="reval"):?>
            <a href="revaluationchallan.php?&id=<?php echo $_GET['id'];?>" class="btn btn-success">Generate Challan</a>
        <?php else:?>
            <a href="generatechallan.php?&id=<?php echo $_GET['id'];?>" class="btn btn-success">Generate Challan</a>
        <?php endif; ?>





</div>
</div>
</div>
    </div><!--end of page wrapper-->







<?php include "footer.php";?>
                        </div><!--Responsive table end-->





                        </div>
                    </div>
                </div>
                <!-- End PAge Content -->
            </div>
            <!-- End Container fluid  -->





        </div>
