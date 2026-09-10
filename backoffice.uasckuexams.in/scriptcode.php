<?php include "header.php";?>

<?php
// include "config.php";
// //check connection
// $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

// if ($conn->connect_error) {
//     die("connection failed:" . mysqli_connect_error());
// } else {
//     $sql = "select * from students";
//     $result = $conn->query($sql);
// }
?>
<?php
include "config.php";
function getsubjectname($subcode)
{
    include "config.php";
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    $getsubname = "select PAPERNAME from PAPERS where PAPERCODE='" . $subcode . "'";
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

<!-- End Left Sidebar  -->

<!-- Page wrapper  -->
<div class="page-wrapper">
<!-- Bread crumb -->

<div class="row page-titles">
 <div class="col-md-5 align-self-center">
 <!--<h3 class="text-primary">Dashboard</h3> -->
 </div>

 <div class="col-md-7 align-self-center">
 <ol class="breadcrumb">
 <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
 <li class="breadcrumb-item active">Student Details</li>
 </ol>
 </div>
 </div>
 <!-- End Bread crumb -->

<!-- Container fluid  -->
<div class="container-fluid">
   
<!-- Start Page Content -->
<div class="row">
<div class="col-12">
<div class="card">
<div class="card-body">
     <h2>PaperCode:<?php echo $_GET['paper']; ?></h2>
<div class="table-responsive m-t-40">
<table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">

            


<?php
?>
<?php
    $sql = 'SELECT DISTINCT(HALLTICKET),`group`,ID,EXAMID,STID,STATUS from enrolledview WHERE feepaid=1 and STATUS="RUNNING" and (S1="' . $_GET["paper"] . '" or S2="' . $_GET["paper"] . '" OR S3="' . $_GET["paper"] . '" OR S4="' . $_GET["paper"] . '" OR S5="' . $_GET["paper"] . '" OR S6="' . $_GET["paper"] . '" OR S7="' . $_GET["paper"] . '" OR S8="' . $_GET["paper"] . '" OR S9="' . $_GET["paper"] . '" OR S10="' . $_GET["paper"] . '" OR E1="' . $_GET["paper"] . '" OR E2="' . $_GET["paper"] . '" OR E3="' . $_GET["paper"] . '" OR E4="' . $_GET["paper"] . '" OR E5="' . $_GET["paper"]  . '") ORDER BY HALLTICKET,`group` ASC';
    
    //echo $sql . "<br>";
    $tstudents = $conn->query($sql);
    $tmstrength = $tstudents->num_rows;
?>
<thead>
    <tr>
        <th>id</th>
        <th>examid</th>
        <th>papercode</th>
        <th>hallticket</th>
        <th>scriptcode</th>
        <th>enrollmentid</th>
        <th>is_absent_internal</th>
        <th>is_absent_external</th>
        <th>is_malpractice</th>
        <th>internal_marks</th>
        <th>internal_total</th>
        <th>external_marks</th>
        <th>external_total</th>
        <th>credits</th>
        <th>created_at</th>
        <th>updated_at</th>
    </tr>
</thead>
<tbody>
    
<?php while ($tstudent = mysqli_fetch_assoc($tstudents)): ?> 
    <tr>
        <td>NULL</th>
        <td><?=$tstudent['EXAMID'];?></th>
        <td><?=$_GET['paper'];?></td>
        <td><?=$tstudent['HALLTICKET'];?></td>
        <td><?=$tstudent['EXAMID'];?><?=$_GET['paper'];?><?=$tstudent['HALLTICKET'];?></td>
        <td><?=$tstudent['ID']; ?></td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
    </tr>
        

        
        
<?php endwhile; ?>      


<?php
mysqli_close($conn);
?>
</tbody>

</table>
</div>
</div>
</div>
</div>
</div>
<!-- footer -->
<?php include "datatablefooter.php";?>