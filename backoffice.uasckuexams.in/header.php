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
    <!-- Bootstrap Core CSS -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->

    <link href="css/lib/calendar2/semantic.ui.min.css" rel="stylesheet">
    <link href="css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-131292962-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-131292962-2');
  gtag('set', 'userId', '<?php echo $_COOKIE['name'].":".$_COOKIE['haltckt']; ?>'); // Set the user ID using signed-in user_id.
</script>

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
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu text-white"></i>  UNIVERSITY ARTS & SCIENCE COLLEGE EXAM BRANCH</a></li>
                    
                    </ul>
                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0">

                        <!-- Search -->
                        <!--<li class="nav-item hidden-sm-down search-box"> <a class="nav-link hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-search"></i></a>-->
                        <!--    <form class="app-search">-->
                        <!--        <input type="text" class="form-control" placeholder="Search here"> <a class="srh-btn"><i class="ti-close"></i></a> </form>-->
                        <!--</li>-->
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
       <!--                 <li class="nav-item dropdown">-->
       <!--                     <a class="nav-link dropdown-toggle text-muted  " href="#" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-envelope"></i>-->
							<!--	<div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>-->
							<!--</a>-->
							
                            <!--<div class="dropdown-menu dropdown-menu-right mailbox animated zoomIn" aria-labelledby="2">-->
                            <!--    <ul>-->
                            <!--        <li>-->
                            <!--            <div class="drop-title">You have 4 new messages</div>-->
                            <!--        </li>-->
                            <!--        <li>-->
                            <!--            <div class="message-center">-->
                                            <!-- Message -->
                            <!--                <a href="#">-->
                            <!--                    <div class="user-img"> <img src="images/users/5.jpg" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>-->
                            <!--                    <div class="mail-contnet">-->
                            <!--                        <h5>Michael Qin</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span>-->
                            <!--                    </div>-->
                            <!--                </a>-->
                                            <!-- Message -->
                            <!--                <a href="#">-->
                            <!--                    <div class="user-img"> <img src="images/users/2.jpg" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span> </div>-->
                            <!--                    <div class="mail-contnet">-->
                            <!--                        <h5>John Doe</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span>-->
                            <!--                    </div>-->
                            <!--                </a>-->
                                            <!-- Message -->
                            <!--                <a href="#">-->
                            <!--                    <div class="user-img"> <img src="images/users/3.jpg" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>-->
                            <!--                    <div class="mail-contnet">-->
                            <!--                        <h5>Mr. John</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span>-->
                            <!--                    </div>-->
                            <!--                </a>-->
                                            <!-- Message -->
                            <!--                <a href="#">-->
                            <!--                    <div class="user-img"> <img src="images/users/4.jpg" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>-->
                            <!--                    <div class="mail-contnet">-->
                            <!--                        <h5>Michael Qin</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>-->
                            <!--                    </div>-->
                            <!--                </a>-->
                            <!--            </div>-->
                            <!--        </li>-->
                            <!--        <li>-->
                            <!--            <a class="nav-link text-center" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>-->
                            <!--        </li>-->
                            <!--    </ul>-->
                            <!--</div>-->
                        </li>
                        <!-- End Messages -->
                        <!-- Profile -->
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted"
                        href="#" data-toggle="dropdown" 
                        aria-haspopup="true" aria-expanded="false">
                        <img src="<?php echo 'upload/images/' . $_COOKIE['aadhar'] . '.jpg'; ?>" alt="user" class="profile-pic" /></a>
                        <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                        <ul class="dropdown-user">
                            <li><a href="#"><i class="ti-user"></i> <?php echo $_COOKIE['name']; ?></a></li>
                            <li><a href="#"><i class="ti-wallet"></i> <?php echo $_COOKIE['userid']; ?></a></li>
                            <!--<li><a href="#"><i class="ti-email"></i> Inbox</a></li>-->
                            <!--<li><a href="#"><i class="ti-settings"></i> Setting</a></li>-->
                            <li><a href="/index.php?loggedout=true"><i class="fa fa-power-off"></i> Logout</a></li>
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
                        <li class="nav-label">UASKU EBMS</li>
