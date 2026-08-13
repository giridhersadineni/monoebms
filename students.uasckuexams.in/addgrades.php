<?php

include 'config.php';

if (isset($_POST['submit'])) {
    //print_r($_GET);exit;
    $ID=$_POST["ID"];
    $MEMO_NO=$_POST["MEMO_NO"];
    $HALLTICKET=$_POST["HALLTICKET"];
    $S1GPA=$_POST["S1GPA"];
    $S2GPA=$_POST["S2GPA"];
    $S3GPA=$_POST["S3GPA"];
    $S4GPA=$_POST["S4GPA"];
    $S5GPA=$_POST["S5GPA"];
    $S6GPA=$_POST["S6GPA"];
    $P1_YOP=$_POST["P1_YOP"];
    $P2_YOP=$_POST["P2_YOP"];
    $ALL_YOP=$_POST["ALL_YOP"];
    $ADM_YEAR=$_POST["ADM_YEAR"];
    $P1DIV=$_POST["P1DIV"];
    $P2DIV=$_POST["P2DIV"];
    $FINALDIV=$_POST["FINALDIV"];
    $P1CF=$_POST["P1CF"];
    $P2CF=$_POST["P2CF"];
    $ALLCF=$_POST["ALLCF"];
    $P1CGPA=$_POST['P1CGPA'];
    $P2CGPA=$_POST['P2CGPA'];
    $ALLCGPA=$_POST['ALLCGPA'];
    
    print_r($POST);
   

    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    }
    $sql="INSERT INTO grades(MEMO_NO, HALLTICKET, S1GPA, S2GPA,
    S3GPA, S4GPA, S5GPA, S6GPA,P1_YOP,P2_YOP,ALL_YOP,ADM_YEAR,
    REMARKS,P1DIV,P2DIV,FINALDIV,P1CF,P2CF,ALLCF,P1CGPA,P2CGPA,ALLCGPA) VALUES ($MEMO_NO,'$HALLTICKET','$S1GPA','$S2GPA','$S3GPA','$S4GPA','$S5GPA',$S6GPA,'$P1_YOP','$P2_YOP','$ALL_YOP','$ADM_YEAR','$REMARKS','$P1DIV','$P2DIV','$FINALDIV',$P1CF,$P2CF,$ALLCF,$P1CGPA,$P2CGPA,$ALLCGPA)";
    echo $sql;
    if ($conn->query($sql) === true) {
        //return print_r($conn);
        header("Location:grades.php?message=gradeadded");
        
    } else {
        if($conn->errno==1062){
          //print_r($conn);
          header('Location:'.$SERVER['HTTP_REFERRER'].'?error=duplicate');
        }
        else{
        echo $sql;
        print_r($conn);
        }
    }

    $conn->close();

}


$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    }
    $sql="select max(MEMO_NO) as MEMO_NO from grades;";
    //print_r($sql);
    $data=$conn->query($sql);
    $memono=mysqli_fetch_assoc($data);
    $conn->close();

?>


<?php include "header.php";?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.0.min.js"></script>

<script type="text/javascript">

$(document).ready(function(){

    $('#btncalcmm').click(function(e) {  
        var hallticket = $("#HALLTICKET").val();
        window.open("calcmm.php?hallticket="+hallticket,"_BLANK");
    });
    $('#btncalcmmold').click(function(e) {  
        var hallticket = $("#HALLTICKET").val();
        window.open("calcmm_old.php?hallticket="+hallticket,"_BLANK");
    });
});
</script> 

