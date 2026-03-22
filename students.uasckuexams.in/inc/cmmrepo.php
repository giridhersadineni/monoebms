<?php
require_once("../projectorm.php");

$hallticket=$_GET['hallticket'];

function calcsgpa($papers)
{
    $credit_total = 0;
    $gpc_total = 0;
    foreach ($papers as $paper) {
        if ($paper['GRADE'] == 'EX') {
            continue;
        }
        $gpc_total += $paper['GPC'];
        $credit_total += $paper['CREDITS'];
    }

    $gpa = $gpc_total / $credit_total;
    $gpa = round($gpa, 2);
    //echo $gpa."<br>";

    return $gpa;
}

function get_division($gpa, $cf)
{
    $percentage = ($gpa * 10) - $cf;
    if ($gpa >= 7) {
        return "FIRST DIVISION WITH DISTINCTION";
    } else if ($gpa >= 6) {
        return "FIRST DIVISION";
    } else if ($gpa >= 5) {
        return "SECOND DIVISION";
    } else {
        return "PASS";
    }

    //return $percentage;
}

function consolidatereport($papers)
{
    $marks_sec = 0;
    $totalmarks = 0;
    $credit_total = 0;
    $gpc_total = 0;
    $gpcc_total = 0;
    $percentages = array();
    $cgpa_percentages = array();
    foreach ($papers as $paper) {
        if ($paper['GRADE'] == 'EX') {
            continue;
        }
        $gpc_total += $paper['GPC'];
        $credit_total += $paper['CREDITS'];
        $marks_sec += $paper['MARKS'];
        $totalmarks += $paper['TOTALMARKS'];
        $gpcc_total += $paper['GPCC'];
    }


    $result = array();
    $result['marks'] = $marks_sec;
    $result['total'] = $totalmarks;
    $result['gpc_total'] = $gpc_total;
    $result['credits'] = $credit_total;
    $result['cgpa'] = $gpc_total / $credit_total;
    $result['OGPA'] = $gpcc_total / $credit_total;
    $per = $marks_sec / $totalmarks * 100;
    $cper = $result['OGPA'] * 10;
    $result['cf'] = $per - $cper;
    $result['division']=get_division($result['cgpa'],$result['cf']);
    return $result;
}


function get_student_result($hallticket){
    

include '../config.php';
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $getsem1 = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='$hallticket' AND PAPERCODE LIKE '1%' ORDER BY PAPERCODE";
    $getsem2 = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='$hallticket' AND PAPERCODE LIKE '2%' ORDER BY PAPERCODE";
    $getsem3 = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='$hallticket' AND PAPERCODE LIKE '3%' ORDER BY PAPERCODE";
    $getsem4 = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='$hallticket' AND PAPERCODE LIKE '4%' ORDER BY PAPERCODE";
    $getsem5 = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='$hallticket' AND PAPERCODE LIKE '5%' ORDER BY PAPERCODE";
    $getsem6 = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='$hallticket' AND PAPERCODE LIKE '6%' ORDER BY PAPERCODE";



    //PART 1
    // $getpart1 = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='$hallticket' AND PART=1 ORDER BY PAPERCODE";
    // $part1result = $conn->query($getpart1);
    // $part1papers = array();
    // while ($sub = mysqli_fetch_assoc($part1result)) {
    //     array_push($part1papers, $sub);
    // };
    // $part1 = consolidatereport($part1papers);
    //print_r($part1);

    $getpart1 = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='$hallticket' AND PART=1 ORDER BY PAPERCODE";
    $part1papers =get_result_array($getpart1);
    $part1 = consolidatereport($part1papers);



    // PART 2
    $getpart2 = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='$hallticket' AND PART=2 ORDER BY PAPERCODE";
    $part2result = $conn->query($getpart2);
    $part2papers = array();
    while ($sub = mysqli_fetch_assoc($part2result)) {
        array_push($part2papers, $sub);
    };
    $part2 = consolidatereport($part2papers);

    // overall
    $getoverall = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='$hallticket' ORDER BY PAPERCODE";
    $overallresult = $conn->query($getoverall);
    $overallpapers = array();
    while ($sub = mysqli_fetch_assoc($overallresult)) {
        array_push($overallpapers, $sub);
    };
    $overall = consolidatereport($overallpapers);

    $s1subs = $conn->query($getsem1);
    $sem1subs = array();
    while ($sub = mysqli_fetch_assoc($s1subs)) {
        array_push($sem1subs, $sub);
    };

    $s2subs = $conn->query($getsem2);
    $sem2subs = array();
    while ($sub = mysqli_fetch_assoc($s2subs)) {
        array_push($sem2subs, $sub);
    };


    $s3subs = $conn->query($getsem3);
    $sem3subs = array();
    while ($sub = mysqli_fetch_assoc($s3subs)) {
        array_push($sem3subs, $sub);
    };


    $s4subs = $conn->query($getsem4);
    $sem4subs = array();
    while ($sub = mysqli_fetch_assoc($s4subs)) {
        array_push($sem4subs, $sub);
    };


    $s5subs = $conn->query($getsem5);
    $sem5subs = array();
    while ($sub = mysqli_fetch_assoc($s5subs)) {
        array_push($sem5subs, $sub);
    };


    $s6subs = $conn->query($getsem6);
    $sem6subs = array();
    while ($sub = mysqli_fetch_assoc($s6subs)) {
        array_push($sem6subs, $sub);
    };
}


$gpas=array();
$gpas['sem1'] = calcsgpa($sem1subs);
$gpas['sem2'] = calcsgpa($sem2subs);
$gpas['sem3'] = calcsgpa($sem3subs);
$gpas['sem4'] = calcsgpa($sem4subs);
$gpas['sem5'] = calcsgpa($sem5subs);
$gpas['sem6'] = calcsgpa($sem6subs);
$gpas['part1'] = calcsgpa($part1papers);
$gpas['part2'] = calcsgpa($part2papers);
$gpas['overall'] = calcsgpa($overallpapers);
$data['gpas']=$gpas;
$data['consolidated']=$overall;
$data['part1']=$part1;
$data['part2']=$part1;
return $data;
}
// $sgpa['consolidated']=

echo $jsonstring=json_encode(get_student_result($hallticket));
// echo "<pre>";
// print_r(json_decode($jsonstring));
// echo "</pre>";
?>