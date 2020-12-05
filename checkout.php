<?php

	session_start();
	require_once("includes/db.php");
	require_once("functions/processing_fee.php");

	if(!isset($_SESSION['seller_user_name'])){
		echo "<script>window.open('login','_self')</script>";
	}

	if(!isset($_POST['add_order']) and !isset($_POST['add_cart']) and !isset($_POST['coupon_submit']) and !isset($_SESSION['c_proposal_id'])){
		echo "<script>window.open('index','_self')</script>";
	}

	$get_payment_settings = $db->select("payment_settings");
	$row_payment_settings = $get_payment_settings->fetch();
	$enable_paypal = $row_payment_settings->enable_paypal;
	$enable_paypal = $row_payment_settings->enable_paypal;
	$paypal_client_id = $row_payment_settings->paypal_app_client_id;
	$paypal_email = $row_payment_settings->paypal_email;
	$paypal_currency_code = $row_payment_settings->paypal_currency_code;
	$paypal_sandbox = $row_payment_settings->paypal_sandbox;
	$enable_dusupay = $row_payment_settings->enable_dusupay;
	$dusupay_method = $row_payment_settings->dusupay_method;
	$dusupay_provider_id = $row_payment_settings->dusupay_provider_id;

	if($paypal_sandbox == "on"){
		$paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	}elseif($paypal_sandbox == "off"){
		$paypal_url = "https://www.paypal.com/cgi-bin/webscr";
	}

	$enable_stripe = $row_payment_settings->enable_stripe;
	$enable_payza = $row_payment_settings->enable_payza;
	$payza_test = $row_payment_settings->payza_test;
	$payza_currency_code = $row_payment_settings->payza_currency_code;
	$payza_email = $row_payment_settings->payza_email;

	$enable_coinpayments = $row_payment_settings->enable_coinpayments;
	$coinpayments_merchant_id = $row_payment_settings->coinpayments_merchant_id;
	$coinpayments_currency_code = $row_payment_settings->coinpayments_currency_code;

	$enable_mercadopago = $row_payment_settings->enable_mercadopago;

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

	$enable_paystack = $row_payment_settings->enable_paystack;

	$login_seller_user_name = $_SESSION['seller_user_name'];
	$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
	$row_login_seller = $select_login_seller->fetch();
	$login_seller_id = $row_login_seller->seller_id;
	$login_seller_email = $row_login_seller->seller_email;
	
	if(isset($_POST['add_order']) or isset($_POST['coupon_submit'])){

	$_SESSION['c_proposal_id'] = $input->post('proposal_id');
	$_SESSION['c_proposal_qty'] = $input->post('proposal_qty');
	if(isset($_POST['package_id'])){
		$_SESSION['c_package_id'] = $input->post('package_id');
	}

	if(isset($_SESSION['c_proposal_id'])){

	$proposal_id = $_SESSION['c_proposal_id'];
	$proposal_qty = $_SESSION['c_proposal_qty'];

	if(isset($_POST['proposal_minutes'])){
		$_SESSION['c_proposal_minutes'] = $input->post('proposal_minutes');
		$proposal_minutes = $input->post('proposal_minutes');
	}else{
		$proposal_minutes = 1;
		unset($_SESSION['c_proposal_minutes']);
	}

	$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
	$row_proposals = $select_proposals->fetch();

	if(isset($_POST['package_id'])){
		$_SESSION['c_package_id'] = $input->post('package_id');
		$get_p = $db->select("proposal_packages",array("proposal_id"=>$proposal_id,"package_id"=>$input->post('package_id')));
		$row_p = $get_p->fetch();
		$proposal_price = $row_p->price;
		$single_price = $row_p->price;
		$revisions = $row_p->revisions;
		$delivery_id = $row_p->delivery_time;
	}else{
		unset($_SESSION['c_package_id']);
		$proposal_price = $row_proposals->proposal_price*$proposal_minutes;
		$single_price = $row_proposals->proposal_price*$proposal_minutes;
		$revisions = $row_proposals->proposal_revisions;
		$delivery_id = $row_proposals->delivery_id;
	}

	$proposal_title = $row_proposals->proposal_title;
	$proposal_seller_id = $row_proposals->proposal_seller_id;
	$proposal_url = $row_proposals->proposal_url;
	$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);

	$select_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
	$row_seller = $select_seller->fetch();
	$proposal_seller_user_name = $row_seller->seller_user_name;
	$proposal_seller_vacation = $row_seller->seller_vacation;

	if($row_proposals->proposal_seller_id == $login_seller_id or $proposal_seller_vacation == "on"){
		echo "<script>window.open('index','_self')</script>";
	}

	if(isset($_POST['proposal_extras'])){
		
		$extra_price = 0;
		$_SESSION['c_proposal_extras'] = $input->post('proposal_extras');
		
		if (isset($_POST['add_order'])) {
			$proposal_extras = $_SESSION['c_proposal_extras'];
		}else{
			$proposal_extras = unserialize(base64_decode($input->post('proposal_extras')));
			$_SESSION['c_proposal_extras'] = $proposal_extras;
		}
		
		foreach($proposal_extras as $value){
			$get_extras = $db->select("proposals_extras",array("id"=>$value));
			$row_extras = $get_extras->fetch();
			$extra_price += $row_extras->price;
			$proposal_price += $row_extras->price;
		}

	}else{
		unset($_SESSION['c_proposal_extras']);
	}

	$_SESSION['c_proposal_price'] = $proposal_price;

	$sub_total = $proposal_price*$proposal_qty;

	$_SESSION["c_sub_total"] = $sub_total;
	$_SESSION["c_proposal_delivery"] = $delivery_id;
	$_SESSION["c_proposal_revisions"] = $revisions;

	$processing_fee = processing_fee($sub_total);
	$total = processing_fee($sub_total)+$sub_total;

	$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
	$row_seller_accounts = $select_seller_accounts->fetch();
	$current_balance = $row_seller_accounts->current_balance;

	if($proposal_id == @$_SESSION['r_proposal_id']){
		$referrer_id = $_SESSION['r_referrer_id'];
		$sel_referrer = $db->select("sellers",array("seller_id" => $referrer_id));
		$referrer_user_name = $sel_referrer->fetch()->seller_user_name;
	}

?>
<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>
<title><?= $site_name; ?> - Checkout</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="<?= $site_author; ?>">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
<link href="styles/bootstrap.css" rel="stylesheet">
<link href="styles/styles.css" rel="stylesheet">
<link href="styles/categories_nav_styles.css" rel="stylesheet">
<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
<link href="styles/owl.carousel.css" rel="stylesheet">
<link href="styles/owl.theme.default.css" rel="stylesheet">
<link href="styles/sweat_alert.css" rel="stylesheet">
<script type="text/javascript" src="js/sweat_alert.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>

<!-- Include the PayPal JavaScript SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&commit=true&disable-funding=credit,card&currency=<?= $paypal_currency_code; ?>"></script>

<?php if(!empty($site_favicon)){ ?>
<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
<?php } ?>
</head>
<body class="is-responsive">
<?php
require_once("includes/header.php");

if($seller_verification != "ok"){
echo "
<div class='alert alert-danger rounded-0 mt-0 text-center'>
Please confirm your email to use this feature.
</div>";
}else{

$site_logo_image = getImageUrl2("general_settings","site_logo",$row_general_settings->site_logo);

$coupon_usage = "no";
$coupon_type = "";

if(isset($_POST['code'])){
	$coupon_code = $input->post('code');
	$select_coupon = $db->select("coupons",array("proposal_id"=>$proposal_id,"coupon_code"=>$coupon_code));
	$count_coupon = $select_coupon->rowCount();
	if($count_coupon == 1){
		$row_coupon = $select_coupon->fetch();
		$coupon_limit = $row_coupon->coupon_limit;
		$coupon_used = $row_coupon->coupon_used;
		$coupon_type = $row_coupon->coupon_type;
		$coupon_price = $row_coupon->coupon_price;
		if($coupon_limit <= $coupon_used){
			$proposal_price = $input->post('proposal_price');
			$proposal_qty = $input->post('proposal_qty');
			$sub_total = $proposal_price * $proposal_qty;
			$processing_fee = processing_fee($sub_total);
			$total = $processing_fee + $sub_total;
			$coupon_usage = "expired";
			echo "<script> $('.coupon-response').html('Your coupon code expired.').attr('class', 'coupon-response mt-2 p-2 bg-danger text-white'); </script>";
		}else{
			$proposal_price = $input->post('proposal_price');
			$proposal_qty = $input->post('proposal_qty');
			$sub_total = $proposal_price * $proposal_qty;
			$processing_fee = processing_fee($sub_total);
			$total = $processing_fee + $sub_total;
			$select_used = $db->select("coupons_used",array("proposal_id"=>$proposal_id,"seller_id"=>$login_seller_id,"coupon_price"=>$sub_total));
			$count_used = $select_used->rowCount();
			if($count_used == 1){
				$coupon_usage = "a_used";
			}else{
				$update_coupon = $db->query("update coupons set coupon_used=coupon_used+1 where proposal_id=:p_id and coupon_code=:c_code",array("p_id"=>$proposal_id,"c_code"=>$coupon_code));
				if($coupon_type == "fixed_price"){
					
					$proposal_price = $input->post('proposal_price');

					if($coupon_price > $proposal_price){
						$numberToAdd = 0;
						$proposal_price = 0;
					}else{
						$numberToAdd = $coupon_price;
						$proposal_price = $proposal_price-$coupon_price;
					}
					
					if(isset($_POST['proposal_extras'])){
						$proposal_price += $extra_price;
					}

				}else{
					$proposal_price = $input->post('proposal_price');
					$numberToAdd = ($proposal_price / 100) * $coupon_price;
					$proposal_price = $proposal_price - $numberToAdd;
					if(isset($_POST['proposal_extras'])){
						$proposal_price += $extra_price;
					}
				}
				$proposal_qty = $input->post('proposal_qty');
				$sub_total = $proposal_price * $proposal_qty;
				$_SESSION['c_proposal_price'] = $proposal_price;
				$_SESSION["c_sub_total"] = $sub_total;
				$select_used = $db->select("coupons_used",array("proposal_id"=>$proposal_id,"seller_id"=>$login_seller_id,"coupon_price"=>$sub_total));
				$count_used = $select_used->rowCount();
				if($count_used == 0){
					$insert_used = $db->insert("coupons_used",array("proposal_id"=>$proposal_id,"seller_id"=>$login_seller_id,"coupon_used"=>1,"coupon_price"=>$sub_total));
				}
				$processing_fee = processing_fee($sub_total);
				$total = $processing_fee + $sub_total;
				$coupon_usage = "used";
			}
		}
	}else{
		$proposal_price = $input->post('proposal_price');
		$proposal_qty = $input->post('proposal_qty');
		$_SESSION['c_proposal_extras'] = $input->post('proposal_extras');
		if (isset($_POST['add_order'])) {
			$proposal_extras = $_SESSION['c_proposal_extras'];
		}else{
			$proposal_extras = unserialize(base64_decode($input->post('proposal_extras')));
		}
		$sub_total = $proposal_price * $proposal_qty;
		$processing_fee = processing_fee($sub_total);
		$total = $processing_fee + $sub_total;
		$coupon_usage = "not_valid";
	}
}
?>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-md-7">
			<div class="row">
      	<?php if($current_balance >= $sub_total){ ?>
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
							Personal Balance - <?= $login_seller_user_name; ?>
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
								require_once("plugins/paymentGateway/paymentMethod1.php");
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
							<?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_mercadopago == "1" or $enable_coinpayments == "yes"){ ?>
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
							<?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_mercadopago == "1" or $enable_paystack == "yes" or $enable_coinpayments == "yes"){ ?>
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
			<div class="card checkout-details">
				<div class="card-header">
					<h5> <i class="fa fa-file-text-o"></i> Order Summary </h5>
				</div>
				<div class="card-body">
					
					<div class="row">
						<div class="col-md-4 mb-3">
						<img src="<?= $proposal_img1; ?>" class="img-fluid">
						</div>
						<div class="col-md-8">
						<h5><?= $proposal_title; ?></h5>
						</div>
					</div>

					<hr>

					<h6>Proposal's Price: <span class="float-right"><?= showPrice($single_price); ?> </span></h6>

					<?php if(isset($_POST['proposal_extras'])){ ?>
					<hr>
					<h6>Proposal's Extras : <span class="float-right"><?= showPrice($extra_price); ?></span> </h6>
					<?php } ?>

					<hr>
					<h6>Proposal's Quantity: <span class="float-right"><?= $proposal_qty; ?></span></h6>
					<?php if(isset($_SESSION['c_proposal_minutes'])){ ?>
					<hr>
					<h6>Proposal's Video Call Minutes: <span class="float-right"><?= $_SESSION['c_proposal_minutes']; ?> Minutes</span></h6>
					<?php } ?>
					<hr class="processing-fee">
					<h6 class="processing-fee">Processing Fee: <span class="float-right"><?= showPrice($processing_fee); ?></span></h6>

					<?php if(isset($numberToAdd) and $coupon_usage == "used"){ ?>
					<hr>
					<h6>Coupon Discount : <span class="float-right"><?= showPrice($numberToAdd); ?></span> </h6>
					<?php } ?>

					<hr>
					<h6>Appy Coupon Code:</h6>
					<form class="input-group" method="post">
						<input type="hidden" name="proposal_id" value="<?= $proposal_id; ?>">
						<?php if(isset($_POST['proposal_extras'])){ ?>
					  	<input type="hidden" name="proposal_price" value="<?= $proposal_price-$extra_price; ?>">
						<?php }else{ ?>
					  	<input type="hidden" name="proposal_price" value="<?= $proposal_price; ?>">
						<?php } ?>
						<input type="hidden" name="proposal_qty" value="<?= $proposal_qty; ?>">
						<?php if(isset($_POST['package_id'])){ ?>
						<input type="hidden" name="package_id" value="<?= $input->post('package_id');?>">
						<?php } ?>
						<?php if(isset($_POST['proposal_extras'])){ ?>
						<input type="hidden" name="proposal_extras" value="<?= base64_encode(serialize($proposal_extras));?>">
						<?php } ?>
						<input type="text" name="code" class="form-control apply-disabled" placeholder="Enter Coupon Code">
						<button type="submit" name="coupon_submit" class="input-group-addon btn">Apply</button>
					</form>
         			
         			<?php if($coupon_usage == "not_valid"){ ?>
					<p class="coupon-response mt-2 p-2 bg-danger text-white"> <?= $lang['coupon_code']['not_valid']; ?> </p>
          			<?php }elseif($coupon_usage == "used"){ ?>
					<p class="coupon-response mt-2 p-2 applied text-white"><?= $lang['coupon_code']['applied']; ?></p>
					<?php }elseif($coupon_usage == "expired"){ ?>
					<p class="coupon-response mt-2 p-2 bg-danger text-white"> <?= $lang['coupon_code']['expired']; ?> </p>
					<?php }elseif($coupon_usage == "a_used"){ ?>
					<p class="coupon-response mt-2 p-2 bg-success text-white">
						<?= $lang['coupon_code']['already_used']; ?>
					</p>
					<?php } ?>
					<hr>
					<h5 class="font-weight-bold">
						Proposal's Total: <span class="float-right total-price"><?= showPrice($total); ?></span>
					</h5>
					<hr>
			    <?php include("checkoutPayMethods.php"); ?>          
				</div>
				<?php if($proposal_id == @$_SESSION['r_proposal_id']){ ?>
				<div class="card-footer">Referred By : <b><?= $referrer_user_name; ?></b></div>
				<?php } ?>
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
$('#mercadopago-form').hide();
$('#mobile-money-form').hide();
$('#coinpayments-form').hide();
$('#paypal-form').hide();
$('#paystack-form').hide();
$('#credit-card-form').hide();
$('#2checkout-form').hide();
<?php }else{ ?>
$('#shopping-balance-form').hide();
<?php } ?>	
<?php if($current_balance < $sub_total){ ?>
<?php if($enable_paypal == "yes"){ ?>
<?php }else{ ?>
$('#paypal-form').hide();
<?php } ?>
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
	$('#mobile-money-form').hide();
	$('#credit-card-form').hide();
	$('#2checkout-form').hide();
	$('#coinpayments-form').hide();
	$('#paystack-form').hide();
	$('#mercadopago-form').hide();
	$('#paypal-form').hide();
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
	$('#2checkout-form').hide();
	$('#paypal-form').hide();
	$('#shopping-balance-form').hide();
	$('#coinpayments-form').hide();
	$('#paystack-form').hide();
	$('#mercadopago-form').hide();
});
$('#coinpayments').click(function(){
	$('.total-price').html('<?= showPrice($total); ?>');
	$('.processing-fee').show();
	$('#2checkout-form').hide();
	$('#mercadopago-form').hide();
	$('#mobile-money-form').hide();
	$('#credit-card-form').hide();
	$('#coinpayments-form').show();
	$('#paystack-form').hide();
	$('#paypal-form').hide();
	$('#shopping-balance-form').hide();
});
$('#paystack').click(function(){
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
	$('#paystack-form').hide();
	$('#paypal-form').hide();
	$('#shopping-balance-form').hide();
});
});
</script>
<?php } ?>
<?php require_once("includes/footer.php"); ?>

