<?php include 'config.php';


$S1=$_POST["S1"];
$S2=$_POST["S2"];
$S3=$_POST["S3"];
$S4=$_POST["S4"];
$S5=$_POST["S5"];
$S6=$_POST["S6"];
$S7=$_POST["S7"];
$S8=$_POST["S8"];
$S9=$_POST["S9"];
$S10=$_POST["S10"];
$E1=$_POST["E1"];
$E2=$_POST["E2"];
$E3=$_POST["E3"];
$E4=$_POST["E4"];
$CHALLANNUMBER=$_POST["CHALLANNUMBER"];
$FEEPAID=$_POST["FEEPAID"];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
}
$sql = 'UPDATE  examenrollments SET S1= "' . $S1 . '",S2= "' . $S2 . '",S3= "' . $S3 . '", S4="' . $S4 . '", S5="' . $S5 . '", S6="' . $S6 . '", S7="' . $S7 . '",S8="' . $S8 . '",S9="' . $S9 . '", S10="' . $S10 . '",E1="' . $E1 . '", E2="' . $E2 . '",E3="' . $E3. '",E4="' . $E4. '"  WHERE ID="' . $_GET['id'].'"';

if ($conn->query($sql) === true) {
    header("Location:editenrolled.php?id=".$_GET['id']."");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
