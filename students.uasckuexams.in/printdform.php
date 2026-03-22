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
<body >


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
<table>
    <tr>
        <td>
           <p style="text-align:center;"> <img src="images/arts.png" height="100px;"></p>
        </td>
        <td><h1 style="text-align:center;">
            University Arts & Science College<br>
            Autonomous<br>
            KAKATIYA UNIVERSITY, Subedari, Warangal(T.S.)
        </h1>
        </td>
    </tr>
    <tr>
        <td colspan="2"><h3 style="text-align:center;"><u>D-FORM</u></h3></td>
    </tr>
    <tr>
        <td ><h2>
            Name of the Examination:<br>
            Title of the Paper: <?php echo getsubjectname($_GET['paper']); ?><br>
            Paper Code:<?php echo $_GET['paper']; ?><br>
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
  $emstrength = 0;
$tmstrength = 0;
while ($egroup = mysqli_fetch_assoc($egroups)) {
    $sql = 'SELECT DISTINCT(haltckt),course,`group`,SEMESTER from DFORMVIEW WHERE `group`="' . $egroup['GROUPCODE'] . '"AND  medium="TM" AND (S1="' . $_GET["paper"] . '" or S2="' . $_GET["paper"] . '" OR S3="' . $_GET["paper"] . '" OR S4="' . $_GET["paper"] . '" OR S5="' . $_GET["paper"] . '" OR S6="' . $_GET["paper"] . '" OR S7="' . $_GET["paper"] . '" OR S8="' . $_GET["paper"] . '" OR S9="' . $_GET["paper"] . '" OR S10="' . $_GET["paper"] . '" OR E1="' . $_GET["paper"] . '" OR E2="' . $_GET["paper"] . '" OR E3="' . $_GET["paper"] . '" OR E4="' . $_GET["paper"] . '") ORDER BY haltckt,`group`';
    //echo $sql . "<br>";
    $estudents = $conn->query($sql);
    $emstrength = $estudents->num_rows;
    echo "<tr><td colspan=2>";
    echo "<div class='spectitle' ><h2 style='text-align:center;'>" . $egroup["COURSE"] . "(" . $egroup["SPECIALIZATION"] . ") Total :" . $estudents->num_rows . " " . $egroup['MEDIUM'] . "</h2></div>";
    echo '<div class="flexbox">';
    while ($estudent = mysqli_fetch_assoc($estudents)) {
        echo "<div class='htholder'><h3 style='text-align:center;'>" . $estudent['haltckt'] . "</h3></div>";

    }
    echo '</td></tr></div>';
}

while ($tgroup = mysqli_fetch_assoc($tgroups)) {
    if ($tgroups->num_rows == 0) {
        continue;
    }

    $sql = 'SELECT DISTINCT(haltckt),course,`group`,SEMESTER from DFORMVIEW WHERE `group`="' . $tgroup['GROUPCODE'] . '" AND  medium="TM" AND(S1="' . $_GET["paper"] . '" or S2="' . $_GET["paper"] . '" OR S3="' . $_GET["paper"] . '" OR S4="' . $_GET["paper"] . '" OR S5="' . $_GET["paper"] . '" OR S6="' . $_GET["paper"] . '" OR S7="' . $_GET["paper"] . '" OR S8="' . $_GET["paper"] . '" OR S9="' . $_GET["paper"] . '" OR S10="' . $_GET["paper"] . '" OR E1="' . $_GET["paper"] . '" OR E2="' . $_GET["paper"] . '" OR E3="' . $_GET["paper"] . '" OR E4="' . $_GET["paper"] . '") ORDER BY haltckt,`group`';
    echo $sql . "<br>";
    $tstudents = $conn->query($sql);
    $tmstrength = $tstudents->num_rows;
    echo "<tr><td colspan=2>";
    echo "<div class='spectitle' ><h2 style='text-align:center;'>" . $tgroup["COURSE"] . "(" . $tgroup["SPECIALIZATION"] . ") Total :" . $tstudents->num_rows . " " . $tgroup["MEDIUM"] . "" . "</h2></div>";
    echo '<div class="flexbox">';
    while ($tstudent = mysqli_fetch_assoc($tstudents)) {
        echo "<div class='htholder'><h3 style='text-align:center;'>" . $tstudent['haltckt'] . "</h3></div>";

    }
    echo '</td></tr></div>';
}
echo '<h1> ENGLISH MEDIUM STUDENTS COUNT' . $emstrength . 'TELUGU MEDIUM STUDENTS COUNT' . $tmstrength . '</h1>';
?>
<tbody>
</table>
</body>
</html>




