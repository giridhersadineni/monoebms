<?php include 'header.php'; ?>
<?php
if (isset($_POST['success'])) {
    echo '<script>alert("Import Successfull");</script>';
}

?>
<style>
label{
    color:black;
}

</style>

<div class="page-wrapper">
<!-- Bread crumb -->
<!-- Container fluid  -->
<div class="container">

<div class="card">

<div class="card-header">
<label>UPLOAD FILE </label>
</div>
 <br>

<div class="card-body">
<div class="basic-form">
    
<form action='uploadcsvfile.php' method='POST' enctype="multipart/form-data">

  <div class="form-group">
    <label for="file">Upload</label>
              <input type="file" class="form-control input-focus" id="csvfile" name="csvfile">
            </div>
  
         <center>
		<input type="submit" class="button" name="submit" value="Upload File">
        </center>
		</form>

</div>
</div>
</div>
</div>
</div>



<?php include 'footer.php';?>