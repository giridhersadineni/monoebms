<?php include "header.php";?>
<?php
if(isset($_POST['update'])){

    include "config.php";
    $new_aadhar=$_POST['aadhar'];
    $old_aadhar=$_COOKIE['aadhar'];
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    
                if (!$conn) {
                    die("connection failed:" . mysqli_connect_error());
                } 
                else {
                $sql = "update students set aadhar='$new_aadhar' where aadhar='$old_aadhar'";
                echo $sql;
                $result = $conn->query($sql);
                print_r($result);
                if($result->num_rows>0){
                    
                }    
            
        }
}


?>
<div class="page-wrapper">
<div class="container">
    <div class="row" >
       <div class="col-10 mx-auto">
        <div class="card">
                <h3>Update Aadhar</h3>
                <form action="updatedetails.php" method="POST">
                    <?php //print_r($_COOKIE); ?>
                   <div class="form-group">
                   <label>Enter aadhar Number</label>
                   <input class="form-control" type="text" name="aadhar" value="<?php echo $_COOKIE['aadhar'];?>"></input>
                   </div> 
                   <div class="form-group">
                       <input type="submit" value="Update" class="btn btn-success"></input
                   </div>
                </form>
        </div>
        </div>
    </div>
    </div>
    </div><!--container-->
</div>
<?php include "footer.php";?>