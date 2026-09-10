<?php 
error_reporting(E_ALL);
require_once("library/qrcode.php");
require_once("library/SpearmanCorrelation.php");


function consolidatereport($papers){
     
     $marks_sec=0;
     $totalmarks=0;
     $credit_total=0;
     $gpc_total=0;
     $gpcc_total=0;
     $percentages=array();
     $cgpa_percentages=array();
     $failed_count=0;
     $failedpapers=array();
     $result=array();
     $result['RESULT']='P';
     foreach($papers as $paper){
         if($paper['GRADE']=='EX'){
            continue;
         }
         if($paper['RESULT']=='AB'){
            $failed_count++;
             $result['RESULT']='R';
            array_push($failedpapers,$paper);
         }
         
         if($paper['RESULT']=='F'){
            $failed_count++;
             $result['RESULT']='R';
            array_push($failedpapers,$paper);
         }
         $gpc_total+=$paper['GPC'];
         $gpcc_total+=$paper['GPCC'];
         $credit_total+=$paper['CREDITS'];
         $marks_sec+=$paper['MARKS'];
         $totalmarks+=$paper['TOTALMARKS'];
     }

     $result['MARKS']=$marks_sec;
     $result['TOTAL']=$totalmarks;
     $result['gpc_total']=$gpc_total;
     $result['gpcc_total']=$gpc_total;
     $result['credits']=$credit_total;
     $result['SGPA']=$gpc_total/$credit_total;
     $result['OGPA']=$gpcc_total/$credit_total;
     $per=$marks_sec/$totalmarks*100;
     $cper=$result['OGPA']*10;
     $result['failed_count']=$failed_count;
     $result['PERCENTAGE']=$per;
     $result['cf']=$per-$cper;
     if($failed_count==1){
        $testpaper=$failedpapers[0];
        if($testpaper)
        $result['FLOATATION']=1;
     }
     else{
            $result['FLOATATION']=0;
     }
     return $result; 
}



