<?php 
include "header.php";
include "config.php";

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("connection failed: " . $conn->connect_error);
}

$hallticket = trim($_POST['hallticket'] ?? '');

/* Render a message inside the page layout and stop.
   Using die() here would end the response with header.php's markup left
   open, so the browser shows a half-drawn page with no footer. */
function bail_page($bodyHtml) {
    echo '<div class="page-wrapper"><div class="container-fluid">'
       . '<div class="card"><div class="card-body">'
       . $bodyHtml
       . '</div></div></div></div>';
    include "footer.php";
    exit;
}

if ($hallticket === '') {
    bail_page(
        '<h4 class="text-danger">No hall ticket entered</h4>'
        . '<p>Type a hall ticket number into the search box and try again.</p>'
    );
}

// ---- Fetch student ----
$student_sql = "SELECT * FROM students WHERE haltckt = ?";
$stmt = $conn->prepare($student_sql);
$stmt->bind_param("s", $hallticket);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();

if (!$student) {

    /* The same student is often registered under a longer hall ticket
       (e.g. 001171007 stored as 19022001171007) while results sit under
       the short one. Offer the match rather than dead-ending. */
    $suggestions = [];
    foreach (['%' . $hallticket, '%' . ltrim($hallticket, '0') . '%'] as $pattern) {
        $sg = $conn->prepare("SELECT haltckt, sname, course, sem FROM students
                              WHERE haltckt LIKE ? AND haltckt <> ? LIMIT 5");
        $sg->bind_param("ss", $pattern, $hallticket);
        $sg->execute();
        $sgr = $sg->get_result();
        while ($s = $sgr->fetch_assoc()) { $suggestions[$s['haltckt']] = $s; }
        $sg->close();
        if ($suggestions) { break; }
    }

    /* Results may exist even with no students row - worth telling staff. */
    $resCount = 0;
    $rc = $conn->prepare("SELECT COUNT(*) c FROM RESULTS WHERE HALLTICKET = ?");
    $rc->bind_param("s", $hallticket);
    $rc->execute();
    $resCount = (int)($rc->get_result()->fetch_assoc()['c'] ?? 0);
    $rc->close();

    $msg = '<h4 class="text-danger">No student record for hall ticket '
         . htmlspecialchars($hallticket) . '</h4>';

    if ($resCount > 0) {
        $msg .= '<p class="text-warning">There are <strong>' . $resCount . '</strong> row(s) in'
              . ' RESULTS under this hall ticket, but no matching row in the students table.</p>';
    }

    if ($suggestions) {
        $msg .= '<p>Did you mean:</p><ul class="list-unstyled">';
        foreach ($suggestions as $s) {
            $msg .= '<li class="m-b-10">'
                  . '<form action="viewstudent.php" method="POST" class="form-inline">'
                  . '<input type="hidden" name="hallticket" value="' . htmlspecialchars($s['haltckt']) . '">'
                  . '<button type="submit" class="btn btn-sm btn-success m-r-10">'
                  . htmlspecialchars($s['haltckt']) . '</button>'
                  . '<span>' . htmlspecialchars($s['sname'])
                  . ' &mdash; ' . htmlspecialchars($s['course'])
                  . ' / sem ' . htmlspecialchars($s['sem']) . '</span>'
                  . '</form></li>';
        }
        $msg .= '</ul>';
    } else {
        $msg .= '<p>No similar hall ticket found in the students table either.</p>';
    }

    bail_page($msg);
}

// ---- Fetch enrollments (from enrolledview) ----
$enroll_sql = "SELECT * FROM enrolledview WHERE HALLTICKET = ?";
$stmt2 = $conn->prepare($enroll_sql);
$stmt2->bind_param("s", $hallticket);
$stmt2->execute();
$enrollments = $stmt2->get_result();

?>

<div class="page-wrapper">
<div class="container-fluid">
<div class='card'>

<div class="row">
    <div class="col-md-12">
        <h3 class="box-title">
            Student Details 
            <span class="pull-right">
                <div class="btn-group">
                    <form action="https://students.uasckuexams.in/index.php" method="POST">
                        <input type="hidden" name="haltckt" value="<?=$student['haltckt'];?>">
                        <input type="hidden" name="dob" value="<?=$student['dob'];?>">
                        <input type="submit" class="btn btn-success" value="Login">
                    </form>

                    <a class="btn btn-danger" href="editstudent.php?stid=<?=$student['stid']?>">Edit Record</a>
                </div>
            </span>
        </h3>
    </div>
</div>

<div class="row m-t-40">
    <div class="col-sm-2">
        <img src="http://uascku.ac.in/ebms/students/upload/images/<?=$student['aadhar']?>.jpg" class="img-circle" width="100"/>
        <br>
        <img src="http://uascku.ac.in/ebms/students/upload/signatures/<?=$student['aadhar']?>.jpg" class="img-circle" width="100"/>
    </div>

    <div class="col-sm-10">
        <div class="row">
            <div class="col-md-6">
                <strong>Hallticket:</strong> <?=$student["haltckt"]?><br>
                <strong>Student Name:</strong> <?=$student["sname"]?><br>
                <strong>Father Name:</strong> <?=$student["fname"]?><br>
                <strong>Mother Name:</strong> <?=$student["mname"]?><br>
                <strong>DOB:</strong> <?=$student["dob"]?><br>
                <strong>Gender:</strong> <?=$student["gender"]?><br>
                <strong>Aadhar:</strong> <?=$student["aadhar"]?><br>
                <strong>Student ID:</strong> <?=$student["stid"]?><br>
            </div>

            <div class="col-md-6">
                <strong>Course:</strong> <?=$student["course"]?><br>
                <strong>Group:</strong> <?=$student["group"]?><br>
                <strong>Medium:</strong> <?=$student["medium"]?><br>
                <strong>Studying Year:</strong> <?=$student["curryear"]?><br>

                <strong>Address:</strong><br>
                <?=$student["address"]?><br>
                <?=$student["address2"]?><br>
                <?=$student["mandal"]?>, <?=$student["city"]?><br>
                <?=$student["state"]?> - <?=$student["pincode"]?>
            </div>
        </div>
    </div>
</div>

<div class="m-t-20">
    <p>
        <span class="text-large">Contact</span>
        <a href="tel:<?=$student['phone']?>" class="btn btn-primary btn-sm">Call: <?=$student['phone']?></a>
        <a href="mailto:<?=$student['email']?>" class="btn btn-primary btn-sm">Email: <?=$student['email']?></a>
    </p>
</div>

</div>

<div class="card">
    <div class="row">
        <div class="col">
            <form action="cmmreport.php" method="POST">
                <input type="hidden" name="HALLTICKET" value="<?=$student['haltckt'];?>">
                <input type="submit" class="btn btn-success" value="Consolidated Marks">
            </form>

            <a href="calcmm.php?hallticket=<?=$student['haltckt']?>" class="btn btn-info">CMM Report</a>
        </div>
    </div>

<div class="table-responsive m-t-40">
<table id="example23" class="table table-hover table-striped table-bordered">
<thead>
<tr>
    <th>ID</th>
    <th>Exam ID</th>
    <th>Exam Name</th>
    <th>Semester</th>
    <th>Exam Type</th>
    <th>Enrollment Date</th>
    <th colspan="3" class="text-center">Actions</th>
</tr>
</thead>

<tbody>
<?php if ($enrollments->num_rows > 0): ?>
    <?php while ($row = $enrollments->fetch_assoc()): ?>
        <tr>
            <td><?=$row["ID"]?></td>
            <td><?=$row["EXAMID"]?></td>
            <td><?=$row["EXAMNAME"]?></td>
            <td><?=$row["SEMESTER"]?></td>
            <td><?=$row["EXAMTYPE"]?></td>
            <td><?=$row["ENROLLEDDATE"]?></td>

            <td>
            <?php if ($row['STATUS']=='RUNNING' || $row['STATUS']=='NOTIFY'): ?>
                <a href="editenrolled.php?id=<?=$row['ID']?>" class="btn btn-info btn-sm">View Subjects</a>
            <?php endif; ?>
            </td>

            <td>
                <a href="studentgpaupdate.php?exid=<?=$row['EXAMID']?>&ht=<?=$student['haltckt']?>" class="btn btn-secondary" target="_blank">Update GPA</a>
            </td>

            <td>
                <a href="printoldgradesheet.php?exid=<?=$row['EXAMID']?>&ht=<?=$student['haltckt']?>" class="btn btn-success" target="_blank">Print Memo</a>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr><td colspan="9" class="text-center">No Enrollments Found</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>

</div>
</div>
</div>

<?php include "footer.php";?>
