<?php include "header.php"?>
<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $data=array();
    $sql = "select * from  students";
    $result = $conn->query($sql);
    while($row=mysqli_fetch_assoc($result)){
        array_push($data,$row);
    }
?>
<div class="page-wrapper">
<!-- Bread crumb -->


<!-- End Bread crumb -->
<!-- Container fluid  -->
<div class="container-fluid bg-light" >
<h3>Student Data</h3>
<table id="example23" class="display nowrxap table table-hover table-striped table-bordered" cellspacing="0" width="100%">


                <thead>
                <th>ACTIONS</th>
                <th>STUDENTID</th>
                <th>STUDENT NAME</th>
                <th>FATHER NAME</th>
                <th>MOTHER NAME</th>
                <th>HALLTICKET</th>
                <th>DATE OF BIRTH</th>
                <th>COURSE</th>
                <th>GROUP</th>
                <th>MEDIUM</th>
                <th>SEMESTER</th>
                <th>PHONE</th>
</thead>
     
<?php
if ($result->num_rows > 0) {
    // output data of each row
    foreach($result as $student): ?>       
        <tr>
            
        <td style="display:flex;flex-direction:row;align-items:center;">
        <form action="viewstudent.php" method="POST">
            <input type="hidden" name="hallticket" value="<?=$student['haltckt']?>">
            <button class="btn btn-primary mr-1" type="submit"><i class="fas fa-eye"></i></button>
        </form>
        <form action="https://students.uasckuexams.in/index.php" method="POST">
            <input type="hidden" name="haltckt" value="<?=$student['haltckt']?>">
            <input type="hidden" name="dob" value="<?=$student['dob']?>">
            <input type="hidden" name="submit" value="submit">
        <button class="btn btn-primary mr-1" type="submit" data-toggle="tooltip" data-placement="top" title="Click Holding Down Shift Key"><i class="fas fa-key"></i></button>
        </form>
        </td>
        <td><?=$student['stid']?></td>
        <td><?=$student['sname']?></td>
        <td><?=$student['fname']?></td>
        <td><?=$student['mname']?></td>
        <td><?=$student['haltckt']?></td>
        <td><?=$student['dob']?></td>
        <td><?=$student['course']?></td>
        <td><?=$student['group']?></td>
         <td><?=$student['medium']?></td>
        <td><?=$student['sem']?></td>
        <td><?=$student['phone']?></td>
        
    </tr>
    <?php endforeach ?>

<?php

} 
else {
    echo '<tr><td colspan="5">No Branches - Empty Table</td></tr>';

}
}
?>

<?php
mysqli_close($conn);

?>

</table>
<script>
    $(function () {
    $('[data-toggle="tooltip"]').tooltip()
    });
    
</script>
</div>
<?php include "datatablefooter.php";?>