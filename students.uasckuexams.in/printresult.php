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
            tr,td{
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
    if(isset($_POST['hallticket'])){
        include "config.php";
        $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
            //check connection
            if ($conn->connect_error){
                die("connection failed:".mysqli_connect_error());
            }
            else{
                $examtype="";
                $hallticket=$_POST['hallticket'];
                $examid=$_POST['EXAMID'];
                $examtype=$_POST['examtype'];
                $res = "select * from students where haltckt='".$_POST['hallticket']."'";
                // echo $res;
                $getexam="select * from examsmaster where EXID=$examid";
                //echo $res;
                $result = $conn->query($res);
                $student=$result->fetch_assoc();
                $examresult=$conn->query($getexam);
                $exam=$examresult->fetch_assoc();
                
                
            }
            
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
    <td colspan="4">
    <h3 style='color:CC3300'><?php 
    echo $exam['EXAMNAME'];
    $examtype=""; 
    ?> </font></h3>
     </td>
  </tr>
  <tr >
  <td colspan='4'>
     Student Name : <strong><?php echo $student['sname'] ?> </strong><br>
    Hallticket No :<strong><?php echo $student['haltckt'] ?></strong><br>
    Course Name : <strong><?php echo $student['course'];  ?><br>
    </strong>Group : <strong><?php echo $student['group'] ?><strong></td>
	
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
$getpapers = "SELECT * FROM `rholdernew` WHERE EXAMID=$examid and HALLTICKET='$hallticket' order by PAPERCODE";
// echo $getpapers;
$gpt=0;
 $totalcredit=0;
 $passed=1;
 $examtype="";
$papers = $conn->query($getpapers);
// print_r($papers);
if($papers->num_rows>0){

while($paper=$papers->fetch_assoc())
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
$getgpa="select * from gpas where EXAMID='$examid' and HALLTICKET='$hallticket'";

//echo $getgpa;
$examtype=$_POST['examtype'];
//echo $examtype;
$gpares=$conn->query($getgpa);
if($gpares->num_rows>0){
    $gpa=mysqli_fetch_assoc($gpares);
    //print_r($gpa);
 if($gpa['RESULT']=='P')
    { 
    echo "<tr><td colspan='2'><strong>FINAL RESULT</strong></td><td colspan='2'>";
    echo "PASSED";
    echo "<tr><td colspan='2'><strong>SGPA</strong></td><td colspan='2' >";
    echo $gpa['SGPA'];
    echo "</td></tr>";
    }
    else if($gpa['RESULT']=='R') 
    { 
    echo "<tr><td colspan='2'><strong>FINAL RESULT</strong></td><td colspan='2'>";
    echo "PROMOTOED"; 
    }
    else if($gpa['RESULT']=='M') { 
    echo "MALPRACTICE"; 
    }
    else{
         echo "";
    } 
     echo "</td></tr>";
}
 
//  echo '<tr><td colspan="4">RESULT: </td></tr>';
}

}
?>
<tr> 
  <td align="center" colspan='4'><button onclick="myFunction()"><strong>Print<strong></button>
    <!--<a href="enrollments.php" type='button' class="btn btn-default">Back</a>--></td>
  </tr>
</table>
<script>
function myFunction() {
    window.print();
}
</script>
<br>
<div class='tab1'>
     <!--<h4  align="center" class="text-danger">Applications for Revaluation will be Accepted from <strong>14-01-2020</strong> to <strong>25-01-2020</strong> </h4>-->
 
    

 <h3 align="center" style='color:CC3300'>O: &gt;=85 to 100;  A: &gt;=70 to &lt;85;   B: &gt;=60 to &lt;70:   
                   C:&gt;=55 to &lt;60;   D:&gt;=50 to &lt;55;   E: &gt;=40 to &lt;50; F:FAIL.
      </h3>
      <h2 style="color:red;text-align:center"> Last date for applying Re-evaluation is 15/04/2023, Application are accepted through Online Mode Only</h2>
      <h4  align="center">This information is provided to the candidate on his/her online request and is only a prototype list.<br>
      If any discrepancies in the marks you may be brought to the notice
 of Controller of Examinations University Arts & Science College - Subedari-Warangal</h4>

 <!--SGPA calculated for passed candidates only-->
 
</div>

    <h4  align="left" bgcolor="#33b5e5"width="30%"><font color="#FFFFFF">&nbsp;&nbsp;© Copyrights Reserved.2018 all rights reserved @ SYS Technology</h4>   

</table>
<center>
    
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({
          google_ad_client: "ca-pub-5755477602321907",
          enable_page_level_ads: true
     });
</script>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <ins class="adsbygoogle"
     style="display:block"
     data-ad-format="fluid"
     data-ad-layout-key="-ef+6k-30-ac+ty"
     data-ad-client="ca-pub-5755477602321907"
     data-ad-slot="7146133093"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>

</center>
</body>
</html>

 