<?php

session_start();
require_once("includes/db.php");
require_once("functions/payment.php");
require_once("functions/processing_fee.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('login','_self')</script>";
}

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$enable_paypal = $row_payment_settings->enable_paypal;
$paypal_client_id = $row_payment_settings->paypal_app_client_id;
$paypal_email = $row_payment_settings->paypal_email;
$paypal_currency_code = $row_payment_settings->paypal_currency_code;
$paypal_sandbox = $row_payment_settings->paypal_sandbox;
if($paypal_sandbox == "on"){
	$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
}elseif($paypal_sandbox == "off"){
	$paypal_url = "https://www.paypal.com/cgi-bin/webscr";	
}
$enable_stripe = $row_payment_settings->enable_stripe;
$enable_dusupay = $row_payment_settings->enable_dusupay;
$dusupay_method = $row_payment_settings->dusupay_method;
$dusupay_provider_id = $row_payment_settings->dusupay_provider_id;
	
$enable_mercadopago = $row_payment_settings->enable_mercadopago;
$payza_test = $row_payment_settings->payza_test;
$payza_currency_code = $row_payment_settings->payza_currency_code;
$payza_email = $row_payment_settings->payza_email;

$enable_coinpayments = $row_payment_settings->enable_coinpayments;
$coinpayments_merchant_id = $row_payment_settings->coinpayments_merchant_id;
$coinpayments_currency_code = $row_payment_settings->coinpayments_currency_code;
$enable_paystack = $row_payment_settings->enable_paystack;

