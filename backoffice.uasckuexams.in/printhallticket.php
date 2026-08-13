<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hallticket</title>
    <script src="jquery-3.3.1.min.js"></script>
</head>
<style>
table{
    border-collapse:collapse;
}
  .subjects tr td{
        border-width:1px;
        padding:10px;
        border-color:black;
        border-style:solid;
    }
</style>
<body onload="window.print()">
<?php
include "config.php";
//check connection
$row;
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from enrolledview where ID=" . $_GET['id'] ." and feepaid=1";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($r = mysqli_fetch_assoc($result)) {
            $row = $r;
            // print_r($row);
            break;
        }
        

    } else {
        header("Location:feenotpaid.html");
    }
    if($row!=null){
        $getstudentdetails = "select * from students where haltckt ='".$row['HALLTICKET']."'";
        $studentdetails = mysqli_fetch_assoc($conn->query($getstudentdetails));
        
    }
}

function getsubjectname($subcode,$scheme)
{
    include "config.php";
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    $getsubname = "select * from PAPERTITLES where PAPERCODE='" . $subcode . "' AND SCHEME=".$scheme;
    // echo $getsubname;
    $res = $conn->query($getsubname);
    $res = mysqli_fetch_assoc($res);
    return $res["PAPERNAME"];
}

?>
    <table style="border-collapse: collapse;" width="100%">
     
            <tr>
                <td colspan="2" >
                    <center>
                        <img src="images/Logo.jpg" height="150px">
                        
                        <h1 style="text-align:center;margin: 0PX;padding: 0px;text-decoration: underline;">HALLTICKET</h1><br><br>
                    </center>
                </td>

           

        </thead>
        <!--Student Details Start here-->
        <tbody>
            <tr style="border-width:1px;border-style:solid;border-color:black">

                <td style="border-width:1px;border-right-style:solid;border-right-color:black;padding-left: 10px" width="70%">
                    <h3>Hallticket No: <?php echo $row["haltckt"]; ?></h3>
                    <h3>Candidate Name:  <?php echo $row["sname"]; ?></h3>
                    <h3>Father Name:  <?php echo $row["fname"]; ?></h3>
                    <h3>Exam Name: <?php echo $row["EXAMNAME"]; ?></h3>
                    <h3>Course: <?php echo $studentdetails["course"]; ?></h3>
                    <h3>Specialization: <?php echo $row["group"]; ?></h3>
                    <!--<h3>Semester: <?php echo $row["sem"]; ?></h3>-->

                </td>

                <!---Display Image -->
                <td width="30%;padding:10px;">
                    <center>
                        <br>
                        <img width="176px" src="<?php echo "upload/images/" . $row["aadhar"] . ".jpg"; ?>" height="220px">
                        <br>
                        <img width="176px" src="<?php echo "upload/signatures/" . $row["aadhar"] . ".jpg"; ?>" height="60px">
                    </center>
                </td>
            </tr>
            <tr>
                <td>
                    <br>
                    <br>
                    <br>
                   
                    <h3 style="
                text-align:center">Signature of the Student</h3>
                </td>
                <td>
                    <br>
                    <center><img src="images/authsign.jpg" height="60px;"></center>
                    <h3 style="text-align:center">Signature of the Issuing Authority</h3>
                </td>

            </tr>
            <tr>
                <td colspan="2">
                    <h2 style="text-align:center">Subjects Appearing</h2>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%">

<style>

    </style>
        <tbody class="subjects">
        <tr>
                <td><strong>Subject Code</strong></td>
                <td><strong>Subject Name</strong></td>
                <td><strong>Invigilator Signature</hstrong</td>
     <tr>
