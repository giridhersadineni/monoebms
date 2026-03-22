<?php 


    // header('Content-type: text/csv');
    // header("Content-Disposition: attachment; filename=report.csv"); 
    // header("Content-Transfer-Encoding: binary");
    // header("Expires: 0");
    // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    // header("Cache-Control: private",true);
    
    // echo "ID,PCNO,HALLTICKET,S1GPA,S2GPA,S3GPA,S4GPA,S5GPA,S6GPA,P1YOP,P2YOP,ALLYOP,ADMYEAR,REMARKS,P1CGPA,P2CGPA,ALLCGPA,P1CF,P2CF,ALLCF,P1DIV,P2DIV,FINALDIV";
    // echo "\n";


    set_time_limit(600);
    include 'config.php';
    $conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
    $query="select * from rholdernew";
    $records=$conn->query($query);
    $count=0;
    while($row=mysqli_fetch_assoc($records)){
        
        $marker=$row['MARKER'];
        $getattemptcount="select count(*) as ATTEMPTS from rholdernew where MARKER='$marker'";
        $result=$conn->query($getattemptcount);
        $count=mysqli_fetch_assoc($result);
        $attempts=$count['ATTEMPTS'];
        $updatetable=" UPDATE rholdernew SET ATTEMPTS=$attempts where MARKER='$marker'";
        $message=$conn->query($updatetable);
        echo (implode($message));
        echo $marker."=>".$attempts."\n";
    }
    echo "done";
    
?>
       
