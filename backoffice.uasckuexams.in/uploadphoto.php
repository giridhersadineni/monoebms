<?php include 'header.php'?>

<?php include 'config.php';
$photodeleted = 0;
$signdeleted = 0;
if (isset($_POST['UPDATE'])) {
    echo "<h4>Deleting Files</h4>";
    if (file_exists("upload/images/" . $_POST['aadhar'] . ".jpg")) {
        $photodeleted = unlink("upload/images/" . $_POST['aadhar'] . ".jpg");
    }
    if (file_exists("upload/images/" . $_POST['aadhar'] . ".jpg")) {
        $signdeleted = unlink("upload/signatures/" . $_POST['aadhar'] . ".jpg");
    }
    echo $photodeleted . " " . $signdeleted;
}

?>
<?php
$imageupload = 0;
$signupload = 0;

if (isset($_POST['UPDATE'])) {

    //FILE UPLOAD CODE
    $target_dir = "upload/signatures/";
    $target_file = $target_dir . basename($_FILES["sign"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (move_uploaded_file($_FILES["sign"]["tmp_name"], $target_dir . $_COOKIE['aadhar'] . ".jpg")) {

        $imageupload = 1;

    } else {
        echo "Sorry, there was an error uploading your file.";
    }

}

?>
<?php
if (isset($_POST['UPDATE'])) {
    //FILE UPLOAD CODE
    $target_dir = "upload/images/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_dir . $_COOKIE['aadhar'] . ".jpg")) {
        $signupload = 1;

    } else {
        echo "Sorry, there was an error uploading your file.";

    }
    if ($imageupload = 1 && $signupload = 1) {
       echo '<script> alert("SUCCESSFULLY UPDATED")</script>';
    }

}

?>

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
<center>
<div class="card-header bg-primary">
<h4 class="m-b-0  text-white">Upload Photo </h4>
</div>
</center>
<br><br>
<div class="card-body">
<div class="basic-form">
<form action="newimageupload.php" method="POST" id="getphoto">
  <!--  <label for="aadhar">Enter your aadhar number</label>
    <input type="text" name="aadhar" >
  <input type="submit" name="getphoto" value="Get Photo" class="btn btn-success" >-->
  <center>
  <img src="<?php echo 'upload/images/' . $_COOKIE['aadhar'] . '.jpg'; ?>"  width="100px"><br>
  <img src="<?php echo 'upload/signatures/' . $_COOKIE['aadhar'] . '.jpg'; ?>"  width="100px">
</center>
</form>

<br>

<?php
/*
if (isset($_POST['aadhar'])) {

    echo '<center><img  src="upload/images/' . $_POST["aadhar"] . '.jpg"';
    echo ' width="100px"></img><br>';
    echo '<img  src="upload/signatures/' . $_POST["aadhar"] . '.jpg"';
    echo ' width="100px"></img></center><br>';

} else {
    echo '<center><img  src="images/placeholder-photo.jpg" width="100px"></img><br>';
    echo '<img  src="images/signature.jpg" width="100px"></img><br></center>';

}*/
?>

 <form action="uploadphoto.php" method="POST" onsubmit="return validate()" id="uploadform" enctype="multipart/form-data">

    <div class="form-group">
        <label>Enter AADHAR Number</label>
        <input type="number" class="form-control" name="aadhar" required>Enter AADHAR Number</input>
    </div>

    <div class="form-group">
      <label>Upload Image:<span>(50kb)</span></h3></label>
       <input type="file" class="form-control " name="photo" id="studimage">
    </div>
    
    <div class="form-group">
      <label for="sign">Upload Signature:<span>(30kb)</span></h3></label>
       <input type="file" class="form-control " name="sign" id="signature">
    </div>
    <div class="form-group">
      <input type="submit" name="UPDATE" value="Update" class="btn btn-primary" >
    </div>

</form>



</div>
</div>
</div>


<script>
var signok=0;
var imageok=0;
document.getElementById('studimage').onchange = function(){
    var filesize = document.getElementById('studimage').files[0].size;
    var filename= document.getElementById('studimage').files[0].name;
    if(filesize>50000){

        alert("Image size should be less than 50kb"+filename);
        document.getElementById('uploadform').reset();
    }
    var n=filename.search(".jpg");
    if(n>0){

    }
    else{
            alert("Please upload only Jpeg File and Size should be less than 50kb");
            document.getElementById('uploadform').reset();
    }
    imageok=1;
}
document.getElementById('signature').onchange = function(){
    var filesize = document.getElementById('signature').files[0].size;
    var filename= document.getElementById('signature').files[0].name;
    if(filesize>50000){

        alert("Image size should be less than 50kb"+filename);
        document.getElementById('uploadform').reset();
    }
    var n=filename.search(".jpg");
    if(n>0){

    }
    else{
            alert("Please upload only Jpeg File and Size should be less than 50kb");
            document.getElementById('uploadform').reset();
    }
     signok=1;
}

function validate(){
    if(imageok==1 && signok==1){
        return true;
    }
    else{
        alert("Please Select Files");
        return false;

    }


}



</script>
<?php include 'datatablefooter.php'?>


