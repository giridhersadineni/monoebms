<?php 
// error_reporting(ALL);
require_once("library/qrcode.php");
require_once("library/SpearmanCorrelation.php");
$qr = new QRCode();
$qr->setErrorCorrectLevel(QR_ERROR_CORRECT_LEVEL_L);
$qr->setTypeNumber(4);
$qr->addData("uascku.ac.in/p.php?i=".$_GET['id']);
$qr->make();
include 'config.php';
$ID=$_GET['id'];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $getgrades="SELECT * FROM grades where ID='$ID'";
    $graderesult=$conn->query($getgrades);
    $grades=mysqli_fetch_assoc($graderesult);
}


//print_r($grades);

$MEMO_NO=$grades['MEMO_NO'];
$P1_YOP=$grades['P1_YOP'];  
$P2_YOP=$grades['P2_YOP'];
$ALL_YOP=$grades['ALL_YOP'];
$S1GPA=$grades['S1GPA'];
$S2GPA=$grades['S2GPA'];
$S3GPA=$grades['S3GPA'];
$S4GPA=$grades['S4GPA'];
$S5GPA=$grades['S5GPA'];
$S6GPA=$grades['S6GPA'];
$ADM_YEAR=$grades['ADM_YEAR'];
$HALLTICKET=$grades['HALLTICKET'];


// $MEMO_NO=$_GET['MEMO_NO'];
// $P1_YOP=$_GET['P1_YOP'];  
// $P2_YOP=$_GET['P2_YOP'];
// $ALL_YOP=$_GET['ALL_YOP'];
// $S1GPA=$_GET['S1GPA'];
// $S2GPA=$_GET['S2GPA'];
// $S3GPA=$_GET['S3GPA'];
// $S4GPA=$_GET['S4GPA'];
// $S5GPA=$_GET['S5GPA'];
// $S6GPA=$_GET['S6GPA'];
// $ADM_YEAR=$_GET['ADM_YEAR'];


