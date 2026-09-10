<?php include 'header.php'?>

<div class="page-wrapper">
<!-- Bread crumb -->
  <div class="row page-titles">
                 <div class="col-md-5 align-self-center">
        <h3 class="text-primary">Change Photo And Signature </h3>
    </div>
        <div class="col-md-7 align-self-center">
             <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
           <li class="breadcrumb-item active">Change Photo And Signature</li>
           </ol>
      </div>
</div>

<!-- Container fluid  -->
<div class="container-fluid">
<!-- Start Page Content -->
<div class="row">
<div class="col-lg-12">
<div class="card card-outline-primary">
     <h3>Update Payment Details </h3>
<?php 

if(isset($_POST['update'])) {

include 'config.php';
$file_upload_status=0;
$txn_date=$_POST['TXN_DATE'];
$txn_number=$_POST['TXN_NUMBER'];
$challan_number=$_POST['CHALLAN_NUMBER'];
$examid=$_POST['EXAMID'];
$hallticket=$_POST['HALLTICKET'];
$txn_date=$_POST['TXN_DATE'];


    $target_dir = "upload/receipts/";
    $target_file = $target_dir . basename($_FILES["RECEIPT"]["name"]);
    $FileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (file_exists($target_file)) {
        unlink($target_file);
    }
    if (move_uploaded_file($_FILES["RECEIPT"]["tmp_name"], $target_dir . $challan_number.".".$FileType)) {
        $file_upload_status=1;
       
    }
    else {
        echo "Sorry, there was an error uploading your file.";
    }
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    if (!$conn){
     
        die("connection failed:".mysqli_connect_error());

    }
    else{
        $checktransaction="select * from transactions where HALLTICKET='$hallticket' and EXAMID='$examid'";
        // echo $checktransaction;
        $transactions=$conn->query($checktransaction);
        // print_r($transactions);
        if($transactions->num_rows>0){
            echo '<h3 class=" alert alert-danger text-dark"> Transaction Details already Updated</h3>';
        }
        else{
            if($file_upload_status==1){
                    $filename=$challan_number.".".$FileType;
                    $sql = "INSERT INTO `transactions` (`TXID`, `HALLTICKET`, `EXAMID`, `ENROLLMENTID`,  `TXN_NUMBER`, `TXN_DATE`,`FILENAME`) VALUES (NULL, '$hallticket', $examid, $challan_number, '$txn_number', '$txn_date','$filename')";
                    // echo $sql;
                    if ($conn->query($sql) === TRUE) {
                         echo '<h3 class=" alert alert-success text-dark"> Successfully Added Payment Details. Hallticket will be issued after verification</h3>';
                    } 
                    else{
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }
                }
            }
        }
        
$conn->close();







}// end of if post check

?>
    
    


<div class="card-body">
    <div class="basic-form">
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" onsubmit="return validate()" id="updateform" enctype="multipart/form-data">
            
                   <input type="hidden" name="HALLTICKET" value="<?php echo $_COOKIE['userid'] ; ?>">
                   <input type="hidden" name="CHALLAN_NUMBER" value="<?php echo $_GET['challan'] ; ?>">
                   <input type="hidden" name="EXAMID" value="<?php echo $_GET['examid'] ; ?>">
                
                   <div class="form-group">
                       <label>Date of Payment</label>
                       <input class="form-control" type="DATE" name="TXN_DATE" required></input>
                   </div> 
                   
                   <div class="form-group">
                       <label>Enter Payment Reference/ Transaction Number</label>
                       <input class="form-control" type="text" name="TXN_NUMBER" id="TXN_NUMBER"value="" required onchange="checktransactionnumber()"></input>
                   </div> 
   
                    <div class="form-group">
                        <label>Upload Receipt / Challan / Screenshot Image</label>
                        <input class="form-control" type="file" name="RECEIPT"  id="RECEIPT"  required></input>
                    </div> 
                    
                    <p class="text-right mt-3">
                        <input type="submit" value="update" name="update" class="btn btn-success" >
                    </p>
                 
        </form>
    </div>
</div>


<script>
document.getElementById('RECEIPT').onchange = function()
    {
        var filename= document.getElementById('RECEIPT').files[0].name;
        var n=0;
        if(filename.search(".jpg") || filename.search(".pdf")){
            n=1;
        }
        if(n>0){
            fileok=1;
        }
        else{
                alert("Please upload only jpg or Pdf Files only");
                document.getElementById('updateform').reset();
        }
        
}

function checktransactionnumber(){
        var txnnumber=document.getElementById('TXN_NUMBER').value;
        var url='../api/transactionumber/checktransactionnumber.php?txn=' + txnnumber ;
        console.log("URL:"+url);
        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);
        xhr.onreadystatechange = function () {

          if(xhr.readyState == 4 && xhr.status == 200)
           {
              console.log(xhr.responseText);
              document.getElementById('group').innerHTML="";
        
                var course=JSON.parse(xhr.responseText);
            
            }
        }
}
function validate(){
    if(fileok==1){
        return true;
    }
    else{
        alert("Please Select Files");
        return false;
    }
}

</script>


<?php include 'footer.php'; ?>



                   
                   
           