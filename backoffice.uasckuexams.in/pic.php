<?Php include 'config.php';

$conn =mysqli_connect($servername,$dbuser,$dbpwd,$dbname);
$sql="select * from students";
$result =mysqli_query($conn,$sql);
while($row=mysqli_fetch_array($result))
{
    echo "<td>";
    echo"<p>".$row['images']."</p>";
    echo "</td>";
    echo "</td>";
    echo $row['images'];
    echo "</td>";
}




?>