<li> <a  href="welcome.php" aria-expanded="false"><i class="fa fa-home text-danger"></i><span class="hide-menu">Home</span></a></li>
<li> <a  href="selectexam.php"  aria-expanded="false"><i class="fa fa-edit"></i><span class="hide-menu">Exam Registration</span></a></li>
<li> <a  href="enrollments.php" aria-expanded="false"><i class="fa fa-address-book"></i><span class="hide-menu">Registered Exams</span></a></li>
<li> <a  href="transactions.php" aria-expanded="false"><i class=" fa fa-book"></i><span class="hide-menu">Transcations Details</span></a></li>
<li> <a  href="newimageupload.php" aria-expanded="false"><i class="fa fa-user"></i><span class="hide-menu">Change Photo And Signature</span></a></li>
<li><a href="index.php?loggedout=true" aria-expanded="false"><i class="fa fa-angle-double-right"></i><span class="hide-menu"><font color="black"> Logout </font></span></a></li>      
                    
                        
                        <!--<li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-table"></i><span class="hide-menu">Tables</span></a>-->
                        <!--    <ul aria-expanded="false" class="collapse">-->
                        <!--        <li><a href="table-bootstrap.html">Basic Tables</a></li>-->
                        <!--        <li><a href="table-datatable.html">Data Tables</a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->
                        <!--<li class="nav-label">Layout</li>-->
                        <!--<li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-columns"></i><span class="hide-menu">Layout</span></a>-->
                        <!--    <ul aria-expanded="false" class="collapse">-->
                        <!--        <li><a href="layout-blank.html">Blank</a></li>-->
                        <!--        <li><a href="layout-boxed.html">Boxed</a></li>-->
                        <!--        <li><a href="layout-fix-header.html">Fix Header</a></li>-->
                        <!--        <li><a href="layout-fix-sidebar.html">Fix Sidebar</a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->
                        <!--<li class="nav-label">EXTRA</li>-->
                        <!--<li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-book"></i><span class="hide-menu">Pages <span class="label label-rouded label-success pull-right">8</span></span></a>-->
                        <!--    <ul aria-expanded="false" class="collapse">-->

                        <!--        <li><a href="#" class="has-arrow">Authentication <span class="label label-rounded label-success">6</span></a>-->
                        <!--            <ul aria-expanded="false" class="collapse">-->
                        <!--                <li><a href="page-login.html">Login</a></li>-->
                        <!--                <li><a href="page-register.html">Register</a></li>-->
                        <!--                <li><a href="page-invoice.html">Invoice</a></li>-->
                        <!--            </ul>-->
                        <!--        </li>-->
                        <!--        <li><a href="#" class="has-arrow">Error Pages</a>-->
                        <!--            <ul aria-expanded="false" class="collapse">-->
                        <!--                <li><a href="page-error-400.html">400</a></li>-->
                        <!--                <li><a href="page-error-403.html">403</a></li>-->
                        <!--                <li><a href="page-error-404.html">404</a></li>-->
                        <!--                <li><a href="page-error-500.html">500</a></li>-->
                        <!--                <li><a href="page-error-503.html">503</a></li>-->
                        <!--            </ul>-->
                        <!--        </li>-->
                        <!--    </ul>-->
                        <!--</li>-->
                        <!--<li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-map-marker"></i><span class="hide-menu">Maps</span></a>-->
                        <!--    <ul aria-expanded="false" class="collapse">-->
                        <!--        <li><a href="map-google.html">Google</a></li>-->
                        <!--        <li><a href="map-vector.html">Vector</a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->
                        <!--<li> <a class="has-arrow  " href="#" aria-expanded="false"><i class="fa fa-level-down"></i><span class="hide-menu">Multi level dd</span></a>-->
                        <!--    <ul aria-expanded="false" class="collapse">-->
                        <!--        <li><a href="#">item 1.1</a></li>-->
                        <!--        <li><a href="#">item 1.2</a></li>-->
                        <!--        <li> <a class="has-arrow" href="#" aria-expanded="false">Menu 1.3</a>-->
                        <!--            <ul aria-expanded="false" class="collapse">-->
                        <!--                <li><a href="#">item 1.3.1</a></li>-->
                        <!--                <li><a href="#">item 1.3.2</a></li>-->
                        <!--                <li><a href="#">item 1.3.3</a></li>-->
                        <!--                <li><a href="#">item 1.3.4</a></li>-->
                        <!--            </ul>-->
                        <!--        </li>-->
                        <!--        <li><a href="#">item 1.4</a></li>-->
                        <!--    </ul>-->
                        <!--</li>-->
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </div>
        
        
      
      
