<?php include 'header.php'?>

<?php
include "config.php";

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "SELECT * FROM students WHERE stid=" . $_COOKIE['stid'];
    $result = $conn->query($sql);
    $row = mysqli_fetch_array($result);
}
?>

<style>
    .page-wrapper {
        padding: 20px;
    }
    .card {
        padding: 20px;
        border-radius: 10px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label.form-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 6px;
        display: block;
    }
    input.form-control, select.form-control {
        height: 42px;
        border-radius: 6px;
    }
</style>

<div class="page-wrapper">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="card shadow">
                    <div class="card-header text-center">
                        <h2 class="text-danger">Update Your Details (As per SSC Certificates)</h2>
                    </div>

                    <div class="card-body">

                        <form action="updatestudent.php?id=<?php echo $_COOKIE['stid'] ?>" method="post">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Student Name</label>
                                        <input type="text" class="form-control" name="sname" value="<?php echo $row['sname']; ?>" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Father Name</label>
                                        <input type="text" class="form-control" name="fname" value="<?php echo $row['fname']; ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Mother Name</label>
                                        <input type="text" class="form-control" name="mname" value="<?php echo $row['mname']; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" name="dob" value="<?php echo $row['dob']; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Gender</label><br>
                                    <input type="radio" name="gender" value="M" <?php if ($row['gender'] == "M") echo 'checked'; ?>> Male
                                    &nbsp;&nbsp;
                                    <input type="radio" name="gender" value="F" <?php if ($row['gender'] == "F") echo 'checked'; ?>> Female
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Caste</label>
                                        <select class="form-control" name="caste">
                                            <option value="<?php echo $row['caste']; ?>"><?php echo $row['caste']; ?></option>
                                            <option value="OC">OC</option>
                                            <option value="BC-A">BC-A</option>
                                            <option value="BC-B">BC-B</option>
                                            <option value="BC-C">BC-C</option>
                                            <option value="BC-D">BC-D</option>
                                            <option value="BC-E">BC-E</option>
                                            <option value="SC">SC</option>
                                            <option value="ST">ST</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Sub Caste</label>
                                        <input type="text" class="form-control" name="subcaste" value="<?php echo $row['subcaste']; ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">Physical Status</label>
                                    <select name="challenged_quota" class="form-control" required>
                                        <option>Select Your Status</option>
                                        <option value="NONE">No, I am Physically Fit</option>
                                        <option value="PHYSICALLY CHALLENGED">PHYSICALLY CHALLENGED</option>
                                        <option value="VISUALLY CHALLENGED">VISUALLY CHALLENGED</option>
                                    </select>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" value="<?php echo $row['phone']; ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Group</label>
                                    <input type="text" class="form-control" name="group" value="<?php echo $row['group']; ?>" readonly>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Hallticket</label>
                                    <input type="text" class="form-control" name="haltckt" value="<?php echo $row['haltckt']; ?>" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Semester</label>
                                    <input type="text" class="form-control" name="semester" value="<?php echo $row['sem']; ?>" readonly>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Year</label>
                                    <input type="text" class="form-control disabled" name="year" value="<?php echo $row['curryear']; ?>" readonly>
                                    
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Aadhar</label>
                                    <input type="text" class="form-control" name="aadhar" value="<?php echo $row['aadhar']; ?>">
                                </div>
                            </div>


                            <!-- NEW: APAAR ID -->
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">APAAR ID</label>
                                    <input type="text" class="form-control" name="apaar_id" value="<?php echo $row['apaar_id']; ?>" maxlength="12">
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" name="address" value="<?php echo $row['address']; ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Street / Area</label>
                                    <input type="text" class="form-control" name="address2" value="<?php echo $row['address2']; ?>">
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Village / Mandal</label>
                                    <input type="text" class="form-control" name="mandal" value="<?php echo $row['mandal']; ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">City / District</label>
                                    <input type="text" class="form-control" name="city" value="<?php echo $row['city']; ?>" required>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">State</label>
                                    <input type="text" class="form-control" name="state" value="<?php echo $row['state']; ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Pincode</label>
                                    <input type="text" class="form-control" name="pincode" value="<?php echo $row['pincode']; ?>" required>
                                </div>
                            </div>

                            <br>
                            <center>
                                <input type="submit" class="btn btn-primary btn-lg" value="Update">
                            </center>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'?>
