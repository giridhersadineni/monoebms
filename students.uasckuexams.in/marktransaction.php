<?php include "header.php";
include "projectorm.php";
?>
<script>
function verify(id){
var row=document.getElementById(id);
var data=JSON.parse(row.getAttribute('data'));

console.log(data);
var filename=data["FILENAME"];
viewer=document.getElementById('docviewer');
viewer.src="../students/upload/receipts/"+filename;
navigator.locate("../students/upload/receipts/"+filename);


}

</script>

<div class="page-wrapper p-3">
    <div class="row">
        <div class="col-6 p-2">
            <?php
            
            $querystring="select transactions.TXID,transactions.* from transactions";
            echo $querystring;
            getdatatable( $querystring,"display nowrap table table-hover table-striped table-bordered","example23",$action="verify");
            ?>
            
        </div>
        <div class="col-4">
            <iframe src="" id="docviewer" width="100%" height="100%" ></iframe>
        </div>
        
    </div>
    
</div





<?php include "datatablefooter.php";?>