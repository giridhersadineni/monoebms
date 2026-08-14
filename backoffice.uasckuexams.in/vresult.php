<?php include "header.php";?>
   
<?php include "config.php";
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * FROM rholdernew WHERE HALLTICKET='".$_GET['hallticket']."' AND  CODE=".$_GET['scripit'];
    $result = $conn->query($sql);
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
 <li class="breadcrumb-item active"> Details</li>
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
<div class="table-responsive m-t-40">
<table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">

            <thead>
                        <tr>
                              <th>Action</th>
                              <th>ExamId</th>
                              <th>Hallticket</th>
                              <th>PaperCode</th>
                              <th>PaperName</th>
                              <th>External</th>
                              <th>External Total</th>
                              <th>Internal</th>
                              <th>Internal Total</th>
                              <th>Result</th>
                              <th>Credits</th>
                        </tr>
                 </thead>
            


<?php
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        //print_r($row);
        echo "<tr>";
        echo '<td><a href=editresult.php?hallticket='. $_GET["hallticket"].'&pcode='. $row["CODE"].' class="btn btn-warning">Edit</a></td>';
        
        echo "<td>". $row["EXAMID"] . "</td><td>" . $row["HALLTICKET"] . "</td><td>" . $row["PAPERCODE"] . "</td><td>" . $row["PAPERNAME"] . "</td><td>" . $row["EXT"] . "</td><td>" . $row["ETOTAL"] . "</td><td>" . $row["INT"] . "</td><td>" . $row["ITOTAL"] . "</td><td>" . $row["RESULT"] . "</td><td>" . $row["CREDITS"] . "</td>";

       
}
} else {
    echo '<tr><td colspan="12">No Branches - Empty Table</td></tr>';
}
?>

<?php
mysqli_close($conn);
?>

</table>
</div>
</div>
</div>
</div>
</div>
<!-- footer -->






<?php include "datatablefooter.php";?>
