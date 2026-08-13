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
            $r['CGPA']=$gpctotal/$credittotal;
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



function getresult($sql)
{
    global $servername, $dbuser, $dbpwd, $dbname;
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    } 
    else 
    {
       $resultset=$conn->query($sql);    
    //   var_dump($resultset);
    }

    return $resultset;

}



function getrow($sql)
{
    global $servername, $dbuser, $dbpwd, $dbname;
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    } 
    else 
    {
    $resultset=$conn->query($sql);  
    $row=mysqli_fetch_array($resultset);
    print_r($row);
    return $row;
    }

}

function get_result_array($sql){
    $resultset=getresult($sql);
    $ret;
    while($row=mysqli_fetch_assoc($resultset)){
        $ret=$row;
    }
    return $ret;
}

function getscalarvalue($sql)
{
    global $servername, $dbuser, $dbpwd, $dbname;
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    } 
    else 
    {
         $resultset=$conn->query($sql);    
         $array=mysqli_fetch_array($resultset);
         $returnvalue = array_shift($array);
    }
    
    return $returnvalue;

}



function getjsonresult($sql)
{
    global $servername, $dbuser, $dbpwd, $dbname;
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    } 
    else 
    {
       if($resultset=0){
           return 0;
       }
       else{
         $resultset=$conn->query($sql);    
         $jsonresult=json_encode($resultset);
       }
       
    }

    return $jsoneresult;

}

function getdatatable($sql,$class,$id,$action=""){
    
    $rows=getresult($sql);
    $flag=true;
    $firstrow=mysqli_fetch_assoc($rows);
    echo "<table id='$id' class='$class'>";
    echo "<thead><tr>";
    echo "<th>Action</th>";
    $delid=array_shift($firstrow);
    foreach($firstrow as $key=>$value){
       echo "<th>".$key."</th>";
    }
    echo "</tr></thead>";
    
    echo "<tbody>";
    while($row=mysqli_fetch_assoc($rows)){
        echo "<tr>";
        $json=json_encode($row);
        $id=array_shift($row);
        echo "<td><button onclick='$action($id);'  class='btn btn-primary' data='$json' id='$id'>$action</button></td>";
        foreach($row as $value){
         echo "<td>$value</td>";   
        }
        echo "</tr>";
    }
    
    echo "</tbody>";
    
    echo "<tfoot></tfoot></table>";
}


?>

