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

    <!-- Bootstrap Core CSS -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<?php include 'config.php';

$conn = mysqli_connect($servername,$dbuser,$dbpwd,$dbname);
//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$sql = "select * from  students where stid=" . $_GET['exid'];



$result = $conn->query($sql);

if($result->num_rows>0){

while($row=$result->fetch_assoc()) {
break;
}
}
}
?>

<style>
    .bordered {
        margin: auto;

        border: 1px solid black;
        width: 80%;
    }

    .header {
        align-content: center;
    }
    tr,td{
        padding: 10px;
    }
    table{
        align:center;
    }
</style>

<body>
    <div class="container-fluid">

        <table  align="center">
            <tr>
                <td width="70%">
                    <table class="bordered">
                        <center>
                            <h1><span>University Arts & Science College<br>
                                    <h4>An Autonomous Institute under Kakatiya Universtiy</h4>
                                </span></h1>
                        </center>
                        <tbody>
                            <tr>
                                <td>Full Name:<?php echo $row['sname']?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Father Name:<?php echo $row['fname']?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Mother Name:<?php echo $row['mname']?></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Email:<?php echo $row['email']?></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Date of Birth:<?php echo $row['dob']?></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Gender:<?php echo $row['gender']?></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Phone Number:<?php echo $row['phone']?></td>
                                <td></td>
                            </tr>



                            <tr>
                                <td>Group:<?php echo $row['group']?></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Halltickt:<?php echo $row['haltckt']?></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Semester:<?php echo $row['sem']?></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Year:<?php echo $row['year']?></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Aadhar:<?php echo $row['aadhar']?></td>
                                <td></td>
                            </tr>



                            <tr>
                                <td>Address:<?php echo $row['address']?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Manadal:<?php echo $row['mandal']?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>City:<?php echo $row['city']?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>State:<?php echo $row['state']?></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Pincode :<?php echo $row['pincode']?></td>
                                <td></td>

                            </tr>



                    </table>
                </td>
                <td width="30%"></td>
            </tr>


        </table>





    </div>





</body>

</html>