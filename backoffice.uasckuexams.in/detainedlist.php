<?php
include "header.php";
include "config.php";

/* ================================================================
   DETAINED LIST
   Cohort  : students who appeared in the selected exam.
   Credits : computed over EVERY paper the student has appeared in
             (all exams in RESULTS), not just the selected exam.
   Each distinct PAPERCODE counts its credits ONCE, however many
   attempts it took; a paper passed on any attempt earns its credits.
   Detained when credits secured < cut-off % of credits appeared.
   ================================================================ */

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if (!$conn) {
    die("Database connection failed");
}

/* ---------------- EXAM DROP DOWN ---------------- */
$exams = $conn->query("
    SELECT EXID, EXAMNAME, COURSE, SEMESTER, MON_YEAR
    FROM examsmaster
    ORDER BY EXID DESC
");

/* ---------------- INPUTS ---------------- */
$examid  = isset($_GET['exid']) ? (int)$_GET['exid'] : 0;
$cutoff  = isset($_GET['cutoff']) ? (float)$_GET['cutoff'] : 50;
if ($cutoff <= 0 || $cutoff > 100) {
    $cutoff = 50;
}

$rows        = [];
$examLabel   = "";
$totalWithResults = 0;

if ($examid > 0) {

    /* exam heading */
    $stmt = $conn->prepare("
        SELECT EXAMNAME, COURSE, SEMESTER, MON_YEAR
        FROM examsmaster WHERE EXID = ?
    ");
    $stmt->bind_param("i", $examid);
    $stmt->execute();
    $ex = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($ex) {
        $examLabel = $ex['EXAMNAME'] . " — " . $ex['COURSE']
                   . " / Sem " . $ex['SEMESTER'] . " / " . $ex['MON_YEAR'];
    }

    /* how many students have results for this exam (denominator for the summary) */
    $stmt = $conn->prepare("
        SELECT COUNT(DISTINCT HALLTICKET) AS c FROM RESULTS WHERE EXAMID = ?
    ");
    $stmt->bind_param("i", $examid);
    $stmt->execute();
    $totalWithResults = (int)($stmt->get_result()->fetch_assoc()['c'] ?? 0);
    $stmt->close();

    /* ------------------------------------------------------------
       Detained students.

       Inner query  : collapse RESULTS to one row per student+paper
                      across ALL exams, so a paper reattempted N
                      times contributes its credits only once.
       Outer query  : total the per-paper credits and compare the
                      secured share against the cut-off.
       ------------------------------------------------------------ */
    $sql = "
        SELECT
            t.HALLTICKET                                              AS HALLTICKET,
            s.sname                                                   AS SNAME,
            s.course                                                  AS COURSE,
            s.`group`                                                 AS SGROUP,
            s.medium                                                  AS MEDIUM,
            s.phone                                                   AS PHONE,
            COUNT(*)                                                  AS PAPERS_APPEARED,
            SUM(t.ATTEMPTS)                                           AS ATTEMPTS,
            SUM(t.PCREDITS)                                           AS TOTAL_CREDITS,
            SUM(CASE WHEN t.PASSED = 1 THEN t.PCREDITS ELSE 0 END)    AS EARNED_CREDITS,
            SUM(CASE WHEN t.PASSED = 0 THEN 1 ELSE 0 END)             AS PAPERS_PENDING,
            ROUND(
                SUM(CASE WHEN t.PASSED = 1 THEN t.PCREDITS ELSE 0 END) * 100
                / SUM(t.PCREDITS), 2
            )                                                         AS CREDIT_PCT
        FROM (
            SELECT
                r.HALLTICKET                                          AS HALLTICKET,
                r.PAPERCODE                                           AS PAPERCODE,
                MAX(COALESCE(r.CREDITS, 0))                           AS PCREDITS,
                MAX(CASE WHEN r.RESULT = 'P' THEN 1 ELSE 0 END)       AS PASSED,
                COUNT(*)                                              AS ATTEMPTS
            FROM RESULTS r
            INNER JOIN (
                SELECT DISTINCT HALLTICKET
                FROM RESULTS
                WHERE EXAMID = ?
            ) c ON c.HALLTICKET = r.HALLTICKET
            WHERE r.PAPERCODE IS NOT NULL
              AND r.PAPERCODE <> ''
            GROUP BY r.HALLTICKET, r.PAPERCODE
        ) t
        LEFT JOIN students s ON s.haltckt = t.HALLTICKET
        GROUP BY t.HALLTICKET, s.sname, s.course, s.`group`, s.medium, s.phone
        HAVING SUM(t.PCREDITS) > 0
           AND (SUM(CASE WHEN t.PASSED = 1 THEN t.PCREDITS ELSE 0 END) * 100
                / SUM(t.PCREDITS)) < ?
        ORDER BY CREDIT_PCT ASC, t.HALLTICKET ASC
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $examid, $cutoff);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
    $stmt->close();
}
?>

<style>
.detained-pct-zero { color:#b71c1c; font-weight:600; }
.detained-summary .card-body { padding:14px 18px; }
</style>

<div class="page-wrapper">

    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-danger">Detained List</h4>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Detained List</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">

        <!-- ---------------- FILTER ---------------- -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="detainedlist.php" class="form-row align-items-end">

                            <div class="col-md-6 mb-2">
                                <label>Exam</label>
                                <select class="form-control" name="exid" required>
                                    <option value="">-- Select Exam --</option>
                                    <?php while ($e = $exams->fetch_assoc()): ?>
                                        <option value="<?= (int)$e['EXID'] ?>"
                                            <?= ($examid === (int)$e['EXID']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars(
                                                    $e['EXAMNAME'] . ' — ' . $e['COURSE']
                                                    . ' / Sem ' . $e['SEMESTER']
                                                    . ' / ' . $e['MON_YEAR']
                                                ) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label>Credits secured below (%)</label>
                                <input type="number" class="form-control" name="cutoff"
                                       min="1" max="100" step="0.01"
                                       value="<?= htmlspecialchars($cutoff) ?>">
                            </div>

                            <div class="col-md-3 mb-2">
                                <button type="submit" class="btn btn-danger">Show Detained</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($examid > 0): ?>

            <!-- ---------------- SUMMARY ---------------- -->
            <div class="row detained-summary">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="m-b-0"><?= htmlspecialchars($examLabel) ?></h5>
                            <p class="m-b-0">
                                Students who appeared in this exam:
                                <strong><?= $totalWithResults ?></strong>
                                &nbsp;|&nbsp;
                                Detained (below <?= htmlspecialchars($cutoff) ?>% credits):
                                <strong class="text-danger"><?= count($rows) ?></strong>
                            </p>
                            <p class="text-muted m-b-0" style="font-size:12px;">
                                Credits are counted over <strong>every paper the student has
                                appeared in</strong> across all exams — not only this exam.
                                Each paper counts its credits once regardless of the number of
                                attempts, and a paper passed on any attempt earns its credits.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ---------------- TABLE ---------------- -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example23"
                                       class="display nowrap table table-hover table-striped table-bordered"
                                       cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Hall Ticket</th>
                                            <th>Student Name</th>
                                            <th>Course</th>
                                            <th>Group</th>
                                            <th>Medium</th>
                                            <th>Papers Appeared</th>
                                            <th>Attempts</th>
                                            <th>Credits Appeared</th>
                                            <th>Credits Secured</th>
                                            <th>% Credits</th>
                                            <th>Papers Pending</th>
                                            <th>Phone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach ($rows as $r): ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= htmlspecialchars($r['HALLTICKET']) ?></td>
                                                <td><?= htmlspecialchars($r['SNAME'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($r['COURSE'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($r['SGROUP'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($r['MEDIUM'] ?? '') ?></td>
                                                <td><?= (int)$r['PAPERS_APPEARED'] ?></td>
                                                <td><?= (int)$r['ATTEMPTS'] ?></td>
                                                <td><?= (int)$r['TOTAL_CREDITS'] ?></td>
                                                <td><?= (int)$r['EARNED_CREDITS'] ?></td>
                                                <td class="<?= ((float)$r['CREDIT_PCT'] == 0) ? 'detained-pct-zero' : '' ?>">
                                                    <?= htmlspecialchars($r['CREDIT_PCT']) ?>%
                                                </td>
                                                <td><?= (int)$r['PAPERS_PENDING'] ?></td>
                                                <td><?= htmlspecialchars($r['PHONE'] ?? '') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if (empty($rows)): ?>
                                <p class="text-success m-t-20">
                                    No detained students for this exam at the
                                    <?= htmlspecialchars($cutoff) ?>% cut-off.
                                </p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php
mysqli_close($conn);
include "datatablefooter.php";
?>
