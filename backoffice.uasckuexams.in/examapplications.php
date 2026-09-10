<?php include "header.php";?>

<?php
if (isset($_GET['updated'])) {
    echo '<script>alert("Updated Sucessfull..!!");</script>';
}

include "config.php";
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from enrolledview where STATUS IN ('NOTIFY','RUNNING')";
    $result = $conn->query($sql);
}
?>

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
 <li class="breadcrumb-item active">Enrolled Details</li>
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
                            <th>Actions</th>
                              <th>ENROLLMENT ID</th>
                              <th>EXAMID</th>
                              <th>Student name</th>
                              <th>Hallticket</th>
                              <th>Sub1<th>
                              <th>Sub2</th>
                              <th>Sub3</th>
                              <th>Sub4</th>
                              <th>Sub5</th>
                              <th>Sub6</th>
                              <th>Sub7</th>
                              <th>Sub8</th>
                              <th>Sub9</th>
                              <th>E1</th>
                              <th>E2</th>
                              <th>E3</th>
                              <th>E4</th>
                              <th>Fee Amount</th>
                              <th>Challan Generated</th>
                              <th>Challan Submited On</th>    
                              
                              </tr>
                 </thead>
            


<?php
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo '<td><a href="enrolledstudent.php?id=' . $row["ID"] . '" class="btn btn-sm btn-info"><i class="fas fa-eye-alt "></i>View</a>';
        echo '<a href="editenrolled.php?id=' . $row["ID"] . '" class="btn btn-sm btn-success"><i class="fas fa-pencil ">Edit</i></a>';
       echo '<a href="deleteenrolled.php?id=' . $row["ID"] . '"  data-confirm="Are You Sure To Delete..!! " class="btn"><i class="fas fa-trash-alt fa-2x text-danger"></i>Delete</a></td>';
        echo "<td><a href='#?id=" . $row["ID"] . "'>" . $row["ID"] . "</a></td><td>" . $row["EXAMID"] . "</td><td>" . $row["sname"] . "</td><td>" . $row["haltckt"] . "</td><td>" . $row["S1"] . "</td><td>" . $row["S2"] . "</td><td>" . $row["S3"] . "</td><td>" . $row["S4"] . "</td><td>" . $row["S5"] . "</td><td>" . $row["S6"] . "</td><td>" . $row["S7"] . "</td><td>" . $row["S8"] . "</td><td>" . $row["S9"] . "</td><td>" . $row["S10"] . "</td> <td>" . $row["E1"] . "</td><td>" . $row["E2"] . "</td><td>" . $row["E3"] . "</td><td>" . $row["E4"] . "</td><td>" . $row["FEEAMOUNT"] . "</td><td>" . $row["CHALLANGENERATED"] . "</td><td>" . $row["CHALLANSUBMITTEDON"] . "</td>";
     
       
       echo '</tr>';
}
} else {
    echo '<tr><td colspan="5">No Branches - Empty Table</td></tr>';
    
}
?>

<?php
mysqli_close($conn);
?>
<script>

var deletedata = document.querySelectorAll('.button1');

for (var i = 0; i < deletedata.length; i++) {
  deletedata[i].addEventListener('click', function(event) {
	  event.preventDefault();

	  var choice = confirm(this.getAttribute('data-confirm'));

	  if (choice) {
	    window.location.href = this.getAttribute('href');
	  }
  });
}

</script>
</table>
</div>
</div>
</div>
</div>
</div>
<!-- footer -->
<?php include "datatablefooter.php";?>