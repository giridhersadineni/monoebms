<?php
include "config.php";


function getresult($sql)
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
       }
        $conn->close();
    }
    
    return $resultset;

}


function get_one($sql)
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
       }
       $conn->close();
    }
    $returndata=mysqli_fetch_assoc($resultset);
    return $returndata;

}


function get_result_array($sql){
    $resultset=getresult($sql);
    $ret=array();
    while($row=mysqli_fetch_assoc($resultset)){
        array_push($ret,$row);
    }
    return $ret;
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

function getdatatable($sql,$class,$id,$action="",$secondaction="",$thirdaction){
    
    $rows=getresult($sql);
    $flag=true;
    $firstrow=mysqli_fetch_assoc($rows);
    echo "<table id='$id' class='$class'>";
    echo "<thead><tr>";
    if($action!=""){
    echo "<th>Action 1</th>";
    }
    if($secondaction!=""){
        echo "<th>Action 2</th>";
    }
    if($thirdaction!=""){
        echo "<th>Action 3</th>";
    }
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
        echo "<td>$id</td>";
        if($secondaction!=""){
        echo "<td><button onclick='$secondaction($id);'  class='btn btn-info' data='$json' id='$id'>$secondaction</button></td>";
        }
        if($thirdaction!=""){
        echo "<td><button onclick='$thirdaction($id);'  class='btn btn-info' data='$json' id='$id'>$thirdaction</button></td>";
        }
        foreach($row as $value){
         echo "<td>$value</td>";   
        }
        echo "</tr>";
    }
    
    echo "</tbody>";
    
    echo "<tfoot></tfoot></table>";
}

//getdatatable("select * from gpas","table table-striped");



?>
