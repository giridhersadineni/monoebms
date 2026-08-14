<?php 
 if(isset($_POST['update'])){
 //print_r($_POST);     
 include 'config.php';
 $papercode=$_POST['PAPERCODE'];
 $papername=$_POST['PAPERNAME'];
 $ext_marks=$_POST["EXT_MARKS"];
 $ext_total=$_POST["EXT_TOTAL"];
 $internal_marks=$_POST["INT_MARKS"];
 $internal_total=$_POST["INT_TOTAL"];
 $result=$_POST["RESULT"];
 $credits=$_POST["CREDITS"];
 $marks=$_POST["MARKS_SECURED"];
 $totalmarks=$_POST["TOTAL_MARKS"];
 $percentage=$_POST["PERCENTAGE"];
 $grade=$_POST["GRADE"];
 $gpc=$_POST["GPC"];
 $gpv=$_POST["GPV"];
 $gpcc=$_POST["GPCC"];
 $gpvv=$_POST["GPVV"];
 
 $sem=$_POST['SEM'];
//print_r($_POST);
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn){
    die("connection failed:".mysqli_connect_error());
}
  $sql="UPDATE rholdernew SET EXT='$ext_marks',ETOTAL='$ext_total' ,`INT`='$internal_marks',ITOTAL='$internal_total',PAPERNAME='$papername',PAPERCODE='$papercode',RESULT='$result',CREDITS='$credits',MARKS='$marks',PERCENTAGE='$percentage', GRADE='$grade', GPV='$gpv', GPC='$gpc',GPVV='$gpvv', GPCC='$gpcc', SEMESTER='$sem' WHERE  HALLTICKET=".$_GET['hallticket']."  AND RHID='".$_POST["RHID"]."'";
  $result=$conn->query($sql);
  echo $sql;
    if ($conn->query($sql) === true) {
    
    echo "<script>alert('Update Successfull.');</script>";
    ?>

<?php
 //  echo "success"; 
}
else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
}
?>


<?php include 'header.php'?>

<!--Php start here-->

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$sql = "select * FROM rholdernew WHERE HALLTICKET='".$_GET['hallticket']."'  AND RHID='".$_GET['id']."'";
$result = $conn->query($sql);
   //echo $sql;
if($result->num_rows>0){

while($row=$result->fetch_assoc()) {
break;
}
}
}
?>
<!--Php end here-->
<div class="page-wrapper">
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card">
<div class="card-header">
    <h3>Edit Marks</h3>
    <form method='POST' action='cmmreport.php'>
        <input type='hidden' name='ht' value='<?php echo $_GET['hallticket']; ?>'>
       <p class="text-right">
       <input type="submit" value="Go Back" class="btn btn-success">
       </p> 
    </form>
    
</div>
<div class="card-body">

<form  action="editmarks.php?hallticket=<?php echo $_GET["hallticket"]?>&id=<?php echo $_GET["id"]?>" method="post">
    
 <input type="hidden" class="form-control" value="<?php echo $row['RHID']; ?>" id="RHID" name="RHID">
 
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>EXAM ID</h4></p>
 <input type="text"  class="form-control" name="EID" id="EID" value="<?php echo $row['EXAMID']; ?>" readonly >