// $date = new DateTime();
// echo $date->format('Y-m-d') . "\n";

            function calcsgpa($papers){
                $credit_total=0;
                $gpc_total=0;
                foreach($papers as $paper){
                    if($paper['GRADE']=='EX'){
                        continue;
                    }
                    
                    $gpc_total+=$paper['GPC'];
                    $credit_total+=$paper['CREDITS'];
                }
                
                $gpa=$gpc_total/$credit_total;
                //echo $gpa."<br>";
                
                return $gpa;
            }
            
            function get_division($gpa,$cf){
                $percentage=($gpa*10) + $cf;
                $gpa=round($gpa,2);
                if($gpa>=7){
                    echo "FIRST DIVISION WITH DISTINCTION";
                }
                else if($gpa>=6){
                    echo "FIRST DIVISION";
                }
                else if($gpa>=5)
                {
                    echo "SECOND DIVISION";
                }
                else if($gpa>=4)
                {
                    echo "PASS";
                }
                else{
                    echo "<script>alert('The Student did not meet minimum credits');</script>";
                }
                
                //return $percentage;
            }
            
            function consolidatereport($papers){
                $marks_sec=0;
                $totalmarks=0;
                $credit_total=0;
                $gpc_total=0;
                $gpcc_total=0;
                $percentages=array();
                $cgpa_percentages=array();
                foreach($papers as $paper){
                    if($paper['GRADE']=='EX'){
                        continue;
                    }
                    $gpc_total+=$paper['GPC'];
                    $credit_total+=$paper['CREDITS'];
                    $marks_sec+=$paper['MARKS'];
                    $totalmarks+=$paper['TOTALMARKS'];
                    $gpcc_total+=$paper['GPCC'];
                }
                  
                
                $result=array();
                $result['marks']=$marks_sec;
                $result['total']=$totalmarks;
                $result['gpc_total']=$gpc_total;
                $result['credits']=$credit_total;
                $result['cgpa']=$gpc_total/$credit_total;
                $result['OGPA']=$gpcc_total/$credit_total;
                $per=($marks_sec/$totalmarks)*100;
                $cper=$result['OGPA']*10;
                $result['cf'] = $result['cgpa'] - $result['OGPA'];

                // echo "Consolidated Report<br><pre>";
                // print_r($result);
                // echo "</pre>";
                
                return $result; 
                
            }
            
             
            
            $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
            //check connection
            if ($conn->connect_error) {
                die("connection failed:" . mysqli_connect_error());
            } else 
            {
                    $getsem1 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$HALLTICKET."' AND SEMESTER = 1 ORDER BY PART,PAPERCODE";
                    $getsem2 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$HALLTICKET."' AND SEMESTER = 2 ORDER BY PART,PAPERCODE";
                    $getsem3 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$HALLTICKET."' AND SEMESTER = 3 ORDER BY PART,PAPERCODE";
                    $getsem4 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$HALLTICKET."' AND SEMESTER = 4 ORDER BY PART,PAPERCODE";
                    $getsem5 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$HALLTICKET."' AND SEMESTER = 5 ORDER BY PART,PAPERCODE";
                    $getsem6 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$HALLTICKET."' AND SEMESTER = 6 ORDER BY PART,PAPERCODE";
                
                
                
                    //PART 1
                    $getpart1 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$HALLTICKET."' AND PART=1 ORDER BY PAPERCODE";
                    $part1result=$conn->query($getpart1);
                    $part1papers=array();
                    while($sub=mysqli_fetch_assoc($part1result)){
                    array_push($part1papers,$sub);
                    };
                    $part1=consolidatereport($part1papers);
                    // print_r($part1);
                    
                    // PART 2
                    $getpart2 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$HALLTICKET."' AND PART=2 ORDER BY PAPERCODE";
                    $part2result=$conn->query($getpart2);
                    $part2papers=array();
                    while($sub=mysqli_fetch_assoc($part2result)){
                    array_push($part2papers,$sub);
                    };
                    $part2=consolidatereport($part2papers);
                    // print_r($part2);
                    
                    // overall
                    $getoverall = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$HALLTICKET."' ORDER BY PAPERCODE";
                    $overallresult=$conn->query($getoverall);
                    $overallpapers=array();
                    while($sub=mysqli_fetch_assoc($overallresult)){
                    array_push($overallpapers,$sub);
                    };
                    $overall=consolidatereport($overallpapers);
                    
                    
          
          
                        $s1subs=$conn->query($getsem1);
                        $sem1subs=array();
                        while($sub=mysqli_fetch_assoc($s1subs)){
                        array_push($sem1subs,$sub);
                        };
                       
                        $s2subs=$conn->query($getsem2);
                        $sem2subs=array();
                        while($sub=mysqli_fetch_assoc($s2subs)){
                        array_push($sem2subs,$sub);
                        };
                        
                        
                        $s3subs=$conn->query($getsem3);
                        $sem3subs=array();
                        while($sub=mysqli_fetch_assoc($s3subs)){
                        array_push($sem3subs,$sub);
                        };
                        
                        
                        $s4subs=$conn->query($getsem4);
                        $sem4subs=array();
                        while($sub=mysqli_fetch_assoc($s4subs)){
                        array_push($sem4subs,$sub);
                        };
                        
                        
                        $s5subs=$conn->query($getsem5);
                        $sem5subs=array();
                        while($sub=mysqli_fetch_assoc($s5subs)){
                        array_push($sem5subs,$sub);
                        };
                        
                        
                        $s6subs=$conn->query($getsem6);
                        $sem6subs=array();
                        while($sub=mysqli_fetch_assoc($s6subs)){
                        array_push($sem6subs,$sub);
                        };
                        
                
                }
                
                
                
                
                
                $sgpa['sem1']=calcsgpa($sem1subs);
                $sgpa['sem2']=calcsgpa($sem2subs);
                $sgpa['sem3']=calcsgpa($sem3subs);
                $sgpa['sem4']=calcsgpa($sem4subs);
                $sgpa['sem5']=calcsgpa($sem5subs);
                $sgpa['sem6']=calcsgpa($sem6subs);
                
            $studentres=$conn->query("select * from students where haltckt=".$HALLTICKET);
            $student=mysqli_fetch_assoc($studentres);
            
            $MemoDetails = mysqli_fetch_assoc($conn->query("select * from grades where ID =".$_GET['id'].""));
    ?>
       

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- Favicon icon -->    
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<style>
*{
    font-family:arial;
    
}
table{
    text-transform:uppercase;
}
.holder-table{
    border-collapse:collapse;
    margin-left:auto;
    margin-right:auto;
     width:100%;
}
.holder-table tbody tr td{
    padding-top:0.5em;
    vertical-align:top;
    padding-bottom:0.5em;
}
.holder-table tbody tr td p{
     marin:0px;
    
}

