<?php include "header.php";?>
<div class="page-wrapper">
    <div class="container p-0">
        
    <div class="card w-100">
        <div class="card-header">
            
            <h2>Apply Consolidated Memo</h2>
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        
             <div class="card-body">
            
            <div class="form-group">
                <label for="HALLTICKET">Enter Hallticket Number</label>
                <input class="form-control" name="HALLTICKET">
            </div>
            
            <div class="form-group">
                <label for="ENTER PAYMENT TRANSACTION ID">Enter the Transaction ID (GOOGLE Pay/PhonePE/UPI)</label>
                <input class="form-control" name="TRANSACTIONID">
                <small>Enter only transaction ID </small>
            </div>
            
            <h3>Enclose Hallticket, All semester Memos, Transfer Certificate / Fee payment proof for CMM & Provisional in a single pdf</h3>
            <div class="form-group">
                <label for="HALLTICKET">Upload Application/Transactions </label>
                <input class="form-control" type="file" name="FILE">
            </div>
            
        </div>
    
        <div class="card-footer">
             <input type="submit" value="Submit Application" name="submit" class="btn btn-primary">   
        </div>
        </form>
        
    </div>    
    
    </div>
</div>

<?php include "footer.php" ?>