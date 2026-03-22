<?php include 'header.php'?>
<?php 
if(isset($_GET['success'])){

echo '<script>alert("Successfully Created");</script>';

}

?>
<!--Php start here-->

<!--Php end here-->
 <div class="page-wrapper">
<!-- Bread crumb -->
<!--<div class="row page-titles">-->
<!--<div class="col-md-5 align-self-center">-->
<!--<h3 class="text-primary">Add Result </h3> </div>-->
<!--<div class="col-md-7 align-self-center">-->
<!--<ol class="breadcrumb">-->
<!--<li class="breadcrumb-item"><a href="javascript:void(0)">Edit master</a></li>-->
<!--<li class="breadcrumb-item active">Add Result</li>-->
<!--</ol>-->
<!--</div>-->
<!--</div>-->


<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-sm-12">
<div class="card">
<div class="card-body">
      
<form  action="updaterholder.php" method="post">
    
 <!--<input type="hidden" class="form-control" placeholder="Enter  Semester" id="RHID" name="RHID">-->
 
<div class="row">
    
<div class="col-md-2 bg-light border">
<label>EXAM ID</label>    
<div class="form-group">
 <input type="text"   class="input-group-prepend form-control" name="EID" id="EID" >

</div></div>

<div class="col-md-3 bg-light border">
<label>HALLTICKET</label>
<div class="form-group">
<input type="text" class="input-group-prepend form-control" name="hallticket" id="hallticket">
</div></div>


<div class="col-md-3 bg-light border">
<label>PAPERCODE</label>
<div class="form-group">
<input type="text" class="input-group-prepend form-control"  id="PAPERCODE" name="PAPERCODE" >
</div></div>

<div class="col-md-4 bg-light border">
<label>PAPERNAME</label>
<div class="input-group">
<input type="text" class="input-group-prepend form-control" id="papername" name="papername" >
</div></div>

</div>

<div class="row">
        
<div class="col-md-3 bg-light border">
<label>ENROLLMENT ID</label>
<div class="form-group">
<input type="text" class="input-group-prepend form-control"  id="enrollmenid" name="enrollmenid">
</div></div>

<div class="col-md-3 bg-light border">
<label>SCRIPITCODE</label>
<div class="form-group">
<input type="text" class="input-group-prepend form-control"  id="CODE" name="CODE">
</div></div>

    
<div class="col-md-3 bg-light border">
<label>CREDITS</label>
<div class="input-group mb-3">
<input type="text" class="input-group-prepend form-control" name="CREDITS" id="CREDITS">
</div></div>

<div class="col-md-3 bg-light border">
<label>RESULT</label>
<div class="input-group mb-3">
<input type="text" class="input-group-prepend form-control" onchange="calculate()"  name="result" id="result">
</div>
</div>
</div>



<div class="row">
<div class="col-md-4 bg-light border">
<label>External Marks/External TotalMarks</label>
<div class="input-group-prepend">
<input type="text" class="input-group-prepend form-control"  placeholder="External Marks" onchange="calculate()"  name="ext" id="emarks">
<div class="input-group-prepend">
<input type="text" class="input-group-prepend form-control" placeholder="External TotalMarks" name="etotal" id="etotal">
</div></div></div>    
    
<div class="col-md-4 bg-light border">
<label>Internal Marks/Internal Total</label>
<div class="input-group-prepend">
<input type="text" class="input-group-prepend form-control" placeholder="Internal Marks" onchange="calculate()"  id="imarks" name="int">
<div class="input-group-append">
<input type="text" class="  form-control" placeholder="Internal total Marks" name="itotal" id="itotal" >
</div></div></div>    
    
<div class="col-md-4 bg-light border">
<label>Marks Secured/ Total Marks</label>
<div class="input-group mb-3">
<input type="text" class="input-group-prepend form-control" onchange="calculate()"  name="marks" id="marks" placeholder="Marks Secured">
<div class="input-group-append">
<input type="text" class="form-control" name="tmarks" id="tmarks" placeholder="Subject Total">
</div></div></div>



</div>

<div class="row">
<div class="col-md-3 bg-light border">
<label>Percentage</label>
<div class="input-group mb-3">
<input type="text" class="input-group-prepend form-control" onchange="calculate()"  name="percentage" id="percentage" >
</div></div>

<div class="col-md-3  bg-light border">
<label>Grade</label>
<div class="input-group mb-3">
<input type="text" class="input-group-prepend form-control" onchange="calculate()" name="grade" id="grade" >
</div></div>

<div class="col-md-3 bg-light border">
<label>Gpv</label>
<div class="form-group mb-3">
<input type="text" class="input-group-prepend form-control" onchange="calculate()" name="gpv" id="gpv" >
</div></div> 

<div class="col-md-3 bg-light border">
<label>Gpc</label>
<div class="input-group mb-3">
<input type="text" class="input-group-prepend form-control" onchange="calculate()" name="gpc" id="gpc"  >
</div></div>

</div>

<br>
<br>
  <center>
        
  <input type="submit" class="btn btn-primary" name="submit" id="submit" value="Update">
</center>
 </form>
 </div>
</div>
     </div>
 </div>
 
                <!-- End PAge Content -->
 </div>

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
        
        
        if(e/etotal>=0.4)
        {
            result='P';
        }else{
            result='F';
        }
        document.getElementById('result').value=result;
        
        //console.log(gpv);
 } 
 </script>
<?php include 'footer.php'?>
