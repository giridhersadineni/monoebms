<?php include 'header.php'?>
<div class="page-wrapper bg-white">
    <div class="row">
    <div class="col-md-12">
    <div class='card'>
 <center>
    <div class="row">
        <div class="col-6">
            <form action="cmmreport.php" method="POST">
            <div class="input-group p-1 bg-warning">
                <input type="text"  name="HALLTICKET" placeholder="Hallticket" class="form-control input-prepend">
                <input type="submit" name="submit" value="View Report" class="btn btn-success input-apppend"></input>
            </div>
            </form>
        </div>
        <div class="col-6">
            <a  href="printcmm.php?hallticket=<?php echo $_POST['ht']?>"  class="btn btn-primary">PRINT MEMO</a>
        </div>
    </div>
</center>
       
    <div class="table-responsive m-t-40">
        
<?php
include "config.php";
//check connection
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
    
         $getresult="select * from RESULTS where HALLTICKET='".$_POST['HALLTICKET']."' ORDER BY PAPERCODE";
        //  echo $getresult;
         echo '<table id="example23" class="display nowrap table table-hover
         table-striped table-bordered" cellspacing="0" width="100%">';
         $enrolledexams=[];
         $subs=$conn->query($getresult);
         
         
 ?>

<thead>
<tr>
    <TH>RHID	</TH>
<TH>EXAMID		</TH>
<TH>PAPERCODE</TH>
<TH>PAPERNAME</TH>
<TH>PART	</TH>
<TH>HALLTICKET</TH>
<TH>EID	</TH>
<TH>SCRIPTCODE</TH>
<TH>PASSED_BY_FLOATATION</TH>
<TH>IS_MALPRACTICE</TH>
<TH>IS_AB_EXTERNAL</TH>
<TH>IS_AB_INTERNAL</TH>
<TH>EXT</TH>
<TH>ETOTAL	</TH>
<TH>INT</TH>
<TH>ITOTAL</TH>
<TH>RESULT	</TH>
<TH>CREDITS</TH>
<TH>MARKS	</TH>
<TH>TOTALMARKS</TH>
<TH>PERCENTAGE</TH>
<TH>GRADE	</TH>
<TH>GPV</TH>
<TH>GPC</TH>
<TH>UPLOADID</TH>
<TH>SEMESTER</TH>
<TH>MYEAR</TH>
<TH>GPVV</TH>
<TH>GPCC</TH>
<TH>MARKER	</TH>
<TH>ATTEMPTS</TH>
<TH>STATUS	</TH>
<TH>FLOATATION_MARKS</TH>
<TH>AC_MARKS</TH>
<TH>MODERATION_MARKS</TH>
<TH>IS_MODERATED</TH>
<TH>FLOAT_DEDUCT</TH>
<TH>FL_SCRIPTCODE</TH>
</tr>
</thead>
<tbody>
<?php
while($paper=mysqli_fetch_assoc($subs)){
            echo "<tr>"; 
            // echo '<td><a  href="editmarks.php?id='. $paper["RHID"]. '&hallticket='. $_POST['ht'].'" class="btn btn-warning"><i class="fa fa-user-edit"></i></a></td>';
            
            foreach($paper as $col){
                echo "<td>".$col."</td>";
                }
            echo "</tr>";
         }

    }
    
    ?>
     


<?php
mysqli_close($conn);
?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>

<?php include 'datatablefooter.php'?>
