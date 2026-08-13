<html>
</head>
<style>
.ht{
    font-size:1.5em;
    display:inline-block;
    padding:5px;
    border:solid 1px black;
    width:<?php echo $_GET['w'];?>%;
    text-align:center;
    font-weight:bold;
    box-sizing:border-box;
}
    
</style>
</head>
<body></body>
<?php include 'config.php';

$conn = mysqli_connect($servername,$dbuser,$dbpwd,$dbname);
//check connection
if ($conn->connect_error){
    die("connection failed:".mysqli_connect_error());
}
else{
$sql = "select * from  gpas where EXAMID IN(". $_GET['exid'].") AND RESULT='P' order by HALLTICKET";
$examname="select * from examsmaster where EXID=".$_GET['exid'];
$e=$conn->query($examname);
$exam=mysqli_fetch_assoc($e);
?>
<h2 style='text-align:center'>Office of the<br>
Controller of Examinations<br>
University Arts & Science College (Autonomous)<br>
Kakatiya University, Subedari, Warangal (T.S)
</h2>
<?php
$result = $conn->query($sql);
$passed=$result->num_rows;
echo "<h1 style='text-align:center'>".$exam['EXAMNAME']."</h1>";
echo "<h3>No of Students Passed:".$passed."</h3>";
    if($result->num_rows>0){
    
        while($row=$result->fetch_assoc()) {
                  
            echo '<div class="ht">'.$row['HALLTICKET']."</div>";
            
            
        }
    }
}
?>
</body>
</html>