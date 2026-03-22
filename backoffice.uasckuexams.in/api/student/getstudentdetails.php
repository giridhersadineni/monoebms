<?php include "../config.php";
$hallticket=$_GET['ht'];
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
} else {
    
    $sql = "select * from students where haltckt='$hallticket'";
    //echo $sql;
    $studentdetails="";
    $result=$conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            foreach($row as $value){
             $studentdetails=$studentdetails.$value.";";
            }
        }
        //echo json_encode($resultset);
        echo $studentdetails;
    } else {
        echo $studentdetails;
        //echo $sql;

    }
}

?>