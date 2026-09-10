

<?php include "header.php";?>
<div class="page-wrapper">
    <div class="card">
    <div class="card-body">
        <form action="printcmm.php" method="GET">
                <h1>Print Consolidated Memo</h1>
                <div class="container-fluid bg-info text-dark">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                        <label for="hallticket">
                            MEMO NO
                        </label>
                        <input type="text" name="MEMO_NO" id="ht" placeholder="ENTER MEMO NUMBER" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row bg-danger">
                    <div class="col-12">
                        <div class="form-group">
                        <label for="hallticket">
                            Enter the Student Hallticket Number
                        </label>
                        <input type="text" name="hallticket" id="ht" placeholder="Enter Hall Ticket Number" class="form-control">
                        </div>
                    </div>
                </div>
                </div>
                
                <div class="container-fluid bg-info text-dark">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 1 GPA">
                            SEMESTER 1 SGPA
                        </label>
                        <input type="text" name="S1GPA" id="ht" placeholder="SEM 1 SGPA" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 2 GPA">
                            SEMESTER 2 SGPA
                        </label>
                        <input type="text" name="S2GPA" id="ht" placeholder="SEM 2 SGPA" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 3 GPA">
                            SEMESTER 3 SGPA
                        </label>
                        <input type="text" name="S3GPA" id="ht" placeholder="SEM 3 SGPA" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 4 GPA">
                            SEMESTER 4 SGPA
                        </label>
                        <input type="text" name="S4GPA" id="ht" placeholder="SEM 4 SGPA" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 5 GPA">
                            SEMESTER 5 SGPA
                        </label>
                        <input type="text" name="S5GPA" id="ht" placeholder="SEM 5 SGPA" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 6 GPA">
                            SEMESTER 6 SGPA
                        </label>
                        <input type="text" name="S6GPA" id="ht" placeholder="SEM 6 SGPA" class="form-control">
                        </div>
                    </div>
                </div>
                </div>
                <div class="container-fluid bg-dark text-white">
                    
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label for="part1yop">
                            PART I YEAR OF PASS
                        </label>
                        <input type="text" name="P1_YOP" id="ht" placeholder="PART I YEAR OF PASS" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="part2yop">
                            PART II YEAR OF PASS
                        </label>
                        <input type="text" name="P2_YOP" id="ht" placeholder="PART II YEAR OF PASS" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 1 GPA">
                            OVERAL ALL YEAR OF PASSING
                        </label>
                        <input type="text" name="ALL_YOP" id="ht" placeholder="OVERALL YEAR OF PASS" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="ADMISSION YEAR">
                            ADMISSION YEAR
                        </label>
                        <input type="text" name="ADM_YEAR" id="ht" placeholder="ADMISSION YEAR" class="form-control">
                        </div>
                    </div>
                    
                </div>
                </div>
                
                <input type="submit" class="btn btn-success"> </input>
        </form>
    </div>
</div>
</div>


<?php include "footer.php";?>