<style>
    table {
        margin-left: auto;
        margin-right: auto;
        border-collapse: collapse;
    }

    table tr td {
        border: solid 1px black;
        padding: 1em;
    }
</style>
<?php

include "config.php";
include "projectorm.php";

$PAPERCODE=$_GET['pcode'];

//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, "atech_ebms");
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  examsmaster where EXID=" . $_GET['exam'];
    $exam = $conn->query($sql);
    if ($exam->num_rows > 0) {
        while ($rows = $exam->fetch_assoc()) {
            break;
        }
    }


    $esql = "select * from allpapers where PAPERCODE='" . $_GET['pcode'] . "'";
    $result = $conn->query($esql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            break;
        }
    }


    $allrecords = "select count(HALLTICKET) AS totalpresent from RESULTS_PREMOD where PAPERCODE='" . $_GET['pcode'] . "'";
    $values = get_one($allrecords);
    $present = $values['totalpresent'];


    $get_total_passed = "select count(*) AS totalpass from RESULTS_PREMOD where  RESULT ='P' and PAPERCODE='" . $_GET['pcode'] . "'";
    $pass = get_one($get_total_passed);
    $tpass = $pass['totalpass'];


    $get_total_absent = "select count(*) AS totalabsent from RESULTS_PREMOD where  RESULT ='AB' and PAPERCODE='" . $_GET['pcode'] . "'";
    $absentees = get_one($get_total_absent);
    $abcount = $absentees['totalabsent'];


    $fsql = "select count(*) AS totalfail from RESULTS_PREMOD where  RESULT ='F' and PAPERCODE='" . $_GET['pcode'] . "'";
    $totalfail = $conn->query($fsql);
    $fail = mysqli_fetch_assoc($totalfail);
    $tfail = $fail['totalfail'];



    $get_minmax = "select MIN(EXT) AS MINIMUMMARKS, MAX(EXT) AS MAXIMUMMARKS, AVG(EXT) AS AVERAGE,ETOTAL from RESULTS_PREMOD where  PAPERCODE='" . $_GET['pcode'] . "' and EXT!=0 AND EXT!='AB'";
    $minmax = get_one($get_minmax);
    // print_r($minmax);
    $minmarks = $minmax['MINIMUMMARKS'];
    $maxmarks = $minmax['MAXIMUMMARKS'];
    $average =$minmax['AVERAGE'];
    $passmarks = ($minmax['ETOTAL'] / 100) * 40;
    //var_dump($minmax);
    // echo $passmarks;
    
    $get_internal_failed = "select count(`INT`) as internal_failed from RESULTS_PREMOD  where  PAPERCODE='" . $_GET['pcode'] . "' and EXT>= $passmarks  AND `INT`/ITOTAL<=0.40";
    $row_internal_failed = get_one($internal_failed);
    $failedininternal = $row_internal_failed['internal_failed'];
    
    $pass_if=array();
    $count=1;
    while($count<=5){
        $marks=$passmarks-$count;
        // echo $marks;
        $getfailedcount="select count(MARKS) as failed_count from RESULTS_PREMOD where EXT=$marks and PAPERCODE='$PAPERCODE'";
        // echo $getfailedcount;
        $failedcount=get_one($getfailedcount);
        // var_dump($getfailedcount);
        $pass_if["count".$count]=$failedcount['failed_count'];
        $count++;
    }
    
   
    $one=$pass_if['count1'];    
    $two=$pass_if['count2'];    
    $three=$pass_if['count3'];    
    $four=$pass_if['count4'];    
    $five=$pass_if['count5'];    


}
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

</head>

<style>
    .collegelogo {
        position: absolute;
        top: 48px;
        display: block;
        margin-left: 200px;
    }
</style>

