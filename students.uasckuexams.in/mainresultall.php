

<html>
    <head>
        <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
   <title>University Arts & Science College Exam Result.</title>
     <script async src="https://www.googletagmanager.com/gtag/js?id=UA-131292962-2"></script>
     <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-131292962-2');
      ga('set', 'userId', '<?php echo $_COOKIE['name']; ?>'); // Set the user ID using signed-in user_id.
    </script>
        <!--<img src="./images/Logo1.jpg" alt="College Logo" width='750px' >-->
</head>
  
<style>
  table{
            border-style:solid;
            border-collapse:collapse;
    
        }
            tr,td,th{
                border:solid 1px black;
                
            }
            td{
                padding:0.25rem;
                
            } 
            img{
                  position: absolute;
                     left: 0;
                   right: 0;
                     margin: 0 auto;
            }
         .topnav {
             background-color: #33b5e5;
         }
         .topnav a{
             padding:1em;
             display: block;
             text-decoration: none;
             width:50px;
         }
         .text-info{
             color:red;
         }
        </style>
</head>

<?php
include "functions.php";
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$res = "select * from  enrolledview where haltckt=" . $_POST['hallticket']." and ID=".$_POST['enrollmentid'];
//echo $res;
$result = $conn->query($res);
$examtype="";
if($result->num_rows>0){

while($row=$result->fetch_assoc())
 { 
     
     
  break;
 }
}
$hallticket=$_POST['hallticket'];
$examid=$row['EXAMID'];
$examtype=$_POST['examtype'];
$sem=$_POST['SEM'];

}
?>
  


<body>
<img src="images/Logo1.jpg" alt="University Arts & Science College" style="width:550px" />
<br> <br><br><br><br>
<div class="topnav">
<a href="#" ></a>
</div>

<table align='center'>
<tr>
<center>
<h3 style='color:CC3300'><?php echo $row['EXAMNAME'];$examtype=""; ?> </font></h3>
 </center>
</tr>
</table>

 <table align='center'>
       <col align="center">
 
 <tr >
  <td colspan='4'>
      Student Name : <strong><?php echo $row['sname'] ?> </strong><br>
      Hallticket No :<strong><?php echo $row['haltckt'] ?></strong><br>
    <!--Course Name : <strong>-->
    <?php
    //echo $row['COURSEE']
    ?>
    </strong>Group : <strong><?php echo $row['group'] ?><strong></td>
	
</tr>

 <tr>
 <td bgcolor="#33b5e5" >PAPERCODE</td>
 <td bgcolor="#33b5e5">PAPERNAME</td>
 <td bgcolor="#33b5e5">CREDITS</td>
 <td bgcolor="#33b5e5">GRADE</td>
 </tr>
 

<?php 
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$sql = "select * from  RESULTS where EID=" . $_POST['enrollmentid'];
$nq="select * from enrolledview where haltckt=".$_POST['hallticket'];
$gpt=0;
 $totalcredit=0;
 $passed=1;
 $examtype="";
$result = $conn->query($sql);

