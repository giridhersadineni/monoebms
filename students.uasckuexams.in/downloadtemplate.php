

<?php
 header("Content-Disposition: attachment; filename=\"demo.xls\"");
                    header("Content-Type: application/vnd.ms-excel;");
                    header("Pragma: no-cache");
                    header("Expires: 0");
                    $out = fopen("php://output", 'w');
include "config.php";
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from revaluationenrollments where feepaid=1";
    
    $result = $conn->query($sql);
  
}
?>
<?php
if ($result->num_rows > 0) {
    // output data of each row
   
    while ($row = mysqli_fetch_assoc($result)) {
        $revid=$row['ID'];
        $stid=$row['STUDENTID'];
        $subs=array_slice($row,3,14);
      
        foreach($subs as $subject){
            
            if($subject!="null"){
                $getresultholder="select * from rescomplete where PAPERCODE='".$subject."' and stid=".$stid;
               // echo $getresultholder;
                $resultholders=$conn->query($getresultholder);
                while($resholder=mysqli_fetch_assoc($resultholders)){
                   $revarr=array("REV_EXID"=>$revid);
                   $temp=array_slice($resholder,0,21);
                   $sdetails=array_slice($resholder,50,10);
                   $rh=array_merge($revarr,$temp);
                   $rhfinal=array_merge($rh,$sdetails);
                   
                   
                   
                   
                   
                   // start here
                   
                           fputcsv($out, $rhfinal,"\t");
                  
                   
                   //end here
                    
                }
            }
        }
        
    }
} ?>

<?php
mysqli_close($conn);
  fclose($out);
?>
