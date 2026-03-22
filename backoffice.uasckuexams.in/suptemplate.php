<?php
    include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} 
else 
{
    $examid=$_POST['examid'];
    $examreg = "select * from examsmaster where EXID='" . $examid . "'";
    $examres = $conn->query($examreg);
    $exam = mysqli_fetch_assoc($examres);

    $getstudent="SELECT * from students where haltckt='". $_POST['HALLTICKET'] . "'";
    $studentres = $conn->query($getstudent);
    $student = mysqli_fetch_assoc($studentres);

}

function getSupplyFee($student,$exam,$count){
        $course = $student['course'];
        $group = $student['group'];
        if($course=="BSC"){
            if($count<=2){
                return $exam['BSC_FEE'] + $exam['FINE'];
            }
            else{
                return $exam['BSC_ABOVE_2'] + $exam['FINE'];
            }
        }
        if($course=="BCOM"){
            if($group=="GEN"){
                    
                if($count<=2){
                    return $exam['BCOM_FEE'] + $exam['FINE'];
                }
                else{
                    return $exam['BCOM_ABOVE_2'] + $exam['FINE'];
                }
            }
            if($group=="CA"){
                if($count<=2){
                    return $exam['BCOMCA_FEE'] + $exam['FINE'];
                }
                else{
                    return $exam['BCOMCA_ABOVE_2'] + $exam['FINE'];
                }
            }
        }
        if($course=="BA"){
                if($count<=2){
                    return $exam['BA_FEE'] + $exam['FINE'];
                }
                else{
                    return $exam['BA_ABOVE_2'] + $exam['FINE'];
                }
        }
}


if (isset($_POST['subs'])) {
    $checked_arr = $_POST['subs'];
    $count = count($checked_arr);
}

if (isset($_POST['E1'])) {
    $count++;
} else {
    $e1 = 'null';
}
if (isset($_POST['E2'])) {
    $count++;
} else {
    $e2 = 'null';
}
if (isset($_POST['E3'])) {
    $count++;
} else {
    $e3 = 'null';
}
if (isset($_POST['E4'])) {
    $count++;
} else {
    $e4 = 'null';
}
if (isset($_POST['E5'])) {
    $count++;
} else {
    $e5 = 'null';
}


if ($_POST['extype'] == "IMPROVEMENT") {
    $improvement=$exam['IMPROVEMENT'];
    $fee = $count * 300;
}else{
    $fee = getSupplyFee($student,$exam,$count);
}



// print_r($_POST);
?>



<?php include "header.php";?>
<div class="page-wrapper">
            <!-- Bread crumb -->
   <div class="row page-titles">
      <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Exam Registration Regular</h3> </div>
         <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Exams</a></li>
                        <li class="breadcrumb-item active">Enrollment</li>
                    </ol>
           </div>
       </div>
            <!-- End Bread crumb -->
            <!-- Container fluid  -->
<div class="container-fluid">
                <!-- Start Page Content -->
<div class="row">
<div class="col-sm-12">
<div class="card">
<div class="table-responsive">


 <!--Content-->
<?php
echo "<h2> Your Selected  $count Subjects</h2> ";
if (isset($_POST['subs'])) {
    $subjects = $_POST['subs'];
    echo implode("<br> ", $subjects);

    for ($i = 0; $i < 10; $i++) {

        if (isset($subjects[$i])) {
            $subjects[$i] = substr($subjects[$i], 0, strpos($subjects[$i], ":"));
        } else {
            $subjects[$i] = 'null';
        }
    }

}

if (isset($_POST['E1'])) {
    echo "<br>" . $_POST['E1'];
    $e1 = substr($_POST['E1'], 0, strpos($_POST['E1'], ":"));
}
if (isset($_POST['E2'])) {
    echo "<br>" . $_POST['E2'];
    $e2 = substr($_POST['E2'], 0, strpos($_POST['E2'], ":"));
}
if (isset($_POST['E3'])) {
    echo "<br>" . $_POST['E3'];
    $e3 = substr($_POST['E3'], 0, strpos($_POST['E3'], ":"));
}
if (isset($_POST['E4'])) {
    echo "<br>" . $_POST['E4'];
    $e4 = substr($_POST['E4'], 0, strpos($_POST['E4'], ":"));
}
if (isset($_POST['E5'])) {
    echo "<br>" . $_POST['E5'];
    $e5 = substr($_POST['E5'], 0, strpos($_POST['E5'], ":"));
}
?>

<br><br><br>


<br>


 <form action="registrationsupply.php" method="POST">

<?php
for ($i = 0; $i < 10; $i++) {
    echo '<input type="hidden"  name="subjects[]" value="' . $subjects[$i] . '">';
}


echo '<input type="hidden" name="e1" value="' . $e1 . '">';
echo '<input type="hidden" name="e2" value="' . $e2 . '">';
echo '<input type="hidden" name="e3" value="' . $e3 . '">';
echo '<input type="hidden" name="e4" value="' . $e4 . '">';
echo '<input type="hidden" name="e4" value="' . $e5 . '">';
echo '<input type="hidden" name="HALLTICKET" value="' . $_POST['HALLTICKET'] . '">';
echo '<input type="hidden" name="userid" value="' . $_COOKIE['userid'] . '">';
echo '<input type="hidden" name="examid" value="' . $_POST['examid'] . '">';
echo '<input type="hidden" name="fee" value="' . $fee . '">';
echo '<input type="hidden" name="stid" values="'.$_COOKIE['stid'].'">';
echo '<input type="hidden" name="extype" value="' . $_POST['extype'] . '">';
echo '<input type="hidden" name="examid" value="' . $_POST['examid'] .'">';
echo '<input type="hidden" name="course" value="' . $_POST['course'] .'">';
echo '<input type="hidden" name="group" value="' . $_POST['group'] .'">';
echo '<input type="hidden" name="medium" value="' . $_POST['medium'] .'">';
echo '<input type="hidden" name="semester" value="' . $_POST['semester'] .'">';
echo '<input type="hidden" name="et" value="' .$exam['EXAMTYPE'] .'">';

?>
 <h4>Total Amount:<input type=text name="fee" value="<?=$fee;?>"  disabled></h4>
 <a  href="examenrollsupply.php? class="btn btn-warning" >Back</a>

 <input type="submit" class="btn btn-success" name="confirm" value="confirm">
 </form>
</div>
</div>
</div>
</div>
<!--end of page wrapper-->
</div><!--Responsive table end-->
     </div>
       </div>
                </div>
                <!-- End PAge Content -->
            </div>
            <!-- End Container fluid  -->
        </div>
<?php include "footer.php";?>