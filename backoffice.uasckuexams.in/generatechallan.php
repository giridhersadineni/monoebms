<!DOCTYPE html>
<html lang="en">

<head>
    
    <title>Challan</title>
   
</head>

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$sql = "select * from  enrolledview where id=" . $_GET['id'];



$result = $conn->query($sql);

if($result->num_rows>0){

while($row=$result->fetch_assoc()) {
break;
}
}
}
?>
<style>
.ex0{
    border: 2px solid black;
    border-radius:5px;
    padding:5px;
    margin-top: 10px;
    margin-bottom: 10px;
    margin-right: 10px;
    width: 150px;
}
table{
    border: 0px solid black;
    width: 450px;
    height: 190px;
}
td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}
.ex1{
    text-align:right;
    }

.ex2 {

 width: 99%;
 border: 0px solid black;


}
</style>

<body>
   <table>
    <tr>
    <!--Quadriplicate-->
    <td>
    <div class="ex0">QUARDUPLICATE</div> 
    <center><span>(To be retained by the Bank)</span></center><br>
    <span>  Challan No.<?php echo $row["ID"]?></span>
    <p>  Date </p>
    <div class="head" align="center">
    <h4>UNIVERSITY ARTS & SCIENCE COLLEGE</h4>
       <span>(autonomous)</span>
   <h4>STATE BANK OF INDIA</h4>
  <p>Subedari Branch,Hanamkonda.</p>
    <h3>Account No.52010041880</h3>   
</div>
<span>Name of the student: <?php echo $row["sname"] ?></span> <br>
          <p >Class: <?php echo $row["course"] ?></p>
          <p>H.T.No: <?php echo $row["haltckt"] ?></p>
          
        
     <p>Name of Exam & Year:<?php echo $row['EXAMNAME']?>  </p>
                
<table  border="" cellspacing="0" cellpadding="8">
<tr>
    <th>Exam Fee</th>
    <th>Amount</th>
    
</tr>
<tr>
  <td>
     
  <?php if($row["S1"]!="null") {echo $row["S1"]."</br>";}?> 
  <?php if($row["S2"]!="null") {echo $row["S2"]."</br>";}?> 
  <?php if($row["S3"]!="null") {echo $row["S3"]."</br>";}?> 
  <?php if($row["S4"]!="null") {echo $row["S4"]."</br>";}?> 
  <?php if($row["S5"]!="null") {echo $row["S5"]."</br>";}?> 
  <?php if($row["S6"]!="null") {echo $row["S6"]."</br>";}?> 
  <?php if($row["S7"]!="null") {echo $row["S7"]."</br>";}?> 
  <?php if($row["S8"]!="null") {echo $row["S8"]."</br>";}?> 
  <?php if($row["S9"]!="null") {echo $row["S9"]."</br>";}?> 
  <?php if($row["S8"]!="null") {echo $row["S10"]."</br>";}?> 
  <?php if($row["E1"]!="null") {echo $row["E1"]."</br>";}?> 
  <?php if($row["E2"]!="null") {echo $row["E2"]."</br>";}?> 
  <?php if($row["E3"]!="null") {echo $row["E3"]."</br>";}?> 
  <?php if($row["E4"]!="null") {echo $row["E4"]."</br>";}?><?php if($row["E5"]!="null") {echo $row["E5"]."</br>";}?> 
  
  </td>
  <td>
      <?php echo $row["FEEAMOUNT"]?>
  </td>
  </tr>

</table>

<p>Rupees in words:----------------------------------------------------------<br><br>---------------------------------------------------
</p> 

<div class="ex1">
<span>Signature of Candidate</span>
</div>

<div class="ex2">
<center><h3>FOR USE BY THE BANK</h3></center>

<h4>Rupees(in figure):</h4>

<h4>Rupees(in words):</h4>

<h4>Receving Cashier</h4>
<h4>Scroll Cashier</h4>

<h4 align="right"> Head Cashier</h4>
<h4>Entered By</h4>
</div>

<div class="ex1">
<h4>Manager/Acctn.</h4>
</div>
</td>
<!--Triplicate-->
<td>
<div class="ex0">TRIPLICATE</div>            
            <center><span>(To be retained by the College Concerned)</span></center><br>
            <span>  Challan No.<?php echo $row["ID"]?></span>
    <p>  Date </p>
                <!--Header place-->
            <div class="head" class="head" align="center">
                <h4>UNIVERSITY ARTS & SCIENCE COLLEGE</h4>
                   <span>(autonomous)</span>
               <h4>STATE BANK OF INDIA</h4>
              <p>Subedari Branch,Hanamkonda.</p>
              <h3>Account No.52010041880</h3>   
            </div>
           <span>Name of the student: <?php echo $row["sname"] ?></span> 
           <p >Class: <?php echo $row["course"] ?></p>
                    <p>H.T.No:<?php echo $row["haltckt"] ?></p>
                    <p>Name of Exam & Year:<?php echo $row["EXAMNAME"]?> </p>

        
        
                
