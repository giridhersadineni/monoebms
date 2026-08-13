<?php 
include "../projectorm.php";
include "cmmrepo.php";

header("Content-Type:   application/x-sql; charset=utf-8");
header("Content-Disposition: attachment; filename=queries.sql"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);


$result=get_result_array("select * from grades where P1CGPA=0 ");

foreach($result as $student){
    $data=get_student_result($student['HALLTICKET']);

    $p1cgpa=round($data['part1'][cgpa],2);
    $p1cf=round($data['part1'][cf],2);
    $p1div=$data['part1'][division];
    
    $p2cgpa=round($data['part2'][cgpa],2);
    $p2cf=round($data['part2'][cf],2);
    $p2div=$data['part2'][division];
    
    $allcgpa=round($data['consolidated'][cgpa],2);
    $allcf=round($data['consolidated'][cf],2);
    $alldiv=$data['consolidated'][division];
    
    $query= "update grades set P1CGPA=$p1cgpa, P2CGPA=$p2cgpa, ALLCGPA=$allcgpa, P1CF=$p1cf , P2CF=$p2cf , ALLCF=$allcf , P1DIV='$p1div ',P2DIV = '$p2div' ,FINALDIV='$alldiv' where HALLTICKET='".$student['HALLTICKET']."'";
    
    echo $query.";\n";
    
    
}








?>