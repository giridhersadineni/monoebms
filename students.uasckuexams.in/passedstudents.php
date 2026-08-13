<?php include('header.php');?>
<?php
include "config.php";

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $students = "select * from gpas where RESULT='P' AND EXAMID BETWEEN 87 AND 111 order by HALLTICKET";
    

}
?>

    
    

<?php
    $examid=$_GET['examid'];
    $sql = "select * from gpas where RESULT='P' AND EXAMID IN ($examid) ORDER BY HALLTICKET";
    //echo $sql . "<br>";
    $students = $conn->query($sql);
 
    // echo "<tr><td colspan=2>";
    // echo "<div class='spectitle' ><h2 style='text-align:center;'></h2></div>";
    // echo '<div class="flexbox">';
    // while ($tstudent = mysqli_fetch_assoc($tstudents)) {
    //     echo "<div class='htholder'><h1 style='text-align:center;'>" . $tstudent['HALLTICKET'] . "</h1></div>";
    // }
    // echo '</td></tr></div>';
?>

<div class="page-wrapper p-3">
    

<table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
    </thead>
    <tbody>
        <?php
        $i=0;
        foreach($students as $student){
            if($i%5==0){
                echo "<tr>";
            }   
            echo "<td>".$student['HALLTICKET']."</td>";
            $i++;
            if($i%5==0){
                echo "</tr>";
            }
        }
        
        if($i%5!=0){
            while($i){
                echo "<td></td>";
                $i--;
            }
            echo "</tr>";
        }
        
        
        ?>
    </tbody>
    
</table>


</div>
<?php
include "datatablefooter.php"; 
?>