table {
font-size:8px;
border-collapse: collapse;
border:solid 1px black;
}

.holder-table td,.holder-table th,.holder-table thead{
text-align:left;
border:solid 1px black;
}


.verticalTableHeader {
    height:2cm;
    text-align:center;
    word-wrap:break-word;
    g-origin:0% 0%;
    -webkit-transform: rotate(-90deg);
    -moz-transform: rotate(-90deg);
    -ms-transform: rotate(-90deg);
    -o-transform: rotate(-90deg);
    transform: rotate(-90deg);
    
}
.verticalTableHeader p {
    margin:0 -100% ;
    display:inline-block;
}

.verticalTableHeader p:before{
    content:'';
    width:0;
    /*padding-top:100%;/* takes width as reference, + 10% for faking some extra padding */
    display:inline-block;
    vertical-align:middle;
}

td p{
    margin:0px;
    margin:0 0 4pt 0;
    font-size:9pt;
}

.gpastable tr th{
    font-size:10pt;
}
.gpastable tr td{
    font-size:10pt;
    font-style:bold;
}
.infotable tr td p{
    font-size:10pt;
}



</style>

</head>    

<body style='min-width:19.5cm;max-width:19.5cm;margin-left:auto;margin-right:auto;padding:1px;'>
             <p class="text-align:right;">
             <div style="float:right;padding-right:10px;" class="qrcode">
                <?php 
                $qr->printHTML();
                ?>
             </div>
             </p>
             <br>
             <br>
<table style="width:100%;border-collapse:none;border:solid 0px" class="infotable" >
    <tr style="border:none;min-width:11cm;max-width:11cm;display:block">
        
        
        <tr>
            <td>
            <div style="float:left">
                <p style="font-weight:bold;font-size:12pt;"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $MEMO_NO; ?></p>    
                <?php// print_r($student); ?>
                <p style="font-weight:bold;font-size:12pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $student['sname']; ?></p>                 
                <p style="font-weight:bold;font-size:12pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $student['mname']; ?></p>
                <p style="font-weight:bold;font-size:12pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $student['fname']; ?></p>
                <p style="font-weight:bold;font-size:12pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $MemoDetails['HALLTICKET'];?></p>
                <p style="font-weight:bold;margin-top:-30px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $ADM_YEAR?></p>
            </div>
            <div style="position:absolute:width:fit-content;margin-top:35px;margin-left:520px;">
                <img src="https://students.uasckuexams.in/upload/images/<?php  echo $student['aadhar']; ?>.jpg"   width="85px" height="110px" style="padding-top:5px">
                <!--<img src="/ebmsdown/students/upload/images/<?php echo $student['aadhar'];?>.jpg"   width="85px" height="110px" style="padding-top:5px">-->
            </div>
            
        </td>
            <td "padding-left:40px;">    
                <br><br><br><br>
                <p style="font-weight:bold;text-align:right">
                <?php echo $ALL_YOP;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                <br>
                <br>
                <br>
                <br>
                <p style="margin-left:40px;font-weight:bold;text-align:right">
                <?php
                
                    $courses=array();
                    $courses['BCOM']="BACHELOR OF COMMERCE";
                    $courses['BSC']="BACHELOR OF SCIENCE";
                    $courses['BA']="BACHELOR OF ARTS";
                    $courses['BVOC']="BACHELOR OF VOCATIONAL";
                    
                    $groups=array();
                    $groups["CA"]= "(COMPUTER APPLICATIONS)";
                    $groups["HSO"]= "(HONORS IN SOCIOLOGY)";
                    $groups["BVOCFM"]= "(FINANCIAL SERVICES MANAGEMENT <br> IN BANKING & INSURANCE)";
                    $groups["BVOCSD"]= "(SOFTWARE DEVELOPMENT)";
                
                ?>
                <?=$courses[$student['course']]?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                <?=$groups[$student['group']]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                
                </p>
            
        </td>
        
    </tr>
