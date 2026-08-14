<?php
session_start();
include 'header.php';
include 'config.php';

/* -------------------------
   INITIALIZE VARIABLES
--------------------------*/
$id = "";
$HALLTICKET = "";
$FEEAMOUNT = "";
$FEEPAID = 0;
$rows = null;
$message = "";

/* -------------------------
   DB CONNECTION FUNCTION
--------------------------*/
function db_connect($servername, $dbuser, $dbpwd, $dbname) {
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn) {
        die("Database connection failed");
    }
    return $conn;
}

/* -------------------------
   FETCH DETAILS (GET)
--------------------------*/
if (isset($_GET['getdetails']) && !empty($_GET['ID'])) {

    $ID = intval($_GET['ID']); // SQL injection protection
    $conn = db_connect($servername, $dbuser, $dbpwd, $dbname);

    $query = "SELECT ID, HALLTICKET, FEEAMOUNT, FEEPAID 
              FROM revaluationenrollments 
              WHERE ID = $ID";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);
        $id = $row['ID'];
        $HALLTICKET = $row['HALLTICKET'];
        $FEEAMOUNT = $row['FEEAMOUNT'];
        $FEEPAID = $row['FEEPAID'];

        /* Fetch student details */
        $studentQuery = "SELECT * FROM students WHERE haltckt = '$HALLTICKET'";
        $studentResult = mysqli_query($conn, $studentQuery);

        if ($studentResult && mysqli_num_rows($studentResult) > 0) {
            $rows = mysqli_fetch_assoc($studentResult);
        }

    } else {
        echo "<script>alert('Details Not Found');</script>";
    }

    mysqli_close($conn);
}

/* -------------------------
   UPDATE PAYMENT (POST)
--------------------------*/
if (isset($_POST['update'])) {

    $conn = db_connect($servername, $dbuser, $dbpwd, $dbname);

    $enrollid = intval($_POST['enrollid']);
    $feepaid = isset($_POST['feepaid']) ? 1 : 0; // checkbox safety
    $challanNo = mysqli_real_escape_string($conn, $_POST['CHALLANNUMBER']);
    $challanDate = mysqli_real_escape_string($conn, $_POST['CHALLANSUBDATE']);

    /* USER ID FROM SESSION (NOT COOKIE) */
    $challanRecdBy = $_SESSION['userid'] ?? 'SYSTEM';

    $sql = "
        UPDATE revaluationenrollments SET
            FEEPAID = '$feepaid',
            CHALLANRECBY = '$challanRecdBy',
            CHALLANSUBMITTEDON = '$challanDate'
        WHERE ID = $enrollid
    ";

    if (mysqli_query($conn, $sql)) {
        $currentDateTime = date('Y-m-d H:i:s');
        $message = "
            <h2 class='text-success'>Payment Marked</h2>
            <h4 class='text-primary'>$currentDateTime</h4>
        ";
    } else {
        $message = "<h4 class='text-danger'>Update Failed</h4>";
    }

    mysqli_close($conn);
}
?>

<div class="page-wrapper">
<div class="container-fluid">
<div class="row">
<div class="col-lg-12">
<div class="card card-outline-primary">
<div class="card-body">

<?= $message ?>

<!-- SEARCH FORM -->
<form method="GET" action="revaluationmpayment.php">
<div class="col-md-6">
<div class="input-group mb-3">
<input type="text" class="form-control" name="ID" required placeholder="Enter Registration Number">
<div class="input-group-append">
<input class="btn btn-warning" type="submit" name="getdetails" value="Get Details">
</div>
</div>
</div>
</form>

<?php if ($rows): ?>

<!-- STUDENT IMAGES -->
<div style="float:right;margin-right:10%;">
<?php
$photo = "../students/upload/images/".$rows['aadhar'].".jpg";
$sign = "../students/upload/signatures/".$rows['aadhar'].".jpg";

if (file_exists($photo)) echo "<img src='$photo' width='100'><br>";
if (file_exists($sign)) echo "<img src='$sign' width='100'>";
?>
</div>

<!-- PAYMENT FORM -->
<form method="POST" action="revaluationmpayment.php">

<input type="hidden" name="enrollid" value="<?= $id ?>">

<table width="60%" border="1">
<tbody>

<h4>1. Candidate Details</h4>

<tr>
<td>Student Name</td>
<td><center><h3><?= htmlspecialchars($rows['sname']) ?></h3></center></td>
</tr>

<tr>
<td>Course</td>
<td><center><h3><?= htmlspecialchars($rows['course']) ?></h3></center></td>
</tr>

<tr>
<td>Fee Amount</td>
<td><center><h3><?= $FEEAMOUNT ?></h3></center></td>
</tr>

<tr>
<td>Fee Status</td>
<td><center>
<?= $FEEPAID ? "<span class='text-success'>FEE PAID</span>" : "<span class='text-danger'>NOT PAID</span>" ?>
</center></td>
</tr>


<tr>
<td>Challan Date</td>
<td><input type="date" class="form-control" name="CHALLANSUBDATE" value="<?= date('Y-m-d') ?>"></td>
</tr>

<tr>
<td>Mark Fee Paid</td>
<td class="text-center">
<input type="checkbox" name="feepaid" value="1">
<strong> Tick to mark payment</strong>
</td>
</tr>

</tbody>
</table>

<br>
<p align="center">
<input type="submit" class="btn btn-primary" name="update" value="Mark Payment">
</p>

</form>

<?php endif; ?>

</div>
</div>
</div>
</div>
</div>
</div>

<?php include 'datatablefooter.php'; ?>