if($paymentGateway == 1){

	$get_plugin = $db->query("select * from plugins where folder='paymentGateway'");
	$row_plugin = $get_plugin->fetch();
	$paymentGatewayVersion = $row_plugin->version;
	if($paymentGatewayVersion >= 1.2){
		$enable_2checkout = $row_payment_settings->enable_2checkout;
	}else{
		$enable_2checkout = "no";
	}

}else{
	$enable_2checkout = "no"; 
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_email = $row_login_seller->seller_email;

$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;

$select_cart =  $db->select("cart",array("seller_id" => $login_seller_id));
$count_cart = $select_cart->rowCount();
$sub_total = 0;
while($row_cart = $select_cart->fetch()){
	$proposal_price = $row_cart->proposal_price;
	$proposal_qty = $row_cart->proposal_qty;
	$cart_extras = $db->select("cart_extras",array("seller_id"=>$login_seller_id,"proposal_id"=>$row_cart->proposal_id));
	while($extra = $cart_extras->fetch()){
	  $proposal_price += $extra->price;
	}
	$cart_total = $proposal_price * $proposal_qty;
	$sub_total += $cart_total;
}
$processing_fee = processing_fee($sub_total);
$total = $sub_total + $processing_fee;

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
	<title> <?= $site_name; ?> - Payment Options</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
	<link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="styles/styles.css" rel="stylesheet">
	<link href="styles/categories_nav_styles.css" rel="stylesheet">
	<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
	<link href="styles/owl.carousel.css" rel="stylesheet">
	<link href="styles/owl.theme.default.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script src="https://checkout.stripe.com/checkout.js"></script>
  <?php if(!empty($site_favicon)){ ?>
  <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>

	<!-- Include the PayPal JavaScript SDK -->
	<script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&disable-funding=credit,card&currency=<?= $paypal_currency_code; ?>"></script>

</head>
<body class="is-responsive">
<?php 

require_once("includes/header.php"); 
$site_logo_image = getImageUrl2("general_settings","site_logo",$row_general_settings->site_logo);

if($seller_verification != "ok"){
	echo "
	<div class='alert alert-danger rounded-0 mt-0 text-center'>
		Please confirm your email to use this feature.
	</div>";
}else{

?>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-md-12">
			<div class="card mb-3">
				<div class="card-body">
					<h5 class="float-left mt-2"> <?= $lang['cart']['your_cart']; ?> (<?= $count_cart; ?>) </h5>
					<h5 class="float-right">
						<a href="index" class="btn btn-success"> <?= $lang['button']['continue_shopping']; ?> </a>
					</h5>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-7">
			<div class="row">
        <?php if($current_balance >= $sub_total ){ ?>
				<div class="col-md-12 mb-3">
					<div class="card payment-options">
						<div class="card-header">
							<h5><i class="fa fa-dollar"></i> Available Shopping Balance</h5>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-1">
									<input id="shopping-balance" type="radio" name="method" class="form-control radio-input" checked>
								</div>
								<div class="col-11">
									<p class="lead mt-2">
					
					               Personal Balance - <b><?= $login_seller_user_name; ?></b> 
					               <span class="text-success font-weight-bold"><?= showPrice($current_balance); ?></span>

									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
       	<?php } ?>
				<div class="col-md-12 mb-3">
					<div class="card payment-options">
						<div class="card-header">
							<h5><i class="fa fa-credit-card"></i> Payment Options</h5>
						</div>
						<div class="card-body">
							<?php if($enable_paypal == "yes"){ ?>
							<div class="row">
							<div class="col-1">
							<input id="paypal" type="radio" name="method" class="form-control radio-input" 
							<?php
							if($current_balance < $sub_total){ echo "checked"; }
							?>>
							</div>
							<div class="col-11">
							<img src="images/paypal.png" height="50" class="ml-2 width-xs-100">
							</div>
							</div>
							<?php } ?>
							<?php if($enable_stripe == "yes"){ ?>
							<?php if($enable_paypal == "yes"){ ?>
							<hr>
							<?php } ?>
							<div class="row">
							<div class="col-1">
							<input id="credit-card" type="radio" name="method" class="form-control radio-input"
							<?php
							  if($current_balance < $sub_total){
							  if($enable_paypal == "no"){ echo "checked"; }
							  }
							?>>
							</div>
							<div class="col-11">
							<img src="images/credit_cards.jpg" height="50" class="ml-2 width-xs-100">
							</div>
							</div>
							<?php } ?>

							<?php 
								if($enable_2checkout == "yes"){ 
									include("plugins/paymentGateway/paymentMethod1.php");
								} 
							?>

							<?php if($enable_mercadopago == "1"){ ?>
							<?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes"){ ?>
							<hr>
							<?php } ?>
							<div class="row">
							<div class="col-1">
							<input id="mercadopago" type="radio" name="method" class="form-control radio-input"
							<?php
							if($current_balance < $sub_total){
							if($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "1"){ 
							echo "checked";
							}
							}
							?>>
							</div>
							<div class="col-11">
							<img src="images/mercadopago.png" height="50" class="ml-2 width-xs-100">
							</div>
							</div>
							<?php } ?>


							<?php if($enable_coinpayments == "yes"){ ?>
							<?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_mercadopago == "1"){ ?>
							<hr>
							<?php } ?>
							<div class="row">
							<div class="col-1">
							<input id="coinpayments" type="radio" name="method" class="form-control radio-input"
							<?php
							if($current_balance < $sub_total){
							if($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0"){ 
							echo "checked";
							}
							}
							?>>
							</div>
							<div class="col-11">
							<img src="images/coinpayments.png" height="50" class="ml-2 width-xs-100">
							</div>
							</div>
							<?php } ?>

							<?php if($enable_paystack == "yes"){ ?>
							<?php if($enable_mercadopago == "1" or $enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_coinpayments == "yes"){ ?>
							<hr>
							<?php } ?>
							<div class="row">
							<div class="col-1">
							<input id="paystack" type="radio" name="method" class="form-control radio-input"
							<?php
							if($current_balance < $sub_total){
							if($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0" and $enable_coinpayments == "no"){ 
							echo "checked";
							}
							}
							?>>
							</div>
							<div class="col-11">
							<img src="images/paystack.png" height="50" class="ml-2 width-xs-100">
							</div>
							</div>
							<?php } ?>
							<?php if($enable_dusupay == "yes"){ ?>
							<?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_paystack == "yes" or $enable_coinpayments == "yes"){ ?>
							<hr>
							<?php } ?>
							<div class="row">
								<div class="col-1">
									<input id="mobile-money" type="radio" name="method" class="form-control radio-input"
									<?php
									if($current_balance < $sub_total){
									if($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0" and $enable_coinpayments == "no" and $enable_paystack == "no"){ 
										echo "checked"; 
									}
									}
									?>>
								</div>
								<div class="col-11">
									<img src="images/dusupay.png" height="50" class="ml-2 width-xs-100">
								</div>
							</div>
              			<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<div class="card">
				<div class="card-body cart-order-details">
					<p>Cart Subtotal <span class="float-right"><?= showPrice($sub_total); ?></span></p>
					<hr>
					<p class="processing-fee">Processing Fee <span class="float-right"><?= showPrice($processing_fee); ?> </span></p>
					<hr class="processing-fee">
					<p>Total <span class="float-right font-weight-bold total-price"><?= showPrice($total); ?></span></p>
					<hr>
					<?php if($current_balance >= $sub_total){ ?>
					<form action="shopping_balance" method="post" id="shopping-balance-form">
					<button type="submit" name="cart_submit_order" class="btn btn-lg btn-success btn-block" onclick="return confirm('Do you really want to order proposal/services from your shopping balance?')">
					<?= $lang['button']['pay_with_shopping']; ?>
					</button>
					</form>
					<?php } ?>

					<?php if($enable_paypal == "yes"){ ?>
						<div id="paypal-form" class="paypal-button-container"></div>
					<?php } ?>

				   <?php if($enable_stripe == "yes"){ ?>
				   <form action="cart_charge" method="post" id="credit-card-form"><!--- credit-card-form Starts --->
	  					<input name='stripe' type='submit' class="btn btn-lg btn-success btn-block" value='<?= $lang['button']['pay_with_stripe']; ?>'/>
				   </form><!--- credit-card-form Ends --->
				  	<?php } ?>

				  	<?php if($enable_2checkout == "yes"){ ?>
					<form action='plugins/paymentGateway/cart_2checkout_charge' id="2checkout-form" method='post'>
					  <input name='2Checkout' type='submit' class="btn btn-lg btn-success btn-block" value='<?= $lang['button']['pay_with_2checkout']; ?>'/>
					</form>
					<?php } ?>

					<?php if($enable_mercadopago == "1"){ ?>
					<form action="cart_mercadopago_charge" method="post" id="mercadopago-form">
						<input type="submit" name="mercadopago" class="btn btn-lg btn-success btn-block" value="<?= $lang['button']['pay_with_mercadopago']; ?>">
					</form>
					<?php } ?>

					  
				  <?php if($enable_coinpayments == "yes"){ ?>

					<form action="cart_crypto_charge" method="post" id="coinpayments-form">
						<button type="submit" name="coinpayments" class="btn btn-lg btn-success btn-block"><?= $lang['button']['pay_with_coinpayments']; ?></button>
					</form>

			      <?php } ?>
				

					<?php if($enable_paystack == "yes"){ ?>
					<form action="cart_paystack_charge" method="post" id="paystack-form"><!--- paystack-form Starts --->
					 
					<button type="submit" name="paystack" class="btn btn-lg btn-success btn-block">
						<?= $lang['button']['pay_with_paystack']; ?>
					</button>

					</form><!--- paystack-form Ends --->
					<?php } ?>

					<?php 
						if($enable_dusupay == "yes"){
							$form_action = "cart_dusupay_charge";
							include("includes/comp/dusupay_method.php");
						}
					?>

            </div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
<?php if($current_balance >= $sub_total){ ?>
$('.total-price').html('<?= showPrice($sub_total); ?>');
$('.processing-fee').hide();
<?php }else{ ?>
$('.total-price').html('<?= showPrice($total); ?>');
$('.processing-fee').show();
<?php } ?>
<?php if($current_balance >= $sub_total){ ?>
$('#mobile-money-form').hide();
$('#paypal-form').hide();
$('#mercadopago-form').hide();
$('#coinpayments-form').hide();
$('#credit-card-form').hide();
$('#2checkout-form').hide();
$('#paystack-form').hide();
<?php }else{ ?>
$('#shopping-balance-form').hide();
<?php } ?>

<?php if($current_balance < $sub_total){ ?>
<?php if($enable_paypal == "yes"){ ?>
$('#credit-card-form').hide();
$('#2checkout-form').hide();
$('#mobile-money-form').hide();
$('#mercadopago-form').hide();
$('#coinpayments-form').hide();
$('#paystack-form').hide();
<?php }elseif($enable_paypal == "no" and $enable_stripe == "yes"){ ?>
$('#2checkout-form').hide();
$('#coinpayments-form').hide();
$('#mercadopago-form').hide();
$('#mobile-money-form').hide();
$('#paystack-form').hide();
<?php }elseif($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "yes") { ?>
$('#coinpayments-form').hide();
$('#mercadopago-form').hide();
$('#mobile-money-form').hide();
$('#paystack-form').hide();
<?php }elseif($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "1") { ?>
$('#coinpayments-form').hide();
$('#mobile-money-form').hide();
$('#paystack-form').hide();
<?php }elseif($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0" and $enable_coinpayments == "yes") { ?>
$('#mobile-money-form').hide();
$('#paystack-form').hide();
<?php }elseif($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0" and $enable_coinpayments == "no" and $enable_paystack == "yes") { ?>
$('#mobile-money-form').hide();
<?php } ?>
<?php } ?>

$('#shopping-balance').click(function(){
	$('.total-price').html('<?= showPrice($sub_total); ?>');
	$('.processing-fee').hide();
	$('#credit-card-form').hide();
	$('#2checkout-form').hide();
	$('#coinpayments-form').hide();
	$('#mercadopago-form').hide();
	$('#paypal-form').hide();
	$('#paystack-form').hide();
	$('#shopping-balance-form').show();
});

$('#paypal').click(function(){
	$('.total-price').html('<?= showPrice($total); ?>');
	$('.processing-fee').show();
	$('#mobile-money-form').hide();
	$('#credit-card-form').hide();
	$('#2checkout-form').hide();
	$('#paypal-form').show();
	$('#shopping-balance-form').hide();
	$('#coinpayments-form').hide();
	$('#paystack-form').hide();
	$('#mercadopago-form').hide();
});

$('#credit-card').click(function(){
	$('.total-price').html('<?= showPrice($total); ?>');
	$('.processing-fee').show();
	$('#mobile-money-form').hide();
	$('#credit-card-form').show();
	$('#2checkout-form').hide();
	$('#paypal-form').hide();
	$('#shopping-balance-form').hide();
	$('#coinpayments-form').hide();
	$('#paystack-form').hide();
	$('#mercadopago-form').hide();
});

$('#2checkout').click(function(){
	$('.total-price').html('<?= showPrice($total); ?>');
	$('.processing-fee').show();
	$('#mobile-money-form').hide();
	$('#credit-card-form').hide();
	$('#2checkout-form').show();
	$('#paypal-form').hide();
	$('#shopping-balance-form').hide();
	$('#coinpayments-form').hide();
	$('#paystack-form').hide();
	$('#mercadopago-form').hide();
});

$('#mobile-money').click(function(){
	$('.total-price').html('<?= showPrice($total); ?>');
	$('.processing-fee').show();
	$('#mobile-money-form').show();
	$('#credit-card-form').hide();
	$('#paypal-form').hide();
	$('#shopping-balance-form').hide();
	$('#coinpayments-form').hide();
	$('#paystack-form').hide();
	$('#mercadopago-form').hide();
});

$('#coinpayments').click(function(){
	$('.total-price').html('<?= showPrice($total); ?>');
	$('.processing-fee').show();
	$('#mercadopago-form').hide();
	$('#mobile-money-form').hide();
	$('#credit-card-form').hide();
	$('#2checkout-form').hide();
	$('#coinpayments-form').show();
	$('#paypal-form').hide();
	$('#paystack-form').hide();
	$('#shopping-balance-form').hide();
});

$('#paystack').click(function(){
	$('.col-md-5 .card br').hide();
	$('.total-price').html('<?= showPrice($total); ?>');
	$('.processing-fee').show();
	$('#mercadopago-form').hide();
	$('#mobile-money-form').hide();
	$('#credit-card-form').hide();
	$('#2checkout-form').hide();
	$('#coinpayments-form').hide();
	$('#paystack-form').show();
	$('#paypal-form').hide();
	$('#shopping-balance-form').hide();
});

$('#mercadopago').click(function(){
	$('.total-price').html('<?= showPrice($total); ?>');
	$('.processing-fee').show();
	$('#mercadopago-form').show();
	$('#mobile-money-form').hide();
	$('#credit-card-form').hide();
	$('#2checkout-form').hide();
	$('#coinpayments-form').hide();
	$('#paypal-form').hide();
	$('#paystack-form').hide();
	$('#shopping-balance-form').hide();
});

});
</script>
<?php } ?>
<?php require_once("includes/footer.php"); ?>
<script src="js/paypal.js" id="paypal-js" data-base-url="<?= $site_url; ?>" data-payment-type="cart"></script>
</body>
</html>