# CLAUDE.md — `students.uasckuexams.in/`

Guidance for AI coding agents working inside this directory. The repo-root `CLAUDE.md` governs the
monorepo; this file governs **this legacy app only** and takes precedence for files under this path.

## What this app actually is

The **student-facing** exam portal.

Login is hall ticket + date of birth (or DOST ID for the 2023 batch) against the `students` table
(`index.php:64`). Every page is written from the logged-in student's point of view: apply for an exam,
generate a challan, upload a payment receipt, print a hall ticket, view a result.

The staff/admin pages (add students, enter marks, tabulation, BOS reports, consolidated memos) live in
the sibling `backoffice.uasckuexams.in/` directory. **Check which app you are in before adding a page.**
A staff-only list (detained candidates, nominal rolls, shortage lists) does not belong here unless it
is a view the *student* is meant to see about *themselves*.

> Until 2026-08-11 this app's files sat in the `backoffice.uasckuexams.in/` directory and the admin
> app's files sat here — the two checkouts had been placed in each other's folders. They were swapped
> so each directory now matches the hostname it deploys to. Commits before that date have the paths
> reversed; keep it in mind when reading `git log` or blame across the boundary.

Status: **maintenance mode.** Keep it stable; net-new features belong in `apps/ebms-platform/`. Bug
fixes, security fixes, and small operational pages the exam branch needs *right now* are acceptable —
follow the rules below when you write them.

## Stack & structure

- Plain PHP (procedural), no framework, no router, no Composer autoload, no build step.
- One file per URL, flat in the directory root. `~86` PHP files at root.
- Bootstrap 4 + jQuery + DataTables + FontAwesome (CDN) on a purchased "SYS Technology" admin theme.
- `lib/tcpdf/` — vendored TCPDF, used only by `getresult.php` at app level.
- URLs are literal filenames: `enrollments.php`, `selectexam.php`. Links are relative, hand-written.

```
config.php              DB credentials from env
functions.php           includes config.php; a few CGPA helpers (uses `global` for the connection vars)
header.php              <head> + navbar + left sidebar; OPENS #main-wrapper and .left-sidebar divs
footer.php              closes wrapper + loads jQuery/Bootstrap
datatablefooter.php     same as footer.php + DataTables init — use this on any page with a table
api/                    GET-param → JSON endpoints (course/ exam/ result/ student/ transactions/)
regularenrollments/     older duplicate of the root enrollment pages — see "Dead code" below
css/ js/ icons/ images/ lib/
```

## The page recipe

Every page follows this shape. Match it — consistency here matters more than modernizing one file:

```php
<?php include "header.php"; ?>
<?php
include "config.php";
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
}
// ... query into $result ...
?>
<div class="page-wrapper">
  <div class="row page-titles">
    <div class="col-md-5 align-self-center"><h3 class="text-primary">Page Title</h3></div>
    <div class="col-md-7 align-self-center">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Student</a></li>
        <li class="breadcrumb-item active">Page Title</li>
      </ol>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row"><!-- Bootstrap cards, one per row of data --></div>
  </div>
</div>
<?php mysqli_close($conn); ?>
<?php include "datatablefooter.php"; ?>
```

Notes:
- `header.php` opens `<div id="main-wrapper">` and the sidebar; the footer includes close them. Always
  include both — omitting the footer breaks the layout silently.
- Each page opens its **own** `mysqli` connection. There is no shared connection or connection pool.
- Adding a page to the sidebar means editing the `<ul id="sidebarnav">` block in `header.php` (~line 214).

## Data model (legacy `uascexams_ebms`)

`config.php` reads `EBMS_DB_HOST`, `EBMS_DB_USER`, `EBMS_DB_PASSWORD`, `EBMS_DB_NAME`
(default `uascexams_ebms`). `api/config.php`, `api/course/config.php`, and `api/exam/config.php` are
duplicated copies of the same file — change all of them together.

