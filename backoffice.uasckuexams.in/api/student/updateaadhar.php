<?php include "config.php";

$oldaadhar=$_GET["oldaadhar"];
$newaadhar=$_GET['newaadhar'];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

            if (!$conn) {
                die("connection failed:" . mysqli_connect_error());
            } 
            else {
            $sql = "update students set aadhar='$newaadhar' where aadhar='$oldaadhar'";
            echo $sql;
            $result = $conn->query($sql);
            print_r($result);
            if($result->num_rows>0){
                
            }    
        
}
?>