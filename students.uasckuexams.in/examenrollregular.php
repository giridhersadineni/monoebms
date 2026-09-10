<?php include "header.php";?>

<?php
    include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $examid=$_GET['examid'];
    $examreg = "select * from examsmaster where EXID='" . $examid . "'";
 //   print_r($examreg);
    $examres = $conn->query($examreg);
    $exam = mysqli_fetch_assoc($examres);
    
    $getstudent = "select * from students where haltckt='" . $_COOKIE['userid'] . "'";
    $sr = $conn->query($getstudent);
    $student=mysqli_fetch_assoc($sr);
//    print_r($student);
  //print_r($exam);
    
}

//print_r($_POST);

function getExamFee($student,$exam){
    if($student['course']=="BCOM"){
        if($student['group']=="CA"){
            return $exam['BCOMCA_FEE']+$exam['FINE'];
        }
        if($student['group']=="GEN"){
            return $exam['BCOM_FEE']+$exam['FINE'];
        }
        if($student['group']=="FIN"){
            return $exam['BCOMCA_FEE']+$exam['FINE'];
        }
        
        
    }
    if($student['course']=="BSC"){
        return $exam['BSC_FEE']+$exam['FINE'];        
    }
    if($student['course']="BA"){
        if($student['group']=="HSO"){
            return $exam['BAHONS_FEE']+$exam['FINE'];
        }
        return $exam['BA_FEE']+$exam['FINE'];
    }
}

?>



<?php

// include 'config.php';
// $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
// if (!$conn) {
//     die("Connection failed: " . mysqli_connect_error());
// }
// if (isset($_POST['Submit'])) {
//     $examid = $_POST['examid'];

//     $sql = "SELECT * FROM examenrollments WHERE EXAMID=$examid";
//     $result = mysqli_query($conn, $sql);
//     if ($conn->query($result) === true) {
//         header("Location:regulartemplate.php?enrolled=true");

//     } else {
//         header("Location:enrollederror.php?error=duplicateenrolled");
//     }

//     $conn->close();

// }

?>
<style>
input{
    clear:none;
}
h4{
    text-transform:uppercase;
    font-style:bold;
}
#formcontent, #E1, #E2, #E3, #E4,#feedetails{
    margin:10px;
    padding:10px;
    background-color:white-smoke;
    border: solid 1px black;
}
</style>
        <div class="page-wrapper">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Exam  Regular</h3> </div>
                <div class="col-md-7 align-self-center">
                    <!-- <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Exams</a></li>
                        <li class="breadcrumb-item active">Enrollment</li>
                    </ol> -->
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
                           <!--COntent-->



<!--?php echo basename(__FILE__);?-->
  <form action="regulartemplate.php" method="post" onsubmit="return validate();">
          <h2 class="text-info text-center">Select Your Papers</h2>
          <!--<input type="hidden" name="extype" value="REGULAR">-->
          <input type="hidden" name="HALLTICKET" value="<?=$_COOKIE['userid']?>">
          <div id="formcontent">
              <h4 class="text-info">Select All  Papers</h4>
             
           </div>
           <div id="E1">
               <h4 class="text-info">Select your Elective 1</h4>
               <strong><h5 class="text-danger">* Select  any one Paper</h5></strong>
           </div>
           <div id="E2">

           <h4 class="text-info">Select your Elective 2</h4>
           <strong><h5 class="text-danger">* Select  any one Paper</h5></strong>
            </div>
            <div id="E3">

               <h4 class="text-info">Select your Elective 3</h4>
               <strong><h5 class="text-danger">* Select  any one Paper</h5></strong>
           </div>
           <div id="E4">
               <h4 class="text-info">Select your Elective 4</h4>
               <strong><h5 class="text-danger">* Select any one Paper</h5></strong>
           </div>
        <div id="E5">
               <h4 class="text-info">Select your Elective 5</h4>
               <strong><h5 class="text-danger">* Select any one Paper</h5></strong>
           </div>





<div id="feedetails">
<h1 class="text-danger">Fee Rs <?= getExamFee($student,$exam);?>  Only</h1>
<input type="hidden" value="<?= getExamFee($student,$exam);?>" name="fee">

</div>
    <input type="hidden" name="examid" value="<?php echo $_GET['examid']; ?>">

       <p class="text-center"><input type="submit" name="submit" value="Finish Selection"  class="btn btn-primary"></p>



    </form>


    <script>
        var form=document.getElementById('formcontent');
        var e1=document.getElementById('E1');
        var e2=document.getElementById('E2');
        var e3=document.getElementById('E3');
        var e4=document.getElementById('E4');
        var e5=document.getElementById('E5');
        
        
        var url="https://students.uasckuexams.in/api/course/getsubjects.php?scheme=<?php echo $_GET['scheme'];?>&course=<?php echo $_GET['course']; ?>&group=<?php echo $_GET['group']; ?>&medium=<?php echo $_GET['medium']; ?>&semester=<?php echo $_GET['semester']; ?>";
        console.log(url);
        var xhr=new XMLHttpRequest();
        xhr.onreadystatechange=function(){
            if(this.readyState==4 && this.status==200){
                var papers=JSON.parse(xhr.responseText);
            papers.forEach(paper=> {
                if(paper.PAPERGROUP=="E1"){

                    e1.innerHTML+= '<input type="radio" name="E1" value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E2"){

                    e2.innerHTML+= '<input type="radio" name="E2" value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E3"){

                    e3.innerHTML+= '<input type="radio" name="E3" value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E4"){
                    e4.innerHTML+= '<input type="radio" name="E4"  value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E5"){
                    e5.innerHTML+= '<input type="radio" name="E5"  value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else{
                    form.innerHTML+= '<input type="checkbox"  name="subs[]"  required value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }

            });

            }
        }
        xhr.open("GET",url,true);
        xhr.send();

        function validate(){
            var r =confirm("Are you sure to submit the Form. This cannot be changed afterwards.");
            return r;
        }

    </script>
</div>
</div>
</div>
    </div><!--end of page wrapper-->

<?php include "footer.php";?>
                        </div><!--Responsive table end-->
                       </div>
                    </div>
                </div>
                <!-- End PAge Content -->
            </div>
            <!-- End Container fluid  -->
        </div>