<script src="js/paypal.js" id="paypal-js" data-base-url="<?= $site_url; ?>" data-payment-type="proposal"></script>

</body>
</html>
<?php
}}elseif(isset($_POST['add_cart'])){

	$proposal_id = $input->post('proposal_id');	
	if(isset($_POST['proposal_minutes'])){
		$proposal_qty = $input->post('proposal_minutes');	
		$video = 1;
	}else{
		$proposal_qty = $input->post('proposal_qty');
		$video = 0;	
	}
	$select_proposal = $db->select("proposals",array("proposal_id"=>$proposal_id));
	$row_proposal = $select_proposal->fetch();
	$proposal_price = $row_proposal->proposal_price;

	if(isset($_POST['package_id'])){
		$package_id = $input->post('package_id');
		$get_package = $db->select("proposal_packages",["proposal_id"=>$proposal_id,"package_id"=>$package_id]);
		$row_package = $get_package->fetch();
		$proposal_price = $row_package->price;
		$delivery_id = $row_package->delivery_time;
		$revisions = $row_package->revisions;
	}else{
		$proposal_price = $row_proposal->proposal_price;
		$delivery_id = $row_proposal->delivery_id;
		$revisions = $row_proposal->proposal_revisions;
	}

	$proposal_url = $row_proposal->proposal_url;
	$proposal_seller_id = $row_proposal->proposal_seller_id;
	$select_seller = $db->select("sellers",array("seller_id"=>$proposal_seller_id));
	$row_seller = $select_seller->fetch();
	$seller_user_name = $row_seller->seller_user_name;
	$count_cart = $db->count("cart",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));
	if($count_cart == 1){
		echo "<script>
		alert('This proposal/service is already in your cart.');
		window.open('proposals/$seller_user_name/$proposal_url','_self');
		</script>";
	}else{
		if(isset($_POST['proposal_extras'])){
			$proposal_extras = $input->post('proposal_extras');
			foreach($proposal_extras as $value){
				$get_extras = $db->select("proposals_extras",array("id"=>$value));
				$row_extras = $get_extras->fetch();
				$name = $row_extras->name;
				$price = $row_extras->price;
				$insert_extra = $db->insert("cart_extras",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id,"name"=>$name,"price"=>$price));
			}
		}
		if($videoPlugin == 1){
			$insert_cart = $db->insert("cart",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id,"proposal_price"=>$proposal_price,"proposal_qty"=>$proposal_qty,"delivery_id"=>$delivery_id,"revisions"=>$revisions,"video"=>$video));
		}else{
			$insert_cart = $db->insert("cart",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id,"proposal_price"=>$proposal_price,"proposal_qty"=>$proposal_qty,"delivery_id"=>$delivery_id,"revisions"=>$revisions));
		}
		echo "<script>window.open('proposals/$seller_user_name/$proposal_url','_self');</script>";
	}
}