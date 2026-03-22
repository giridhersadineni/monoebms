<?php
include "header.php";
include "config.php";

/* ================= PAYMENT UPDATE ================= */
if (isset($_POST['update'])) {

    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $enrollid = (int)$_POST['enrollid'];
    $feepaid  = isset($_POST['feepaid']) ? 1 : 0;

    $sql = "
        UPDATE revaluationenrollments SET
            FEEPAID = $feepaid,
            CHALLANSUBMITTEDON = '".mysqli_real_escape_string($conn,$_POST['CHALLANSUBDATE'])."',
            CHALLANRECBY = '".mysqli_real_escape_string($conn,$_POST['challanrecd'])."'
        WHERE ID = $enrollid
    ";

    if ($conn->query($sql)) {
        echo "<script>alert('Payment marked successfully');location.reload();</script>";
    } else {
        echo "<script>alert('Payment update failed');</script>";
    }

    $conn->close();
}

/* ================= FETCH ENROLLMENTS ================= */
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
$sql = "SELECT * FROM revaluationenrollments";
$result = $conn->query($sql);

/* ================= HELPER ================= */
function get_row_fields($row, $columns){
    $out = "";
    foreach ($columns as $c) {
        $out .= "<td>".($row[$c] ?? '')."</td>";
    }
    return $out;
}
?>

<style>
tr.row-paid {
    background-color: #e6f4ea !important;
}
tr.row-unpaid {
    background-color: #fffaf0;
}
table.dataTable tbody tr.row-paid:hover {
    background-color: #d6eddc !important;
}
</style>

<div class="page-wrapper">
<div class="container-fluid">
<div class="card">
<div class="card-body">

<div class="table-responsive">
<table id="example23" class="display nowrap table table-hover table-striped table-bordered" width="100%">

<?php
$columns = [
    'RHID','EXAMID','HALLTICKET','PAPERCODE','PAPERNAME',
    'CODE','EID','EXT','ETOTAL','INT','ITOTAL','RESULT','GRADE'
];

echo "<thead><tr>
        <th>Action</th>
        <th>APP_ID</th>";
foreach ($columns as $h) {
    echo "<th>$h</th>";
}
echo "</tr></thead><tbody>";

if ($result->num_rows > 0) {

    while ($enrollment = mysqli_fetch_assoc($result)) {

        $enrollmentid = $enrollment['ID'];
        $examid       = $enrollment['EXAMID'];
        $hallticket   = $enrollment['HALLTICKET'];
        $rowClass     = ($enrollment['FEEPAID'] == 1) ? 'row-paid' : 'row-unpaid';

        // subject paper codes are stored after first few columns
        $subs = array_slice($enrollment, 4, 14);

        foreach ($subs as $subject) {

            if ($subject !== null && $subject !== '') {

                $q = "
                    SELECT * FROM RESULTS
                    WHERE EXAMID = $examid
                      AND HALLTICKET = $hallticket
                      AND PAPERCODE = '".mysqli_real_escape_string($conn,$subject)."'
                ";

                $res = $conn->query($q);
                $sub = mysqli_fetch_assoc($res);

                if (!$sub) continue;

                /* ACTION BUTTON */
                if ($enrollment['FEEPAID'] == 0) {
                    $actionBtn = "
                    <button
                        class='btn btn-sm btn-success'
                        data-toggle='modal'
                        data-target='#paymentModal'
                        data-enrollid='$enrollmentid'
                        data-challan='$enrollmentid'
                        data-hallticket='{$sub['HALLTICKET']}'
                    >
                        <i class='fas fa-rupee-sign'></i> Mark Payment
                    </button>";
                } else {
                    $actionBtn = "
                        <span class='badge badge-success'>
                            <i class='fas fa-check'></i> Paid
                        </span>";
                }

                echo "
                <tr class='$rowClass'>
                    <td>$actionBtn</td>
                    <td>$enrollmentid</td>
                    ".get_row_fields($sub, $columns)."
                </tr>";
            }
        }
    }
}

echo "</tbody></table>";
?>

</div>
</div>
</div>
</div>
</div>

<!-- ================= PAYMENT MODAL ================= -->
<div class="modal fade" id="paymentModal" tabindex="-1">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<form method="POST">

<div class="modal-header">
    <h5 class="modal-title">Mark Payment Received</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<input type="hidden" name="enrollid" id="modal_enrollid">
<input type="hidden" name="challanrecd" value="<?php echo $_COOKIE['userid']; ?>">

<div class="form-group">
    <label>Challan Number (APP_ID)</label>
    <input type="text" name="CHALLANNUMBER" id="modal_challan" class="form-control" readonly>
</div>

<div class="form-group">
    <label>Hall Ticket</label>
    <input type="text" id="modal_hallticket" class="form-control" readonly>
</div>

<div class="form-group">
    <label>Challan Date</label>
    <input type="date" name="CHALLANSUBDATE"
           class="form-control"
           value="<?php echo date('Y-m-d'); ?>" required>
</div>

<div class="form-group text-center">
    <label>
        <input type="checkbox" name="feepaid" value="1" required>
        <strong>Confirm payment received</strong>
    </label>
</div>

</div>

<div class="modal-footer">
    <button type="submit"
            name="update"
            class="btn btn-primary"
            onclick="return confirm('Confirm payment received?');">
        Mark Payment
    </button>
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
</div>

</form>

</div>
</div>
</div>

<script>
$('#paymentModal').on('show.bs.modal', function (event) {

    var btn = $(event.relatedTarget);

    $('#modal_enrollid').val(btn.data('enrollid'));
    $('#modal_challan').val(btn.data('challan'));
    $('#modal_hallticket').val(btn.data('hallticket'));

});
</script>

<?php include "datatablefooter.php"; ?>
