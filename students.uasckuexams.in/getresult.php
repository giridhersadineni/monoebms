<?php
require_once('lib/tcpdf/tcpdf.php');

include "config.php";

// Check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . mysqli_connect_error());
}

$res = "SELECT * FROM examenrollments 
        LEFT JOIN students ON examenrollments.HALLTICKET = students.haltckt
        LEFT JOIN examsmaster ON examenrollments.EXAMID = examsmaster.EXID 
        WHERE examenrollments.HALLTICKET ='" . $_POST['hallticket'] . "' AND examenrollments.ID=" . $_POST['enrollmentid'];

$result = $conn->query($res);
$examtype = "";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}

$hallticket = $_POST['hallticket'];
$examid = $row['EXAMID'];
$examtype = $_POST['examtype'];

$hallticket = "001172022";
$examid = "2";
$examtype = "REGULAR";

ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Arts & Science College Exam Result</title>
    <style>
        table {
            border-style: solid;
            border-collapse: collapse;
            width: 100%;
        }
        tr, td {
            border: solid 1px black;
            padding: 0.25rem;
        }
        .header img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
        .topnav {
            background-color: #33b5e5;
            overflow: hidden;
        }
        .topnav a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }
        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }
        .watermark {
            position: fixed;
            top: 30%;
            width: 100%;
            text-align: center;
            opacity: 0.2;
            z-index: -1;
        }
        h3 {
            color: #CC3300;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="images/Logo1.jpg" alt="University Arts & Science College">
    </div>
    <br><br><br>
    <div class="topnav">
        <a href="#"></a>
    </div>
    <table>
        <tr>
            <td colspan="4">
                <h3><?php echo $row['EXAMNAME']; ?></h3>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                Student Name: <strong><?php echo $row['sname']; ?></strong><br>
                Hallticket No: <strong><?php echo $row['haltckt']; ?></strong><br>
                Group: <strong><?php echo $row['group']; ?></strong>
            </td>
        </tr>
        <tr>
            <td bgcolor="#33b5e5">PAPERCODE</td>
            <td bgcolor="#33b5e5">PAPERNAME</td>
            <td bgcolor="#33b5e5">CREDITS</td>
            <td bgcolor="#33b5e5">GRADE</td>
        </tr>
        <?php
        $sql = "SELECT * FROM RESULTS WHERE EID='" . $_POST['enrollmentid'] . "'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($paper = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td style='text-align:center;'>" . $paper['PAPERCODE'] . "</td>";
                echo "<td>" . strtoupper($paper['PAPERNAME']) . "</td>";
                echo "<td style='text-align:center;'>" . $paper['CREDITS'] . "</td>";
                echo "<td style='text-align:center;'>" . $paper['GRADE'] . "</td>";
                echo "</tr>";
            }

            $getgpa = "SELECT * FROM gpas WHERE EXAMID='$examid' AND HALLTICKET='$hallticket'";
            $gpares = $conn->query($getgpa);
            if ($gpares->num_rows > 0) {
                $gpa = $gpares->fetch_assoc();
                echo "<tr><td colspan='2'><strong>FINAL RESULT</strong></td><td colspan='2'>";
                echo ($gpa['RESULT'] == 'P') ? "PASSED" : (($gpa['RESULT'] == 'R') ? "PROMOTED" : (($gpa['RESULT'] == 'M') ? "MALPRACTICE" : ""));
                echo "</td></tr>";
                echo "<tr><td colspan='2'><strong>SGPA</strong></td><td colspan='2'>" . $gpa['SGPA'] . "</td></tr>";
            }
        }
        ?>
    </table>
    <div class='tab1'>
        <h3 align="center">O: &gt;=85 to 100; A: &gt;=70 to &lt;85; B: &gt;=60 to &lt;70; C:&gt;=55 to &lt;60; D:&gt;=50 to &lt;55; E: &gt;=40 to &lt;50; F: FAIL.</h3>
        <h4 align="center">This information is provided to the candidate on his/her online request and is only a prototype list.<br>If any discrepancies in the marks you may be brought to the notice of Controller of Examinations University Arts & Science College - Subedari-Warangal</h4>
        <h4 align="left" bgcolor="#33b5e5"><font color="#FFFFFF">&nbsp;&nbsp;© Copyrights Reserved. 2018 all rights reserved @ SYS Technology</h4>
    </div>
    <div class="watermark">
        <img src="http://uascku.ac.in/kulogo.jpg" alt="Watermark">
    </div>
</body>
</html>

<?php
$html = ob_get_clean();

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('University Arts & Science College');
$pdf->SetTitle('Exam Result');
$pdf->SetSubject('Exam Result');
$pdf->SetKeywords('TCPDF, PDF, exam, result');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
$pdf->lastPage();

//Close and output PDF document
$pdf->Output('exam_result.pdf', 'I');

?>
