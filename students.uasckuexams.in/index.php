<?php
$error = '';
session_start();
if (isset($_GET['accountcreated'])) {
    echo '<script>alert("Registration Successfull Please login with Date of birth and hallticket Number");</script>';
}

if (isset($_GET['loggedout']) && $_GET['loggedout'] == true) {
    session_destroy();
    $_SESSION = array();
    $_COOKIE = array();
}

if (isset($_POST['submit'])) {
    if (empty($_POST['haltckt'])) {
        $error = "Hallticket or Date of birth is invalid";
    } else {
        $haltckt = $_POST['haltckt'];
        $dob = $_POST['dob'];
        $dostid = $_POST['dostid'];
        $error = '';

        include 'config.php';
        $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

        $haltckt = stripslashes($haltckt);
        $dob = stripslashes($dob);
        $haltckt = mysqli_real_escape_string($conn, $haltckt);
        $dob = mysqli_real_escape_string($conn, $dob);

        $query = "select * from students where haltckt='$haltckt' AND (dob='$dob' OR dostid = '$dostid')";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $id = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                setcookie("userfilename", $row["imgurl"], time() + 3600);
                $id = $row['id'];
                setcookie("aadhar", $row['aadhar']);
                setcookie("name", $row["sname"]);
                setcookie("stid", $row['stid']);
                $scheme = $row['SCHEME'] ?? null;
            }
            $_SESSION['login'] = $haltckt;
            setcookie("userid", $_POST['haltckt'], time() + 3600);
            $_SESSION['hallticket'] = $haltckt;
            $_SESSION['aadhar'] = $aadhar;
            mysqli_close($conn);

            // Scheme-2025+ students: redirect to new portal via signed SSO token
            if (in_array($scheme, ['2025', '2026'], true)) {
                include 'sso_config.php';
                $ts  = time();
                $sig = hash_hmac('sha256', $haltckt . '|' . $ts, $sso_secret);
                $url = $sso_target . '?' . http_build_query([
                    'ht'  => $haltckt,
                    'ts'  => $ts,
                    'sig' => $sig,
                ]);
                header('Location: ' . $url);
                exit;
            }
            header("location:welcome.php");
        } else {
            $error = "Hallticket or Date of birth is invalid";
            $_SESSION = array();
            session_destroy();
        }
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login — UASC Exams</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/arts.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;0,9..144,700;1,9..144,400&family=Nunito:wght@400;500;600;700&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <style>
        :root { --navy:#162B3E; --navy2:#1E3A52; --amber:#D4912E; }
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }

        body {
            font-family:'Nunito', sans-serif;
            background:#FAFAF8;
            min-height:100vh;
            display:flex;
            -webkit-font-smoothing:antialiased;
        }

        /* ── Brand panel ── */
        .brand-panel {
            width:42%;
            background:var(--navy);
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            padding:48px 44px;
            position:relative;
            overflow:hidden;
            flex-shrink:0;
        }
        .brand-panel::before {
            content:'';
            position:absolute; right:-80px; top:-80px;
            width:320px; height:320px; border-radius:50%;
            border:60px solid rgba(212,145,46,.15);
        }
        .brand-panel::after {
            content:'';
            position:absolute; left:-60px; bottom:-60px;
            width:240px; height:240px; border-radius:50%;
            border:40px solid rgba(255,255,255,.06);
        }
        .brand-inner { position:relative; z-index:1; }

        .brand-logo {
            height:60px; object-fit:contain; object-position:left;
            margin-bottom:40px;
            filter:brightness(0) invert(1); opacity:.9;
        }

        .brand-badge {
            display:inline-flex; align-items:center; gap:7px;
            background:rgba(212,145,46,.2); color:var(--amber);
            padding:5px 12px; border-radius:99px;
            font-size:11px; font-weight:700; letter-spacing:.8px;
            text-transform:uppercase; margin-bottom:16px;
        }
        .brand-badge-dot {
            width:6px; height:6px; background:var(--amber); border-radius:50%;
        }

        .brand-heading {
            font-family:'Fraunces', serif;
            color:#fff; font-size:34px; line-height:1.2;
            font-weight:600; margin-bottom:16px;
        }
        .brand-heading em { color:var(--amber); font-style:italic; }

        .brand-desc {
            color:rgba(255,255,255,.5);
            font-size:14px; line-height:1.7; max-width:280px;
        }

        .brand-footer {
            position:relative; z-index:1;
            border-top:1px solid rgba(255,255,255,.1);
            padding-top:20px;
        }
        .brand-footer p {
            color:rgba(255,255,255,.35); font-size:12px;
        }

        /* ── Form panel ── */
        .form-panel {
            flex:1;
            display:flex; align-items:center; justify-content:center;
            padding:32px 20px;
        }

        .form-card {
            width:100%; max-width:380px;
            animation:fadeUp .4s ease both;
        }

        @keyframes fadeUp {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Form logo (always visible in right pane) */
        .form-logo {
            text-align:center;
            margin-bottom:32px;
        }
        .form-logo img { height:64px; object-fit:contain; margin:0 auto 10px; display:block; }
        .form-logo p { font-size:12px; color:#8A9AB0; font-weight:600; letter-spacing:.5px; text-transform:uppercase; }

        .form-heading { margin-bottom:28px; }
        .form-heading h2 {
            font-family:'Fraunces', serif;
            font-size:26px; font-weight:600; color:#162B3E; margin-bottom:6px;
        }
        .form-heading p { font-size:14px; color:#8A9AB0; }

        /* Error banner */
        .error-banner {
            background:#FEF2F2;
            border:1px solid #FECACA;
            border-left:4px solid #EF4444;
            border-radius:8px;
            padding:12px 16px;
            color:#991B1B;
            font-size:14px;
            margin-bottom:20px;
        }

        /* Fields */
        .field { margin-bottom:16px; }
        .field:last-of-type { margin-bottom:24px; }

        .field label {
            display:block;
            font-size:13px; font-weight:700; color:#162B3E;
            margin-bottom:6px; letter-spacing:.2px;
        }
        .field label span {
            color:#A0AEC0; font-weight:400; font-size:12px;
        }
        .field-hint { font-size:12px; color:#A0AEC0; margin-top:5px; }

        .form-input {
            width:100%;
            border:1.5px solid #E2DDD6; border-radius:10px;
            padding:13px 16px; font-size:15px;
            font-family:'Nunito', sans-serif;
            background:#fff; color:#1C2B3A;
            outline:none;
            transition:border-color .15s, box-shadow .15s;
        }
        .form-input:focus {
            border-color:var(--navy);
            box-shadow:0 0 0 3px rgba(22,43,62,.1);
        }
        .mono { font-family:'JetBrains Mono', monospace; letter-spacing:.06em; }

        .btn-login {
            width:100%; background:var(--navy); color:#fff;
            padding:14px; border-radius:10px;
            font-size:15px; font-weight:700;
            font-family:'Nunito', sans-serif;
            border:none; cursor:pointer;
            transition:background .15s;
            letter-spacing:.2px;
        }
        .btn-login:hover { background:var(--navy2); }

        /* Responsive */
        @media (max-width:768px) {
            .brand-panel  { display:none; }
            .mobile-logo  { display:block; }
        }
    </style>
</head>
<body>

    <!-- Brand panel -->
    <div class="brand-panel">
        <div class="brand-inner">
            <div class="brand-badge">
                <span class="brand-badge-dot"></span>
                Student Portal
            </div>

            <h1 class="brand-heading">
                Examination<br>Management<br><em>System</em>
            </h1>
            <p class="brand-desc">
                View enrollment records, exam results, and apply for revaluation.
            </p>
        </div>

        <div class="brand-footer">
            <p>University Arts, Science &amp; Commerce &middot; Examination Branch &middot; <?php echo date('Y'); ?></p>
        </div>
    </div>

    <!-- Form panel -->
    <div class="form-panel">
        <div class="form-card">

            <!-- University logo -->
            <div class="form-logo">
                <img src="https://uasckuexams.in/wp-content/uploads/2021/11/cropped-cropped-cropped-uascku-header-png-1-1.png"
                     alt="UASC KU">
                <p>Student Portal</p>
            </div>

            <div class="form-heading">
                <h2>Sign in</h2>
                <p>Enter your hall ticket number to continue</p>
            </div>

            <?php if ($error != ''): ?>
            <div class="error-banner"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php" novalidate>

                <div class="field">
                    <label>Hall Ticket Number</label>
                    <input type="text" name="haltckt" class="form-input mono"
                           placeholder="e.g. 1234567890"
                           value="<?php echo isset($_POST['haltckt']) ? htmlspecialchars($_POST['haltckt']) : ''; ?>"
                           autocomplete="username" required>
                </div>

                <div class="field">
                    <label>Date of Birth</label>
                    <input type="date" name="dob" class="form-input"
                           value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : ''; ?>">
                    <p class="field-hint">2023 batch? Use your DOST ID instead &darr;</p>
                </div>

                <div class="field" style="margin-bottom:24px;">
                    <label>DOST ID <span>(alternative)</span></label>
                    <input type="text" name="dostid" class="form-input mono"
                           placeholder="DOST / TS number"
                           value="<?php echo isset($_POST['dostid']) ? htmlspecialchars($_POST['dostid']) : ''; ?>">
                </div>

                <button type="submit" name="submit" class="btn-login">Sign In &rarr;</button>

            </form>

        </div>
    </div>

</body>
</html>
