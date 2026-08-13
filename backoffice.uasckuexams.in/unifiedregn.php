<?php 
include('header.php' ); 
include('projectorm.php');
?>

    <div class="page-wrapper">
    <!-- Bread crumb -->
        <div class="row page-titles">
        
        <div class="col-md-5 align-self-center">
                <h3 class="text-primary">Exam Regular</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Exams</a></li>
            <li class="breadcrumb-item active">Registration </li>
        </ol>
    </div>
</div>

<div class='container-fluid'>
<div class='row'> 

<?php
$hallticket = $_COOKIE['userid'];
$query="select * from students where haltckt='".$hallticket."'";
$student = get_result_array($query)[0];
function getPapers($hallticket , $semester=""){
    if($semester==""){
        $query="select * from RESULTS where HALLTICKET='".$hallticket."'";
    }
    else{
        $query="select * from RESULTS where HALLTICKET='".$hallticket."' and SEMESTER=".$semester;
    }
    $papers = get_result_array($query);
    return $papers;
}

?>

<div class="card">
    
<?php 

echo $getOpenExams = "select * from examsmaster where STATUS='NOTIFY' and SCHEME='".$student['SCHEME'] ."'";

$openExams = get_result_array($getOpenExams);
?>
<pre>
<?= var_dump($openExams);?>
</pre>

</div>


</div>
</div>

<?php include('footer.php' ); ?>