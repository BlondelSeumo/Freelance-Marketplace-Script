<?php

session_start();
require_once("../../includes/db.php");
require_once("../../functions/processing_fee.php");
if(!isset($_SESSION['seller_user_name'])){
  echo "<script>window.open('../login','_self')</script>";
}

$login_seller_email = $row_login_seller->seller_email;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$enable_paypal = $row_payment_settings->enable_paypal;
$enable_stripe = $row_payment_settings->enable_stripe;
$enable_dusupay = $row_payment_settings->enable_dusupay;
$dusupay_method = $row_payment_settings->dusupay_method;
$dusupay_provider_id = $row_payment_settings->dusupay_provider_id;

$enable_coinpayments = $row_payment_settings->enable_coinpayments;
$coinpayments_merchant_id = $row_payment_settings->coinpayments_merchant_id;
$coinpayments_currency_code = $row_payment_settings->coinpayments_currency_code;
if($paymentGateway == 1){
  $enable_2checkout = $row_payment_settings->enable_2checkout;
  $get_plugin = $db->query("select * from plugins where folder='paymentGateway'");
  $row_plugin = $get_plugin->fetch();
  $paymentGatewayVersion = $row_plugin->version;
}else{
  $enable_2checkout = "no"; 
  $paymentGatewayVersion = 0.0;
}
$enable_mercadopago = $row_payment_settings->enable_mercadopago;
$enable_paystack = $row_payment_settings->enable_paystack;

$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;

$order_id = $input->post('order_id');
$amount = $input->post('amount');
$message = $input->post('message');

$processing_fee = processing_fee($amount);
$total = $amount+$processing_fee;

// below variable shall use in paymentMethodCharges
$_SESSION['tipOrderId'] = $order_id;
$_SESSION['tipAmount'] = $amount;
$_SESSION['tipMessage'] = $message;

$site_logo = $row_general_settings->site_logo;
$site_logo_image = getImageUrl2("general_settings","site_logo",$row_general_settings->site_logo);

?>

<div id="payment-modal" class="modal fade" style="overflow-y: scroll;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><!-- modal-header Starts -->
        <h5 class="modal-title"> 
          <span class="float-left">Payment For Order Tip.</span>
        </h5>
        <button class="closeExtendTimePayment close" data-dismiss="modal">
          <span> &times; </span>
        </button>
      </div>
      <div class="modal-body p-0">
        <div class="order-details">
          <div class="request-div" style="background-color: #d1ecf1;">
            <p> <b> Tip Amount: </b> <?= showPrice($amount); ?> </p>
            <p class="processing-fee"> <b> Processing Fee: </b> <?= showPrice($processing_fee); ?> </p>
            <p class="processing-fee"> <b> Total </b> <?= showPrice($total); ?> </p>
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
          <?php if($enable_paypal == "yes" or $enable_stripe == "yes" or $enable_coinpayments == "yes" or $enable_dusupay == "yes"){ ?>
          <hr>
          <?php } ?>
          <?php } ?>
          
          <?php if($enable_paypal == "yes"){ ?>
          <div class="payment-option">
            <input type="radio" name="payment_option" id="paypal" class="radio-custom" <?php if($current_balance < $amount){ echo "checked"; } ?>>
            <label for="paypal" class="radio-custom-label"></label>
            <img src="images/paypal.png" class="img-fluid">
          </div>
          <?php } ?>

          <?php if($enable_stripe == "yes"){ ?>
          <?php if($enable_paypal == "yes"){ ?>
          <hr>
          <?php } ?>
          <div class="payment-option">
            <input type="radio" name="payment_option" id="credit-card" class="radio-custom" <?php if($current_balance < $amount){ if($enable_paypal == "no"){ echo "checked";}} ?>>
            <label for="credit-card" class="radio-custom-label"></label>
            <img src="images/credit_cards.jpg" class="img-fluid">
          </div>
          <?php } ?>

          <?php 
            if($enable_2checkout == "yes" AND $paymentGatewayVersion >= 1.2){ 
              include("$dir/plugins/paymentGateway/paymentMethod2.php");
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
            <img src="images/mercadopago.png" class="img-fluid">
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
                ?>
              >
            <label for="coinpayments" class="radio-custom-label"></label>
            <img src="images/coinpayments.png">
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
            <img src="images/paystack.png" class="img-fluid">
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
              ?>
              >
            <label for="mobile-money" class="radio-custom-label"></label>
            <img src="images/dusupay.png" class="img-fluid">
          </div>
          <?php } ?> 
        </div>
      </div>
      <div class="modal-footer">
        <button class="closeExtendTimePayment btn btn-secondary" data-dismiss="modal"> Close </button>
        <?php if($current_balance >= $amount){ ?>
        <form action="orderIncludes/charge/shoppingBalance" method="post" id="shopping-balance-form">
          <button class="btn btn-success" type="submit" name="orderTip" onclick="return confirm('Are you sure you want to pay for this with your shopping balance?')">
          <?= $lang['button']['pay_with_shopping']; ?>
          </button>
        </form>
        <br>
        <?php } ?>

        <?php if($enable_paypal == "yes"){ ?>
          <div id="paypal-form" class="paypal-button-container"></div>
        <?php } ?>

        <?php 
        if($enable_stripe == "yes"){
        ?>
        <form action="orderIncludes/charge/stripe" method="post" id="credit-card-form">

          <input name="stripe" type="submit" class="btn btn-success" value="<?= $lang['button']['pay_with_stripe']; ?>"/>

        </form>
        <?php } ?>
        
        <?php if($enable_2checkout == "yes" AND $paymentGatewayVersion >= 1.2){ ?>
        <form action='plugins/paymentGateway/orderTip/2checkout_charge' id="2checkout-form" method='post'>
            <input name='2Checkout' type='submit' class="btn btn-success btn-block" value='<?= $lang['button']['pay_with_2checkout']; ?>'/>
        </form>
        <?php } ?>

        <?php if($enable_mercadopago == "1"){ ?>
        <form action="orderIncludes/charge/mercadopago" method="post" id="mercadopago-form">
          <input type="submit" name="mercadopago" class="btn btn-success" value="<?= $lang['button']['pay_with_mercadopago']; ?>">
        </form>
        <?php } ?>

        <?php if($enable_paystack == "yes"){ ?>
        <form action="orderIncludes/charge/paystack" method="post" id="paystack-form"><!--- paystack-form Starts --->
          <button type="submit" name="paystack" class="btn btn-success btn-block"><?= $lang['button']['pay_with_paystack']; ?></button>
        </form><!--- paystack-form Ends --->
        <?php } ?>

        <?php 
           if($enable_dusupay == "yes"){
              $main_modal = "payment-modal";
              $form_action = "orderIncludes/charge/dusupay";
              include("../../includes/comp/dusupay_method2.php");
           }
        ?>

        <?php if($enable_coinpayments == "yes"){ ?>

        <form action="orderIncludes/charge/crypto" method="post" id="coinpayments-form">
          <button type="submit" name="coinpayments" class="btn btn-lg btn-success btn-block"><?= $lang['button']['pay_with_coinpayments']; ?></button>
        </form>

        <?php } ?>
      </div>
    </div>
  </div>