| Table | Role |
|---|---|
| `students` | Identity + demographics. Key is `haltckt` (hall ticket); `stid` is the numeric student id. `onboarding_complete=1` gates exam application. |
| `examsmaster` | Exam definitions. PK `EXID`. Drives the whole lifecycle via `STATUS`. |
| `examenrollments` | One row per student per exam. PK `ID`, FK `EXAMID` → `examsmaster.EXID`. |
| `revaluationenrollments` | Same shape, for revaluation applications. |
| `rholdernew` | Result holder — paper-wise marks, `GRADE`, `CREDITS`, `GPC`, `PART`, `RESULT`. The source for every results page. |
| `gpas`, `grades` | SGPA / CGPA and division rollups. |
| `enrolledview` | A DB view joining enrollment + student + exam; used by the challan / hall-ticket / print pages. |
| `transactions` | Payment records written by `updatepaymentdetails.php`. |
| `allpapers`, `subs5sem` | Paper/subject catalogues used to build the enrollment forms. |

`database/schema.sql` at the repo root is **partial** — `rholdernew`, `enrolledview`, `transactions`,
and `subs5sem` are not in it. Inspect the live DB when you need their columns.

### Two schema quirks that shape every query

1. **Subjects are flat columns, not rows.** `examenrollments` stores selected papers in
   `S1..S10` (core) and `E1..E5` (electives), all `varchar`. There is no enrollment-subject table.
   Any "which papers did this student take" logic has to walk those 15 columns.
2. **Booleans are strings.** `FEEPAID` and `CHALLANGENERATED` are `varchar(4)` holding `'0'` / `'1'` /
   `''`. Existing code compares loosely (`$row["FEEPAID"] == '1'`, and treats `''`, `'0'`, `'1'` as
   distinct cases in `enrollments.php:80`). Don't assume `NULL` or a real boolean.

### `examsmaster.STATUS` lifecycle

Observed values, and what the UI does at each:

| STATUS | Student sees |
|---|---|
| `NOTIFY` | Exam listed on `selectexam.php` for application; challan + print application available |
| `NOTIFYTEST` | Same, but only via `selectexam.php?test=1` — staging/preview of a notification |
| `OPEN` | Challan + print application + update payment details |
| `RUNNING` | Print hall ticket (only when `FEEPAID='1'`) |
| `CLOSED` | Get Result (gated on `EXAMID > 298`); revaluation link when `REVALOPEN=1` |
| `HIDDEN` | Same branch as `CLOSED` but without the result button |

The `EXAMID > 298` guard in `enrollments.php:62` is a hard-coded cutover marker for "exams new enough
to have results in the new result holder". Leave it alone unless the exam branch asks.

### Fee resolution

Fees are per-course columns on `examsmaster` (`BA_FEE`, `BCOM_FEE`, `BSC_FEE`, `BCOMCA_FEE`,
`BAHONS_FEE`, the matching `*_ABOVE_2` variants, plus `FEE`, `ABOVE2SUBS`, `IMPROVEMENT`, `FINE`).
A `getExamFee($student, $exam)` function resolves course + group → column, and is **copy-pasted** into
each enrollment page rather than living in `functions.php`.

Known bug, left in place deliberately: `examenrollregular.php:41` reads `if($student['course']="BA")`
— an assignment, not a comparison, so it is always true. It happens to be the last branch after BCOM
and BSC have already returned, so BA acts as the fallback and the output is correct today. Fixing it
changes `$student['course']` in place for the rest of the request. Don't touch it as a drive-by.

## Security reality — read before writing any page

This app predates the security controls the root `CLAUDE.md` requires. The current state:

- **Identity comes from a client-controlled cookie.** Only `index.php` and `updatestudent.php` touch
  `$_SESSION`. Twenty-three pages read `$_COOKIE['userid']` (the hall ticket) and query on it directly.
  Editing that cookie in the browser impersonates any student. `aadhar`, `name`, `stid`, and
  `userfilename` are also stored in plain cookies, and `header.php` builds the profile-photo path from
  `$_COOKIE['aadhar']`.
- **SQL is string-interpolated everywhere.** Only the login query in `index.php` escapes anything, via
  `mysqli_real_escape_string`. Every other page concatenates `$_GET` / `$_COOKIE` straight into SQL.
- **Output is unescaped.** No `htmlspecialchars` anywhere.
- **Debug output is left in production paths.** `api/exam/getexams.php` echoes the failing SQL to the
  client on the no-rows branch.
