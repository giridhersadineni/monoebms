<html>
<?php include 'config.php';

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
  
if($conn){
    
    if (isset($_POST['submit'])) {
       $filename = $_FILES['profileimage']['name'];
       $filetmpname=$_FILES['profileimage']['name'];
       $folder ='upload/';
       echo"image uploaded in floder";
    $sql ="INSERT INTO `students`(`images`) VALUES (' $filename')";
    $qry=mysqli_query($conn,$sql);
    if($qry){
        echo "image uploaded";
    }
}
}
 
  ?>
<body>
    <form action="imageexmple.php" method="post" enctype="multipart/form-data">
    <input type="file" name="profileimage">
    <button type="submit" name="submit"> submit</button>
    </form>

</body>
</html>