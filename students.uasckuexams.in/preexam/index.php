
<?php
$error='';
session_start();

// if(isset($_SESSION['userid'])){
//     // $user=getcookie("userid");
//     // echo "<script>alert($user)</script>";
//     // header('location:dashboard.php');
//     // setcookie("userid",$username,time() + (300));
    
// }

if(isset($_GET['loggedout']))
{
    session_destroy();
    $_SESSION=array();
    $_COOKIE=array();
}


if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password']))
     {
    $error = "Username or Password is invalid";
    }
else
{
    // Define $username and $password
    $username=$_POST['username'];
    $password=$_POST['password'];
    // Establishing Connection with Server by passing server_name, user_id and password as a parameter
    $error=''; // Variable To Store Error Message
    
    //database connection
    include ('config.php');
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    // To protect MySQL injection for Security purpose
    $username = stripslashes($username);
    $username = mysqli_real_escape_string($conn,$username);
    $password = stripslashes($password);
    $password = mysqli_real_escape_string($conn,$password);
    
    // Selecting Database
    // SQL query to fetch information of registerd users and finds user match.
      $query = "select * from users where password='$password' AND username='$username'";
    $result = $conn->query($query);
     
    $rows = $result->num_rows;
    
    if ($rows == 1) 
    {
         // Starting Session
    
         // Initializing Session
        $_SESSION['userid']=$username;
            setcookie("userid",$username,time() + (300));
        header("location:dashboard.php");
    
    } 
else 
{

$error = "Username or Password is invalid";

$_SESSION=array();

session_destroy();

}

mysqli_close($conn); // Closing Connection
}
}


?>


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
    <link href="../css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<style>
.login-content{
margin:auto;
}
.page-bg{
    
    background: hsla(192, 95%, 50%, 1);

    background: linear-gradient(90deg, hsla(192, 95%, 50%, 1) 0%, hsla(225, 89%, 47%, 1) 100%);
    
    background: -moz-linear-gradient(90deg, hsla(192, 95%, 50%, 1) 0%, hsla(225, 89%, 47%, 1) 100%);
    
    background: -webkit-linear-gradient(90deg, hsla(192, 95%, 50%, 1) 0%, hsla(225, 89%, 47%, 1) 100%);
    
    filter: progid: DXImageTransform.Microsoft.gradient( startColorstr="#07C8F9", endColorstr="#0D41E1", GradientType=1 );

}
.page-bg-2{
     
    background: hsla(192, 95%, 50%, 1);
    background: linear-gradient(90deg, hsla(192, 95%, 50%, 1) 0%, hsla(225, 89%, 47%, 1) 100%);
    background: -moz-linear-gradient(90deg, hsla(192, 95%, 50%, 1) 0%, hsla(225, 89%, 47%, 1) 100%);
    background: -webkit-linear-gradient(90deg, hsla(192, 95%, 50%, 1) 0%, hsla(225, 89%, 47%, 1) 100%);
    filter: progid: DXImageTransform.Microsoft.gradient( startColorstr="#07C8F9", endColorstr="#0D41E1", GradientType=1 );
    
}


</style>

<body>
<div class="page-bg-2 d-sm-flex align-items-center " style="height:100vh;">
    
    <div class="container bg-white rounded mt-4" style="height:80vh;">
            <div class="d-sm-flex  p-0 rounded" style="height:100%">
                
                <div class="d-flex flex-column p-4">
                    <img src="..\images\Logo1.jpg" alt="University Arts & Science College" class="img"  style="width:100%"/></b>
                    <form method="POST" action="index.php">
                <h2 class="text-center text-primary">Pre Exam Portal</h2>
<div class="form-group">

<label>Username</label>
<input type="text" class="form-control" name ="username" placeholder="Enter Username"  required>
</div>

<div class="form-group">
<label>Password</label>
<input type="password" class="form-control" name="password" placeholder="Password" required>
</div>

<div class="checkbox">
<label>
<input type="checkbox"> Remember Me
</label>


<label class="text-danger">
<?php if ($error != '') {
    echo $error;
}
?>

</label>
</div>
 <input type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30" name="submit" value="Sign In">

                                </form>
                    
                </div><!--end of login-->
                
                
            </div>
            
            
        
    </div>
    
</div>


 
   
</body>

</html>