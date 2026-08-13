<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>University Arts & Science College Exam Result</title>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-131292962-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-131292962-2');
        // Assuming this is a valid userId value set by PHP (Ensure PHP code is running in the right environment)
        gtag('set', 'userId', '<?php echo htmlspecialchars($_COOKIE['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>');
    </script>
    <style>
        table {
            border-style: solid;
            border-collapse: collapse;
            width: 80%;
            margin: auto;
        }
        tr, td {
            border: solid 1px black;
        }
        td {
            padding: 0.25rem;
            text-align: center;
        }
        img {
            position: absolute;
            left: 0;
            right: 0;
            margin: 0 auto;
            display: block;
            max-width: 100%;
        }
        .topnav {
            background-color: #33b5e5;
            text-align: center;
        }
        .topnav a {
            padding: 1em;
            display: inline-block;
            text-decoration: none;
            color: #fff;
            width: 50px;
        }
        .text-info {
            color: red;
        }
        .watermark {
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1000;
        }
    </style>
</head>
<body>
    <?php
    include "config.php";

    // Check connection
    $conn = new mysqli($servername, $dbuser, $dbpwd, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve POST data and sanitize it
    $hallticket = $_POST['hallticket'] ?? '';
    $enrollmentid = $_POST['enrollmentid'] ?? '';
    $examtype = $_POST['examtype'] ?? '';

    // Validate POST data
    if (empty($hallticket) || empty($enrollmentid)) {
        die("Required data not provided.");
    }

    // Using prepared statement for the first query
    $stmt = $conn->prepare("SELECT * FROM examenrollments 
                            LEFT JOIN students ON examenrollments.HALLTICKET = students.haltckt
                            LEFT JOIN examsmaster ON examenrollments.EXAMID = examsmaster.EXID 
                            WHERE examenrollments.HALLTICKET = ? 
                            AND examenrollments.ID = ?");
    $stmt->bind_param("si", $hallticket, $enrollmentid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("No enrollment found for this hall ticket and enrollment ID.");
    }

    // Using prepared statement for the second query (results)
    $stmt = $conn->prepare("SELECT * FROM RESULTS WHERE EID = ?");
    $stmt->bind_param("i", $enrollmentid);
    $stmt->execute();
    $result = $stmt->get_result();

    // Query for GPA
    $stmt = $conn->prepare("SELECT * FROM gpas WHERE EXAMID = ? AND HALLTICKET = ?");
    $stmt->bind_param("is", $row['EXAMID'], $hallticket);
    $stmt->execute();
    $gpares = $stmt->get_result();

    $gpa = $gpares->num_rows > 0 ? $gpares->fetch_assoc() : null;
    ?>

    <img src="images/Logo1.jpg" alt="University Arts & Science College" style="width:550px">
    <br><br><br><br><br>
    <div class="topnav">
        <a href="#">Home</a>
    </div>

    <table>
        <tr>
            <td colspan="4">
                <h3 style='color:#CC3300'><?php echo htmlspecialchars($row['EXAMNAME'], ENT_QUOTES, 'UTF-8'); ?></h3>
            </td>
        </tr>
        <tr>
            <td colspan='4'>
                Student Name: <strong><?php echo htmlspecialchars($row['sname'], ENT_QUOTES, 'UTF-8'); ?></strong><br>
                Hallticket No: <strong><?php echo htmlspecialchars($row['haltckt'], ENT_QUOTES, 'UTF-8'); ?></strong><br>
                Group: <strong><?php echo htmlspecialchars($row['group'], ENT_QUOTES, 'UTF-8'); ?></strong>
            </td>
        </tr>
        <tr>
            <td bgcolor="#33b5e5">PAPERCODE</td>
            <td bgcolor="#33b5e5">PAPERNAME</td>
            <td bgcolor="#33b5e5">CREDITS</td>
            <td bgcolor="#33b5e5">GRADE</td>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while ($paper = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($paper['PAPERCODE'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>" . strtoupper(htmlspecialchars($paper['PAPERNAME'], ENT_QUOTES, 'UTF-8')) . "</td>";
                echo "<td>" . htmlspecialchars($paper['CREDITS'], ENT_QUOTES, 'UTF-8') . "</td>";
                echo "<td>";
                if ($gpa && $gpa['RESULT'] == 'M') {
                    echo "MP";
                } else {
                    echo htmlspecialchars($paper['GRADE'], ENT_QUOTES, 'UTF-8');
                }
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>

        <tr>
            <td colspan='2'><strong>FINAL RESULT</strong></td>
            <td colspan='2'>
                <?php
                if ($gpa) {
                    if ($gpa['RESULT'] == 'P') {
                        echo "PASSED";
                    } elseif ($gpa['RESULT'] == 'R') {
                        echo "PROMOTED";
                    } elseif ($gpa['RESULT'] == 'M') {
                        echo "MALPRACTICE";
                    } else {
                        echo "";
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan='2'><strong>SGPA</strong></td>
            <td colspan='2'>
                <?php 
                if ($gpa && ($gpa['RESULT'] != 'M' && $gpa['RESULT'] != 'R')) {
                    echo htmlspecialchars($gpa['SGPA'], ENT_QUOTES, 'UTF-8');
                }
                ?>
            </td>
        </tr>

        <tr>
            <td align="center" colspan='4'><button onclick="myFunction()"><strong>Print</strong></button></td>
        </tr>
    </table>

    <script>
        function myFunction() {
            window.print();
        }
    </script>

    <br>
    <div class='tab1'>
        <h3 align="center" style='color:#CC3300'>
            O: &gt;=85 to 100; A: &gt;=70 to &lt;85; B: &gt;=60 to &lt;70; C: &gt;=55 to &lt;60; D: &gt;=50 to &lt;55; E: &gt;=40 to &lt;50; F: FAIL.
        </h3>
        <h4 align="center">
            This information is provided to the candidate on his/her online request and is only a prototype list.<br>
            If there are any discrepancies in the marks, they should be brought to the notice of the Controller of Examinations, University Arts & Science College - Subedari-Warangal.
        </h4>
    </div>

    <h4 align="left" style="background-color:#33b5e5; width:30%; color:#FFFFFF;">&nbsp;&nbsp;© Copyrights Reserved. 2018. All rights reserved @ SYS Technology</h4>

    <div class="watermark">
        <img src="http://uascku.ac.in/kulogo.jpg" style="opacity:0.2">
    </div>
</body>
</html>
