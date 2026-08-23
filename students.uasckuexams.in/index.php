<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/arts.png">
    <title>University Arts & Science College</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    
</head>


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
// Define $username and $password
        $haltckt = $_POST['haltckt'];
        $dob = $_POST['dob'];
        $dostid = $_POST['dostid'];
// Establishing Connection with Server by passing server_name, user_id and password as a parameter
        $error = ''; // Variable To Store Error Message

        include 'config.php';

        $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

// To protect MySQL injection for Security purpose
        $haltckt = stripslashes($haltckt);
        $dob = stripslashes($dob);

        $haltckt = mysqli_real_escape_string($conn, $haltckt);
        $dob = mysqli_real_escape_string($conn, $dob);

// Selecting Database
        // SQL query to fetch information of registerd users and finds user match.
        $query = "select * from students where haltckt='$haltckt' AND (dob='$dob' OR dostid = '$dostid')";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $id = 0;
            // Starting Session
            while ($row = mysqli_fetch_assoc($result)) {
                setcookie("userfilename", $row["imgurl"], time() + 3600);
                $id = $row['id'];
                setcookie("aadhar", $row['aadhar']);
                setcookie("name", $row["sname"]);
                setcookie("stid",$row['stid']);
            }
            $_SESSION['login'] = $haltckt; // Initializing Session
            setcookie("userid", $_POST['haltckt'], time() + 3600);
            $_SESSION['hallticket'] = $haltckt;
            $_SESSION['aadhar'] = $aadhar;
            mysqli_close($conn);
            header("location:welcome.php");
        } else {
            $error = "Hallticket or Date of birth is invalid";
            $_SESSION = array();
            session_destroy();
        }
        mysqli_close($conn); // Closing Connection
    }
}
?>
<style>
.login-content{
margin:auto;
}
.page-bg{
    
background: #6441A5;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #2a0845, #6441A5);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #2a0845, #6441A5); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
}
.page-bg-2{
    background: #43cea2;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #185a9d, #43cea2);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #185a9d, #43cea2); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
}
</style>

<body>
<div class="page-bg-2 d-sm-flex align-items-center " style="height:100vh;">
            
             <div class="container bg-white rounded mt-3" style="height:70vh;">
            <div class="d-sm-flex  p-0 rounded" style="height:100%">
                
                <div class="d-flex flex-column p-4">
                    <img src="images\Logo1.jpg" alt="University Arts & Science College" class="img"  style="width:100%"/></b>
                    <form method="POST" action="index.php" class="form w-100">
                         <h3>Login</h3>
                        <div class="form-group">
                            <label>Hallticket Number</label>
                            <input type="text" class="form-control" name ="haltckt" placeholder="Enter Hallticket" required>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" class="form-control" name="dob" placeholder="dob" >
                        </div>

                        <div class="form-group">
                            <label>Dost ID for 2023 batch students</label>
                            <input type="text" class="form-control" name="dostid" placeholder="DOST ID" >
                        </div>
                    
                        <div class="checkbox">
                            <label><input type="checkbox" >Remember Me</label>
                        </div>
                        <h4 class="text-danger">
                        <?php if ($error != '') :?>
                            <?=$error; ?>
                        <?php endif; ?>
                        </h4>
                        <p class="text-right">
                            <input type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30" name="submit" value="Sign In">
                        </p>                
                    </form>
                    
                </div><!--end of login-->
                
                
            </div>
</div>
    
    
   
</div>


</body>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-5755477602321907",
    enable_page_level_ads: false
  });
</script>

</html>