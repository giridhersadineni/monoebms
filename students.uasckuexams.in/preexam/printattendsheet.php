<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
<style>
    *{
        box-sizing:border-box;
    }
    table{
        border:solid 2px black;
        border-collapse:collapse;
        width:100%;
    }
    tr,td {
        border:solid 1px black;
    }
    
</style>
</head>
<body>


<?php
include "config.php";
$paper=$_GET['paper'];
$examid=$_GET['examid'];
function getsubjectname($subcode)
{
    include "config.php";
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    $getsubname = "select PAPERNAME from PAPERTITLES where PAPERCODE='" . $subcode . "'";
    $res = $conn->query($getsubname);
    $res = mysqli_fetch_assoc($res);
    return $res["PAPERNAME"];
}
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $egroupquery = 'select distinct(GROUPCODE),COURSE,SPECIALIZATION,MEDIUM from courses WHERE MEDIUM="EM" ORDER BY COURSE';
    $egroups = $conn->query($egroupquery);
    $tgroupquery = 'select distinct(GROUPCODE),COURSE,SPECIALIZATION,MEDIUM from courses WHERE MEDIUM="TM"  ORDER BY MEDIUM';
    $tgroups = $conn->query($tgroupquery);

}
?>

           <div style="diplay:flex;flex-direction:row;justify-content:space-around">
               <div style="display:flexbox;width:fit-content;">
                 <h3 style="text-align:center">University Arts & Science College (Autonomous)</h1>
                 <h3 style="text-align:center">ATTENDANCE SHEET </h2>
                 
               </div>
             <div>
                Name of the Examination:<br>
                Title of the Paper: <?php echo getsubjectname($_GET['paper']); ?><br>
                Paper Code:<?php echo $_GET['paper']; ?> 
                 <h5 style="text-align:right;padding-right:180px;">
                Paper:<br>
                Date of Exam:
                </h5>
               </div>
           </div>
               

<?php
$sql = 'SELECT DISTINCT(HALLTICKET),enrolledview.* from enrolledview  WHERE STATUS="RUNNING" AND FEEPAID=1 and (S1="' . $paper . '" OR S2="' . $paper . '" OR S3="' . $paper . '" OR S4="' . $paper . '" OR S5="' . $paper . '" OR S6="' . $paper . '" OR S7="' . $paper . '" OR S8="' . $paper . '" OR S9="' . $paper . '" OR S10="' . $paper . '" OR E1="' . $paper . '" OR E2="' . $paper . '" OR E3="' . $paper . '" OR E4="' . $paper . '" OR E5="' . $paper . '") ORDER BY HALLTICKET,`group` ASC';

// $sql = 'SELECT DISTINCT(HALLTICKET),sname,aadhar,`group` from enrolledview WHERE FEEPAID=1 AND STATUS="RUNNING" AND (S1="' . $_GET["paper"] . '" OR S2="' . $_GET["paper"] . '" OR S3="' . $_GET["paper"] . '" OR S4="' . $_GET["paper"] . '" OR S5="' . $_GET["paper"] . '" OR S6="' . $_GET["paper"] . '" OR S7="' . $_GET["paper"] . '" OR S8="' . $_GET["paper"] . '" OR S9="' . $_GET["paper"] . '" OR S10="' . $_GET["paper"] . '" OR E1="' . $_GET["paper"] . '" OR E2="' . $_GET["paper"] . '" OR E3="' . $_GET["paper"] . '" OR E4="' . $_GET["paper"] . '") ORDER BY HALLTICKET,`group` ASC';
//$sql = "SELECT DISTINCT(HALLTICKET),sname,aadhar,`group` from enrolledview WHERE FEEPAID=1 AND STATUS='RUNNING' and '$paper' in (S1,S2,S3,S4,S4,S6,S7,S8,S9,S10,E1,E2,E3,E4) ORDER BY HALLTICKET";
// echo $sql . "<br>";
$tstudents = $conn->query($sql);
$tmstrength = $tstudents->num_rows;
?>
<table>
    <thead>
        <th>HALLTICKET</th>
        <th>STUDENT NAME</th>
        <th>PHOTO</th>
        <th>SIGNATURE</th>
        <th>BOOKLET NUMBER</th>
        <th>SIGNATURE</th>
    </thead>
<tbody>
<?php while($student=mysqli_fetch_assoc($tstudents)): ?>
    <?php  // echo implode($student,""); ?>
   <tr>
       
       <td><?=$student['HALLTICKET'];?></td>
       <td><?=$student['sname'];?></td>
       <td><img src="http://students.uasckuexams.in/upload/images/<?=$student['aadhar'];?>.jpg" width="60px" height="auto"></td>
       <td><img src="http://students.uasckuexams.in/upload/signatures/<?=$student['aadhar'];?>.jpg" width="60px" height="auto"></td>
       <td></td>
       <td></td>
   </tr>
<?php endwhile;?>
</tbody>
<tfoot>
    <tr>
       <td colspan="3">Room Number:
           <br>Name of the Invigilator(s):</td>
       <td colspan="3">
          <br><br> Signature of Invigilator(s)</td>
    </tr>
</tfoot>
</table>
<h1><?=$tstudents->num_rows?></h1>
</body>
</html>




