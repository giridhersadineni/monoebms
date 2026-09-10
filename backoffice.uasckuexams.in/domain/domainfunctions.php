<?php
include "../projectorm.php";

function isAbsentInternal($paper){
    if($paper['INT']=="AB"){
    return true;
    }
    return false;
}
function isAbsentExternal($paper){
    if($paper['EXT']=="AB"){
        return true;
    }
    return false;
}

function isAbsent($paper){
    return isAbsentExternal($paper);
}




function isPracticalPaper($paper){
    if($paper['INT']==0 && $paper['ITOTAL']==0){
        return true;
    }
    return false;
}
function isTheoryPaper($paper){
    if( $paper['ETOTAL']!=0 && $paper['ITOTAL']!=0 ){
        return true;
    }
    return false;
}
function isPassedExternal($paper){
    if(isAbsentExternal($paper)){
        return false;
    }
    else if($paper['EXT']/$paper['ETOTAL']>=0.40){
        return true;
    }    
    return false;
}

function isPassedInternal($paper){
    if(isAbsentInternal($paper)){
        return false;
    }
    else if($paper['ITOTAL']==0 && $paper['INT']==0){
        return true;
    }
    else if($paper['INT']/$paper['ITOTAL']>=0.40){
        return true;
    }    
    return false;
}

function getPaperByScriptCode($table,$scriptcode){
    
    
}


function isPassedExternalOnly($paper){
    if($paper['EXT']/$paper['TOTALMARKS']>=0.40){
        return true;
    }
}
function isFailedInternal($paper){
    if($paper['INT']/$paper['ITOTAL']>=0.40){
        return true;
    }
}
function isFailedExternal($paper){
    if($paper['EXT']/$paper['ETOTAL']>=0.40){
        return true;
    }
}

function calculateTotal($paper){
    $paper['TOTALMARKS'] = $paper['ITOTAL'] + $paper['ETOTAL'];
    if($paper['EXT']==='AB' &&  $paper['INT']!='AB'){
        $paper['MARKS']=$paper['INT'];
    }
    if($paper['EXT']==='AB' &&  $paper['INT']==='AB'){
        $paper['MARKS']=0;
    }
    if($paper['EXT']!='AB' &&  $paper['INT']=='AB'){
        $paper['MARKS'] = $paper['EXT'];
    }
    
    if($paper['EXT']!='AB' &&  $paper['INT']!='AB'){
        $paper['MARKS']=$paper['EXT']+$paper['INT'];
    }
    return $paper; 
}

function calculatePaperResult($paper){
    if($paper['EXT']==="AB" && $paper['INT']!="AB"){
        $paper['MARKS']=0;
    }
    if($paper['EXT']!="AB" && $paper['INT']!="AB"){
        $paper['MARKS'] = $paper['EXT'] + $paper['INT'];
    }
    if($paper['EXT']!="AB" && $paper['INT']==="AB"){
        $paper['MARKS']=$paper['INT'];
    }
    $paper=calculateTotal($paper);
    $paper['PERCENTAGE'] = round($paper['MARKS']/$paper['TOTALMARKS']*100,2);
    $paper['GPV']=round($paper['PERCENTAGE']/10,2);
    $paper['GPC']=$paper['CREDITS']*$paper['GPV'];
    $paper['GPCC']=$paper['GPC'];
    $paper['GPVV']=$paper['GPV'];
    $paper['GRADE']=getGrade($paper);
    $paper['RESULT']=getResult($paper);
  //  print_r($paper);
    return $paper;
}



