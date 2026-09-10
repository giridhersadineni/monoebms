<?php 
ini_set('display_errors',1); 
error_reporting(E_ALL);
?>

<?php include 'header.php';?>
<?php
function grade($p,$r){
    if($r=="F"){
        return "F";
    }
    else if($p=="AB"){
        return "F";
    }
    else if($p>=85){
        return 'O';
    }
    else if($p>=70){
        return 'A';
    }
    else if($p>=60){
        return 'B';
    }
    else if($p>=55){
        return 'C';
    }
    else if($p>=50){
        return 'D';
    }
    else if($p>=40){
        return 'E';
    }
    else{
        return 'F';
    }
}

// $i= internal marks $it=internal total $e=external Marks $et=externaltotal
function calcresult($external,$externaltotal,$internal,$inttotal){
    if($external=="AB"){
        return "AB";
    }
    else if($internal=="AB"){
        $totalmarks=$externaltotal+$inttotal;
        if($external/$totalmarks>=0.4){
            return "P";
        }
        else{
            return "F";
        }
    }
    else{
        $passmarks=$externaltotal;
        if($external/$passmarks>=0.4){
            return "P";
        }
        else{
            return "F";
        }
        
    }

}


//check connection
include "config.php";
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from PRERESULTS where PAPERCODE='".$_POST['pcode']."'";
    echo $sql;
    $result = $conn->query($sql);
     
    //echo "  " . $result->num_rows;
}
?>



<!-- End Left Sidebar  -->

<!-- Page wrapper  -->
<div class="page-wrapper">
<!-- Bread crumb -->

<div class="row page-titles">
 <div class="col-md-5 align-self-center">
 <!--<h3 class="text-primary">Dashboard</h3> -->
 </div>

 <div class="col-md-7 align-self-center">
 <ol class="breadcrumb">
 <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
 <li class="breadcrumb-item active">Enrolled Details</li>
 </ol>
 </div>
 </div>
 <!-- End Bread crumb -->

<!-- Container fluid  -->
<div class="container-fluid">
<!-- Start Page Content -->
<div class="row">
<div class="col-12">
<div class="card">



<!--<form  action='testingmarks.php' method="POST">-->
<form  action='moderationresult.php' method="POST">
  <div class="form-group">
    <input type="hidden"  name='pcode' id='pcode'  value='<?php echo $_POST['pcode']?>' class="form-control mx-sm-3">
    <input type="hidden"  name='exid'  id='exid' value='<?php echo $_POST['exid']?>' class="form-control mx-sm-3">
    <input type="hidden"  name='no_of_marks'  id='no_of_marks' value='<?php echo $_POST['no_of_marks']?>' class="form-control mx-sm-3">
    <label for="confirm" class="display-6">Confirm Update</label>
    <input type="submit" name='confirmupdate' class="btn btn-primary my-1" value="OK"> 
    <input type="reset" name='' class="btn btn-primary my-1" value="cancel">
  </div>
</form>
    
    
<div class="card-body">
<div class="table-responsive m-t-40">
<table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">

                   <thead>
                        <tr>
                              <th>RHID</th>
                              <th>EXAMID</th>
                              <th>PAPERCODE</th>
                              <th>PAPERNAME</th>
                              <th>PART</th>
                              <th>HALLTICKET</th>
                              <th>EID</th>
                              <th>CODE</th>           
                              <th>EXT</th>
                              <th>ETOTAL</th>
                              <th>INT</th>
                              <th>ITOTAL</th>
                              <th>RESULT</th>
                              <th>CREDITS</th>
                              <th>MARKS</th>
                              <th>TOTAL MARKS</th>  
                              <th>PERCENTAGE</th>
                              <th>GRADE</th>
                              <th>GPV</th>
                              <th>GPC</th>
                              <th>UPLOADID</th>
                              <th>SEMESTER</th>
                              <th>MYEAR</th>
                              <th>GPVV</th>
                              <th>GPCC</th>
                        </tr>
                 </thead>
            


<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from PRERESULTS where PAPERCODE='".$_POST['pcode']."'";
    //echo $sql;
    $result = $conn->query($sql);
     //var_dump($result);
  //echo "  " . $result->num_rows;
}

