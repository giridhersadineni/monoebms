<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Attendance Statement</title>
<style>
h1{
    margin-bottom:0px;
    padding-bottom:opx;

}
h2{
    margin-bottom:0px;
    padding-bottom:opx;
}
.studentstable{
border:solid 1px black;
border-collapse:collapse;
width:100%;
}
.studentstable tr td{
    border:solid 1px black;
}
</style>
</head>
<body>


<?php
include "config.php";
function getsubjectname($subcode)
{
    include "config.php";
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    $getsubname = "select PAPERNAME from allpapers where PAPERCODE='" . $subcode . "'";
    $res = $conn->query($getsubname);
    $res = mysqli_fetch_assoc($res);
    return $res["PAPERNAME"];
}
function getstudentrecord($hallticket)
{
    include "config.php";
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    $getstudent = "select * from students where haltckt='" . $hallticket . "'";
    $res = $conn->query($getstudent);
    $res = mysqli_fetch_assoc($res);
    return $res;
}

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $groupquery = 'select distinct(GROUPCODE),COURSE,SPECIALIZATION from courses';
    $groups = $conn->query($groupquery);

}
?>
<table>


<?php
echo '<table>';
echo '<tr><th><img src="images/arts.png" height="100px;"></th>';
echo '<th><h1>University Arts & Science College</h1><h2>Autonomous</h2><h2>KAKATIYA UNIVERSITY, Subedari, Warangal(T.S.)</h2>';
echo '</h1></th></tr></table>';
    $sql = "SELECT HALLTICKET from enrolledview WHERE FEEPAID=1 AND $paper in (S1,S2,S3,S4,S4,S6,S7,S8,S9,S10,E1,E2,E3,E4)";
    echo $sql;
    $students = $conn->query($sql);
?>
    
    
    
<table width="100%;">
    <tr>    
        <th colspan="2">
        <h3 style="page-break-before:always"><u>Attendance Sheet</u></h3>
        </th>
    </tr>;
    <tr>
        <th>
            <h2 style="text-align:left;">Name of the Examination:
            <br> Title of the Paper: <?=getsubjectname($_GET['paper'])?> <br>
            Group & Course</h2>
        </th>
        <th>
            <h2 style="text-align:right;padding-right:180px;">
            Paper:<?=$_GET['paper'] ?><br>
            Date of Exam:</h2>
        </th>
    </tr>
    </table >
    
<table class="studentstable">
<thead>
    <tr>
    <td>SNO</td>
    <td>Hall Ticket</td>
    <td>Name of the Candidate</td>
    <td>Photo</td>
    <td>Signature Specimen</td>
    <td>Booklet No</td>
    <td>Signature</td>
    </tr>
    </thead>
    <tbody>
<?php
    while ($student = mysqli_fetch_assoc($students)) {
        print_r($student);
        echo ' <tr>';
        echo '<td width="5%">'.$i.'</td>';
        echo '<td width="15%;">' . $student["HALLTICKET"] . '</td>';
        echo '<td width="22%;">' . $student["sname"] . '</td>';
        echo '<td width="6%;"><img src="../students/upload/images/' . $student["aadhar"] . '.jpg" style="height:60px;"></td>';
        echo '<td width="10%;"><img src="../students/upload/signatures/' . $student["aadhar"] . '.jpg" style="height:30px;width:60px;"></td>';
        echo '<td width="20%;"></td>';
        echo '<td width="20%;"></td>';
        echo '</tr>';
       
    }
?>
<tbody>
    <tfoot>
        <th colspan='4'>
            <br>
            <small>Name of the Invigilator</small>
        </th>
        <th colspan='3'>
            <br>
            <small>Signature of the Invigilator</small>
        </th>
    </tfoot>
</table>
</body>
</html>




