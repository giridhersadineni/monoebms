<?php
include "header.php";
include "config.php";

/* ================================================================
   DETAINED LIST
   Covers EVERY student with results, over EVERY paper they have
   appeared in (all exams in RESULTS). There is no exam selection --
   detention is judged on the student's whole record.
   Each distinct PAPERCODE counts its credits ONCE, however many
   attempts it took; a paper passed on any attempt earns its credits.
   Detained when credits secured < cut-off % of credits appeared.
   ================================================================ */

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if (!$conn) {
    die("Database connection failed");
}

/* ---------------- FILTER OPTIONS ---------------- */
$courseOpts = [];
$res = $conn->query("SELECT DISTINCT course FROM students
                     WHERE course <> '' ORDER BY course");
while ($r = $res->fetch_assoc()) { $courseOpts[] = $r['course']; }

$schemeOpts = [];
$res = $conn->query("SELECT DISTINCT SCHEME FROM students
                     WHERE SCHEME <> '' ORDER BY SCHEME");
while ($r = $res->fetch_assoc()) { $schemeOpts[] = $r['SCHEME']; }

/* ---------------- INPUTS ---------------- */
$cutoff = isset($_GET['cutoff']) ? (float)$_GET['cutoff'] : 50;
if ($cutoff <= 0 || $cutoff > 100) {
    $cutoff = 50;
}

$course = isset($_GET['course']) ? trim($_GET['course']) : '';
$scheme = isset($_GET['scheme']) ? trim($_GET['scheme']) : '';
if ($course !== '' && !in_array($course, $courseOpts, true)) { $course = ''; }
if ($scheme !== '' && !in_array($scheme, $schemeOpts, true)) { $scheme = ''; }

/* unmatched hall tickets (no students row) are included unless excluded */
$includeUnmatched = !isset($_GET['go']) || isset($_GET['unmatched']);

/* ------------------------------------------------------------
   Inner query  : collapse RESULTS to one row per student+paper
                  across all exams, so a paper reattempted N
                  times contributes its credits only once.
   Outer query  : total the per-paper credits and compare the
                  secured share against the cut-off.
   A course/scheme filter, or excluding unmatched hall tickets,
   requires a students row -- pushed into the inner query so the
   aggregate only walks the rows it needs.
   ------------------------------------------------------------ */
$needStudent = ($course !== '' || $scheme !== '' || !$includeUnmatched);

$innerJoin  = "";
$innerWhere = "";
$types      = "";
$params     = [];

if ($needStudent) {
    $innerJoin = "INNER JOIN students sf ON sf.haltckt = r.HALLTICKET";
    if ($course !== '') {
        $innerWhere .= " AND sf.course = ?";
        $types .= "s";
        $params[] = $course;
    }
    if ($scheme !== '') {
        $innerWhere .= " AND sf.SCHEME = ?";
        $types .= "s";
        $params[] = $scheme;
    }
}

$sql = "
    SELECT
        t.HALLTICKET                                              AS HALLTICKET,
        s.sname                                                   AS SNAME,
        s.course                                                  AS COURSE,
        s.`group`                                                 AS SGROUP,
        s.medium                                                  AS MEDIUM,
        s.SCHEME                                                  AS SCHEME,
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
            MAX(COALESCE(r.CREDITS, 0))                            AS PCREDITS,
            MAX(CASE WHEN r.RESULT = 'P' THEN 1 ELSE 0 END)        AS PASSED,
            COUNT(*)                                              AS ATTEMPTS
        FROM RESULTS r
        $innerJoin
        WHERE r.PAPERCODE IS NOT NULL
          AND r.PAPERCODE <> ''
          $innerWhere
        GROUP BY r.HALLTICKET, r.PAPERCODE
    ) t
    LEFT JOIN students s ON s.haltckt = t.HALLTICKET
    GROUP BY t.HALLTICKET, s.sname, s.course, s.`group`, s.medium, s.SCHEME, s.phone
    HAVING SUM(t.PCREDITS) > 0
       AND (SUM(CASE WHEN t.PASSED = 1 THEN t.PCREDITS ELSE 0 END) * 100
            / SUM(t.PCREDITS)) < ?
    ORDER BY CREDIT_PCT ASC, t.HALLTICKET ASC
";

$types   .= "d";
$params[] = $cutoff;

$rows        = [];
$unmatched   = 0;
$queryMs     = 0;
$queryFailed = false;

$t0   = microtime(true);
$stmt = $conn->prepare($sql);
if (!$stmt) {
    $queryFailed = true;
} else {
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
        if ($row['SNAME'] === null) { $unmatched++; }
    }
    $stmt->close();
}
$queryMs = (int)round((microtime(true) - $t0) * 1000);

