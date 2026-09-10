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
    
.flexbox{
display:flex;
flex-wrap:wrap;
width:100%;
}
.spectitle{
border:solid 2px black;
}
.htholder{
    border:solid 1px black;
    width:20%;
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
<table style="width:100%;">
    <tr>
        <td colspan="2"><h1>D-FORM</h1></td>
    </tr>
    <tr>
       <td colspan="2">
           <div style="diplay:flex;flex-direction:row;justify-content:space-around">
               <div style="display:flexbox;width:fit-content;">
                   <img src="images/arts.png">
               </div>
               <div style="display:flexbox">
                Name of the Examination:<br>
                Title of the Paper: <?php echo getsubjectname($_GET['paper']); ?><br>
                Paper Code:<?php echo $_GET['paper']; ?><br> 
               </div>
           </div>
       </td>
    </tr>
    
    <tr>
        <td ><h2>
            
        </h2>
        </td>
        <td>
            <h2 style="text-align:right;padding-right:180px;">
                Paper:<br>
                Date of Exam:
        </h2>

        </td>
    </tr>
    
    <tbody>

<?php
    //$sql = 'SELECT DISTINCT(haltckt) from enrolledview WHERE FEEPAID=1 AND (S1=".$paper." OR S2=".$paper." OR S3=".$paper." OR S4=".$paper." OR S5=".$paper." OR S6=".$paper." OR S7=".$paper." OR S8=".$paper." OR S9=".$paper." OR S10=".$paper." OR E1=".$paper." OR E2=".$paper." OR E3=".$paper." OR E4=".$paper.") ORDER BY HALLTICKET,`group` ASC';
 //   $sql = "SELECT HALLTICKET from enrolledview WHERE FEEPAID=1 AND STATUS='RUNNING' AND '.$paper.' in (S1,S2,S3,S4,S4,S6,S7,S8,S9,S10,E1,E2,E3,E4) ORDER BY HALLTICKET" ;
    $sql = "SELECT DISTINCT(HALLTICKET),`group`,ID,EXAMID,STID,STATUS from enrolledview WHERE STATUS='RUNNING' AND FEEPAID=1 and (S1='".$paper."' or S2='".$paper."' OR S3='".$paper."' OR S4='".$paper."' OR S5='".$paper."' OR S6='".$paper."' OR S7='".$paper."' OR S8='".$paper."' OR S9='".$paper."' OR S10='".$paper."' OR E1='".$paper."' OR E2='".$paper."' OR E3='".$paper."' OR E4='".$paper."' OR E5='".$paper."') ORDER BY HALLTICKET,`group` ASC";
 
    // echo $sql . "<br>";
    $tstudents = $conn->query($sql);
    $tmstrength = $tstudents->num_rows;
    echo "<tr><td colspan=2>";
    echo "<div class='spectitle' ><h2 style='text-align:center;'></h2></div>";
    echo '<div class="flexbox">';
    while ($tstudent = mysqli_fetch_assoc($tstudents)) {
        echo "<div class='htholder'><h1 style='text-align:center;'>" . $tstudent['HALLTICKET'] . "</h1></div>";
    }
    echo '</td></tr></div>';
    echo '<h1>'.$tstudents->num_rows.'</h1>'
?>
<tbody>
</table>
</body>
</html>




