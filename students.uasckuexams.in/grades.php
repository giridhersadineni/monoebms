<?php
ob_start();
include "header.php";
include "projectorm.php";
include "config.php";

/* =========================
   HANDLE FORM SUBMISSION
========================= */
if (isset($_POST['submit'])) {

    $hallticket = $_POST['hall_ticket_no'];
    $p1cgpa = $_POST['P1CGPA'];
    $p2cgpa = $_POST['P2CGPA'];
    $allcgpa = $_POST['ALLCGPA'];
    $p1cf = $_POST['P1CF'];
    $p2cf = $_POST['P2CF'];
    $allcf = $_POST['ALLCF'];
    $p1div = $_POST['P1DIV'];
    $p2div = $_POST['P2DIV'];
    $finaldiv = $_POST['FINALDIV'];
    $p1subs = $_POST['P1SUBS'];
    $p2subs = $_POST['P2SUBS'];
    $p2sub1 = $_POST['P2SUB1'];
    $p2sub2 = $_POST['P2SUB2'];
    $p2sub3 = $_POST['P2SUB3'];

    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    /* SECURE UPDATE */
    $sql = "UPDATE grades SET 
        P1CGPA=?, P2CGPA=?, ALLCGPA=?,
        P1CF=?, P2CF=?, ALLCF=?,
        P1DIV=?, P2DIV=?, FINALDIV=?,
        P1SUBS=?, P2SUBS=?,
        P2SUB1=?, P2SUB2=?, P2SUB3=?
        WHERE HALLTICKET=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ddddddsssssssss",
        $p1cgpa, $p2cgpa, $allcgpa,
        $p1cf, $p2cf, $allcf,
        $p1div, $p2div, $finaldiv,
        $p1subs, $p2subs,
        $p2sub1, $p2sub2, $p2sub3,
        $hallticket
    );

    if ($stmt->execute()) {
        header("Location: grades.php?message=Provisional Updated Successfully");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="page-wrapper p-3">
    <div class="row mx-3">
        <button class="btn btn-primary" data-toggle="modal" data-target="#provisionalform">
            Provisional Form
        </button>
    </div>

    <div class="row card mt-3">
        <div class="col-12 p-2">
            <?php
            $query = "SELECT * FROM grades 
                      LEFT JOIN students 
                      ON grades.HALLTICKET = students.haltckt";
            getdatatable(
                $query,
                "display table table-hover table-striped table-bordered table-responsive",
                "example23",
                "PrintCMM",
                "Provisional",
                "GetTabulation"
            );
            ?>
        </div>
    </div>
</div>

<!-- ======================
     JAVASCRIPT FUNCTIONS
====================== -->
<script>
function PrintCMM(id){
    window.open("printcmm.php?id=" + id, "_blank");
}
function Provisional(id){
    window.open("printprovisional.php?id=" + id, "_blank");
}
function GetTabulation(id){
    window.open(
        "http://postexam.uasckuexams.in/process/universityreports/studenttabulation.php?id=" + id,
        "_blank"
    );
}
</script>

<!-- ======================
     MODAL FORM
====================== -->
<div class="modal fade" id="provisionalform">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

<form method="POST">
<div class="modal-header">
    <h5>Provisional Details</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<div class="form-group">
    <label>Hall Ticket No</label>
    <input type="text" class="form-control" name="hall_ticket_no" required>
</div>

<div class="row">
    <div class="col"><input class="form-control" name="P1CGPA" placeholder="P1 CGPA"></div>
    <div class="col"><input class="form-control" name="P2CGPA" placeholder="P2 CGPA"></div>
    <div class="col"><input class="form-control" name="ALLCGPA" placeholder="ALL CGPA"></div>
</div><br>

<div class="row">
    <div class="col"><input class="form-control" name="P1CF" placeholder="P1 CF"></div>
    <div class="col"><input class="form-control" name="P2CF" placeholder="P2 CF"></div>
    <div class="col"><input class="form-control" name="ALLCF" placeholder="ALL CF"></div>
</div><br>

<div class="row">
    <div class="col"><input class="form-control" name="P1DIV" placeholder="P1 DIV"></div>
    <div class="col"><input class="form-control" name="P2DIV" placeholder="P2 DIV"></div>
    <div class="col"><input class="form-control" name="FINALDIV" placeholder="FINAL DIV"></div>
</div><br>

<div class="row">
    <div class="col"><input class="form-control" name="P1SUBS" placeholder="P1 Subjects"></div>
    <div class="col"><input class="form-control" name="P2SUBS" placeholder="P2 Subjects"></div>
</div><br>

<div class="row">
    <div class="col"><input class="form-control" name="P2SUB1" placeholder="P2 Subject 1"></div>
    <div class="col"><input class="form-control" name="P2SUB2" placeholder="P2 Subject 2"></div>
    <div class="col"><input class="form-control" name="P2SUB3" placeholder="P2 Subject 3"></div>
</div>

</div>

<div class="modal-footer">
    <input type="submit" name="submit" class="btn btn-success" value="Update">
</div>

</form>
    </div>
  </div>
</div>

<?php include "datatablefooter.php"; ?>
<?php ob_end_flush(); ?>
