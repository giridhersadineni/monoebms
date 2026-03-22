<?php
    include "config.php";
    
    // Check connection
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    if (isset($_GET['scheme'])) {
        
        $selectedScheme = intval($_GET['scheme']);
       $sql = "SELECT * from STUDENT_CREDITS left join students on students.haltckt = STUDENT_CREDITS.HALLTICKET where students.scheme = $selectedScheme 
                ORDER BY HALLTICKET";
    
        $result = $conn->query($sql);
    
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            // echo '<td><a href="printoldgradesheet.php?exid=' . $row['EXAMID'] . '&ht=' . $row["HALLTICKET"] . '" class="btn btn-success" target="_BLANK"><i class="fas fa-print"></i> Print</td>';
            echo '<td></td>'; 
            echo '<td>' . $row['HALLTICKET'] . '</td>';
            echo '<td>' . $row['sname'] . '</td>';
            echo '<td>' . $row['CREDITS_ACQUIRED'] . '</td>';
            echo '<td></td>';
            // echo '<td><a href="studentgpaupdate.php?exid=' . $row['EXAMID'] . '&ht=' . $row["HALLTICKET"] . '" class="btn btn-success" target="_BLANK"><i class="fas fa-print"></i> UPDATE GPA</td>';
            echo '</tr>';
        }
    }
    
    mysqli_close($conn);
?>
