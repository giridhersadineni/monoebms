

<?php include "config.php";
$course = $_GET['course'];
$group = $_GET['group'];
$medium = $_GET['medium'];
$semester = $_GET['semester'];
$scheme = $_GET['scheme'];

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

$sql = "";


if (!$conn) {
    die("connection failed:" . mysqli_connect_error());
} else {
    
    $exampaperssql = "SELECT DISTINCT(EXAMPAPERS.PAPERCODE), PAPERTITLES.PAPERNAME,ELECTIVEGROUP FROM EXAMPAPERS LEFT JOIN PAPERTITLES ON PAPERTITLES.PAPERCODE=EXAMPAPERS.PAPERCODE WHERE EXAMPAPERS.COURSE='$course' and EXAMPAPERS.COMBINATION='$group' and EXAMPAPERS.SEM=$semester and EXAMPAPERS.SCHEME='$scheme'";

    $allpaperssql = "SELECT * FROM `allpapers` WHERE `SEM` = $semester AND `COURSE` LIKE '$course' AND `GROUPCODE` LIKE '$group' AND `MEDIUM` LIKE '$medium' AND `SCHEME` LIKE '$scheme';";


    if($scheme == '2016' or $scheme == '2025'){
        $sql = $allpaperssql;
    }
    if($scheme == '2020' or $scheme == '2022' or $scheme == '2023' ){
        $sql = $exampaperssql;
    }
    
    $result = $conn->query($sql);
    // echo $sql;
    // print_r($result);
    if ($result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $resultset[] = array(
                'PAPERCODE' => $row['PAPERCODE'],
                'PAPERNAME' => $row['PAPERNAME']  ,
                'PAPERGROUP' => $row["ELECTIVEGROUP"],
                'MEDIUM' => $row["MEDIUM"],
            );

        }
        
        echo json_encode($resultset);
    } else {
        echo "No Subjects Fetched";
        echo $sql;

    }
}

?>