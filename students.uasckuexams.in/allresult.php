<?php include "header.php";?>

<?php
include "config.php";
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from rholdernew";
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
 <li class="breadcrumb-item active">Enrolled</li>
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
                              <th>RHID</th>
                              <th>EXAMID</th>   
                              <th>PAPERCODE</th>                     
                              <th>PAPERNAME</th>
                              <th>HALLTICKET</th>
                             <th>EID</th>                     
                              <th>CODE</th>
                              <th>EXT</th>
                             <th>EXTOTAL</th>                     
                              <th>INT</th>
                              <th>ITOTAL</th>
                             <th>RESULT</th>                     
                              <th>CRIDITS</th>
                              <th>MARKS</th>
                               <th>TOTALMARKS</th>                     
                              <th>PERCENTAGE</th>
                              <th>GRADE</th>
                              <th>GPV</th>                     
                              <th>GPC</th>
                    </tr>
                 </thead>
            


<?php
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
echo "<td>" . $row["RHID"] . "</td><td>" . $row["EXAMID"] . "</td><td>" . $row["PAPERCODE"] . "</td><td>" . $row["PAPERNAME"] . "</td><td>" . $row["HALLTICKET"] . "</td><td>" . $row["EID"] . "</td><td>" . $row["CODE"] . "</td><td>" . $row["EXT"] . "</td><td>" . $row["ETOTAL"] . "</td><td>" . $row["INT"] . "</td><td>" . $row["ITOTAL"] . "</td><td>" . $row["RESULT"] . "</td><td>" . $row["CREDITS"] . "</td><td>" . $row["MARKS"] . "</td><td>" . $row["TOTALMARKS"] . "</td><td>" . $row["PERCENTAGE"] . "</td><td>" . $row["GRADE"] . "</td><td>" . $row["GPV"] . "</td><td>" . $row["GPC"] . "</td>";

'</tr>';
}
} else {
    echo '<tr><td colspan="5">No Branches - Empty Table</td></tr>';
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