<div class="page-wrapper">    
    <div class="card w-100">
        <p class="text-right"><a href="grades.php" class="btn btn-primary">Print Memos</a></p>
    </div>
    <div class="card">
    <div class="card-body">
                <?php if(isset($_GET['error']) && $_GET['error']=='duplicate'): ?>
                <div class='alert alert-danger'>
                        Duplicate Entry
                    <input type="button" VALUE="Go Back" onClick="history.go(-1)" class="btn btn-primary">
                </div>
            <?php endif ?>
        
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                <h1>Generate Consolidated Memo</h1>
                <div class="container-fluid bg-info text-dark">
                <div class="row"> 
                
                    <div class="col-12">
                        <div class="form-group">
                        <label for="hallticket">
                            MEMO NO
                        </label>
                        <input type="text" name="MEMO_NO" id="MEMO_NO" placeholder="ENTER MEMO NUMBER" value="<?php echo $memono['MEMO_NO']+1;?>" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row bg-danger">
                    <div class="col-9">
                        <div class="form-group">
                            <label for="hallticket">
                                Enter the Student Hallticket Number
                            </label>
                            <input type="text" name="HALLTICKET" id="HALLTICKET" placeholder="Enter Hall Ticket Number" class="form-control">
                            </div>
                        </div>
                        <div class="col-3">
                            <label>Calculate Grades</label>
                            <input type="button" id="btncalcmm" class="btn btn-info" value="Calculate Grades">
                        </div>
                        <div class="col-3">
                            <label>Calculate Grades</label>
                            <input type="button" id="btncalcmmold" class="btn btn-info" value="Calculate Grades OLD">
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
                        <input type="text" name="S1GPA" id="S1GPA" placeholder="SEM 1 SGPA" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 2 GPA">
                            SEMESTER 2 SGPA
                        </label>
                        <input type="text" name="S2GPA" id="S2GPA" placeholder="SEM 2 SGPA" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 3 GPA">
                            SEMESTER 3 SGPA
                        </label>
                        <input type="text" name="S3GPA" id="S3GPA" placeholder="SEM 3 SGPA" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 4 GPA">
                            SEMESTER 4 SGPA
                        </label>
                        <input type="text" name="S4GPA" id="S4GPA" placeholder="SEM 4 SGPA" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 5 GPA">
                            SEMESTER 5 SGPA
                        </label>
                        <input type="text" name="S5GPA" id="S5GPA" placeholder="SEM 5 SGPA" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 6 GPA">
                            SEMESTER 6 SGPA
                        </label>
                        <input type="text" name="S6GPA" id="S6GPA" placeholder="SEM 6 SGPA" class="form-control">
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
                        <input type="text" name="P1_YOP" id="P1_YOP" placeholder="PART I YEAR OF PASS" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="part2yop">
                            PART II YEAR OF PASS
                        </label>
                        <input type="text" name="P2_YOP" id="P2_YOP" placeholder="PART II YEAR OF PASS" class="form-control">
                        </div>
                    </div>
                </div>
                    <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                        <label for="SEM 1 GPA">
                            OVERAL ALL YEAR OF PASSING
                        </label>
                        <input type="text" name="ALL_YOP" id="ALL_YOP" placeholder="OVERALL YEAR OF PASS" class="form-control">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                        <label for="ADMISSION YEAR">
                            ADMISSION YEAR
                        </label>
                        <input type="text" name="ADM_YEAR" id="ADM_YEAR" placeholder="ADMISSION YEAR" class="form-control">
                        </div>
                    </div>
                    
                      <div class="col-12">
                        <div class="form-group">
                        <label for="remarks">
                        REMARKS
                        </label>
                        <input type="text" name="REMARKS" id="REMARKS" placeholder="REMARKS" class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                        <label for="p1cgpa">
                            PART I CGPA
                        </label>
                        <input type="text" name="P1CGPA" required class="form-control" placeholder="PART 1 CGPA">    
                        </div>
                    </div>
                    
                    <div class="col-4">
                        <div class="form-group">
                        <label for="p1cgpa">
                            PART II CGPA
                        </label>
                        <input type="text" name="P2CGPA" required class="form-control" placeholder="PART 2 CGPA">    
                        </div>
                    </div>
                    
                    <div class="col-4">
                        <div class="form-group">
                        <label for="allcgpa">
                            OVERALL CGPA
                        </label>
                        <input type="text" name="ALLCGPA" required class="form-control" placeholder="OVERALL CGPA">    
                        </div>
                    </div>
                    
                </div>

                
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                        <label for="p1cf">
                            PART I CF
                        </label>
                        <input type="text" name="P1CF" required class="form-control" placeholder="PART 1 CF">    
                        </div>
                    </div>
                    
                    <div class="col-4">
                        <div class="form-group">
                        <label for="p1cf">
                            PART II CF
                        </label>
                        <input type="text" name="P2CF" required class="form-control" placeholder="PART 2 CF">    
                        </div>
                    </div>
                    
                    <div class="col-4">
                        <div class="form-group">
                        <label for="p1cf">
                            OVERALL CF
                        </label>
                        <input type="text" name="ALLCF" required class="form-control" placeholder="OVERALL CF">    
                        </div>
                    </div>
                    
                </div>

            
                
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                        <label for="p1div">
                            PART I DIVISION
                        </label>
                        <select name="P1DIV" class="form-control" required>
                            <option>SELECT DIVISION</option >
                            <option value="FIRST DIVISION WITH DISTINCTION">FIRST DIVISION WITH DISTINCTION</option>
                            <option value="FIRST DIVISION">FIRST DIVISION</option>
                            <option value="SECOND DIVISION">SECOND DIVISION</option>
                            <option value="THIRD DIVISION">THIRD DIVISION</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                        <label for="p2div">
                            PART II DIVISION
                        </label>
                        <select name="P2DIV" class="form-control" required>
                            <option>SELECT DIVISION</option>
                            <option value="FIRST DIVISION WITH DISTINCTION">FIRST DIVISION WITH DISTINCTION</option>
                            <option value="FIRST DIVISION">FIRST DIVISION</option>
                            <option value="SECOND DIVISION">SECOND DIVISION</option>
                            <option value="THIRD DIVISION">THIRD DIVISION</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                        <label for="p2div">
                            FINAL DIVISION
                        </label>
                        <select name="FINALDIV" class="form-control" required>
                            <option>SELECT DIVISION</option>
                            <option value="FIRST DIVISION WITH DISTINCTION">FIRST DIVISION WITH DISTINCTION</option>
                            <option value="FIRST DIVISION">FIRST DIVISION</option>
                            <option value="SECOND DIVISION">SECOND DIVISION</option>
                            <option value="THIRD DIVISION">THIRD DIVISION</option>
                        </select>
                        </div>
                    </div>
                </div>

            
                </div>
                
                <input type="submit" name='submit' class="btn btn-success"> </input>
        </form>
    </div>
</div>
</div>


<?php include "footer.php";?>