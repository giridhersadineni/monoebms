<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<!-- Favicon icon -->    
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>    
<body style='min-width:20cm;max-width:20cm;margin-left:auto;margin-right:auto;margin-top:50px;padding:1px;'>
<style>
table {
font-size:10px;
border-collapse: collapse;
width: 100%;
}

td{
border:0px solid;
text-align:left;
padding: 0px;
}
</style>

<div class="row">
<div class="col">
<?php include 'config.php';
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND PAPERCODE LIKE '1%' ORDER BY PAPERCODE ";
//echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
echo "<table>";
        while ($row = $result->fetch_assoc()) {
            
echo "<tr>";
echo "<td>".strtoupper($row['PAPERNAME'])."</td>";
echo "<td style='text-align:center;'>".$row["CREDITS"]."</td>";
echo "<td style='text-align:center;'>".$row["GRADE"]."</td>";
 echo "</tr>";
}
echo "</table>";
}
}
?>
</div>
<br>
<div class="col">
<?php include 'config.php';
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND PAPERCODE LIKE '2%' ORDER BY PAPERCODE ";
//echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
echo "<table>";         
        while ($row = $result->fetch_assoc()) {
echo "<tr>";
echo "<td>".strtoupper($row['PAPERNAME'])."</td>";
echo "<td style='text-align:center;padding:1px'>".$row["CREDITS"]."</td>";
echo "<td style='text-align:center;'>".$row["GRADE"]."</td>";
 echo "</tr>";
        }
echo "</table>";        
    }
}
?>
</div>
</div>

<br><br>
<!--Second Semester-->
<div class="row">
<div class="col">
    
<?php include 'config.php';
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND PAPERCODE LIKE '3%' ORDER BY PAPERCODE ";
//echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
echo "<table >";         
        while ($row = $result->fetch_assoc()) {
echo "<tr>";
echo "<td>".strtoupper($row['PAPERNAME'])."</td>";
echo "<td style='text-align:center;padding-right:0.5cm;'>".$row["CREDITS"]."</td>";
echo "<td style='text-align:center;'>".$row["GRADE"]."</td>"; 
echo "</tr>";
}
echo "</table>";
    }
}
?>
</div>
<br>
<div class="col">
<?php include 'config.php';
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND PAPERCODE LIKE '4%' ORDER BY PAPERCODE ";
//echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
echo "<table>";          
        while ($row = $result->fetch_assoc()) {
echo "<tr>";
echo "<td>".strtoupper($row['PAPERNAME'])."</td>";
echo "<td style='text-align:center;'>".$row["CREDITS"]."</td>";
echo "<td style='text-align:center;'>".$row["GRADE"]."</td>";

 echo "</tr>";
        }
echo "</table>";        
    }
}
?>
</div>
</div>


<br><br>
<!--Second Semester-->
<div class="row">
<div class="col">
    
<?php include 'config.php';
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  rholdernew where  RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND PAPERCODE LIKE '5%' ORDER BY PAPERCODE ";
//echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
echo "<table>";        
        while ($row = $result->fetch_assoc()) {
echo "<tr>";
echo "<td>".strtoupper($row['PAPERNAME'])."</td>";
echo "<td style='text-align:center;'>".$row["CREDITS"]."</td>";
echo "<td style='text-align:center;'>".$row["GRADE"]."</td>";
 echo "</tr>";
        }
echo "</table>";        
    }
}
?>
</div>
<br>
<div class="col">
<?php include 'config.php';
$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
//check connection
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select * from  rholdernew where RESULT NOT IN('F','AB') AND HALLTICKET='".$_GET["hallticket"]."' AND PAPERCODE LIKE '6%' ORDER BY PAPERCODE ";
//echo $sql;
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
echo "<table>";         
        while ($row = $result->fetch_assoc()) {
echo "<tr>";
echo "<td>".strtoupper($row['PAPERNAME'])."</td>";
echo "<td style='text-align:center;'>".$row["CREDITS"]."</td>";
echo "<td style='text-align:center;'>".$row["GRADE"]."</td>";
 echo "</tr>";
        }
echo "</table>";

    }
}
?>
</div>
</div>

</body>
</html>