function updatePaper($paper){
    global $servername,$dbuser,$dbpwd;
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, "atech_ebms");
    if ($conn->connect_error) {
        die("connection failed:" . mysqli_connect_error());
    } else {
    $query="update PRERESULTS set ";
    $query.= "RESULT='".$paper['RESULT']. "' ";
    $query.= ",GRADE='".$paper['GRADE']. "' ";
    $query.= ",MARKS=".$paper['MARKS']. " ";
    $query.= ",TOTALMARKS=".$paper['TOTALMARKS']. " ";
    $query.= ",GPC=".$paper['GPC']. " ";
    $query.= ",GPCC=".$paper['GPCC']. " ";
    $query.= ",GPV=".$paper['GPV']. " ";
    $query.= ",GPVV=".$paper['GPVV']. " ";
    $query.= ",PERCENTAGE=".$paper['PERCENTAGE']. " ";
    $query.= "WHERE RHID=".$paper['RHID']. " ";
    // echo $query;
        $data=$conn->query($query);
        
    }
}


function updateTableByPaper($table,$paper){
    global $servername,$dbuser,$dbpwd;
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, "atech_ebms");
    if ($conn->connect_error) {
        die("connection failed:" . mysqli_connect_error());
    } else {
    $query="update ".$table." set ";
    $query.= "RESULT='".$paper['RESULT']. "' ";
    $query.= ",GRADE='".$paper['GRADE']. "' ";
    $query.= ",MARKS=".$paper['MARKS']. " ";
    $query.= ",TOTALMARKS=".$paper['TOTALMARKS']. " ";
    $query.= ",GPC=".$paper['GPC']. " ";
    $query.= ",GPCC=".$paper['GPCC']. " ";
    $query.= ",GPV=".$paper['GPV']. " ";
    $query.= ",GPVV=".$paper['GPVV']. " ";
    $query.= ",PERCENTAGE=".$paper['PERCENTAGE']. " ";
    $query.= "WHERE RHID=".$paper['RHID']. " ";
    // echo $query;
        $data=$conn->query($query);
        $conn->close();
    }
    return true;
}


function getGrade($paper){

    if($paper['RESULT']=="AB") {
        return "AB";
    }
    if($paper['RESULT']=="F"){
        return "F";
    }
    else{
        $percentage=$paper['PERCENTAGE'];
        if($percentage>=85 ){
            return "O";
        }
        else if($percentage>=70){
            return "A";
        }
        else if($percentage>=60){
            return "B";
        }
        else if($percentage>=55){
            return "C";
        }
        else if($percentage>=50){
            return "D";
        }
        else if($percentage>=40){
            return "E";
        }
        else{
            return "F";
        }
    }
    
}


function getFailedCount($hallticket,$examid){

    $q="select count(RHID) as FAILEDCOUNT from PRERESULTS where HALLTICKET='".$hallticket."' AND EXAMID='".$examid."' AND RESULT='F'";
    $paper=get_one($q);
    return $paper['FAILEDCOUNT'];
}

function getAbsentCount($hallticket,$examid){
  
    $q="select count(RHID) as ABSENTCOUNT from PRERESULTS where HALLTICKET='".$hallticket."' AND EXAMID='".$examid."' AND RESULT='AB'";
    $paper=get_one($q);
    return $paper['ABSENTCOUNT'];
}

function getInternalAbsentCount($hallticket,$examid){
    $q="select count(RHID) as ABSENTCOUNT from PRERESULTS where HALLTICKET='".$hallticket."' AND EXAMID='".$examid."' AND `INT`='AB'";
    $paper=get_one($q);
    return $paper['ABSENTCOUNT'];
}


function getExternalAbsentCount($hallticket,$examid){
    $q="select count(RHID) as ABSENTCOUNT from PRERESULTS where HALLTICKET='".$hallticket."' AND EXAMID='".$examid."' AND `EXT`='AB'";
    $paper=get_one($q);
    return $paper['ABSENTCOUNT'];
}

function getFailedPaper($hallticket,$examid){
     $failedCount=0;
    $q="select * from PRERESULTS where HALLTICKET='".$hallticket."' AND EXAMID='".$examid."' AND RESULT='F'";
    return get_one($q);
}

function getFloatationMarks($maxmarks){
    if($maxmarks<=50){
        return 3;
    }
    else if($maxmarks<=75){
        return 4;
    }
    else if($maxmarks<=100){
        return 5;
    }
}