<table  border="" cellspacing="0" cellpadding="8">
<tr>
    <th>Exam Fee</th>
    <th>Amount</th>
    
</tr>
<tr>
  <td> 
  <?php if($row["S1"]!="null") {echo $row["S1"]."</br>";}?> 
  <?php if($row["S2"]!="null") {echo $row["S2"]."</br>";}?> 
  <?php if($row["S3"]!="null") {echo $row["S3"]."</br>";}?> 
  <?php if($row["S4"]!="null") {echo $row["S4"]."</br>";}?> 
  <?php if($row["S5"]!="null") {echo $row["S5"]."</br>";}?> 
  <?php if($row["S6"]!="null") {echo $row["S6"]."</br>";}?> 
  <?php if($row["S7"]!="null") {echo $row["S7"]."</br>";}?> 
  <?php if($row["S8"]!="null") {echo $row["S8"]."</br>";}?> 
  <?php if($row["S9"]!="null") {echo $row["S9"]."</br>";}?> 
  <?php if($row["S8"]!="null") {echo $row["S10"]."</br>";}?> 
  <?php if($row["E1"]!="null") {echo $row["E1"]."</br>";}?> 
  <?php if($row["E2"]!="null") {echo $row["E2"]."</br>";}?> 
  <?php if($row["E3"]!="null") {echo $row["E3"]."</br>";}?> 
  <?php if($row["E4"]!="null") {echo $row["E4"]."</br>";}?>
  <?php if($row["E5"]!="null") {echo $row["E5"]."</br>";}?> 
  </td>
  <td>
      <?php echo $row["FEEAMOUNT"]?>
  </td>
  </tr>

</table>
<p>Rupees in words:----------------------------------------------------------<br><br>----------------------------------------------------------
</p> 

        <div class="ex1">
                <span>Signature of Candidate</span>
                </div>
 
                <div class="ex2">
                        <center><h3>FOR USE BY THE BANK</h3></center>
                     
                        <h4>Rupees(in figure):</h4>
                        
                        <h4>Rupees(in words):</h4>
                        
                        <h4>Receving Cashier</h4>
                        <h4>Scroll Cashier</h4>
                        
  <h4 align="right"> Head Cashier</h4>
   <h4>Entered By</h4>
                     </div>
                     <div class="ex1">
                            <h4>Manager/Acct.</h4>
                        </div>
        </td>


</td>

<td>
<table>
<tr>
<td>                        
                    <div class="ex0">DUPLICATE</div>                    
                    <center><span>(To be attached with the Form)</span></center><br>
                    <span>  Challan No.<?php echo $row["ID"]?></span>
    <p>  Date </p>
                    <!--Header place-->
                    <div class="head" class="head" align="center">
                        <h4>UNIVERSITY ARTS & SCIENCE COLLEGE</h4>
                           <span>(autonomous)</span>
                       <h4>STATE BANK OF INDIA</h4>
                      <p>Subedari Branch,Hanamkonda.</p>
                      <h3>Account No.52010041880</h3>   
                    </div>
                   <span>Name of the student:<?php echo $row["sname"] ?></span>
                   <p >Class:<?php echo $row["course"] ?> </p>
                            <p>H.T.No:<?php echo $row["haltckt"] ?></p>
                
           <p>Name of Exam & Year:<?php echo $row["EXAMNAME"]?> </p>

                
                
<table  border="" cellspacing="0" cellpadding="8">
<tr>
    <th>Exam Fee</th>
    <th>Amount</th>
    
</tr>
<tr>
  <td> 
  <?php if($row["S1"]!="null") {echo $row["S1"]."</br>";}?> 
  <?php if($row["S2"]!="null") {echo $row["S2"]."</br>";}?> 
  <?php if($row["S3"]!="null") {echo $row["S3"]."</br>";}?> 
  <?php if($row["S4"]!="null") {echo $row["S4"]."</br>";}?> 
  <?php if($row["S5"]!="null") {echo $row["S5"]."</br>";}?> 
  <?php if($row["S6"]!="null") {echo $row["S6"]."</br>";}?> 
  <?php if($row["S7"]!="null") {echo $row["S7"]."</br>";}?> 
  <?php if($row["S8"]!="null") {echo $row["S8"]."</br>";}?> 
  <?php if($row["S9"]!="null") {echo $row["S9"]."</br>";}?> 
  <?php if($row["S8"]!="null") {echo $row["S10"]."</br>";}?> 
  <?php if($row["E1"]!="null") {echo $row["E1"]."</br>";}?> 
  <?php if($row["E2"]!="null") {echo $row["E2"]."</br>";}?> 
  <?php if($row["E3"]!="null") {echo $row["E3"]."</br>";}?> 
  <?php if($row["E4"]!="null") {echo $row["E4"]."</br>";}?>
  <?php if($row["E5"]!="null") {echo $row["E5"]."</br>";}?> 
  </td>
  <td>
      <?php echo $row["FEEAMOUNT"]?>
  </td>
  </tr>