if($result->num_rows>0){

while($paper=$result->fetch_assoc())
 { 
     
    echo "<tr>";
    
   echo "<td style='text-align:center;'>".$paper['PAPERCODE']."</td>";
   
   echo "<td>".strtoupper($paper['PAPERNAME'])."</td>";
   
   echo "<td style='text-align:center;'>".$paper['CREDITS']."</td>";
   
   echo "<td style='text-align:center;'>".$paper['GRADE']."</td>";
   
//   $gpt+=$row['GPC'];
//   $totalcredit+=$row['CREDITS'];
//   if($row['RESULT']=="F" || $row['RESULT']=="AB"){
//       $passed=0;
//   }
   echo "</tr>";
}
//end of displaying papers
?>


 
</table>
<br>
<table align="center">
    
    <thead>
        <tr>
        <th>RESULT</th>
        <th>SGPA</th>
        <th>CGPA Part-I</th>
        <th>CGPA Part-II</th>
        <th>CGPA (Overall)</th>
        </tr>
    </thead>
    
    <tbody>
    <?php
//result block
$getgpa="select * from gpas where EXAMID='$examid' and HALLTICKET='$hallticket'";

//echo $getgpa;
$examtype=$_POST['examtype'];
//echo $examtype;
$gpares=$conn->query($getgpa);
if($gpares->num_rows>0){
$gpa=mysqli_fetch_assoc($gpares);
//print_r($gpa);

    $p1=getPartOneCgpa($_POST['hallticket']);
    $p2=getPartTwoCgpa($_POST['hallticket']);
    
    if($gpa['RESULT']=='P')
    { 
         
            echo "<tr><td>PASSED</td>";
            echo "<td>".$gpa['SGPA']."</td>";
            if($sem==6){
            echo "<td>".$p1['CGPA']."</td>";
            echo "<td>".$p2['CGPA']."</td>";
            echo "<td>".($p1['CGPA']+$p2['CGPA'])/2,"</td>";
            }
            else{
                echo "<td>-</td>";
                echo "<td>-</td>";
                echo "<td>-</td>";
            }
            echo "</tr>";
            
            echo "<tr><td colspan='2'>Sec/Total Marks</td>";
            
            if($sem==6){
                echo "<td>".$p1['MARKS']."/".$p1['TOTAL']."</td>";
                echo "<td>".round($p2['MARKS'],0)."/".$p2['TOTAL']."</td>";
                echo "<td>".round(($p1['MARKS']+$p2['MARKS']),0)."/".($p1['TOTAL']+$p2['TOTAL'])."</td>";
            }
            else{
                echo "<td>-</td>";
                echo "<td>-</td>";
                echo "<td>-</td>";
            }
            
            
            echo "</tr>";
            
    }
        
    else if($gpa['RESULT']=='R') 
    { 
            
            echo "<tr><td>PROMOTED</td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            echo "</tr>";
        
    }
     else if($gpa['RESULT']=='M') 
     { 
            echo "<tr><td>MALPRACTICE</td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            echo "</tr>"; 
         
     }
     else{
         echo "<tr><td>WITHHELD</td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            echo "<td>-</td>";
            echo "</tr>";
     } 
          
 

}
 

    
}

}
?>
        
    </tbody>
    <tfoot>
        
        <tr> 
  <td align="center" colspan='5'><button onclick="print()"><strong>Print<strong></button>
    <!--<a href="enrollments.php" type='button' class="btn btn-default">Back</a>--></td>
  </tr>
    </tfoot>
</table>



      <script>
            function print() {
                window.print();
            }
        </script>
<br>
<div class='tab1'>
     <!--<h4  align="center" class="text-info">Applications for Revaluation will be Accepted from <strong>10-07-2019</strong> to <strong>18-07-2019</strong> </h4>-->
 
    

 <h3 align="center" style='color:CC3300'>O: &gt;=85 to 100;  A: &gt;=70 to &lt;85;   B: &gt;=60 to &lt;70:   
                   C:&gt;=55 to &lt;60;   D:&gt;=50 to &lt;55;   E: &gt;=40 to &lt;50; F:FAIL.
      </h3>
      <h5>Division :  CGPA 7.00-10.00 (First With Distinction) - CGPA 6.00-6.99 First Division - CGPA 5.00-5.99 Second CGPA 4.00-4.99 Pass</h5>
      <h4  align="center">This information is provided to the candidate on his/her online request and is only a prototype list.<br>
      If any discrepancies in the marks you may be brought to the notice
 of Controller of Examinations University Arts & Science College - Subedari-Warangal</h4>

 <!--SGPA calculated for passed candidates only-->
 
</div>
<br><br><br><br><br>
<table>
<tr >
    <td  align="left" bgcolor="#33b5e5"width="30%"><font color="#FFFFFF">&nbsp;&nbsp;© Copyrights Reserved.2018 all rights reserved @ SYS Technology</td>   
</tr>

<?php include ("datatablefooter.php"); ?>
