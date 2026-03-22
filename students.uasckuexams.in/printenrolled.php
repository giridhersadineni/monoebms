<?php
/*
session_start();
if(!(isset($_SESSION['login'])))
{
header("location:index.php?sessionexpired");
}
 */
?>
<style>
*{
    font-family:arial;
}
td{
    margin-left:10px;
    padding-left:10px;



}
table{
    border-collapse:collapse;
}

h4{
    text-transform:uppercase;
}
</style>
<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from enrolledview where id=" . $_GET['id'];
    $result = $conn->query($sql);
    // echo "  " . $result->num_rows;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            break;
        }
    }
}
?>

<table>

<tr>

<td width="20%">
<img src="images/arts.png" alt="" width="110px">
</td>

<td width="90%" style="">
<blockquote>
<h3>UNIVERSITY ARTS & SCIENCE COLLEGE<h3>
 <h5>An Autonomous Institute under Kakatiya Universtiy</h5>
 <h3 >ENROLLED STUDENT DETAILS</h3>
 </blockquote>
</td>

</tr>
</table>

<table style="border-style:solid;border-width:2px;border-color:black;border-collapse:collapse; ">
<tr>
<td style="border-style:solid;border-width:2px;border-color:black;padding:5px;">
<table>
<tr>
<h4>1.Candidate details</h4>
</tr>
<tr>
<td>Full Name:</td>
<td><center><?php echo $row["sname"] ?></center></td>
</tr>
<tr>
<td>Father Name:</td>
<td><center><?php echo $row["fname"] ?></center></td>
</tr>
<tr>
<td>Mother Name:</td>
<td><center><?php echo $row["mname"] ?></center></td>
</tr>

<tr>
<td>Email:</td>
<td><center><?php echo $row["email"] ?></center></td>
</tr>

<tr>
<td>Date of Birth:</td>
<td><center><?php echo $row["dob"] ?></center></td>
</tr>

<tr>
<td>Gender:</td>
<td><center><?php echo $row["gender"] ?></center></td>
</tr>

<tr>
<td>Phone Number:</td>
<td><center><?php echo $row["phone"] ?></center></td>
</tr>
</tbody>
</table>
<table >

<br>
<tr>
<h4>2.Course details</h4>

</tr>
<tr>
<td>Group:</td>
<td><center><?php echo $row["group"] ?></center></td>
</tr>

<tr>
<td>Halltickt:</td>
<td><center><?php echo $row["haltckt"] ?></center></td>
</tr>

<tr>
<td>Semester:</td>
<td><center><?php echo $row["sem"] ?></center></td>
</tr>

<tr>
<td>Year:</td>
<td><center><?php echo $row["YEAR"] ?></center></td>
</tr>

<tr>
<td>Aadhar:</td>
<td><center><?php echo $row["aadhar"] ?></center></td>
</tr>

</table>
<br>

<table>
<tr>
<h4>3.Address of Candidate</h4>
</tr>
<tr>
<td>Address:</td>
<td><center><?php echo $row["address"] ?></center></td>
</tr>
<tr>
<td>Mandal:</td>
<td><center><?php echo $row["mandal"] ?></center></td>
</tr>
<tr>
<td>City:</td>
<td><center><?php echo $row["city"] ?></center></td>
</tr>
<tr>
<td>State:</td>
<td><?php echo $row["state"] ?></td>
</tr>
<tr>
<td>Pincode :</td>
<td><?php echo $row["pincode"] ?></td>
</tr>
</table>

<table >
<tr>
<h4>3.Selected Subjects</h4>
</tr>
<tr>
<tr>
<td><strong>Subject1:</strong><?php echo $row["S1"] ?></td>
<td><strong>Subject2:</strong><?php echo $row["S2"] ?></td>
</tr>
<tr>
<td><strong>Subject3:</strong><?php echo $row["S3"] ?></td>
<td><strong>Subject4:</strong><?php echo $row["S4"] ?></td>
</tr>

<tr>
<td><strong>Subject4:</strong><?php echo $row["S4"] ?></td>
<td><strong>Subject5:</strong><?php echo $row["S5"] ?></td>
</tr>

<tr>
<td><strong>Subject6:</strong><?php echo $row["S6"] ?></td>
<td><strong>Subject7:</strong><?php echo $row["S7"] ?></td>
</tr>

<tr>
<td><strong>Subject8:</strong><?php echo $row["S8"] ?></td>
<td><strong>Subject9:</strong><?php echo $row["S9"] ?></td>
</tr>
<tr>
<td><strong>Subject10:</strong><?php echo $row["S10"] ?></td>
<td><strong>Elective1:</strong><?php echo $row["E1"] ?></td>
</tr>

<tr>
<td><strong>Elective2:</strong><?php echo $row["E2"] ?></td>
<td><strong>Elective3:</strong><?php echo $row["E3"] ?></td>
</tr>


<tr>
<td><strong>Elective4:</strong><?php echo $row["E4"] ?></td>
<td></td>
</tr>
</table>
<br>

<table>
<h4>4.Fee Details</h4>
<tr>
<td>Fee Amount:<?php echo $row["FEEAMOUNT"] ?></td>
<td><td>Challan Submited On:<?php echo $row["CHALLANSUBMITTEDON"] ?></td></td>
</tr>

</table>
</td>
<!--Content-->
<td style="vertical-align:top;border-style:solid;border-width:2px;border-color:black;padding:10px;">
<p align="center">Photograph</p>
<img src="../students/upload/images/<?php echo $row['aadhar'] . ".jpg"; ?>" width="176px" style="border-style:solid">
<p align="center">Signature</p>
<img src="../students/upload/signatures/<?php echo $row['aadhar'] . ".jpg"; ?>" width="176px"  style="border-style:solid"><br>



</td>
</tr>
</table>