</table>
<p>Rupees in words:----------------------------------------------------------<br><br>-------------------------------------------------------
</p>  

                <div class="ex1">
                        <span>Signature of Candidate</span>
                        </div>
                        <div class="ex2">
                                <center><h3>FOR USE BY THE BANK</h3></center>
                             
                                <h4>Rupees(in figure):</h4>
                                
                                <h4>Rupees(in words):</h4>
                                
                                <h4>Receving Cashier</h4>
                                <h4>Scroll Cashier</h4>
                               
  <h4 align="right"> Head Cashier</h4>
                                   <h4>Entered By</h4>
                             </div>
                             <div class="ex1">
                                    <h4>Manager/Acctn.</h4>
                                </div>


</td>  <!--end of third table-->




</tr>
   </table>







            
<!--Third table-->


    

<!--Fourth Table-->

    <td>
            <table>
                    <tr>
                        <td>
                        
                    <div class="ex0">ORIGINAL</div>
                       <center><span>(To be retained by the Student)</span></center><br>
                       <span>  Challan No.<?php echo $row["ID"]?></span>
    <p>  Date </p>  
                    <!--Header place-->
                    <div class="head" class="head" align="center">
                        <h4>UNIVERSITY ARTS & SCIENCE COLLEGE</h4>

                           <p>(autonomous)</p>

                       <h4>STATE BANK OF INDIA</h4>
                      <p>Subedari Branch,Hanamkonda.</p>
                      <h3>Account No.52010041880</h3>  
                    </div>

                   <span>Name of the student:<?php echo $row["sname"] ?></span> 
                   <p >Class:<?php echo $row["course"] ?> </p>
                            <p>H.T.No:<?php echo $row["haltckt"] ?></p>
                
                            <p>Name of Exam & Year:<?php echo $row["EXAMNAME"]?>  </p>

                
                
<table  border="" cellspacing="0" cellpadding="8">
<tr>
    <th>Exam Fee</th>
    <th>Amount</th>
    
</tr>
<tr>
  <td> 
  <?php if($row["S1"]!="null") {echo $row["S1"]."</br>";}?> 
  <?php if($row["S2"]!="null") {echo $row["S2"]."</br>";}?> 
  <?php if($row["S3"]!="null") {echo $row["S3"]."</br>";}?> 
  <?php if($row["S4"]!="null") {echo $row["S4"]."</br>";}?> 
  <?php if($row["S5"]!="null") {echo $row["S5"]."</br>";}?> 
  <?php if($row["S6"]!="null") {echo $row["S6"]."</br>";}?> 
  <?php if($row["S7"]!="null") {echo $row["S7"]."</br>";}?> 
  <?php if($row["S8"]!="null") {echo $row["S8"]."</br>";}?> 
  <?php if($row["S9"]!="null") {echo $row["S9"]."</br>";}?> 
  <?php if($row["S8"]!="null") {echo $row["S10"]."</br>";}?> 
  <?php if($row["E1"]!="null") {echo $row["E1"]."</br>";}?> 
  <?php if($row["E2"]!="null") {echo $row["E2"]."</br>";}?> 
  <?php if($row["E3"]!="null") {echo $row["E3"]."</br>";}?> 
  <?php if($row["E4"]!="null") {echo $row["E4"]."</br>";}?>
  <?php if($row["E5"]!="null") {echo $row["E5"]."</br>";}?> 
  </td>
  <td>
      <?php echo $row["FEEAMOUNT"]?>
  </td>
  </tr>

</table>
<p>Rupees in words:----------------------------------------------------------<br><br>------------------------------------------------</p> 

                <div class="ex1">
                        <span>Signature of Candidate</span>
                        </div>
                
                        <div class="ex2">
                                <center><h3>FOR USE BY THE BANK</h3></center>
                             
                                <h4>Rupees(in figure):</h4>
                                
                                <h4>Rupees(in words):</h4>
                                
                                <h4>Receving Cashier</h4>
                                <h4>Scroll Cashier</h4>
                            
  <h4 align="right"> Head Cashier</h4>
                                     <h4>Entered By</h4>
                             </div>
                             <div class="ex1">
                                    <h4>Manager/Acctn.</h4>
                                </div>
    </tr>







</table>
</body>

</html>