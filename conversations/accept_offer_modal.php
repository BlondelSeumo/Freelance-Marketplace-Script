<?php

session_start();
require_once("../includes/db.php");
require_once("../functions/processing_fee.php");
if(!isset($_SESSION['seller_user_name'])){
  echo "<script>window.open('../login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_email = $row_login_seller->seller_email;

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
	$enable_2checkout = $row_payment_settings->enable_2checkout;

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

$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;
$_SESSION['c_message_offer_id'] = $input->post('offer_id');
$_SESSION['c_single_message_id'] = $input->post('single_message_id');
$offer_id = $input->post('offer_id');
$single_message_id = $input->post('single_message_id');

$select_offers = $db->select("messages_offers",array("offer_id" => $offer_id));
$row_offers = $select_offers->fetch();
$proposal_id = $row_offers->proposal_id;
$description = $row_offers->description;
$delivery_time = $row_offers->delivery_time;
$amount = $row_offers->amount;
$processing_fee = processing_fee($amount);
$total = $amount+$processing_fee;

$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
$row_proposals = $select_proposals->fetch();
$proposal_title = $row_proposals->proposal_title;

$site_logo_image = getImageUrl2("general_settings","site_logo",$row_general_settings->site_logo);

?>

<div id="accept-offer-modal" class="modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5> Select A Payment Method To Order </h5>
				<button type="button" class="close" data-dismiss="modal"> <span> &times; </span> </button>
			</div>
			<div class="modal-body p-0">
				<div class="order-details">
					<div class="request-div">
						<h4 class="mb-3">
							THIS ORDER IS RELATED TO THE FOLLOWING OFFER:
							<span class="total-price float-right d-sm-block d-none"> <?= showPrice($amount); ?> </span>
						</h4>
						<p> "<?= $description; ?>" </p>
						<p> <b> Proposal: </b> <?= $proposal_title; ?> </p>
						<p> <b> Price: </b> <?= showPrice($amount); ?> </p>
						<p class="processing-fee"> <b> Processing Fee: </b> <?= showPrice($processing_fee); ?> </p>
						<p> <b> Delivery Time: </b> <?= $delivery_time; ?> </p>
					</div>
				</div>
				<div class="payment-options-list">
               	
		        <?php if($current_balance >= $amount){ ?>
				<div class="payment-options mb-2">
					<input type="radio" name="payment_option" id="shopping-balance" class="radio-custom" checked>
					<label for="shopping-balance" class="radio-custom-label" ></label>
					<span class="lead font-weight-bold"> Shopping Balance </span>
					<p class="lead ml-5">
					Personal Balance - <?= $login_seller_user_name; ?> <span class="text-success font-weight-bold"> <?= showPrice($current_balance); ?> </span>
					</p>
				</div>
                <?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_mercadopago == "1" or $enable_coinpayments == "yes" or $enable_dusupay == "yes"){ ?>
				<hr>
                <?php } ?>
                <?php } ?>
                <?php if($enable_paypal == "yes"){ ?>
				<div class="payment-option">
					<input type="radio" name="payment_option" id="paypal" class="radio-custom"
                        <?php
                        if($current_balance < $amount){
                        	echo "checked";
                        }
                        ?>>
					<label for="paypal" class="radio-custom-label"></label>
					<img src="../images/paypal.png" class="img-fluid">
				</div>
               <?php } ?>
               <?php if($enable_stripe == "yes"){ ?>
               <?php if($enable_paypal == "yes"){ ?>
				<hr>
               <?php } ?>
				<div class="payment-option">
					<input type="radio" name="payment_option" id="credit-card" class="radio-custom"
               <?php
                    if($current_balance < $amount){
                       if($enable_paypal == "no"){
                       echo "checked";
                       }
                    }
                ?>>
					<label for="credit-card" class="radio-custom-label"></label>
					<img src="../images/credit_cards.jpg" class="img-fluid">
				</div>
            	<?php } ?>

				<?php 
				if($enable_2checkout == "yes"){ 
					include("../plugins/paymentGateway/paymentMethod2.php");
				} 
				?>

                <?php if($enable_mercadopago == "1"){ ?>
                <?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes"){ ?>
				<hr>
                <?php } ?>
					<div class="payment-option">
						<input type="radio" name="payment_option" id="mercadopago" class="radio-custom"
                            <?php
                            if($current_balance < $amount){
	                            if($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "1"){ 
	                            echo "checked";
	                            }
                            }
                            ?>>
                  <label for="mercadopago" class="radio-custom-label"></label>
						<img src="../images/mercadopago.png" class="img-fluid">
					</div>
                <?php } ?>

                <?php if($enable_coinpayments == "yes"){ ?>
                <?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_mercadopago == "1"){ ?>
				<hr>
                <?php } ?>
					<div class="payment-option">
						<input type="radio" name="payment_option" id="coinpayments" class="radio-custom"
                            <?php
                            if($current_balance < $amount){
                            if($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0"){ 
                            echo "checked";
                            }
                            }
                            ?>>
						<label for="coinpayments" class="radio-custom-label"></label>
						<img src="../images/coinpayments.png" class="img-fluid">
					</div>
                <?php } ?>
                <?php if($enable_paystack == "yes"){ ?>
                <?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_mercadopago == "1" or $enable_coinpayments == "yes"){ ?>
				<hr>
                <?php } ?>
					<div class="payment-option">
						<input type="radio" name="payment_option" id="paystack" class="radio-custom"
                            <?php
                            if($current_balance < $amount){
                            if($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0" and $enable_coinpayments == "no"){ 
                            echo "checked";
                            }
                            }
                            ?>>
						<label for="paystack" class="radio-custom-label"></label>
						<img src="../images/paystack.png" class="img-fluid">
					</div>
                <?php } ?>   
                <?php if($enable_dusupay == "yes"){ ?>
                <?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_2checkout == "yes" or $enable_mercadopago == "1" or $enable_coinpayments =="yes" or $enable_paystack == "yes"){ ?>
				<hr>
                <?php } ?>
					<div class="payment-option">
						<input type="radio" name="payment_option" id="mobile-money" class="radio-custom"
	                           <?php
	                                if($current_balance < $amount){
	                                if($enable_paypal == "no" and $enable_stripe == "no" and $enable_2checkout == "no" and $enable_mercadopago == "0" and $enable_coinpayments == "no" and $enable_paystack == "no"){ 
	                                	echo "checked"; 
	                                }
	                                }
	                            ?>>
						<label for="mobile-money" class="radio-custom-label"></label>
						<img src="../images/dusupay.png" class="img-fluid">
					</div>
                    <?php } ?>  


				</div>
			</div>
			<div class="modal-footer">
			<button class="btn btn-secondary" data-dismiss="modal"> Close </button>
            <?php if($current_balance >= $amount){ ?>
		    <form action="../shopping_balance" method="post" id="shopping-balance-form">
				  <button class="btn btn-success" type="submit" name="message_offer_submit_order" onclick="return confirm('Are you sure you want to pay the featured listing fee with your shopping balance ?')">
					 <?= $lang['button']['pay_with_shopping']; ?>
				  </button>
			   </form>
           <br>

         <?php } ?>
		
      <?php if($enable_paypal == "yes"){ ?>
         <div id="paypal-form" class="paypal-button-container"></div>
		<?php } ?>

		<?php if($enable_stripe == "yes"){ ?>
		<form action="stripe_charge" method="post" id="credit-card-form"><!--- credit-card-form Starts --->

      <input name="stripe" type="submit" class="btn btn-success" value="<?= $lang['button']['pay_with_stripe']; ?>"/>

		</form><!--- credit-card-form Ends --->
		<?php } ?>
		<?php if($enable_2checkout == "yes"){ ?>
			<form action="../plugins/paymentGateway/conversations/2checkout_charge" method="post" id="2checkout-form">
	           <button type="submit" name="2Checkout" class="btn btn-success"><?= $lang['button']['pay_with_2checkout']; ?></button>
	     	</form>
		<?php } ?>

      <?php if($enable_mercadopago == "1"){ ?>
      <form action="mercadopago_charge" method="post" id="mercadopago-form">
         <input type="submit" name="mercadopago" class="btn btn-success" value="<?= $lang['button']['pay_with_mercadopago']; ?>">
      </form>
      <?php } ?>

      <?php if($enable_coinpayments == "yes"){ ?>

      <form action="crypto_charge" method="post" id="coinpayments-form">
         <button type="submit" name="coinpayments" class="btn btn-lg btn-success btn-block"><?= $lang['button']['pay_with_coinpayments']; ?></button>
      </form>

      <?php } ?>

		<?php if($enable_paystack == "yes"){ ?>
		<form action="paystack_charge" method="post" id="paystack-form"><!--- paystack-form Starts --->
		 <button type="submit" name="paystack" class="btn btn-success btn-block"><?= $lang['button']['pay_with_paystack']; ?></button>
		</form><!--- paystack-form Ends --->
		<?php } ?>

      <?php 
         if($enable_dusupay == "yes"){
            $main_modal = "accept-offer-modal";
            $form_action = "dusupay_charge";
            include("../includes/comp/dusupay_method2.php");
         }
      ?>
      