</table>



                
                
           

    
<br>
<table class="holder-table">
         <thead style="border-bottom:solid 1px black">
                   <tr>
                <th class="verticalTableHeader" style="width:0.3in;text-align:center"><p>PART</p></th>
                <th style="width:5cm"  ><p style="text-align:center">SUBJECT NAME</p></th>
                <th class="verticalTableHeader" style="width:0.5cm;text-align:center">CREDITS</th>
                 <th class="verticalTableHeader" style="width:0.5cm;text-align:center"><p>GRADE</p></th>
                <th style="width:2cm"><p  style="text-align:center">YEAR OF <br> PASSING</p></th>
                <th class="verticalTableHeader" style="width:0.5cm;text-align:center"><p>PART</p></th>
                <th style="width:5cm"  ><p style="text-align:center">SUBJECT NAME</p></th>
                <th class="verticalTableHeader" style="width:0.5cm;text-align:center">CREDITS</th>
                 <th class="verticalTableHeader" style="width:0.5cm;text-align:center"><p>GRADE</p></th>
                <th style="width:2cm"><p  style="text-align:center">YEAR OF <br> PASSING</p></th>
                </tr>
                
              </thead>
              <tbody>
                  
                  <tr>
                      <!--Sem1-->
                      <!--Part-->
                      <td style="text-align:center;">
                          
                          <?php
                            foreach($sem1subs as $paper){
                                echo "<p>".$paper['PART']."</p>";
                            }
                          ?>
                          
                      </td>
                      <!--Subject-->
                      <td> 
                        <?php
                            
                            foreach($sem1subs as $paper){
                                echo "<p>".$paper['PAPERNAME']."</p>";
                            }
                            echo "<p><strong>I Year I Semester SGPA :".$S1GPA."</strong><p>";
                          ?>
                      </td>
                      <!--Credits-->
                      <td style="text-align:center;"> 
                        <?php
                            foreach($sem1subs as $paper){
                                echo "<p>".$paper['CREDITS']."</p>";
                            }
                          ?>
                      </td>
                      <!--Grade-->
                      
                      <td style="text-align:center;"> 
                            <?php foreach($sem1subs as $paper){
                               echo "<p>".$paper['GRADE']."</p>";
                            }  ?>
                      </td>
                      <!--Year of Passing-->
                      <td style="text-align:center;">
                          <?php
                            foreach($sem1subs as $paper){
                                echo "<p>".$paper['MYEAR'] ."</p>";
                            }
                          ?>
                      </td>

                      <!--Sem2-->
                       <td style="text-align:center;">
                          
                          <?php
                            foreach($sem2subs as $paper){
                                echo "<p>".$paper['PART']."</p>";
                            }
                          ?>
                          
                      </td>
                      <!--Subject-->
                      <td> 
                        <?php
                            
                            foreach($sem2subs as $paper){
                                echo "<p>".$paper['PAPERNAME']."</p>";
                            }
                            echo "<p><strong>I Year II Semester SGPA :".$S2GPA."</strong><p>";
                          ?>
                      </td>
                      <!--Credits-->
                      <td style="text-align:center;"> 
                        <?php
                            foreach($sem2subs as $paper){
                                echo "<p>".$paper['CREDITS']."</p>";
                            }
                          ?>
                      </td>
                      <!--Grade-->
                      <td style="text-align:center;"> 
                            <?php
                            foreach($sem2subs as $paper){
                               echo "<p>".$paper['GRADE']."</p>";
                            }
                          ?>
                      </td>
                      <!--Year of Passing-->
                      <td style="text-align:center;">
                          
                          <?php
                            foreach($sem2subs as $paper){
                                echo "<p>".$paper['MYEAR'] ."</p>";
                            }
                          ?>
                      </td>
                  </tr>    
               <!--Part-->
                   
                  <!--II YEAR-->
                    
                <tr>
                     <td style="text-align:center;">
                          
                          <?php
                            foreach($sem3subs as $paper){
                                echo "<p>".$paper['PART']."</p>";
                            }
                          ?>
                          
                      </td>
                      <!--Subject-->
                      <td> 
                        <?php
                            
                            foreach($sem3subs as $paper){
                                echo "<p>".$paper['PAPERNAME']."</p>";
                            }
                            echo "<p><strong>II Year I Semester SGPA :".$S3GPA."</strong><p>";
                          ?>
                      </td>
                      <!--Credits-->
                      <td style="text-align:center;"> 
                        <?php
                            foreach($sem3subs as $paper){
                                echo "<p>".$paper['CREDITS']."</p>";
                            }
                          ?>
                      </td>
                      <!--Grade-->
                      <td style="text-align:center;"> 
                            <?php
                            foreach($sem3subs as $paper){
                               echo "<p>".$paper['GRADE']."</p>";
                            }
                          ?>
                      </td>
                      <!--Year of Passing-->
                      <td style="text-align:center;">
                          
                          <?php
                            foreach($sem3subs as $paper){
                                echo "<p>".$paper['MYEAR'] ."</p>";
                            }
                          ?>
                      </td>
                      
                      
                       <td style="text-align:center;">
                          
                          <?php
                            foreach($sem4subs as $paper){
                                echo "<p>".$paper['PART']."</p>";
                            }
                          ?>
                          
                      </td>
                      <!--Subject-->
                      <td> 
                        <?php
                            
                            foreach($sem4subs as $paper){
                                echo "<p>".$paper['PAPERNAME']."</p>";
                            }
                            echo "<p><strong>II Year II Semester SGPA :".$S4GPA."</strong><p>";
                          ?>
                      </td>
                      <!--Credits-->
                      <td style="text-align:center;"> 
                        <?php
                            foreach($sem4subs as $paper){
                                echo "<p>".$paper['CREDITS']."</p>";
                            }
                          ?>
                      </td>
                      <!--Grade-->
                      <td style="text-align:center;"> 
                            <?php
                            foreach($sem4subs as $paper){
                               echo "<p>".$paper['GRADE']."</p>";
                            }
                          ?>
                      </td>
                      <!--Year of Passing-->
                      <td style="text-align:center;">
                          
                          <?php
                            foreach($sem4subs as $paper){
                                echo "<p>".$paper['MYEAR'] ."</p>";
                            }
                          ?>
                      </td>
                </tr> 
                
                <tr>
                     <td style="text-align:center;">
                          
                          <?php
                            foreach($sem5subs as $paper){
                                echo "<p>".$paper['PART']."</p>";
                            }
                          ?>
                          
                      </td>
                      <!--Subject-->
                      <td> 
                        <?php
                            
                            foreach($sem5subs as $paper){
                                echo "<p>".$paper['PAPERNAME']."</p>";
                            }
                            echo "<p><strong>III Year I Semester SGPA :".$S5GPA."</strong><p>";
                          ?>
                      </td>
                      <!--Credits-->
                      <td style="text-align:center;"> 
                        <?php
                            foreach($sem5subs as $paper){
                                echo "<p>".$paper['CREDITS']."</p>";
                            }
                          ?>
                      </td>
                      <!--Grade-->
                      <td style="text-align:center;"> 
                            <?php
                            foreach($sem5subs as $paper){
                               echo "<p>".$paper['GRADE']."</p>";
                            }
                          ?>
                      </td>
                      <!--Year of Passing-->
                      <td style="text-align:center;">
                          
                          <?php
                            foreach($sem5subs as $paper){
                                echo "<p>".$paper['MYEAR'] ."</p>";
                            }
                          ?>
                      </td>
                      
                       <td style="text-align:center;">
                          
                          <?php
                            foreach($sem6subs as $paper){
                                echo "<p>".$paper['PART']."</p>";
                            }
                          ?>
                          
                      </td>
                      <!--Subject-->
                      <td> 
                        <?php
                            
                            foreach($sem6subs as $paper){
                                echo "<p>".$paper['PAPERNAME']."</p>";
                            }
                            echo "<p><strong>III Year II Semester SGPA :".$S6GPA."</strong><p>";
                          ?>
                      </td>
                      <!--Credits-->
                      <td style="text-align:center;"> 
                        <?php
                            foreach($sem6subs as $paper){
                                echo "<p>".$paper['CREDITS']."</p>";
                            }
                          ?>
                      </td>
                      <!--Grade-->
                      <td style="text-align:center;"> 
                            <?php
                            foreach($sem6subs as $paper){
                               echo "<p>".$paper['GRADE']."</p>";
                            }
                          ?>
                      </td>
                      <!--Year of Passing-->
                      <td style="text-align:center;">
                          
                          <?php
                            foreach($sem6subs as $paper){
                                echo "<p>".$paper['MYEAR'] ."</p>";
                            }
                          ?>
                      </td>
                    
                    
                    
                    
                    
                </tr>
                
                          
                  
                  
                  
                  
                  
        </tbody>