<body>

    <div class="header">
     
        <div>
        <img src="images/arts.png" alt="" width="120px" class="collegelogo">
        </div>
        <div >
        <center>
            <h2> Office of the<br>CONTROLLER OF EXAMINATIONS <br>UNIVERSITY ARTS & SCIENCE COLLEGE (Autonomous)<br>KAKATIYA UNIVERSITY<br> SUBEDARI WARANGAL</h2>
            <h3><?php echo $rows['EXAMNAME'] ?><br> </h3>
           
            <h3>DEPARTMENT OF______________________________</h3>
        </center>
        </div>

    </div>


    <table style="border:solid 1px black;">

        <tr>
            <td style='border:solid 1px black;text-align:center;'>Subject Code</td>
            <td style='text-align:center;border:solid 1px black;'>Title of the Paper</td>
            <td style='text-align:center;border:solid 1px black;'>Registered</td>
            <td style='text-align:center;border:solid 1px black;'>Absent</td>
            <td style='text-align:center;border:solid 1px black;'>Pass</td>
            <td style='text-align:center;border:solid 1px black;'>Failed<br> </td>
            <td style='text-align:center;border:solid 1px black;'> Max.Marks</td>
            <td style='text-align:center;border:solid 1px black;'>Average</td>
            <td style='text-align:center;border:solid 1px black;'>Min.Marks</td>
            <td style='text-align:center;border:solid 1px black;' colspan="5">
                <center>No.Of Student to PASS<br>If__Marks Added </center>
            </td>

        <tr>


        <tr>
            <td style='text-align:center;'><?php echo $_GET['pcode'] ?></t>
            <td><?php echo $row['PAPERNAME'] ?></td>
            <td style='text-align:center;'><?php echo $present ?></td>
            <td style='text-align:center;'><?php echo $abcount ?></td>
            <td style='text-align:center;'><?php echo $tpass ?></td>
            <td style='text-align:center;'><?php echo $tfail ?></td>
            <td style='text-align:center;'><?php echo $maxmarks ?></td>
            <td style='text-align:center;'><?php echo ROUND($average) ?> </td>
            <td style='text-align:center;'><?php echo $minmarks ?></td>
            <td style='text-align:center;'>
                <?php
                echo 'ONE TO PASS:' . '<br><br><b><center>';

                echo $one;

                echo '</b></center>';
                ?>
            </td>
            <td style='text-align:center;'>
                <?php
                echo 'TWO TO PASS:' . '<br><br><b><center>';

                    echo $one + $two;
                echo '</b></center>';
                ?>
            </td>
            <td style='text-align:center;'>
                <?php
                echo 'THREE TO PASS:' . '<br><br><b><center>';

                echo $one + $two + $three;

                echo '</b></center>';
                ?>
            </td>
            <td style='text-align:center;'>

                <?php
                echo 'FOUR TO PASS:' . '<br><br><b><center>';

                echo $one + $two + $three + $four;

                echo '</b></center>';
                ?>
            </td>
            <td style='text-align:center;'>

                <?php
                echo 'FIVE TO PASS:' . '<br><br><b><center>';

                echo $one + $two + $three + $four + $five;

                echo '</b></center>';
                ?>
            </td>




            <!--<td style='text-align:center;'><?php echo ('TOTAL ' . '<br><br><b><center>' . $sum . '</b></center>'); ?> </td>-->
        <tr>
    </table>
    <h4>*As per the KU Letter on Modifiaction in Moderation of Marks(KU Ltr. No. 205/B2/KU/2018 Dt. 03-02-2018 and other rules and regulations of Examinations.</h4>
    <br><br><br><br>
    <!--<p>For Office Use: Exam Id: <b><?php // $_GET['exam'] ?></b> PaperCode : <b><?php // $_GET['pcode'] ?> </b>Marks to Add : ____________ </h3>-->
    <p><?php 
    // echo "Failed Candidates in Internal Exam: ". $failedininternal; 
    ?></p>
    <p><strong>
        Recommendations:
    </strong>
    </p>
    <br><br><br><br><br><br>

    <p style="text-align:center;font-size:1.5em">
        Sign.of HEAD/BOS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Controller of Examinations &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        Principal

    </p>



</body>

</html>

