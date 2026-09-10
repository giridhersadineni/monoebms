<?php include "header.php";?>


<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $examreg = "select * from examsmaster where EXID='" . $_GET['examid'] . "'";
 //   print_r($examreg);
    $examres = $conn->query($examreg);
    $exam = mysqli_fetch_assoc($examres);
  //print_r($exam);
 
    
}

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
          <div id="formcontent">
              <h4 class="text-info">Select All  Paper</h4>
             
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





<div id="feedetails">
<?php
// include "config.php";
// $conn=new mysqli_connect($servername,$dbuser,$dbname,$dbpwd);
// $examreg="select * from examsmaster where EXID='" . $_GET['examid'] . "'";
// $examres=$conn->query("$examreg");
// print_r($examres);
// $exam=mysqli_fetch_assoc($examres);

$fee=$exam['FEE']+$exam['FINE'];
if ($_GET['et'] == "REGULAR" && $_GET['course'] == "BA") {
    echo '<h2 class="text-danger">Total Fee : '.$fee.' Rs Only</h2>';
    echo '<input type="hidden"  name="fee" value="'.$fee.'">';
}
if ($_GET['et'] == "REGULAR" && $_GET['course'] == "BCOM" && $_GET["group"] == "GEN") {
    echo '<h2 class="text-danger">Total Fee : '.$fee.' Rs Only</h2>';
    echo '<input type="hidden"  name="fee" value="'.$fee.'"></input>';
}
if ($_GET['et'] == "REGULAR" && $_GET['course'] == "BCOM" && $_GET["group"] == "CA") {
    $fee=$fee+100;
    echo '<h2 class="text-danger">Total Fee : '.$fee.' Rs Only</h2>';
    echo '<input type="hidden"  name="fee" value="'.$fee.'"></input>';
}

if ($_GET['et'] == "REGULAR" && $_GET['course'] == "BSC") {
    echo '<h2 class="text-danger">Total Fee : '.$fee.' Rs Only</h2>';
    echo '<input type="hidden" name="fee" value="'.$fee.'"></input>';
}

?>

</div>
    <input type="hidden" name="examid" value="<?php echo $_GET['examid']; ?>">

       <p class="text-center"><input type="submit" name="submit" value="Finish Selection"  class="btn btn-primary"></p>



    </form>


    <script>
        var form=document.getElementById('formcontent');
        var e1=document.getElementById('E1');
        var e2=document.getElementById('E2');
        var e3=document.getElementById('E3');
        var url="../api/course/getsubjects.php?course=<?php echo $_GET['course']; ?>&group=<?php echo $_GET['group']; ?>&medium=<?php echo $_GET['medium']; ?>&semester=<?php echo $_GET['semester']; ?>";
        console.log(url);
        var xhr=new XMLHttpRequest();
        xhr.onreadystatechange=function(){
            if(this.readyState==4 && this.status==200){
                var papers=JSON.parse(xhr.responseText);
            papers.forEach(paper=> {
                if(paper.PAPERGROUP=="E1"){

                    e1.innerHTML+= '<input type="radio" name="E1"   value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E2"){

                    e2.innerHTML+= '<input type="radio" name="E2"  value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E3"){

                    e3.innerHTML+= '<input type="radio" name="E3"  value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E4"){
                e3.innerHTML+= '<input type="radio" name="E4"  value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else{
                    form.innerHTML+= '<input type="checkbox"  name="subs[]"  value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
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
