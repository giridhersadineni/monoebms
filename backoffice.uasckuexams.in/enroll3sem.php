<?php include "header.php"; ?>
<?php include "config.php";
                            $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

                            if (!$conn){
                                die("connection failed:".mysqli_connect_error());
                            }
                           else{
                            $sql = "SELECT * FROM subs5sem WHERE COURSE='BA' and GROUPCODE='JSP'";
                                $result=$conn->query($sql);
                                $aresult=$result;
                                if($result->num_rows>0){
                                    echo "Has rows";
                                }
                                else{
                                    echo "No Rows fetched";
                                }
                           }                         
            
?>
<div class="page-wrapper">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Exam Registrations</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Student Dashboard</a></li>
                        <li class="breadcrumb-item active">Exam Enroll</li>
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
                           <form   method="post" action="test.php">
                           
                            <?php
                           echo '<h2>Common Papers</h2>';     
                           while($row=mysqli_fetch_assoc($result)){ 
                            if($row['PAPERTYPE']=='ELECTIVE'){
                             }
                            else{
                                echo '<input type="checkbox" name="subjects[]" value='.$row['ID'].'>'.$row['PAPERNAME'].'</input><br>';
                            }
                        
                        
                        }
                        echo '<h2>Electives</h2>';
                           while($row=mysqli_fetch_assoc($aresult)){ 
                                if($row['PAPERTYPE']=='ELECTIVE'){
                                       echo '<input type="radio" name="'.$row["EGROUP"].'" value="'.$row['ID'].'">'.$row["PAPERNAME"].'"</input><br>';
                                    }
                                else{
                                    
                                }
                            
                            
                            }
                            
                            ?>
                           
                           <button type="submit" value="hello" name="submit">Enroll</button>
                           </form>


                            </div>
                        </div>
                    </div>
                </div>
                <!-- End PAge Content -->
            </div>
            <!-- End Container fluid  -->
            <!-- footer -->
            <footer class="footer">   </footer>
            <!-- End footer -->
        </div>

        
<?php include "footer.php";?>