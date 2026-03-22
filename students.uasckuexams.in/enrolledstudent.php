<?php include "header.php"; ?>
<?php include "config.php"; ?>
<?php

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connection

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from enrolledview where id=" . $_GET['id'];
    $result = $conn->query($sql);
    echo "  " . $result->num_rows;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            break;
        }
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-primary">Student Details</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-md-12">
                            <h4>1. Candidate details</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            Photo <br>Signature
                                        </td>
                                        <td>
                                            <div class="col-md-4">
                                                <img src="https://students.uasckuexams.in/upload/images/<?php echo $row['aadhar']; ?>.jpg" alt="profile pic" width="100px"/><br>
                                                <img src="https://students.uasckuexams.in/upload/signatures/<?php echo $row['aadhar']; ?>.jpg" alt="signature" width="100px"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Full Name:</td>
                                        <td><strong><?php echo $row["sname"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Father Name:</td>
                                        <td><strong><?php echo $row["fname"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Mother Name:</td>
                                        <td><strong><?php echo $row["mname"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td><strong><?php echo $row["email"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Date of Birth:</td>
                                        <td><strong><?php echo $row["dob"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Gender:</td>
                                        <td><strong><?php echo $row["gender"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Phone Number:</td>
                                        <td><strong><?php echo $row["phone"] ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>

                            <h4>2. Course details</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Group:</td>
                                        <td><strong><?php echo $row["group"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Halltickt:</td>
                                        <td><strong><?php echo $row["haltckt"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Semester:</td>
                                        <td><strong><?php echo $row["sem"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Year:</td>
                                        <td><strong><?php echo $row["curryear"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Aadhar:</td>
                                        <td><strong><?php echo $row["aadhar"] ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <h4>3. Address of Candidate</h4>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Address:</td>
                                        <td><strong><?php echo $row["address"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Manadal:</td>
                                        <td><strong><?php echo $row["mandal"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>City:</td>
                                        <td><strong><?php echo $row["city"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>State:</td>
                                        <td><strong><?php echo $row["state"] ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Pincode:</td>
                                        <td><strong><?php echo $row["pincode"] ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            <h4>4. Selected Subjects</h4>
                            <table class="table table-striped">
                                
                                <div class="col-md-8">
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tbody>
                    <?php if (!empty($row["S1"])) : ?>
                        <tr>
                            <td>Subject1:</td>
                            <td><strong><?php echo $row["S1"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["S2"])) : ?>
                        <tr>
                            <td>Subject2:</td>
                            <td><strong><?php echo $row["S2"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["S3"])) : ?>
                        <tr>
                            <td>Subject3:</td>
                            <td><strong><?php echo $row["S3"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["S4"])) : ?>
                        <tr>
                            <td>Subject4:</td>
                            <td><strong><?php echo $row["S4"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["S5"])) : ?>
                        <tr>
                            <td>Subject5:</td>
                            <td><strong><?php echo $row["S5"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-bordered">
                <tbody>
                    <?php if (!empty($row["S6"])) : ?>
                        <tr>
                            <td>Subject6:</td>
                            <td><strong><?php echo $row["S6"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["S7"])) : ?>
                        <tr>
                            <td>Subject7:</td>
                            <td><strong><?php echo $row["S7"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["S8"])) : ?>
                        <tr>
                            <td>Subject8:</td>
                            <td><strong><?php echo $row["S8"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["S9"])) : ?>
                        <tr>
                            <td>Subject9:</td>
                            <td><strong><?php echo $row["S9"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["S10"])) : ?>
                        <tr>
                            <td>Subject10:</td>
                            <td><strong><?php echo $row["S10"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <h4>4. Elective Subjects</h4>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered">
                <tbody>
                    <?php if (!empty($row["E1"])) : ?>
                        <tr>
                            <td>Elective1:</td>
                            <td><strong><?php echo $row["E1"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["E2"])) : ?>
                        <tr>
                            <td>Elective2:</td>
                            <td><strong><?php echo $row["E2"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["E3"])) : ?>
                        <tr>
                            <td>Elective3:</td>
                            <td><strong><?php echo $row["E3"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-bordered">
                <tbody>
                    <?php if (!empty($row["E4"])) : ?>
                        <tr>
                            <td>Elective4:</td>
                            <td><strong><?php echo $row["E4"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($row["E5"])) : ?>
                        <tr>
                            <td>Elective5:</td>
                            <td><strong><?php echo $row["E5"] ?></strong></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <h4>5. Fee Details</h4>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Fee Amount:</td>
                                <td><strong><?php echo $row["FEEAMOUNT"] ?></strong></td>
                            </tr>
                            <tr>
                                <td>Challan Submited On:</td>
                                <td><strong><?php echo $row["CHALLANSUBMITTEDON"] ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <p align="center">
                        <a href="printenrolled.php?id=<?php echo $row["ID"]; ?>" class="btn btn-primary" target="_blank">Print Student Details</a>
                    </p>
                    <p align="center">
                        <a class="btn btn-success" href="enrolledview.php">Back</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
@hasSection ('name') 
    
@else 

@endif