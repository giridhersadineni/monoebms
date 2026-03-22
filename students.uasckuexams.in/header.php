<?php 

session_start();
if(!isset($_SESSION['userid'])){
 setcookie(PHPSESSID,"expired",time()+0);  
 header("location:index.php?invalid-session");   
 
}

?>
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
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <title>University Arts & Science College EBMS</title>
    
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="css/lib/calendar2/semantic.ui.min.css" rel="stylesheet">
    <link href="css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
    <link href="../css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../css/lib/calendar2/semantic.ui.min.css" rel="stylesheet">
    <link href="../css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="../css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="../css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="../css/helper.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    
    
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- Main wrapper  -->
    <div id="main-wrapper ">
        <!-- header header  -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark bg-dark">
                <!-- Logo -->
                <!--div class="navbar-header bg-dark">
                <!--    <a class="navbar-brand bg-dark" href="#" >-->
                        
                        <!--<b><img src="#" alt="USACKU" class="dark-logo" /> USACKU</b>-->
                <!--        <h5 class="bg-dark text-white">USACKU</h5>-->
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <!--<span><img src="#" alt="homepage" class="dark-logo" /></span>-->
                <!--    </a>-->
                <!--</div-->
                <!-- End Logo -->
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu text-white text-center">  UNIVERSITY ARTS & SCIENCE COLLEGE EXAM BRANCH</i></a></li>
                    
                    </ul>
                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0">

                        <!-- Search -->
                        <li class="nav-item hidden-sm-down search-box"> <a class="nav-link hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search" action="viewstudent.php" method="POST">
                                <input type="text" class="form-control" placeholder="Enter Hallticket Number" name="hallticket"> <a class="srh-btn"><i class="ti-close"></i></a> 
                                </form>
                        </li>
                        <!-- Comment -->
       <!--                 <li class="nav-item dropdown">-->
       <!--                     <a class="nav-link dropdown-toggle text-muted text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-bell"></i>-->
							<!--	<div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>-->
							<!--</a>-->
                            <!--<div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn">-->
                            <!--    <ul>-->
                            <!--        <li>-->
                            <!--            <div class="drop-title">Notifications</div>-->
                            <!--        </li>-->
                            <!--        <li>-->
                            <!--            <div class="message-center">-->
                                            <!-- Message -->
                            <!--                <a href="#">-->
                            <!--                    <div class="btn btn-danger btn-circle m-r-10"><i class="fa fa-link"></i></div>-->
                            <!--                    <div class="mail-contnet">-->
                            <!--                        <h5>This is title</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span>-->
                            <!--                    </div>-->
                            <!--                </a>-->
                                            <!-- Message -->
                            <!--                <a href="#">-->
                            <!--                    <div class="btn btn-success btn-circle m-r-10"><i class="ti-calendar"></i></div>-->
                            <!--                    <div class="mail-contnet">-->
                            <!--                        <h5>This is another title</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span>-->
                            <!--                    </div>-->
                            <!--                </a>-->
                                            <!-- Message -->
                            <!--                <a href="#">-->
                            <!--                    <div class="btn btn-info btn-circle m-r-10"><i class="ti-settings"></i></div>-->
                            <!--                    <div class="mail-contnet">-->
                            <!--                        <h5>This is title</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span>-->
                            <!--                    </div>-->
                            <!--                </a>-->
                                            <!-- Message -->
                            <!--                <a href="#">-->
                            <!--                    <div class="btn btn-primary btn-circle m-r-10"><i class="ti-user"></i></div>-->
                            <!--                    <div class="mail-contnet">-->
                            <!--                        <h5>This is another title</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>-->
                            <!--                    </div>-->
                            <!--                </a>-->
                            <!--            </div>-->
                            <!--        </li>-->
                            <!--        <li>-->
                            <!--            <a class="nav-link text-center" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>-->
                            <!--        </li>-->
                            <!--    </ul>-->
                            <!--</div>-->
       <!--                 </li>-->
                        <!-- End Comment -->
                        <!-- Messages -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-envelope"></i>
								<div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
							</a>
                           
                        </li>
                        <!-- End Messages -->
                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="images/users/5.jpg" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <!--<li><a href="#"><i class="ti-user"></i> Profile</a></li>-->
                                    <li><a href="#"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- End header header -->
        <!-- Left Sidebar  -->
        <div class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-divider"></li>
                        <li class="nav-label">UASCKU EBMS</li>
                        <li> <a class="has-arrow  " href="/dashboard.php" aria-expanded="false"><i class="fa fa-home text-danger"></i><span class="hide-menu">Dashboard </span></a>
                        <!--    <ul aria-expanded="false" class="collapse">-->
                        <!--        <li><a href="index.html">Ecommerce </a></li>-->
                        <!--        <li><a href="index1.html">Analytics </a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->
