<?php
    include "config.php";
    
    // Check connection
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    if (isset($_GET['examid'])) {
        $selectedExamId = intval($_GET['examid']);
        $sql = "SELECT DISTINCT(HALLTICKET), EXAMID, EXAMNAME FROM RESULTS
                LEFT JOIN examsmaster ON RESULTS.EXAMID = examsmaster.EXID
                WHERE EXAMID = $selectedExamId
                ORDER BY HALLTICKET";
    
        $result = $conn->query($sql);
    
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td><a href="printoldgradesheet.php?exid=' . $row['EXAMID'] . '&ht=' . $row["HALLTICKET"] . '" class="btn btn-success" target="_BLANK"><i class="fas fa-print"></i> Print</td>';
            echo '<td>' . $row['HALLTICKET'] . '</td>';
            echo '<td>' . $row['EXAMID'] . '</td>';
            echo '<td>' . $row['EXAMNAME'] . '</td>';
            echo '<td><a href="studentgpaupdate.php?exid=' . $row['EXAMID'] . '&ht=' . $row["HALLTICKET"] . '" class="btn btn-success" target="_BLANK"><i class="fas fa-print"></i> UPDATE GPA</td>';
            echo '</tr>';
        }
    }
    
    mysqli_close($conn);
?>
