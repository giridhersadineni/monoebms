<?php include "header.php"?>

<style>
.button
{
    background-color:#008CBA;
    color:white;
}
h3
{
    color:blue;
}
</style>

<div class="page-wrapper">
<!-- Bread crumb -->


<div class="row justify-content-center">
<div class="col-lg-9">
<div class="card card-outline-primary">

<center>
<div class="card-header">
<h4 class="m-b-0 text-white">EMAIL US</h4>                          
</center>
     
                
<div class="col-md-15">
<div class="card">
<div class="card-body">
<h4 class="card-title"></h4>

<!-- Tab panes -->
<div class="tab-pane active" id="employee" role="tabpanel">
<div class="p-100">

<div class="card-body">
<div class="basic-form">


<?php
	if (!array_key_exists('Submitted',$_POST))
	{
?>

<form method="post" action="email.php">
            
<div class="row ">
<div class="col-md-6">
<div class="form-group">
<input type="hidden"  name="Submitted" value="true">
    </div>
    </div>
    </div>

<div class="row">
<div class="col-md-6">
<div class="form-group">
	<b style="color:Black;">To:</b> <input type="text" class="form-control input-focus" name="To" size="25">
    </div>
    </div>
    </div>
<div class="row">
<div class="col-md-6">
<div class="form-group">
		<b style="color:Black;">From:</b> <input type="text" class="form-control input-focus" name="From" size="25">
    </div>
    </div>
    </div>
    <div class="row ">
<div class="col-md-6">
<div class="form-group">
<b style="color:Black;">Subject:</b> <input type="text" class="form-control input-focus" name="Subject" size="25">
    </div>
    </div>
    </div>
    <div class="row ">
<div class="col-md-6">
    <div class="form-group">
<b style="color:Black;">Message:</b><br>
<textarea name="Message" class="input-focus" cols="60" rows="10"></textarea>
    </div>
    </div>
    </div>
		<input type="submit" class="button" value="Send Email">
        
		</form>
        </div>
</div>
</div>
</div>
</div>
<!-- /# card -->
</div>
<!-- /# column -->


<?php
	}
	else
	{
		ini_set('SMTP',$_POST['Host']);
		$to = $_POST['To'];
		$from = 'From: ' . $_POST['From'];
		$subject = $_POST['Subject'];
		$message = $_POST['Message'];
	
		if(mail($to,$subject,$message,$from))
		{
			echo "Message Sent";
		}
		else
		{
			 echo "Message Not Sent";
		}
	}
?>
</div>
</div>
<?php include "footer.php"?>