<li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-envelope text-primary"></i><span class="hide-menu">Nominal Rolls</span></a>
    <ul aria-expanded="false" class="collapse">
         <!--<li><a href="revaluationview.php">Revaluation Students</a></li>-->
        <li><a href="/examapplications.php">Regular Applications</a></li>
        <li><a href="/revenrollments.php">Revalution Enrollments</a></li>
        <li><a href="/gendform.php">Generate D-Form</a></li>
        <li><a href="/genattend.php">Attendance Sheet</a></li>
        <li><a href="/scriptcoding.php">Answer Script Coding</a></li>
        <li><a href="/viewresult.php">Result Processing</a></li>
    </ul>
    </li>
    <li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-bar-chart text-success"></i><span class="hide-menu">Fee Payments</span></a>
        <ul aria-expanded="false" class="collapse">
            <li><a href="/markpayment.php">Mark Fee Payment</a></li>
            <li><a href="/revaluationmpayment.php">Mark Revaluation Payment</a></li>
            <li><a href="/transcation.php"> Transcations Details</a></li>
        </ul>
    </li>
                        <!--<li class="nav-label">Reports</li>-->
    <li> 
    <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-scroll text-primary"></i><span class="hide-menu">Reports</span></a>
    <ul aria-expanded="false" class="collapse">
        <li><a href="/newshortmemos.php"> <i class="fa fa-table"></i>   Short Memo  2020 Batch </a></li>
        <li><a href="/viewenrolled.php"> <i class="fa fa-file"></i>   Print Memo </a></li>
        <li><a href="/printofflinememo.php"> <i class="fa fa-file"></i>   Print Offline Memo </a></li>
        <li><a href="/consolidatedmemo.php"> <i class="fa fa-table"></i>   Consolidated Memo </a></li>
        <li><a href="/studentmemos.php"> <i class="fa fa-table"></i>  Print Memo (Student Search) </a></li>
        <li><a href="/tabulation.php"> <i class="fa fa-table"></i>   Tabulation Report </a></li>
        <li><a href="/oldtabreport.php" target="blank"> <i class="fa fa-table"></i>   Old Tabulation Report </a></li>
        <li><a href="/revresult.php"> <i class="fa fa-table"></i>  Revaluation Result </a></li>
        <li><a href="/oldmemogen.php"> <i class="fa fa-table"></i>  Print Old memo </a></li>
        <li><a href="/addgrades.php"> <i class="fa fa-table"></i>  Generate CMM </a></li>
        <li><a href="/student_credits.php"> <i class="fa fa-table"></i> Student Credits Status </a></li>
        
        <!--<li><a href="cmmreport.php">Cmm Report</a></li>-->
        <!--<li><a href="managemarks.php">Manage Marks</a></li>-->
        
    </ul>
</li>


<li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-asterisk "></i><span class="hide-menu">Masters</span></a>
<ul aria-expanded="false" class="collapse">
    <li><a href="/papers.php">View Paper</a></li>
    <li><a href="/examtable.php">View Exam</a></li>
    <li><a href="/studentsdata.php">View all Students Data</a></li>
     <li>
         <!--<a href="addholder.php"> Add Subject Marks</a>-->
     </li>
     <li><a href="registerstudent.php">Register Student</a></li>
     
    
</ul>
</li>
                        
                        
<li> <a class="has-arrow  " href="index.php?loggedout=true" aria-expanded="false"><i class="fa fa-angle-double-right"></i><span class="hide-menu">LogOut</span></a>

</li>
                    
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </div>
        
        
      
      
