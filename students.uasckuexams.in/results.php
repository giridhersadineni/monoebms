<?php include "header.php"?>

<?php
include "config.php";
//check connection

$conn = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);
if ($conn->connect_error) {
    die("connection failed:" . mysqli_connect_error());
} else {
    $sql = "select distinct(EXAMID),HALLTICKET,examsmaster.EXAMNAME,examsmaster.SEMESTER,examsmaster.EXAMTYPE from rholdernew left join examsmaster ON rholdernew.EXAMID=examsmaster.EXID where HALLTICKET='".$_COOKIE['userid'] ."' ORDER BY examsmaster.SEMESTER";
    // echo $sql;
    $result = $conn->query($sql);
    // echo $sql;

}
?>

        <!-- Page wrapper  -->

<div class='page-wrapper'>
<!-- Bread crumb -->
<div class="row page-titles">
<div class="col-md-5 align-self-center">
<h3 class="text-primary">Exam Results</h3> </div>
<div class="col-md-7 align-self-center">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="javascript:void(0)">Student</a></li>
<li class="breadcrumb-item active">Enrollments</li>
</ol>
</div>
</div>


<div class='container-fluid'>

<div class='row'>

<?php
if ($result->num_rows > 0) {
   
    // output data of each row
    while ($row = mysqli_fetch_assoc($result)) {
    // print_r($row);
    //print_r($row["FEEPAID"]);
        //echo $row['EXAMNAME']." ".$row['STATUS'];
    echo "<div class='col-sm-6'>";
    echo "<div class='card'>";
    echo '<form method="POST" action="printresult.php">';
    echo "<h5>" . $row["EXAMNAME"]."</h5>";
    echo "<h6>Semester: ".$row["SEMESTER"]. "</h6>";
    echo '<input type="hidden" value='.$_COOKIE["userid"].' name="hallticket"></input>';
    echo '<input type="hidden" value='.$row["EXAMID"].' name="EXAMID"></input>';
    echo '<input type="hidden" value='.$_COOKIE["stid"].' name="studentid"></input>';
    echo '<input type="hidden" value='.$row["EXAMTYPE"].' name="examtype"></input>';
    echo '<input type="hidden" value='.$row["SEMESTER"].' name="SEM"></input>';

    echo '<input type="submit" class="btn btn-info" value="Get Result"></input>';
    echo "</form>";
    echo "</div>";
    echo "</div>";
    }
}else {
    echo '<h1>Results Will be Updated Soon....</h1>';

}

?>


<?php
mysqli_close($conn);

?>
</div>
</div>
<!--<div class='row'>-->
<!--    <div class="col-sm-6">-->
<!--                    <div class="card">-->
<!--                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
<!--                    <ins class="adsbygoogle"-->
<!--                         style="display:block;width:100%"-->
<!--                         data-ad-format="fluid"-->
<!--                         data-ad-layout-key="-ef+6k-30-ac+ty"-->
<!--                         data-full-width-responsive="true"-->
<!--                         data-ad-client="ca-pub-5755477602321907"-->
<!--                         data-ad-slot="7146133093"></ins>-->
<!--                    <script>-->
<!--                         (adsbygoogle = window.adsbygoogle || []).push({});-->
<!--                    </script>-->
<!--                </div>-->
<!--    </div>-->
<!--        <div class="col-sm-6">-->
<!--                    <div class="card">-->
<!--                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
<!--                    <ins class="adsbygoogle"-->
<!--                         style="display:block;width:100%"-->
<!--                         data-ad-format="fluid"-->
<!--                         data-ad-layout-key="-ef+6k-30-ac+ty"-->
<!--                         data-full-width-responsive="true"-->
<!--                         data-ad-client="ca-pub-5755477602321907"-->
<!--                         data-ad-slot="7146133093"></ins>-->
<!--                    <script>-->
<!--                         (adsbygoogle = window.adsbygoogle || []).push({});-->
<!--                    </script>-->
<!--                </div>-->
<!--    </div>-->
<!--</div>-->
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-5755477602321907",
    enable_page_level_ads: true
  });
</script>


<?php include "datatablefooter.php";?>