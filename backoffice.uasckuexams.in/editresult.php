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
   echo $sql;
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
<div class="card-body">
      
<form  action="updatemarks.php?exam=<?php echo $_GET["exam"]?>&hallticket=<?php echo $_GET["hallticket"]?>&code=<?php echo $_GET["pcode"]?>" method="post">
    
 <input type="hidden" class="form-control" value="<?php echo $row['RHID']; ?>" id="RHID" name="RHID">
 
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>EXAM ID</h4></p>
 <input type="text"  class="form-control" name="EID" id="EID" value="<?php echo $row['EXAMID']; ?>" disabled >

</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>HALLTICKET</h4></p>
<input type="text" class="form-control" value="<?php echo $row['HALLTICKET']; ?>" name="hallticket" id="hallticket"  disabled >
</div></div></div>
 
<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>PAPERCODE</h4></p>
<input type="text" class="form-control" value="<?php echo $row['PAPERCODE']; ?>" id="PAPERCODE" name="PAPERCODE" disabled>
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>PAPERNAME</h4></p>
<input type="text" class="form-control" id="papername" name="papername" value="<?php echo $row['PAPERNAME']; ?>" disabled>
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>EXTERNAL</h4></p>
<input type="text" class="form-control"  onchange="calculate()" value="<?php echo $row['EXT']; ?>" name="ext" id="emarks">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>EXT TOTAL</h4></p>
<input type="text" class="form-control" name="etotal" id="etotal" value="<?php echo $row['ETOTAL']; ?>" >
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>INTERNAL</h4></p>
<input type="text" class="form-control" onchange="calculate()" value="<?php echo $row['INT']; ?>" id="imarks" name="int">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>INTERNAL TOTAL </h4></p>
<input type="text" class="form-control" name="itotal" id="itotal" value="<?php echo $row['ITOTAL']; ?>" >
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>RESULT</h4></p>
<input type="text" class="form-control" onchange="calculate()"  value="<?php echo $row['RESULT']; ?>" name="result" id="result">
</div></div>

<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>CREDITS</h4></p>
<input type="text" class="form-control" name="CREDITS" id="CREDITS" value="<?php echo $row['CREDITS']; ?>">
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>MARKS</h4></p>
<input type="text" class="form-control" onchange="calculate()" value="<?php echo $row['MARKS']; ?>" name="marks" id="marks" >
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>TOTAL MARKS</h4></p>
<input type="text" class="form-control" name="tmarks" id="tmarks" value="<?php echo $row['TOTALMARKS']; ?>">
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>PERCENTAGE</h4></p>
<input type="text" class="form-control" onchange="calculate()" value="<?php echo $row['PERCENTAGE']; ?>" name="percentage" id="percentage" >
</div></div>
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>GRADE</h4></p>
<input type="text" class="form-control" onchange="calculate()" name="grade" id="grade" value="<?php echo $row['GRADE']; ?>" >
</div></div></div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>GPV</h4></p>
<input type="text" class="form-control" onchange="calculate()" value="<?php echo $row['GPV']; ?>" name="gpv" id="gpv" >
</div></div>    
<div class="col-md-6">
<div class="form-group">
<p class="text-muted m-b-15 f-s-12"><h4>GPC</h4></p>
<input type="text" class="form-control" onchange="calculate()" name="gpc" id="gpc" value="<?php echo $row['GPC']; ?>" >
</div></div>    

</div>

  <center>
        
  <input type="submit" class="btn btn-primary" name="submit" value="Update">
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
     
         var i=parseInt(document.getElementById('imarks').value);
         
         var itotal=parseInt(document.getElementById('itotal').value);
         
         var e=parseInt(document.getElementById('emarks').value);
         
         var etotal=parseInt(document.getElementById('etotal').value);
         
       
         document.getElementById('marks').value=e+i;
         var grade="N";
         var totalmarks=itotal+etotal;
         marks=e+i;
       
        var percentage=Math.round((marks/totalmarks)*100);
       
     
        document.getElementById('percentage').value=percentage;
      
            if(percentage>=90){
                
            grade="O";
            
            }
            else if(percentage>=80){
            grade="A";
            }
            
            else if(percentage>=70){
                grade="B";
            }    
             else if(percentage>=60){
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
        document.getElementById('grade').value=grade;
        
        if(percentage>=90){
            
        gpv=10;
        
        }
        else if(percentage>=80){
        gpv=9;
        }
        
        else if(percentage>=70){
            gpv=8;
        }    
         else if(percentage>=60){
            gpv=7;
        }    
       else if(percentage>=50){
           
            gpv=6;
        }    
      else if(percentage>=40) {
           
            gpv=5;
        }
      else if(percentage>=30){
         gpv=4;
      }
        else if(percentage>=20){
         gpv=3;
      }
       else if(percentage>=10){
         gpv=2;
      }
      else{
          gpv=1;
      }
        document.getElementById('gpv').value=gpv;
        
        var credits=parseInt(document.getElementById('CREDITS').value);
        var gpc=credits*gpv;
        
        document.getElementById('gpc').value=gpc;
        //console.log(gpv);
 } 
 </script>
<?php include 'footer.php'?>