</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>HALLTICKET</h4></p>
<input type="text" class="form-control" value="<?php echo $row['HALLTICKET']; ?>" name="HALLTICKET" id="HALLTICKET" >
</div></div></div>
 
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>PAPERCODE</h4></p>
<input type="text" class="form-control" value="<?php echo $row['PAPERCODE']; ?>" id="PAPERCODE" name="PAPERCODE">
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>PAPERNAME</h4></p>
<input type="text" class="form-control" id="PAPERNAME" name="PAPERNAME" value="<?php echo $row['PAPERNAME']; ?>">
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>EXTERNAL</h4></p>
<input type="text" class="form-control"  onchange="calculate()" value="<?php echo $row['EXT']; ?>" name="EXT_MARKS" id="EXT_MARKS">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>EXT TOTAL</h4></p>
<input type="text" class="form-control" name="EXT_TOTAL" id="EXT_TOTAL" onchange="calculate()" value="<?php echo $row['ETOTAL']; ?>" >
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>INTERNAL</h4></p>
<input type="text" class="form-control" onchange="calculate()" value="<?php echo $row['INT']; ?>" id="INT_MARKS" name="INT_MARKS">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>INTERNAL TOTAL </h4></p>
<input type="text" class="form-control" onchange="calculate()" name="INT_TOTAL" id="INT_TOTAL" value="<?php echo $row['ITOTAL']; ?>" >
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>RESULT</h4></p>
<input type="text" class="form-control" onchange="calculate()"  value="<?php echo $row['RESULT']; ?>" name="RESULT" id="RESULT">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>CREDITS</h4></p>
<input type="text" class="form-control" name="CREDITS" id="CREDITS" onchange="calculate()" value="<?php echo $row['CREDITS']; ?>">
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>MARKS</h4></p>
<input type="text" class="form-control" onchange="calculate()" value="<?php echo $row['MARKS']; ?>" name="MARKS_SECURED" id="MARKS_SECURED" >
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>TOTAL MARKS</h4></p>
<input type="text" class="form-control" name="TOTAL_MARKS" id="TOTAL_MARKS" value="<?php echo $row['TOTALMARKS']; ?>" readonly>
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>PERCENTAGE</h4></p>
<input type="text" class="form-control" onchange="calculate()" value="<?php echo $row['PERCENTAGE']; ?>" name="PERCENTAGE" id="PERCENTAGE" >
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>GRADE</h4></p>
<input type="text" class="form-control" onchange="calculate()" name="GRADE" id="GRADE" value="<?php echo $row['GRADE']; ?>" >
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>GPV</h4></p>
<input type="text" class="form-control" onchange="calculate()" value="<?php echo $row['GPV']; ?>" name="GPV" id="GPV" >
</div></div>    
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>GPC</h4></p>
<input type="text" class="form-control" onchange="calculate()" name="GPC" id="GPC" value="<?php echo $row['GPC']; ?>" >
</div></div>    
</div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>SEMESTER</h4></p>
<input type="text" class="form-control"  value="<?php echo $row['SEMESTER']; ?>" name="SEM" id="SEM" >
</div></div>    
 </div>



  <center>
  <input type="hidden" id="GPVV" name="GPVV" value="<?php echo $row['GPV']; ?>">
  <input type="hidden" id="GPCC" name="GPCC" value="<?php echo $row['GPC']; ?>">
  <input type="submit" class="btn btn-primary" name="update" value="update">
</center>
 </form>
 </div>
</div>
     </div>
 </div>
 
                <!-- End PAge Content -->
 </div>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 <script>
 function calculate(){
     
         var i=parseInt(document.getElementById('INT_MARKS').value);
         
         var itotal=parseInt(document.getElementById('INT_TOTAL').value);
         
         var e=parseInt(document.getElementById('EXT_MARKS').value);
         
         var etotal=parseInt(document.getElementById('EXT_TOTAL').value);
         
         document.getElementById('MARKS_SECURED').value=e+i;
         var grade="N";
         var totalmarks=itotal+etotal;
         document.getElementById('TOTAL_MARKS').value=totalmarks;
         
         marks=e+i;
       
        var percentage=Math.round((marks/totalmarks)*100);
       
     
        document.getElementById('PERCENTAGE').value=percentage;
      
            if(percentage>=85){
                
            grade="O";
            
            }
            else if(percentage>=70){
            grade="A";
            }
            else if(percentage>=60){
                grade="B";
            }
            else if(percentage>=55){
                grade="C";
            }    
            else if(percentage>=50){
               
                grade="D";
            }    
            else if(percentage>=40) {
               
                grade="E";
            }
            else{
              grade="F";
          }
            
            document.getElementById('GRADE').value=grade;
        
            if(percentage>=90){
            
        gpv=10;
        
        }
            else if(percentage>=80){ gpv=9; }
            else if(percentage>=70){ gpv=8; }    
            else if(percentage>=60){ gpv=7; }    
            else if(percentage>=50){ gpv=6; }    
            else if(percentage>=40) { gpv=5; }
            else if(percentage>=30){ gpv=4;} 
            else if(percentage>=20){ gpv=3; }
            else if(percentage>=10){ gpv=2; }
            else{ gpv=1; }
            
            var credits=parseInt(document.getElementById('CREDITS').value);
            gpv=percentage/10;
            var gpc=credits*gpv;
            var gpvv=gpv;
            var gpcc=gpc;
            document.getElementById('GPVV').value=gpvv;
            document.getElementById('GPCC').value=gpcc;
            document.getElementById('GPC').value=gpc;
            document.getElementById('GPV').value=gpv;
        //console.log(gpv);
           if(e/etotal>=0.4)
        {
            result='P';
        }else{
            result='F';
        }
        document.getElementById('RESULT').value=result;
 } 
 </script>
<?php include 'footer.php'?>