function isFloatationApplicable($hallticket,$examid){
    $failedcount=getFailedCount($hallticket,$examid);
    $absentcount=getAbsentCount($hallticket,$examid);
    $internalAbsentCount=getInternalAbsentCount($hallticket,$examid);
    if($failedcount==1 && $absentcount==0){
        $failedPaper=getFailedPaper($hallticket,$examid);
        //  echo implode($failedPaper,"////")."<br>";
                $passmarks=$failedPaper['ETOTAL']*0.4;
                $maxmarks=$failedPaper['TOTALMARKS'];
                //echo $passmarks;
                if(($passmarks-$failedPaper['EXT'])==getFloatationMarks($maxmarks)){
                    return "FL";
                }
                else if(($passmarks-$failedPaper['EXT']) == (getFloatationMarks($maxmarks)+1)){
                    return "FL AC";
                }
                else{
                    return false;
                }
        
    }
    return false;
}

function getPartOnePapers($table,$hallticket){
    if($hallticket==null){
        return $message['error']="Hallticket Empty";
    }
    $query="SELECT * from PRERESULTS where HALLTICKET='".$hallticket."' and PART=1";
    $papers=get_result_array($query);
}

function getPartTwoPapers($table,$hallticket){
     $query="SELECT * from PRERESULTS where HALLTICKET='".$hallticket."' and PART=2";
     
}

function getPart2BySemester($table,$hallticket,$sem){
     $query="SELECT * from PRERESULTS where HALLTICKET='".$hallticket."' and PART=2 and SEMESTER=".$sem;
     
}

function getPart1BySemester($table,$hallticket,$sem){
     $query="SELECT * from PRERESULTS where HALLTICKET='".$hallticket."' and PART=1 and SEMESTER=".$sem;
}
function getPapersPreresult($hallticket,$examid=null,$part=null){
    $query=null;
    if($examid==null && $part==null){
    $query="SELECT * from PRERESULTS where HALLTICKET='".$hallticket."'";
    }
    else if($part!=null){
     $query = "SELECT * from PRERESULTS where HALLTICKET='". $hallticket ."' and PART= " .$part . "";
    }
    else if($examid!=null){
    $query="SELECT * from PRERESULTS where HALLTICKET='".$hallticket."' and PART=".$part;
    }
    else {
         $query="SELECT * from PRERESULTS where HALLTICKET='".$hallticket."' and PART=".$part." and EXAMID='".$examid."'";
    }
    
}



function doPassFloatation($paper){
    
    if($paper['PART']==1){
        
    }
    else if($paper['PART']==2){
        
    }
    else{
        die("Paper Part Error");
    }
}



function consolidatereport(
    $papers
    ){
     $marks_sec=0;
     $totalmarks=0;
     $credit_total=0;
     $gpc_total=0;
     $gpcc_total=0;
     $percentages=array();
     $cgpa_percentages=array();
     $failed_count=0;
     $failedpapers=array();
     foreach($papers as $paper){
         if($paper['GRADE']=='EX'){
            continue;
         }
         
         if($paper['RESULT']=='F'){
            $failed_count++;
            array_push($failedpapers,$paper);
         }
         $gpc_total+=$paper['GPC'];
         $credit_total+=$paper['CREDITS'];
         $marks_sec+=$paper['MARKS'];
         $totalmarks+=$paper['TOTALMARKS'];
     }
     $result=array();
     $result['MARKS']=$marks_sec;
     $result['TOTAL']=$totalmarks;
     $result['gpc_total']=$gpc_total;
     $result['credits']=$credit_total;
     $result['SGPA']=$gpc_total/$credit_total;
     $result['OGPA']=$gpc_total/$credit_total;
     $per=$marks_sec/$totalmarks*100;
     $cper=$result['OGPA']*10;
     $result['failed_count']=$failed_count;
     $result['PERCENTAGE']=$per;
     $result['cf']=$per-$cper;
     if($failed_count==1){
        $testpaper=$failedpapers[0];
        if($testpaper)
        $result['FLOATATION']=1;
     }
     else{
            $result['FLOATATION']=0;
     }
     return $result; 
}




?>