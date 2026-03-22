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
    <link rel="icon" type="image/png" sizes="16x16" href="images/arts.png">
    <title>University Arts & Science College</title>
    <!-- Bootstrap Core CSS -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/helper.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<style>
.container{
    max-width: 900px;
    margin: auto;
}
</style>



<div class="container">
<!-- Start Page Content -->
<div class="row">
<div class="col-lg-9" >
<div class="card card-outline-primary">  

<div class="card-body"align="center">
<div class="basic-form" >

<!-- CREDIT CARD FORM STARTS HERE -->
<div class="panel panel-default credit-card-box">
<div class="panel-heading display-table" >

<div class="row display-tr" >
<h3 class="panel-title display-td" >Payment Details</h3>

<div class="display-td" >                            
<img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
</div>
</div>                    
</div>




<form role="form" id="payment-form">

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">
<label for="cardNumber">CARD NUMBER</label>
<div class="input-group">
<input type="tel" class="form-control input-focus" name="cardNumber"placeholder="Valid Card Number"autocomplete="cc-number"required autofocus/>
<span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
</div>
</div>                            
</div>
</div>

<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group">    
<label for="cardExpiry"><span class="visible-xs-inline">EXP</span> DATE</label>
<input 
type="tel" 
class="form-control input-focus" 
name="cardExpiry"
placeholder="MM / YY"
autocomplete="cc-exp"
required 
/>
</div>
</div>

<div class="col-md-6">
<div class="form-group">
<label for="cardCVC">CV CODE</label>
<input 
type="tel" 
class="form-control input-focus"
name="cardCVC"
placeholder="CVC"
autocomplete="cc-csc"
required
/>
</div>
</div>
</div>
<div class="row p-t-20">
<div class="col-md-6">
<div class="form-group"> 
<label for="couponCode">COUPON CODE</label>
<input type="text" class="form-control input-focus" name="couponCode" />
</div>
</div>                        
</div>

<div class="row">
<div class="col-xs-12">
<button class="btn btn-success btn-lg btn-block" type="submit">Start Subscription</button>
</div>
</div>

</form>
</div>
</div>            
<!-- CREDIT CARD FORM ENDS HERE -->


</div>            

</div>
</div>

</div>
</div>

	<!-- If you're using Stripe for payments -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	
</body>

</html>