/* population the list was drawn from, for context in the summary */
$evaluated = 0;
if (!$needStudent) {
    $q = $conn->query("SELECT COUNT(DISTINCT HALLTICKET) c FROM RESULTS
                       WHERE PAPERCODE IS NOT NULL AND PAPERCODE <> ''");
    $evaluated = (int)($q->fetch_assoc()['c'] ?? 0);
}
?>

<style>
.detained-pct-zero { color:#b71c1c; font-weight:600; }
.detained-unmatched td { background-color:#fff8e1; }
.detained-note { font-size:12px; }
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

        <!-- ---------------- FILTERS ---------------- -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="detainedlist.php" class="form-row align-items-end">
                            <input type="hidden" name="go" value="1">

                            <div class="col-md-3 mb-2">
                                <label>Credits secured below (%)</label>
                                <input type="number" class="form-control" name="cutoff"
                                       min="1" max="100" step="0.01"
                                       value="<?= htmlspecialchars($cutoff) ?>">
                            </div>

                            <div class="col-md-3 mb-2">
                                <label>Course</label>
                                <select class="form-control" name="course">
                                    <option value="">All Courses</option>
                                    <?php foreach ($courseOpts as $c): ?>
                                        <option value="<?= htmlspecialchars($c) ?>"
                                            <?= ($course === $c) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($c) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2 mb-2">
                                <label>Scheme</label>
                                <select class="form-control" name="scheme">
                                    <option value="">All</option>
                                    <?php foreach ($schemeOpts as $sc): ?>
                                        <option value="<?= htmlspecialchars($sc) ?>"
                                            <?= ($scheme === $sc) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($sc) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2 mb-2">
                                <label class="detained-note d-block">Unmatched hall tickets</label>
                                <label class="detained-note">
                                    <input type="checkbox" name="unmatched" value="1"
                                        <?= $includeUnmatched ? 'checked' : '' ?>>
                                    Include (no student record)
                                </label>
                            </div>

                            <div class="col-md-2 mb-2">
                                <button type="submit" class="btn btn-danger">Refresh List</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ---------------- SUMMARY ---------------- -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <?php if ($queryFailed): ?>
                            <h5 class="text-danger m-b-0">Could not build the list — query error.</h5>
                        <?php else: ?>
                            <p class="m-b-0">
                                Detained (below <?= htmlspecialchars($cutoff) ?>% credits):
                                <strong class="text-danger"><?= count($rows) ?></strong>
                                <?php if ($evaluated): ?>
                                    &nbsp;of&nbsp;<strong><?= $evaluated ?></strong> students with results
                                <?php endif; ?>
                                <?php if ($course !== '' || $scheme !== ''): ?>
                                    &nbsp;|&nbsp;Filter:
                                    <strong><?= htmlspecialchars($course !== '' ? $course : 'All courses') ?></strong>
                                    <?php if ($scheme !== ''): ?>
                                        / Scheme <strong><?= htmlspecialchars($scheme) ?></strong>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <span class="text-muted detained-note">
                                    &nbsp;(<?= $queryMs ?> ms)
                                </span>
                            </p>
                            <p class="text-muted m-b-0 detained-note">
                                Credits are counted over <strong>every paper the student has
                                appeared in</strong> across all exams. Each paper counts its credits
                                once regardless of the number of attempts, and a paper passed on any
                                attempt earns its credits.
                            </p>
                            <?php if ($unmatched > 0): ?>
                                <p class="text-warning m-b-0 detained-note">
                                    <strong><?= $unmatched ?></strong> row(s) have no matching student
                                    record (highlighted below) — these are stray hall tickets in
                                    RESULTS. Untick &ldquo;Include&rdquo; above to leave them out.
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>
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
                                        <th>Scheme</th>
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
                                        <tr class="<?= ($r['SNAME'] === null) ? 'detained-unmatched' : '' ?>">
                                            <td><?= $i++ ?></td>
                                            <td><?= htmlspecialchars($r['HALLTICKET']) ?></td>
                                            <td><?= htmlspecialchars($r['SNAME'] ?? '— no student record —') ?></td>
                                            <td><?= htmlspecialchars($r['COURSE'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($r['SGROUP'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($r['MEDIUM'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($r['SCHEME'] ?? '') ?></td>
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

                        <?php if (!$queryFailed && empty($rows)): ?>
                            <p class="text-success m-t-20">
                                No detained students at the
                                <?= htmlspecialchars($cutoff) ?>% cut-off for this filter.
                            </p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
mysqli_close($conn);
include "datatablefooter.php";
?>