<?php if ($row["S1"] != "null" && $row["S1"] != "") {
    echo "<tr>";
    echo "<td>" . $row["S1"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["S1"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>
<?php if ($row["S2"] != "null" && $row["S2"] != "") {
    echo "<tr>";
    echo "<td>" . $row["S2"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["S2"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>
<?php if ($row["S3"] != "null" && $row["S3"] != "") {
    echo "<tr>";
    echo "<td>" . $row["S3"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["S3"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>
<?php if ($row["S4"] != "null" && $row["S4"] != "") {
    echo "<tr>";
    echo "<td>" . $row["S4"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["S4"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>
<?php if ($row["S5"] != "null" && $row["S5"] != "") {
    echo "<tr>";
    echo "<td>" . $row["S5"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["S5"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>
<?php if ($row["S6"] != "null" && $row["S6"] != "") {
    echo "<tr>";
    echo "<td>" . $row["S6"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["S6"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>
<?php if ($row["S7"] != "null" && $row["S7"] != "") {
    echo "<tr>";
    echo "<td>" . $row["S7"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["S7"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>
<?php if ($row["S8"] != "null" && $row["S8"] != "") {
    echo "<tr>";
    echo "<td>" . $row["S8"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["S8"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>
<?php if ($row["S9"] != "null" && $row["S9"] != "") {
    echo "<tr>";
    echo "<td>" . $row["S9"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["S9"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>
<?php if ($row["S10"] != "null" && $row["S10"] != "") {
    echo "<tr>";
    echo "<td>" . $row["S10"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["S10"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>
<?php if ($row["E1"] != "null" && $row["E1"] != "") {
    echo "<tr>";
    echo "<td>" . $row["E1"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["E1"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>

<?php if ($row["E2"] != "null" && $row["E2"] != "") {
    echo "<tr>";
    echo "<td>" . $row["E2"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["E2"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>

<?php if ($row["E3"] != "null" && $row["E3"] != "") {
    echo "<tr>";
    echo "<td>" . $row["E3"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["E3"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>

<?php if ($row["E4"] != "null" && $row["E4"] != "") {
    echo "<tr>";
    echo "<td>" . $row["E4"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["E4"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}?>


<?php if ($row["E5"] != "null" && $row["E5"] != "") {
    echo "<tr>";
    echo "<td>" . $row["E5"] . "</td>";
    echo "<td>";
    echo getsubjectname($row["E5"],$studentdetails['SCHEME']);
    echo "</td>";
    echo "<td></td>";
    echo "</tr>";
}

?>





        </tbody>

    </table>
    </td>
    </tr>
    <tr>
        <td colspan="2">
            <div>
                <h5>Instructions to the Candidates</h5>
                <ol>
                    <li>
                        The Examination will be held on the Mentioned dates in the college premises.
                    </li>
                    <li>The College reserves the right to cancel the admission of the candidate at any stage when it is
                        detected that his/her admission is against the rules</li>
                    <li>Candidate should reach the examination center 30 Mins before commencement of the exam <b>Students
                            will not be allowed into examination hall after commencement of exam</b></li>
                    <li>
                        Candidate is prohibited from bringing following items to the examination hall. Books,
                        Calculators, Mobile Phones, Pagers, Smart watches.
                        Candidate is not allowed to use any book or neither books not they allowed to keep with them a
                        book or part thereof, Slate or paper of any kind. They are also prohibited from talking to each
                        other, Copying from others, or allowing others to copy from them, or talking or giving any
                        other kind of assistanceor communicating with a paper outsode the Examination hall. If a
                        candidate is found violating the riles or causing nuisance or disturbance to other candidates
                        or any other malpractive and / or behaving in an indiscipline manner. they will be expelled
                        from examination hall and will not be allowed to write any further examinations.
                        a)When a candidate is found in possession of forbidden material,even if it is not relevant, he/she shall not be allowed to continue remaining part of the examination.
                         b)if the material found is relevant,but not used, the candidate shall forego two [2] examinatiopn i.e., the current one and the immediate subsequent examination.
                         c)if the candidate  uses the material he/she shall forego four[4] examination, inculding the current one. d)In the writing in the main answer book[i] impersionation [ii] misbehavior with the invigilators [iii] insertion of writing sheets in different hand writing
                        in the main answer book[iv] Leaving the examination hall with the answer book. The candidate shall forego six examination i.e., the current one and immediate subsequent five examinations. If candidate booked under M.P. case will not be allowed to continue further studies till the punishment is over.
                                        </li>
                    <li>
                      Whenever, course or scheme of the examination is changed in a particular year, two more examinations in the following year shall be conducted according to the old syllabus & regulations for those who have failed in the examination conducted before the change. Candidates not availing themselves of this chance of failing shall take
                      the examination there after according to the changed syllabus & regulations.
                     </li>
<li>
Candidates is requested to bring the Hall Ticket, Identity Card every day and has to produce whenever demanded
</li>     <span>Smoking or eatables are prohibited in the Examination Hall.</span>


                </ol>
            </div>

        </td>
    </tr>

    </tbody>


    </table>
    
    
    
    
    
    <!--COVID DECLARATION-->
    
    <p style='page-break-after:always'></p>
<table>
    <tbody>
        <tr>
            <td bgcolor="white" style="border:.75pt solid white;vertical-align:top;background:white;">&nbsp;<table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td>
                                <div style="padding:4.35pt 7"><img width="112" src="data:image/jpeg;base64,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" alt="image"></div>
                            </td>
                            <td>
                                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><strong><span style='font-size:17px;font-family:"Cambria",serif;'>&nbsp; &nbsp; &nbsp; &nbsp;University Arts &amp; Science College (Autonomous)</span></strong></p>
                                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><em><span style='font-size:17px;font-family:"Cambria",serif;'>(Accredited with &ldquo;A&rdquo; Grade by NAAC)&nbsp;</span></em></p>
                                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><span style='font-size:17px;font-family:"Cambria",serif;'>Kakatiya University, Subedari, Warangal, T.S. &ndash; 506001&nbsp;</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:150%;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><br></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:150%;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><strong><span style='font-size:24px;line-height:150%;font-family:"Times New Roman",serif;color:black;background:#A6A6A6;'>COVID-19 Specific Special Instructions</span></strong><strong><span style='font-size:21px;line-height:150%;font-family:"Times New Roman",serif;'>&nbsp;</span></strong></p>
                <ol style="list-style-type: decimal;">
                    <li><span style='line-height:150%;font-family:"Times New Roman",serif;font-size:19px;'>Candidates must maintain social distancing starting from point of entry in to the exam center till his/her exit from the exam center. Candidates need to follow directions given by the representatives &amp; need to stand in the circles drawn at the entry points of Test Centre.</span></li>
                    <li><span style='line-height:150%;font-family:"Times New Roman",serif;font-size:19px;'>Candidates experiencing cough, cold, sneezing, fever etc., must inform well in advance to the authorities at the Test Centre so as to make necessary arrangements.</span></li>
                    <li><span style='line-height:150%;font-family:"Times New Roman",serif;font-size:19px;'>Candidate must bring their own Masks and a simple pen. You may also bring flexible gloves, personal hand sanitizer (50ml) and transparent water bottle (if you wish). No other items will be permitted inside exam venue.</span></li>
                    <li><span style='line-height:150%;font-family:"Times New Roman",serif;font-size:19px;'>The candidate should bring a signed declaration stating that he/she has NOT tested positive for the Corona virus or identified as a potential carrier of the COVID-19 virus (declaration will be provided along with the Hall Ticket) and show the same to the Security Guard at the entry into the exam venue and submit it to the Invigilator.&nbsp;</span></li>
                    <li><span style='line-height:150%;font-family:"Times New Roman",serif;font-size:19px;'>Temperature of candidates will be checked at the entry to the exam venue via a Thermo Gun.&nbsp;</span></li>
                    <li><span style='line-height:150%;font-family:"Times New Roman",serif;font-size:19px;'>At Entrance, the candidate will be directed to sanitize his / her hands using the sanitizer. Photograph will be captured during the registration process.&nbsp;</span></li>
                    <li><span style='line-height:150%;font-family:"Times New Roman",serif;font-size:19px;'>No waiting place will be provided to the accompanying persons at the Examination centers in view of COVID-19 Pandemic.</span></li>
                </ol>
                
                
                
                <p style='page-break-after:always;'></p>
                
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><strong><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;background:darkgray;'>Self-Declaration</span></strong></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:center;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>&nbsp;</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>We are concerned about your health, safety &amp; hygiene. In the interest of your well-being and that of everyone at the venue, you are requested to declare if you have any of the below listed symptoms (Yes: I have, No: I do not have).</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>&nbsp;</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>Cough &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Yes / No &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Fever &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Yes / No</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>Cold / Runny Nose &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Yes / No &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Breathing Problem &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Yes / No</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>&nbsp;</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>I herewith certify that I am NOT tested Positive for the Coronavirus nor identified as a potential carrier of the COVID-19.</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>&nbsp;</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>Candidate Name &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;: &nbsp;</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>Candidate Hall Ticket No.&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;:&nbsp;</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>Date of Examination &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;: &nbsp;</span></p>
                <p style='margin-top:0in;margin-right:0in;margin-bottom:0in;margin-left:0in;line-height:115%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'><span style='font-size:19px;line-height:115%;font-family:"Times New Roman",serif;'>Signature of Candidate &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Date:&nbsp;</span></p>
            </td>
        </tr>
    </tbody>
</table>


</body>
<script>
$(function(){
    window.print();
});


</script>
</html>