<?php include "header.php";?>
<div class="page-wrapper">
    <div class="card">
    <div class="card-body">
                <form action="cmmreport.php" method="POST">
                        <div class="row bg-primary">
                                <div class="col-12 p-3">
                                    <h1 class="text-white">Print Consolidated Memo</h1>
                                    <br>
                                    <div class="form-group">
                                        <label for="hallticket">
                                            Enter the Student Hallticket Number
                                        </label>
                                        <input type="text" name="ht" id="ht" placeholder="Enter Hall Ticket Number" class="form-control">
                                        <p class="text-right p-2">
                                        <input type="submit" class="btn btn-success"> </input>
                                        </p>
                                    </div>
                                </div>
                                    
                        </div>
                        </div>
                </form>
    </div>
</div>
</div>


<?php include "footer.php";?>