if ($result->num_rows > 0) {
  $updatedresults=[];
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
       // print_r($row);
        echo "<tr>";
        foreach($row as $col){
                    echo "<td>".$col."</td>";
                }
        echo "</tr><tr>";
        //ADD MARKS
        if($row['EXT']=="AB"){
            $row["RESULT"]=="AB";
            $row["GRADE"]=="AB";
            $row["GPC"]=="AB";
            $row["GPV"]=="AB";
            $row["PERCENTAGE"]=="AB";
            $row['RESULT']="AB";
            $row['MARKS']="AB";
         }
        else if($row['INT']=="AB"){
             
            if( $row['EXT'] + $_POST['no_of_marks'] >= $row['ETOTAL'] ){
                
                $row['EXT']=$row['ETOTAL'];
                $row['MARKS']=$row['EXT'];
                $row["RESULT"]=calcresult($row["EXT"],$row['ETOTAL'],$row['INT'],$row['ITOTAL']);
                $row["PERCENTAGE"]=100* ($row["MARKS"]/$row["TOTALMARKS"]); 
                $row["GPV"]=$row['PERCENTAGE']/10;
                $row["GPC"]=$row['GPV']*$row['CREDITS'];
                $row['GRADE']=grade($row["PERCENTAGE"],$row['RESULT']);
                
            }
            else{
                
                $row['EXT']=$row['EXT']+$_POST['no_of_marks'];
                $row["MARKS"]=$row['EXT'];
                $row["RESULT"]=calcresult($row["EXT"],$row['ETOTAL'],$row['INT'],$row['ITOTAL']);
                $row["PERCENTAGE"]=100* ($row["MARKS"]/$row["TOTALMARKS"]); 
                $row["GPV"]=$row['PERCENTAGE']/10;
                $row["GPC"]=$row['GPV']*$row['CREDITS'];
                $row['GRADE']=grade($row["PERCENTAGE"],$row['RESULT']);
                
            }
            
            
        }
        else{
                 if($row['EXT']+$_POST['no_of_marks']>=$row['ETOTAL']){
                    $row['EXT']=$row['ETOTAL'];
                    $row["MARKS"]=$row['EXT']+$row['INT'];
                    $row["RESULT"]=calcresult($row["EXT"],$row['ETOTAL'],$row['INT'],$row['ITOTAL']);
                    $row["PERCENTAGE"]=100* ($row["MARKS"]/$row["TOTALMARKS"]); 
                    $row["GPV"]=$row['PERCENTAGE']/10;
                    $row["GPC"]=$row['GPV']*$row['CREDITS'];
                    $row['GRADE']=grade($row["PERCENTAGE"],$row['RESULT']);
                }
                else{
                    $row['EXT']=$row['EXT']+$_POST['no_of_marks'];
                    $row["MARKS"]=$row['EXT']+$row['INT'];
                    $row["RESULT"]=calcresult($row["EXT"],$row['ETOTAL'],$row['INT'],$row['ITOTAL']);
                    $row["PERCENTAGE"]=100* ($row["MARKS"]/$row["TOTALMARKS"]); 
                    $row["GPV"]=$row['PERCENTAGE']/10;
                    $row["GPC"]=$row['GPV']*$row['CREDITS'];
                     $row["GPVV"]=$row['PERCENTAGE']/10;
                    $row["GPCC"]=$row['GPV']*$row['CREDITS'];
                   $row['GRADE']=grade($row["PERCENTAGE"],$row['RESULT']);
                }
        }
        array_push($updatedresults,$row);
        //PRINT UPDATED ROW
        foreach($row as $col){
            echo "<td>".$col."</td>";
        }
        echo "</tr>";
    }
    
    
} 
else {
    echo '<tr><td colspan="13">No Branches - Empty Table</td></tr>';
}
if(isset($_POST['confirmupdate'])=="OK"){

    foreach($updatedresults as $r){
        $rhid=$r["RHID"];
        $examid=$r["EXAMID"];
        $hallticket=$r["HALLTICKET"];
        $papercode=$r["PAPERCODE"];
        $papername=$r["PAPERNAME"];
        $eid=$r["EID"];
        $code=$r["CODE"];
        $ext=$r["EXT"];           
        $etotal=$r["ETOTAL"];
        $int=$r["INT"];
        $itotal=$r["ITOTAL"];
        $result=$r["RESULT"];
        $marks=$r["MARKS"];
        $totalmarks=$r["TOTALMARKS"];
        $credits=$r["CREDITS"];
        $percentage=$r["PERCENTAGE"]; 
        $grade=$r["GRADE"];
        $gpv=$r["GPV"];
        $gpc=$r["GPC"];
        $gpvv=$r["GPVV"];
        $gpcc=$r["GPCC"];
        $updaterh="UPDATE `PRERESULTS` SET `EXT`='$ext',`INT`='$int',`RESULT`='$result',`MARKS`='$marks',`PERCENTAGE`='$percentage',`GRADE`='$grade',`GPV`='$gpv',`GPC`='$gpc',`GPVV`='$gpvv',`GPCC`='$gpcc' WHERE RHID=$rhid";
            if($conn->query($updaterh)){
               echo $rhid."Updated <br>";
            }   
        }
 }

?>

<?php
mysqli_close($conn);
?>

</table>
</div>



<center>

</center>
</div>
</div>
</div>
</div>

<?php 
include 'datatablefooter.php';
?>