</div>

<?php include("../../includes/comp/dusupay_payment_modal.php"); ?>

<script 
   src="js/paypal.js" 
   id="paypal-js" 
   data-base-url="<?= $site_url; ?>" 
   data-payment-type="orderTip">
</script>

<script>
  
$(document).ready(function(){

  // $("#payment-modal").modal('show');

  $('#payment-modal').modal({ backdrop: 'static', show: true });
  
  $("body").css('overflowY', 'hidden');

  $('#payment-modal').on('hidden.bs.modal', function (e) {
    $("body").css('overflowY', 'scroll');
  })

  <?php if($current_balance >= $amount){ ?>
    $('#paypal-form').hide();
    $('#credit-card-form').hide();
    $('#2checkout-form').hide();
    $('#coinpayments-form').hide();
    $('#paystack-form').hide();
    $('#mercadopago-form').hide();
    $('#mobile-money-form').hide();
    $('.processing-fee').hide();
  <?php }else{ ?>
    $('#shopping-balance-form').hide();
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
    $('#credit-card-form').hide();
    $('#2checkout-form').hide();
    $('#paypal-form').hide();
    $('#shopping-balance-form').show();
    $('#coinpayments-form').hide();
    $('#mercadopago-form').hide();
    $('#paystack-form').hide();
    $('.processing-fee').hide();
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
    $('.processing-fee').show();
  });

  $('#credit-card').click(function(){
    $('.total-price').html('<?= showPrice($total); ?>');
    $('.processing-fee').show();
    $('#mobile-money-form').hide();
    $('#credit-card-form').show();
    $('#paypal-form').hide();
    $('#2checkout-form').hide();
    $('#shopping-balance-form').hide();
    $('#coinpayments-form').hide();
    $('#mercadopago-form').hide();
    $('#paystack-form').hide();
    $('.processing-fee').show();
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
    $('.processing-fee').show();
  });

  $('#mercadopago').click(function(){
    $('.total-price').html('<?= showPrice($total); ?>');
    $('.processing-fee').show();
    $('#mobile-money-form').hide();
    $('#credit-card-form').hide();
    $('#paypal-form').hide();
    $('#coinpayments-form').hide();
    $('#paystack-form').hide();
    $('#mercadopago-form').show();
    $('#shopping-balance-form').hide();
    $('#2checkout-form').hide();
    $('.processing-fee').show();
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
    $('.processing-fee').show();
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
    $('.processing-fee').show();
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
    $('.processing-fee').show();
  });

});

</script>