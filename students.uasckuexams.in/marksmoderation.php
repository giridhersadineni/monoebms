<?php include "header.php";?>

<div class="page-wrapper">
    
    <div class="card">
    <div class="card-header">
           <h3>Update Marks</h3> 
    </div>
    <div class="card-body bg-dark p-3">
        <form action="moderationresult.php" method="POST">
                
                <div class="form-group">
                    <label for="pcode">
                        Enter PaperCode
                    </label>
                    <input type="text" name="pcode" placeholder="Enter paperCode" class="form-control">
                </div>
                
                 <div class="form-group">
                    <label for="EXAM">
                        Enter Exam
                    </label>
                    <input type="text" name="exid" placeholder="Enter Exam IDs Seperated by Commas" class="form-control">
                </div>
                 <div class="form-group">
                    <label for="no_of_marks">
                        Enter Marks to be Added
                    </label>
                    <input type="text" name="no_of_marks" placeholder="No of Marks to be Added" class="form-control">
                </div>
                
                
                <input type="submit" class="btn btn-success"> </input>
        </form>
    </div>
</div>
    
</div>


<?php include "footer.php";?>