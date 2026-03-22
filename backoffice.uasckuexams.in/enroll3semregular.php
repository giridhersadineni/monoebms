<?php include "header.php";?>

<?php
if (isset($_POST["submit"])) {
    echo '<script>alert("data submitted");</script>';
}
?>


<style>
input{
    clear:none;
}
</style>
        <div class="page-wrapper">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3> </div>
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
                           <!--COntent-->



<!--?php echo basename(__FILE__);?-->
  <form action="test.php" method="post">
          <h2>Select Your Papers</h2>
          <div id="formcontent">
              <h2>Compulsory Papers</h2>
           </div>
           <div id="E1">
               <h3>Select your Elective 1</h3>
           </div>
           <div id="E2">

           <h3>Select your Elective 2</h3>
            </div>
            <div id="E3">

               <h3>Select your Elective 3</h3>
           </div>
           <div id="E3">
               <h3>Select your Elective 4</h3>
           </div>



        <input type="submit" name="submit" value="submit">


    </form>


    <script>
        var form=document.getElementById('formcontent');
        var e1=document.getElementById('E1');
        var e2=document.getElementById('E2');
        var e3=document.getElementById('E3');
        var url="../api/course/getbcom3semsubs.php";
        console.log(url);
        var xhr=new XMLHttpRequest();
        xhr.onreadystatechange=function(){
            if(this.readyState==4 && this.status==200){
                console.log(xhr.responseText);
                var papers=JSON.parse(xhr.responseText);
                papers.forEach(paper=> {
                if(paper.PAPERGROUP=="E1"){

                    e1.innerHTML+= '<input type="radio" name="E1"  required value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E2"){

                    e2.innerHTML+= '<input type="radio" name="E2" required value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else if(paper.PAPERGROUP=="E3"){

                    e3.innerHTML+= '<input type="radio" name="E3" required value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                else{
                    form.innerHTML+= '<input type="checkbox" name="subs[]"  value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }
                if(paper.PAPERTYPE=="COMMON"){
                    form.innerHTML+= '<input type="checkbox" name="subs[]"  value="'+paper.PAPERCODE+'">'+paper.PAPERCODE+" "+paper.PAPERNAME+'</input><br>';
                }



            });

            }



        }
        xhr.open("GET",url,true);
        xhr.send();



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
<?php
include "footer.php";
?>