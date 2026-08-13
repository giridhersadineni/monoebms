<!-- Page wrapper  -->
<?php 
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "SELECT DISTINCT(YEAR) FROM examsmaster ORDER BY YEAR";
    $exam= $conn->query($sql);
    
    //  $nsql = "SELECT DISTINCT(PAPERCODE),PAPERNAME FROM allpapers ORDER BY PAPERCODE";
    // $paper= $conn->query($nsql);
}
?>
<?php include 'header.php'?>

<div class="page-wrapper">
    <!-- Bread crumb -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-primary">Tabulaion Report Generation</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active">Tabulaion Report Generation</li>
            </ol>
        </div>
    </div>
    <!-- End Bread crumb -->
    <!-- Container fluid  -->
    <div class="container-fluid">
        <!-- Start Page Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                
                        
                        <br><br>
                        <h1></h1>

                        <form action="process/tabulationreport.php" method="GET">
                           
                            <label for="year" class="text-primary"> Select Year</label>
                          
                            <select  id="yearselect" onchange='updateexam()' class="form-control">
                            <option name="year" value="0">--Select--</option>
                                  <?php
                            while ($nexam= mysqli_fetch_assoc($exam)) {
                            
                                echo '<option name="year" value="'.$nexam['YEAR'].'">' . $nexam["YEAR"]. "</option>";
                            }
                            ?>
                            
                           </select>
                           
                           <br>
                                    <label for="exid" class="text-primary"> Select Exam</label>
                          
                            <select name="exid" class="form-control"  id="exid">
                            
                            <option   value="0">--Select--</option>
            
                           </select>
                         
<script>
 function updateexam(){
       var yselect= document.getElementById("yearselect");
        var year = yselect.options[yselect.selectedIndex].value;
        var url = '../api/exam/getexams.php?year='+year;
        console.log("sending request"+url);

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function () {

          if(xhr.readyState == 4 && xhr.status == 200)
           {
      console.log(xhr.responseText);
    document.getElementById('exid').innerHTML="";

        var year=JSON.parse(xhr.responseText);
        year.forEach(element => {
    
        document.getElementById('exid').innerHTML+='<option value="'+element.EXID+'">'+element.EXAMNAME+'</option>"';
            });
 }
   }
        xhr.send();
      }
      var yselect = document.getElementById("year");

</script>
                           <br>
                          <!--<label for="pcode" class="text-primary"> Select Papercode</label>-->
                          
                          <!--  <select name="pcode">-->
                          <!--   <option name="pcode"  value="0">--Select--</option>-->
                                  <?php
                        // while ($papercode= mysqli_fetch_assoc($paper)) {
                            
                          //      echo '<option name="pcode" value="'.$papercode['PAPERCODE'].'">' . $papercode["PAPERCODE"].'-'.$papercode["PAPERNAME"]."</option>";
                        //    }
         ?>
                          <!-- </select> -->
                          
                          <!-- <br>-->
                           <center>
                            <input type="submit" >
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



<?php include 'footer.php'?>