- `error_log` (1.8 MB) and `arts.sql` (a table dump) are committed to git in this directory.

**Rules for code you write here:**

1. Use prepared statements — `mysqli_prepare` + `bind_param` — for every new or edited query. Never
   interpolate a request value into SQL, even to match surrounding style.
2. Escape every echoed value with `htmlspecialchars($v, ENT_QUOTES, 'UTF-8')`.
3. Start any new page with a session guard, not a cookie read:
   ```php
   session_start();
   if (empty($_SESSION['login'])) { header('Location: index.php'); exit; }
   $hallticket = $_SESSION['login'];
   ```
   Take identity from `$_SESSION['login']`, never from `$_COOKIE['userid']`, and never from a `GET`
   parameter the client supplies.
4. Never add a page that returns another student's data keyed only on a request parameter. If a page
   genuinely needs to list many students, it belongs in the staff app behind a staff login — raise it
   rather than building it here.
5. No credentials in code. `config.php` + env vars only.
6. Don't "fix" SQLi across untouched files as a side quest. Harden what you touch; report the rest.

## Dead / duplicated code — don't extend these

- `regularenrollments/` — an older copy of `examenrollregular.php`, `examenrollsupply.php`,
  `supplychallan.php`, `regulartemplate.php`, `registrationsuccess.php`, `currentexams.php`. The root
  copies are the live ones.
- `index.php.up`, `newregistration.bakk`, `old_enrollments.php`, `oldenrollments_old.php`,
  `test_enrollments.php`, `imageexmple.php`, `regtemp.php`, `projectorm.php`, `table.php`, `form.php`,
  `form.html`, `feenotpaid.html`.
- `bankchallan.php` and `generatechallan.php` are byte-identical (13,239 bytes each);
  `revaluationchallan.php` / `getrevchallan.php` are near-identical. Fix both sides of a pair.
- `senddetails.php` / `formdetails.php` are duplicate registration handlers.

## `api/` endpoints

Pattern: read `$_GET`, query, `echo json_encode($rows)`. No auth, no content-type header, no error
contract. Used by the enrollment forms' inline JS.

`course/getcourses.php`, `course/getsubjects.php`, `course/getexamdetails.php`,
`course/getbcom3semsubs.php`, `course/getaadhar.php`, `exam/getexams.php`, `exam/getexamid.php`,
`exam/getintmarks.php`, `exam/getoldintmarks.php`, `result/getresult.php`, `result/getcredits.php`,
`result/getpassedsubjects.php`, `result/coursestatus.php`, `student/getstudentdetails.php`,
`student/updateaadhar.php`, `transactions/checktransactionnumber.php`.

## Main flows

| Flow | Entry → pages |
|---|---|
| Login | `index.php` → sets `$_SESSION['login']` + cookies → `welcome.php` |
| Apply for an exam | `selectexam.php` → `examenrollregular.php` \| `examenrollsupply.php` → `regulartemplate.php` \| `registrationsupply.php` (INSERT into `examenrollments`) |
| Pay | `enrollments.php` → `bankchallan.php` (challan PDF) → `updatepaymentdetails.php` (INSERT into `transactions`) |
| Hall ticket | `enrollments.php` → `printhallticket.php` (needs `STATUS='RUNNING'` + `FEEPAID='1'`) |
| Results | `results.php` → `printresult.php` \| `mainresult.php` \| `mainresultall.php` \| `gpcresult.php` |
| Revaluation | `enrollments.php` (`REVALOPEN=1`) → `applyrevaluation.php` → `processrevaluationapplication.php` → `revaluationchallan.php` |
| Profile | `editdetails.php`, `updatedetails.php`, `newimageupload.php`, `uploadphoto.php` |

## Testing & deployment

There is no test suite, no linter, and no build step for this app. Verify changes by loading the page
against a copy of the legacy DB and reading the rendered output.

Deployment is a plain file copy into the app's cPanel docroot — the path and credentials for *this*
app are not recorded in the repo (`deploy.env` and the `ebms-deploy` skill cover `ebms-platform` only).
Confirm the target with the repo owner before uploading, and never run the `ebms-platform` deploy
tooling against this directory.
