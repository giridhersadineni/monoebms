<?php include "projectorm.php";


if( isset($_POST['processapplication'])){
    echo "Processing Application";
    $oldid=$_POST['EID'];
    $subs = $_POST['PAPERS'];
    $subjectcount = sizeof($subs);
    $subsarray = array();
    $fee=$_POST['FEE'];
    
    print_r($_POST);
    
    $i=0;
    foreach($subs as $subject) {
           array_push($subsarray , $subject );
           $i++;
    }
    for($i;$i<10;$i++){
        array_push($subsarray , "null");
    }
    

    $conn=getconnection();
    
    $regularenrollment=get_one_assoc("select * from examenrollments where ID=$oldid");
    $examid=$regularenrollment['EXAMID'];
    echo "<hr>";
    print_r($regularenrollment);
    
    $student = get_one_assoc("select * from students where stid=".$regularenrollment['STUDENTID']);
    echo "<hr>";
    print_r($student);

    $checkenroll='select * from revaluationenrollments where EXAMID='.$examid.' and STUDENTID='.$student['stid'];
    $enrollments=$conn->query($checkenroll);
    echo "<hr>";
    if($enrollments->num_rows>0){
         header("location:enrollederror.php?error=duplicateentry");
    }   
    else{
        $subsquery = implode("','",$subsarray);
        $values = "'" . $subsquery . "','null','null','null','null',";
        $sql = "INSERT INTO revaluationenrollments(EXAMID, STUDENTID,HALLTICKET, S1, S2, S3, S4, S5, S6, S7, S8, S9, S10, E1, E2, E3, E4,FEEAMOUNT,ENROLLEDDATE) VALUES (" . $regularenrollment['EXAMID'] . "," . $regularenrollment["STUDENTID"] . ",'" . $regularenrollment["HALLTICKET"] . "',".$values . $fee. ", CURRENT_TIMESTAMP)";
        echo $sql;
        echo "<hr>";
        if($conn->query($sql)){
            header("location:registrationsuccess.php?et=reval&id=".$conn->insert_id);
        } else {
            echo "Unable to register now";
            die();
                
        }
     }
}


?>
<?php include "header.php";
$res = "select * from RESULTS where EID=".$_GET["id"];
$result=get_result_assoc($res);

?>

<div class="page-wrapper">
    <div class="card p-0 m-3">
        
        
        <?php if($_GET['confirm']==1):?>
            
                <div class="card">
                    <div class="card-header bg-primary ">
                    <h1 class="text-white">
                        Selected Papers
                    </h1>
                </div>
                <div class="card-header">'
                    <p>
                    <form method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?process=1';?>">
                     <input type="hidden" name="processapplication" value ="1" >    
                    
                    <input type="hidden" name="EID" value="<?php echo $_GET['id']?>">
                    
                        <?php foreach($_POST['PAPERS'] as $selectedpaper=>$value): ?>
                            <span class="btn btn-info btn-disabled"><?=$value?></span>
                            <input type="hidden" name='PAPERS[]' value="<?=$value?>"> 
                        <?php endforeach;?>
                        <?php 
                            $count=count($_POST['PAPERS']);
                            $price=275*$count;
                        ?>
                        <input type="hidden" name='FEE' value="<?=$price;?>">
                        <input type="submit" value="Submit Application" name="submit" class="btn btn-success">
                        </form>
                    </p>
                </div>
                
                 <h2 class="text-danger">Total Fee : <?=$price;?></h2>
                 <div class="card-footer bg-light ">
                    <p class="text-center"> </p>
                </div>
            </div>
                
        <?php else:?>
            <div class="card">
                <div class="card-header bg-primary ">
                <h1 class="text-white">
                    Select Papers
                </h1>
                </div>
                <div class="card-body bg-white">
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']."?confirm=1&id=".$_GET['id'];?>">
                    
                <?php foreach($result as $paper): ?>
                    <div class="form-control p-3">
                        <h4 p-2><input type="checkbox" name="PAPERS[]" value="<?=$paper['PAPERCODE']?>">
                        <?=$paper['PAPERCODE'];?>&nbsp <?=$paper['PAPERNAME'] ; ?>
                    
                        </h4>
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="card-footer bg-light ">
                    <p class="text-center"><input type="submit" value="Finish Paper Selection" class="btn btn-success"</p>
                </div>
            </div>
            </form>
        <?php endif;?>
        
        <h2 class="text-danger">Fee: 275 Rs Per Paper</h2>
        </div>
        
    </div>
    
</div>
<?php include "footer.php";?>