</table>
<table style="text-align:center;width:100%" class="gpastable" border="1">
    <tr>
        <th colspan="2">TOTAL MARKS</th>
        <th>CREDITS</th>
        <th>CGPA</th>
        <th>C.F.</th>
        <th>DIVISION</th>
        <th>YEAR OF PASSING</th>
    </tr>
<tr >
            <td>PART-1</td>
            <td><?php echo $part1['marks']."/".$part1['total']; ?></td>
            <td><?php echo $part1['credits']; ?></td>
            <td>
                <?php 
                echo $MemoDetails['P1CGPA'];
                ?>
            </td>
            <td>
                <?php 
                echo $MemoDetails['P1CF'];
                ?>
            </td>
            <td>
                <?php 
                echo $MemoDetails['P1DIV'];
                ?>
            </td>
            
            <td><?php echo $P1_YOP;?></td>
</tr>
<tr>
            <td>PART-2</td>
            <td><?php echo $part2['marks']."/".$part2['total']; ?></td>
            <td><?php echo $part2['credits']; ?></td>
            <td>
                <?php 
                echo $MemoDetails['P2CGPA'];
                ?>
            </td>
            <td>
                <?php 
                echo $MemoDetails['P2CF'];
                ?>
            </td>
            <td>
                <?php 
                echo $MemoDetails['P2DIV'];
                ?>
            </td>
            
            <td><?php echo $P2_YOP;?></td>
</tr>
<tr>
            <td>Overall</td>
            <td><?php echo $overall['marks']."/".$overall['total']; ?></td>
            <td><?php echo $overall['credits']; ?></td>
            <td>
                <?php 
                echo $MemoDetails['ALLCGPA'];
                ?>
            </td>
            <td>
                <?php 
                echo $MemoDetails['ALLCF'];
                ?>
            </td>
            <td>
                <?php 
                echo $MemoDetails['FINALDIV'];
                ?>
            </td>
            
          
            <td><?php echo $ALL_YOP;?></td>
</tr>
    <?php if($grades['REMARKS']!=""): ?>
    
    <tr style="border:none;">
    <td colspan="7" style="border:none;"><?php echo $grades['REMARKS'];?></td>
    </tr>
    <?php endif;?>
    <tr style="border:none;" > 
    <td colspan = "7" style="border:none;" >  <p class="text-left">Date of issue: 
    <?php echo date("d-m-Y"); ?> </p> </td>
    </tr>
</tbody> 
</table>
</body>
</html>