</div><!-- modal-footer Ends -->
</div><!-- modal-content Ends -->
</div><!-- modal-dialog Ends -->
</div><!-- accpet-offer-modal Ends -->

<?php include("../includes/comp/dusupay_payment_modal.php"); ?>

<script 
   src="../js/paypal.js" 
   id="paypal-js" 
   data-base-url="<?= $site_url; ?>" 
   data-payment-type="message_offer">
</script>

<script>
$(document).ready(function(){
   $("#accept-offer-modal").modal('show');
   <?php if($current_balance >= $amount){ ?>
   $('#paypal-form').hide();
   $('#credit-card-form').hide();
   $('#2checkout-form').hide();
   $('#coinpayments-form').hide();
   $('#paystack-form').hide();
   $('#mercadopago-form').hide();
   $('#mobile-money-form').hide();
   <?php }else{ ?>
   $('#shopping-balance-form').hide();
   <?php } ?>
   <?php if($current_balance >= $amount){ ?>	
   $('.total-price').html('<?= showPrice($amount); ?>');
   $('.processing-fee').hide();
   <?php }else{ ?>
   $('.total-price').html('<?= showPrice($total); ?>');
   $('.processing-fee').show();
   <?php } ?>

   <?php if($current_balance < $amount){ ?>
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
   	$('.total-price').html('<?= showPrice($amount); ?>');
   	$('.processing-fee').hide();
    $('#mobile-money-form').hide();
   	$('#credit-card-form').hide();
   	$('#2checkout-form').hide();
   	$('#paypal-form').hide();
   	$('#shopping-balance-form').show();
   	$('#coinpayments-form').hide();
   	$('#mercadopago-form').hide();
   	$('#paystack-form').hide();
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
   	$('#mercadopago-form').hide();
   	$('#paystack-form').hide();
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
   	$('#mercadopago-form').hide();
   	$('#paystack-form').hide();
   });

   $('#2checkout').click(function(){
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

   $('#mercadopago').click(function(){
   	$('.total-price').html('<?= showPrice($total); ?>');
   	$('.processing-fee').show();
   	$('#mobile-money-form').hide();
   	$('#paypal-form').hide();
   	$('#coinpayments-form').hide();
   	$('#paystack-form').hide();
   	$('#mercadopago-form').show();
   	$('#shopping-balance-form').hide();
    $('#2checkout-form').hide();
   });

   $('#coinpayments').click(function(){
   	$('.total-price').html('<?= showPrice($total); ?>');
   	$('.processing-fee').show();
   	$('#mobile-money-form').hide();
   	$('#credit-card-form').hide();
   	$('#2checkout-form').hide();
   	$('#paypal-form').hide();
    $('#coinpayments-form').show();
    $('#paystack-form').hide();
   	$('#mercadopago-form').hide();
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

});
</script>