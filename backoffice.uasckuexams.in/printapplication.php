<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->

</head>


<?php include 'config.php';

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  enrolledview where id=" . $_GET['id'];

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            break;
        }
    }
}
?>


<style>
.collegelogo {
    position: absolute;
    top: 15px;
    display: block;
    margin-left: 200px;
}
table{
    border-collapse: collapse;
    width: 100%;
}

.table1 td{
    border: 1px solid black;
    width: 80%;
    padding:10px;

}

td, th {
    border: 1px solid black;
    text-align: left;
    padding: 10px;

}
.tbl td th{
  border:1px solid black;
 width:70%;

}


</style>

<body>

<img src="images/arts.png" alt="" width="150px" class="collegelogo">

<div class="header">
<center>
<h1>University Arts & Science College<h1>
 <h4>(An Autonomous Institute under Kakatiya Universtiy)</h4>
 <h3>APPLICATION FORM FOR REGISTRATION</h3>
</center>
</div>


<table>

<tr>
    <td>Course</td>
        <th><?php echo $row['course'] ?></th>
        <td>MEDIUM</td>
        <th><?php echo $row['medium'] ?></th>
           <td>GROUP:</td>
           <th><?php echo $row['group'] ?></th>

    <tr>
 </table>
<br>

<table border="1" class="table1" >
        <col width="200">


        <tr>
          <td COLSPAN=2>Hallticket No: <strong><?php echo $row['haltckt'] ?><strong></td>

        </tr>

        <tr>
          <td>Name of the Candidate: <strong><?php echo $row['sname'] ?><strong> </td>
          <td rowspan="7" style="vertical-align:top"><img src="" alt="">
          <p align="center">Photograph</p>
            <img src="../students/upload/images/<?php echo $row['aadhar'] . ".jpg"; ?>" width="176px" style="border-style:solid">
            <p align="center">Signature</p>
            <img src="../students/upload/signatures/<?php echo $row['aadhar'] . ".jpg"; ?>" width="176px"  style="border-style:solid"><br>



          </td>
       </tr>

<tr>
<td>Father Name: <strong><?php echo $row['fname'] ?><strong></td>
</tr>
<tr>
<td>Mother Name: <strong><?php echo $row['mname'] ?><strong></td>
</tr>
<tr>
<td>Aadhar Card No: <strong><?php echo $row['aadhar'] ?><strong></td>
</tr>

<tr>
<td>Date of birth: <strong><?php echo $row['dob'] ?><strong></td>
</tr>

<tr>
<td>Gender: <strong><?php echo $row['gender'] ?><strong></td>
</tr>
<tr>
<td>Medium: <strong><?php echo $row['medium'] ?><strong></td>

</tr>
<tr>
<td>Course: <strong><?php echo $row['course'] ?><strong></td>
<td></td>
</tr>


<tr>
<td>Group: <strong><?php echo $row['group'] ?><strong></td>
<td></td>
</tr>
<tr>
<td>Semester: <strong><?php echo $row['sem'] ?><strong></td>
<td></td>
</tr>

<tr>
<td>Year: <strong><?php echo $row['curryear'] ?><strong></td>
<td></td>
</tr>

<tr>
<td height="80">Address for Correspondence: <strong><?php echo $row['address'] ?><strong>, <strong><?php echo $row['mandal'] ?><strong> <strong><?php echo $row['city'] ?><strong> <strong><?php echo $row['pincode'] ?><strong></td>
<td></td>
</tr>


                      <tr>
                            <td >Mobile Number:  <strong><?php echo $row['phone'] ?><strong></td>
                                  <td></td>
                          </tr>


                          <tr>
                                <td>Email Id: <strong><?php echo $row['email'] ?><strong></td>
                                    <td></td>
                              </tr>


<table>
<tr>
    <th>Social Status(Please Tick On This)</th>

        <td>SC</td>
           <td>ST</td>
           <td>BC:A</td>
           <td>BC:B</td>
           <td>BC:C</td>
           <td>BC:D</td>
           <td>OC:</td>
           <td>Others:</td>

    <tr>
</table>

  <table class="tbl">
  <h3>Subject appearing for the 3 Year 3 Semester Examination</h3>
<tr>
<th>Subjects</th>

       <td>S1:<strong><?php echo $row['S1'] ?></strong></td>
       <td>S2:<strong><?php echo $row['S2'] ?></strong></td>
       <td>S3:<strong><?php echo $row['S3'] ?></strong></td>
       <td>S4:<strong><?php echo $row['S4'] ?></strong></td>
       <td>S5:<strong><?php echo $row['S5'] ?></strong></td>
       <td>S6:<strong><?php echo $row['S6'] ?></strong></td>
       <td>S7:<strong><?php echo $row['S7'] ?></strong></td>
       <td>S8:<strong><?php echo $row['S8'] ?></strong></td>
        <td>S9:<strong><?php echo $row['S9'] ?></strong></td>
       <td>S10:<strong><?php echo $row['S10'] ?></strong></td>
       <td>E1:<strong><?php echo $row['E1'] ?><strong></td>
       <td>E2:<strong><?php echo $row['E2'] ?></strong></td>
       <td>E3:<strong><?php echo $row['E3'] ?></strong></td>
       <td>E4:<strong><?php echo $row['E4'] ?></strong></td>
<tr>

</table>

<table>


<tr>
<th>Fee Paid</th>

        <td>Fee Amount:<strong><?php echo $row['FEEAMOUNT'] ?></strong></td>
           <td>Challan No:</strong></td>
           <td>Date:</td>
    <tr>
    </table>


 <p>Declaration:</p>
<blockquote> I hereby decalare that all the information furnished above is correct to best of my knowledge.In the event of any information being found incorrect or misleading my candidate shall be liable to cancellation by the University at anycost me and I shall not be entitled to refund any fee paid by me to universtiy.</blockquote>

<h3 align="right">Signature of the Candidate</h3>
</table>

<h2 align="center"> <ins>Candidate Acknowledgement</ins></h2>


<table >
<tr>
<td colspan="5">Hallticket No:<strong><?php echo $row['haltckt'] ?><strong> </td>
</tr>

<tr>
<td colspan="5"><strong>Name of the Candidate:<?php echo $row['sname'] ?><strong> </td>
</tr>

<tr>
<td>Course:<strong><?php echo $row['course'] ?></strong></td>
<td>Medium:<strong><?php echo $row['medium'] ?> </strong></td>
<td>Group:<strong><?php echo $row['group'] ?></strong></td>
<td></td>
</tr>
<tr>
<th>Fee Paid</th>

        <td>Fee Amount:<strong><?php echo $row['FEEAMOUNT'] ?></strong></td>
           <td>Challan No:</strong></td>
           <td>Date:</td>
    <tr>
</table>
<br>
<h3 align="right">Signature of the Received</h3>
</body>

</html>