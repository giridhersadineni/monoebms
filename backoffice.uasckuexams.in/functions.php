<?php
include "config.php";


function getrows($sql)
{
    
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    } else {
        $sql = "SELECT * FROM subs5sem WHERE COURSE='" . $course . "' and GROUPCODE='" . $group . "'and MEDIUM='" . $medium . "'";
        $result = $conn->query($sql);
        $aresult = $result;
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

                $resultset[] = array(
                    'PAPERCODE' => $row['PAPERCODE'],
                    'PAPERNAME' => $row['PAPERNAME'],
                    'PAPERTYPE' => $row['PAPERTYPE'],
                    'PAPERGROUP' => $row["EGROUP"],
                );

            }

        } else {

        }
    }

    return $resultset;

}





function getPartOneCgpa($hallticket){
       
    global $servername,$dbuser,$dbpwd,$dbname;
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    } else {
        $sql = "SELECT * from rholdernew where HALLTICKET=$hallticket  and PART=1 AND RESULT!='F'" ;
        $result = $conn->query($sql);
        $aresult = $result;
        $marks=0;
        $total=0;
        $r=array();
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $marks+=$row['MARKS'];
                $total+=$row['TOTALMARKS'];
                $credittotal+=$row['CREDITS'];
                $gpctotal+=$row['GPC'];
                
            }
            $r['MARKS']=$marks;
            $r['TOTAL']=$total;
            $r['CTOTAL']=$credittotal;
            $r['GTOTAL']=$gpctotal;
            $r['CGPA']=round($gpctotal/$credittotal,2);
            return $r;

        } else {

        }
    }
}

function getPartTwoCgpa($hallticket){
       
    global $servername,$dbuser,$dbpwd,$dbname;
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    } else {
        $sql = "SELECT * from rholdernew where HALLTICKET=$hallticket  and PART=2 AND RESULT!='F'" ;
        $result = $conn->query($sql);
        $aresult = $result;
        $marks=0;
        $total=0;
        $r=array();
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $marks+=$row['MARKS'];
                $total+=$row['TOTALMARKS'];
                $credittotal+=$row['CREDITS'];
                $gpctotal+=$row['GPC'];
                
            }
            $r['MARKS']=$marks;
            $r['TOTAL']=$total;
            $r['CTOTAL']=$credittotal;
            $r['GTOTAL']=$gpctotal;
            $r['CGPA']=round($gpctotal/$credittotal,2);
            return $r;

        } else {

        }
    }
}



?>