function print_formatted($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function updatePaperByTable($paper,$table){
    global $servername,$dbuser,$dbpwd;
    $conn = mysqli_connect($servername, $dbuser, $dbpwd,$database);
    if ($conn->connect_error) {
        die("connection failed:" . mysqli_connect_error());
    } else {
    $query="update ".$table." set ";
    $query.= "EXT='".$paper['EXT']. "' ";
    $query.= ",ETOTAL='".$paper['ETOTAL']. "' ";
    $query.= ",GRADE='".$paper['GRADE']. "' ";
    $query.= ",RESULT='".$paper['RESULT']. "' ";
    $query.= ",MARKS=".$paper['MARKS']. " ";
    $query.= ",TOTALMARKS=".$paper['TOTALMARKS']. " ";
    $query.= ",GPC=".$paper['GPC']. " ";
    $query.= ",GPCC=".$paper['GPCC']. " ";
    $query.= ",GPV=".$paper['GPV']. " ";
    $query.= ",GPVV=".$paper['GPVV']. " ";
    $query.= ",PERCENTAGE=".$paper['PERCENTAGE']. " ";
    $query.=",STATUS='".$paper['STATUS']. "'";
    $query.= " WHERE RHID=".$paper['RHID']. " ";
    echo $query;
    $data=$conn->query($query);
    var_dump($data);
    return $data;   
 }
}

$qr = new QRCode();
$qr->setErrorCorrectLevel(QR_ERROR_CORRECT_LEVEL_L);
$qr->setTypeNumber(4);
$qr->addData("uascku.ac.in/p.php?i=".$_GET['hallticket']);
$qr->make();

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
                    if($credit_total){
                    $gpa=$gpc_total/$credit_total;
                    }
                    $gpa=round($gpa,2);
                    //echo $gpa."<br>";
                    return $gpa;
            }
    
            function get_division($gpa,$cf){
                $percentage=($gpa*10)-$cf;
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
                else
                {
                    echo "PASS";
                }
                
                //return $percentage;
            }
            
        
            
                include 'config.php';
                $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
                //check connection
                if ($conn->connect_error) {
                    die("connection failed:" . mysqli_connect_error());
                } else 
                {
                    $getsem1 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND SEMESTER = 1 ORDER BY PAPERCODE";
                    $getsem2 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND SEMESTER = 2 ORDER BY PAPERCODE";
                    $getsem3 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND SEMESTER = 3 ORDER BY PAPERCODE";
                    $getsem4 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND SEMESTER = 4 ORDER BY PAPERCODE";
                    $getsem5 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND SEMESTER = 5 ORDER BY PAPERCODE";
                    $getsem6 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND SEMESTER = 6 ORDER BY PAPERCODE";
                    
                    
                    
                        //PART 1
                        $getpart1 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND PART=1 ORDER BY PAPERCODE";
                            $part1result=$conn->query($getpart1);
                            $part1papers=array();
                            while($sub=mysqli_fetch_assoc($part1result)){
                            array_push($part1papers,$sub);
                            };
                            $part1=consolidatereport($part1papers);
                            //print_r($part1);
                        
                        // PART 2
                            $getpart2 = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND PART=2 ORDER BY PAPERCODE";
                            $part2result=$conn->query($getpart2);
                            $part2papers=array();
                            while($sub=mysqli_fetch_assoc($part2result)){
                            array_push($part2papers,$sub);
                            };
                            $part2=consolidatereport($part2papers);
                        
                        // overall
                            $getoverall = "select * from  RESULTS where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' ORDER BY PAPERCODE";
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
                $sgpa['part1']=calcsgpa($part1papers);
                $sgpa['part2']=calcsgpa($part2papers);
                $sgpa['overall']=calcsgpa($overallpapers);
                
                // $sem1report = consolidate_report($sem1subs);
                // var_dump($sem1report);
                //$sem2report = consolidatereport($sem2subs);
                //var_dump($sem2report);
                // $sem3report = consolidate_report($sem3subs);
                // var_dump($sem3report);             
                // $sem4report = consolidate_report($sem4subs);
                // var_dump($sem4report);
                // $sem5report = consolidate_report($sem5subs);
                // var_dump($sem5report);
                // $sem6report = consolidate_report($sem6subs);
                // var_dump($sem6report);
                // $part1report = consolidate_report($part1papers);
                // var_dump($part1report);             
                // $part2report = consolidate_report($part2report);
                // var_dump($part2report);
                // $overallreport = consolidate_report($overallpapers);
                // var_dump($overallreport);
                
                
                $studentres=$conn->query("select * from students where haltckt=".$_GET['hallticket']);
                $student=mysqli_fetch_assoc($studentres);
            
        
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

td,th,thead{
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
}

</style>

</head>    

<body style='min-width:19.5cm;max-width:19.5cm;margin-left:auto;margin-right:auto;padding:1px;'>
<?php 

?>
<div style="display:flex;flex-direction:column;" >
    <div style="display:flex;flex-direction:row-reverse;">
         <div style="float:right;height:60px:width:60px;" class="qrcode">
         <?php 
         $qr->printHTML();
         ?>
         <br>
         <br>
         </div>
    </div>
    <div style="display:flex;flex-direction:row;">
            <div style="width:400px;padding-left:4cm">
            
            <strong>
            
            <?php echo $student['MEMONO']; ?><br>                 
            <?php echo $student['sname']; ?><br>                 
            <?php echo $student['mname']; ?><br>
            <?php echo $student['fname']; ?><br>
            <?php echo $student['haltckt']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$student['ADMYEAR']?>  <br>
            </strong>
            
        </div>
        <div style="display:flex;flex-direction:row;">
            <div width="96px">
                <img src="http://uascku.ac.in/ebms/students/upload/images/<?php echo $student['aadhar'];?>.jpg"   width="96px" height="128x" >
            </div>
            <div style="padding-left:8em;">
                <br>
                <p>APRIL - MAY 2019</p>
                <br>
                <p>
                <?php
                $courses=array();
                $courses['BCOM']="BACHELOR OF COMMERCE";
                $courses['BSC']="BACHELOR OF SCIENCE";
                $courses['BA']="BACHELOR OF ARS";
                echo $courses[$student['course']];
                ?>
                </p>
                
            </div>
        </div>
    </div>
    
    
   
</div>

    
<br>
<table class="holder-table">
         <thead style="border-bottom:solid 1px black">
                  <tr>
                <th class="verticalTableHeader" style="width:0.3in"><p>PART</p></th>
                <th style="width:5cm"  ><p style="text-align:center">SUBJECT NAME</p></th>
                <th class="verticalTableHeader" style="width:0.5cm">CREDITS</th>
                 <th class="verticalTableHeader" style="width:0.5cm"><p>GRADE</p></th>
                <th style="width:2cm"><p  style="text-align:center">YEAR OF <br> PASSING</p></th>
                <th class="verticalTableHeader" style="width:0.5cm"><p>PART</p></th>
                <th style="width:5cm"  ><p style="text-align:center">SUBJECT NAME</p></th>
                <th class="verticalTableHeader" style="width:0.5cm">CREDITS</th>
                 <th class="verticalTableHeader" style="width:0.5cm"><p>GRADE</p></th>
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
                            echo "<strong>I Year I Semester SGPA :".round($sgpa['sem1'],2)."</strong><br>";
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
                            <?php
                            foreach($sem1subs as $paper){
                               echo "<p>".$paper['GRADE']."</p>";
                            }
                          ?>
                      </td>
                      <!--Year of Passing-->
                      <td style="text-align:center;">
                          
                          <?php
                            foreach($sem1subs as $paper){
                                echo "<p>".$paper['MYEAR'] . $paper['RHID'] . "</p>";
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
                            echo "<strong>I Year II Semester SGPA :".round($sgpa['sem2'],2)."</strong><br>";
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
                                echo "<p>".$paper['MYEAR'] . $paper['RHID'] . "</p>";
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
                            echo "<strong>II Year I Semester SGPA :".round($sgpa['sem3'],2)."</strong><br>";
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
                                echo "<p>".$paper['MYEAR'] . $paper['RHID'] . "</p>";
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
                            echo "<strong>II Year II Semester SGPA :".round($sgpa['sem4'],2)."</strong><br>";
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
                                echo "<p>".$paper['MYEAR'] . $paper['RHID'] . "</p>";
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
                            echo "<strong>III Year I Semester SGPA :".round($sgpa['sem5'],2)."</strong><br>";
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
                                echo "<p>".$paper['MYEAR'] . $paper['RHID'] . "</p>";
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
                            echo "<strong>I Year I Semester SGPA :".round($sgpa['sem6'],2)."</strong><br>";
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
                                echo "<p>".$paper['MYEAR'] . $paper['RHID'] . "</p>";
                            }
                          ?>
                      </td>
                    
                    
                    
                    
                    
                </tr>
                
                          
                  
                  
                  
                  
                  
        </tbody>
</table>
<br>
<table style="text-align:center;width:100%">
    <thead>
    <tr>
        <th colspan="2">TOTAL MARKS</th>
        <th>CREDITS</th>
        <th>CGPA</th>
        <th>C.F.</th>
        <th>DIVISION</th>
        <th>YEAR OF PASSING</th>
    </tr>
    </thead>
    <tbody>
        <!--PART1-->
        <tr>
            <td>PART-1</td>
            <td><?php echo $part1['MARKS']."/".$part1['TOTAL']; ?></td>
            <td><?php echo $part1['credits']; ?></td>
            <td><?php echo round($part1['SGPA'],2); ?></td>
            <td><?php echo round($part1['cf'],2); ?></td>
            <td><?php echo get_division($part1['SGPA'],$part1['cf']);?></td>
            <td><? echo $_GET['p1yop'];?></td>
        </tr>
        <tr>
            <td>PART-2</td>
            <td><?php echo $part2['MARKS']."/".$part2['TOTAL']; ?></td>
            <td><?php echo $part2['credits']; ?></td>
            <td><?php echo round($part2['SGPA'],2); ?></td>
            <td><?php echo round($part2['cf'],2); ?></td>
            <td><?php echo get_division($part2['SGPA'],$par2['cf']);?></td>
            <td><? echo $_GET['p2yop'];?></td>
        </tr>
        <tr>
            <td>Overall</td>
            <td><?php echo $overall['MARKS']."/".$overall['TOTAL']; ?></td>
            <td><?php echo $overall['credits']; ?></td>
            <td><?php echo round($overall['SGPA'],2); ?></td>
            <td><?php echo round($overall['cf'],2); ?></td></td>
            <td><?php echo get_division($overall['SGPA'],$overall['cf']);?></td>
            <td><? echo $_GET['oyop'];?></td>
        </tr>
        
    </tbody>
</table>
</body>
</html>