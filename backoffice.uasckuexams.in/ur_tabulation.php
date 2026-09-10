<style>
    *{
        font-family:arial;
    }
    .resulttable{
        border-bottom:1px solid black;
        padding-bottom:1em;
        padding-top:1em;
    }
</style>
<?php
ini_set('display_errors', 1);
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
    } else {
    $getexamname=$conn->query("select * from examsmaster where EXID=".$_GET['exid']);
    $exam=mysqli_fetch_assoc($getexamname);
        echo '<h3>'.$exam['EXAMNAME'].'</h3>';
    
    $getcandidates="select distinct(HALLTICKET),`EID` FROM rholdernew WHERE EXAMID=".$_GET['exid']." ORDER BY HALLTICKET";
    //echo $getcandidates;
    
    $candidates=$conn->query($getcandidates);

    while($candidate=mysqli_fetch_assoc($candidates)){
     echo '<div class="resulttable">';
               
        $getname=$conn->query('select concat("<b style=padding-left:1em;> Student Name: </b>",sname,"<b style=padding-left:1em;> Parent Name: </b>",fname) as details from students where haltckt ="'.$candidate['HALLTICKET'].'"');
        $name=mysqli_fetch_assoc($getname);
                   echo"<b style='padding-left:1em;'>EID:</b> ".$candidate['EID']."</b>";
     echo "<b style='padding-left:1em;'>Hallticket:</b> ". $candidate['HALLTICKET']." ".$name['details'];
     
     echo '<br>';
        
    
        $sql="SELECT `PAPERCODE`,`INT`,`EXT`,`GRADE`,`EID` FROM rholdernew WHERE HALLTICKET='".$candidate['HALLTICKET']."' AND EXAMID=".$_GET['exid']." AND GRADE !='#VALUE!' ORDER BY EXAMID";
        $result = $conn->query($sql);

        echo '<table><tr>';

        echo '<td style="padding:1em";>EID<br><br>PCODE<br>INT<br>EXT<br>GRADE<b></td>';
                          
        while($row=mysqli_fetch_assoc($result)){
            
          echo '<td style="padding-left:2em;">'.$row['PAPERCODE'].'<br>'.ROUND($row['INT']).'<br>'.$row['EXT'].'<br>'.$row['GRADE'].'</td>';
             
        }
 
 echo '</tr></table>';
 
 
 
$qsql="SELECT `TOTAL`,`RESULT`,`SGPA`,`CGPA` FROM gpas WHERE HALLTICKET='".$candidate['HALLTICKET']."' AND EXAMID=".$_GET['exid'];
 $total= $conn->query($qsql);
 
echo '<table><tr>';
  //echo '<td style="padding:0.5em;"><b>TOTAL<br>RESULT<br>SGPA<br>CGPA<b></td>';
    while($row=mysqli_fetch_assoc($total)){
  
          echo '<td style="padding-left:1em;"><b>TOTAL : </b>'.$row['TOTAL'].'</td>';
          echo '<td style="padding-left:2em;"><b>RESULT : </b>'.$row['RESULT'].'</td>';
          echo '<td style="padding-left:2em;"><b>SGPA : </b>'.$row['SGPA'].'</td>';
         // echo '<td style="padding-left:2em;"><b>CGPA:</b>'.$row['CGPA'].'</td>';
          
            
        }
        echo '</tr></table>';
        
        
        
     
       echo '</div><!--endof result table-->';
    }
}
?>

</table>