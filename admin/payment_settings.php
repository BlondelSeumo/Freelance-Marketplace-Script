<?php

  @session_start();
  if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
  }else{

  $get_payment_settings = $db->select("payment_settings");
  $row_payment_settings = $get_payment_settings->fetch();
  $min_proposal_price = $row_payment_settings->min_proposal_price;
  $days_before_withdraw = $row_payment_settings->days_before_withdraw;
  $withdrawal_limit = $row_payment_settings->withdrawal_limit;
  $featured_fee = $row_payment_settings->featured_fee;
  $featured_duration = $row_payment_settings->featured_duration;
  $featured_proposal_while_creating = $row_payment_settings->featured_proposal_while_creating;
  $processing_feeType = $row_payment_settings->processing_feeType;
  $processing_fee = $row_payment_settings->processing_fee;
  $enable_paypal = $row_payment_settings->enable_paypal;
  $paypal_email = $row_payment_settings->paypal_email;
  $paypal_currency_code = $row_payment_settings->paypal_currency_code;
  $paypal_app_client_id = $row_payment_settings->paypal_app_client_id;
  $paypal_app_client_secret = $row_payment_settings->paypal_app_client_secret;
  $paypal_sandbox = $row_payment_settings->paypal_sandbox;
  $enable_payoneer = $row_payment_settings->enable_payoneer;
  $enable_stripe = $row_payment_settings->enable_stripe;
  $stripe_secret_key = $row_payment_settings->stripe_secret_key;
  $stripe_publishable_key = $row_payment_settings->stripe_publishable_key;
  $stripe_webhook_key = $row_payment_settings->stripe_webhook_key;
  $stripe_currency_code = $row_payment_settings->stripe_currency_code;
  
  $enable_coinpayments = $row_payment_settings->enable_coinpayments;
  $coinpayments_merchant_id = $row_payment_settings->coinpayments_merchant_id;
  $coinpayments_currency_code = $row_payment_settings->coinpayments_currency_code;
  $coinpayments_ipn_secret = $row_payment_settings->coinpayments_ipn_secret;
  $coinpayments_withdrawal_fee = $row_payment_settings->coinpayments_withdrawal_fee;
  $coinpayments_public_key = $row_payment_settings->coinpayments_public_key;
  $coinpayments_private_key = $row_payment_settings->coinpayments_private_key;

  $enable_mercadopago = $row_payment_settings->enable_mercadopago;
  $mercadopago_access_token = $row_payment_settings->mercadopago_access_token;
  $mercadopago_currency = $row_payment_settings->mercadopago_currency;
  $mercadopago_sandbox = $row_payment_settings->mercadopago_sandbox;

  $enable_paystack = $row_payment_settings->enable_paystack;
  $paystack_public_key = $row_payment_settings->paystack_public_key;
  $paystack_secret_key = $row_payment_settings->paystack_secret_key;
  $enable_dusupay = $row_payment_settings->enable_dusupay;
  $dusupay_sandbox = $row_payment_settings->dusupay_sandbox;
  $dusupay_currency_code = $row_payment_settings->dusupay_currency_code;
  $dusupay_webhook_hash = $row_payment_settings->dusupay_webhook_hash;
  $dusupay_method = $row_payment_settings->dusupay_method;
  $dusupay_provider_id = $row_payment_settings->dusupay_provider_id;

  $dusupay_payout_method = $row_payment_settings->dusupay_payout_method;
  $dusupay_payout_provider_id = $row_payment_settings->dusupay_payout_provider_id;

  $dusupay_api_key = $row_payment_settings->dusupay_api_key;
  $dusupay_secret_key = $row_payment_settings->dusupay_secret_key;

  $days = array("01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23","24","25","26","27","28","29","30");
  
?>
<div class="breadcrumbs">
  <div class="col-sm-4">
    <div class="page-header float-left">
      <div class="page-title">
        <h1><i class="menu-icon fa fa-cog"></i> Settings / Payment Settings</h1>
      </div>
    </div>
  </div>
  <div class="col-sm-8">
    <div class="page-header float-right">
      <div class="page-title">
        <ol class="breadcrumb text-right">
          <li class="active">Payment Settings</li>
        </ol>
      </div>
    </div>
  </div>
</div>
<div class="container pt-3">
  <div class="row">
    <!--- 2 row Starts --->
    <div class="col-lg-12">
      <?php 
      $form_errors = Flash::render("general_payment_errors");
      $form_data = Flash::render("form_data");
      if(is_array($form_errors)){
      ?>
      <div class="alert alert-danger"><!--- alert alert-danger Starts --->
      <ul class="list-unstyled mb-0">
      <?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
      <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
      <?php } ?>
      </ul>
      </div><!--- alert alert-danger Ends --->
      <?php } ?>
      <!--- col-lg-12 Starts --->
      <div class="card mb-5">
        <!--- card mb-5 Starts --->
        <div class="card-header">
          <!--- card-header Starts --->
          <h4 class="h4">
            <i class="fa fa-money fa-fw"></i> General Payment Settings
          </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
          <!--- card-body Starts --->
          <form action="" method="post">
            <!--- form Starts --->
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Days Before Withdrawal : </label>
              <div class="col-md-6">
                <div class="input-group">
                  <!--- input-group Starts --->
                  <input type="number" name="days_before_withdraw" class="form-control" value="<?= $days_before_withdraw; ?>" min="1" placeholder="1 Minimum" required="">
                  <span class="input-group-addon">
                  <b>Days</b>
                  </span>
                </div>
                <!--- input-group Ends --->
                <small class="form-text text-muted">
                Number of days before revenue earned can be available for withdrawal.
                </small>
              </div>
            </div>
            <!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Minimum Proposal Price : </label>
              <div class="col-md-6">
                <div class="input-group"><!--- input-group Starts --->
                  <span class="input-group-addon">
                  <b><?= $s_currency; ?></b>
                  </span>
                  <input type="number" name="min_proposal_price" class="form-control" value="<?= $min_proposal_price; ?>" placeholder="" required="">
                </div><!--- input-group Ends --->
                <small class="form-text text-muted">&nbsp;</small>
              </div>
            </div><!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Minimum Withdrawal Limit : </label>
              <div class="col-md-6">
                <div class="input-group"><!--- input-group Starts --->
                  <span class="input-group-addon">
                    <b><?= $s_currency; ?></b>
                  </span>
                  <input type="number" name="withdrawal_limit" class="form-control" value="<?= $withdrawal_limit; ?>" placeholder="" required="">
                </div>
                <!--- input-group Ends --->
                <small class="form-text text-muted">
                The minimum available balance a user must have to be able to request a withdrawal. Entering 5 will be $5.00 limit.
                </small>
              </div>
            </div><!--- form-group row Ends --->

            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Featured Proposal Listing Fee : </label>
              <div class="col-md-6">
                <div class="input-group">
                  <!--- input-group Starts --->
                  <span class="input-group-addon">
                  <b><?= $s_currency; ?></b>
                  </span>
                  <input type="number" name="featured_fee" class="form-control" value="<?= $featured_fee; ?>" required="">
                </div>
                <!--- input-group Ends --->
                <small class="form-text text-muted">
                Price you want to charge sellers in order to get their proposals featured
                </small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Featured listing duration : </label>
              <div class="col-md-6">
                <div class="input-group">
                  <!--- input-group Starts --->
                  <input type="number" name="featured_duration" class="form-control" value="<?= $featured_duration; ?>" min="1" placeholder="1 Minimum" required="">
                  <span class="input-group-addon">
                    <b>Days</b>
                  </span>
                </div>
                <!--- input-group Ends --->
                <small class="form-text text-muted">
                Number of days you'd want featured proposals to be featured.
                </small>
              </div>
            </div>
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Allow Featured Proposal Request While Creating a Proposal : </label>
              <div class="col-md-6">
                <div class="input-group">
                <select name="featured_proposal_while_creating" class="form-control" required="">
                  <option value="1" <?php if($featured_proposal_while_creating == 1){ echo "selected"; } ?>> Yes </option>
                  <option value="0" <?php if($featured_proposal_while_creating == 0){ echo "selected"; } ?>> No </option>
                </select>
                </div>
              </div>
            </div>
            <!--- form-group row Ends --->

            <div class="form-group row processing-feeType">
              <label class="col-md-3 control-label"> Processing Fee : </label>
              <div class="col-md-3">
                <select name="processing_feeType" class="form-control">
                  <?php if($processing_feeType == "fixed"){ ?>
                  <option value="fixed"> Fixed Price </option>
                  <option value="percentage">Percentage</option>
                  <?php }else{ ?>
                  <option value="percentage">Percentage</option>
                  <option value="fixed">Fixed Price</option>
                  <?Php } ?>
                </select>
              </div>
              <div class="col-md-3">
                <div class="input-group">
                  <?php if($processing_feeType == "fixed"){ ?>
                  <span class="input-group-addon"><b><?= $s_currency; ?></b></span>
                  <?php }else{ ?> 
                  <span class="input-group-addon"><b>%</b></span>
                  <?php } ?>
                  <input type="number" name="processing_fee" class="form-control" value="<?= $processing_fee; ?>" min="0" placeholder="Enter Processing Fee" required="">
                </div>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"></label>
              <div class="col-md-6">
                <input type="submit" name="update_general_payment_settings" value="Update General Payment Settings" class="btn btn-success form-control">
              </div>
            </div>
            <!--- form-group row Ends --->
          </form>
          <!--- form Ends --->
        </div>
        <!--- card-body Ends --->
      </div>
      <!--- card mb-5 Ends --->
    </div>
    <!--- col-lg-12 Ends --->
  </div>
  <!--- 2 row Ends --->



 <div class="row"><!--- 2 row Starts --->
    <div class="col-lg-12">
      <div class="card mb-5"><!--- card mb-5 Starts -->
        <div class="card-header"><!--- card-header Starts --->
          <h4 class="h4">
            <i class="fa fa-commenting-o"></i> Seller Payment Settings Based On Their Levels
          </h4>
        </div><!--- card-header Ends --->
        <div class="card-body"><!--- card-body Starts --->
          <form action="" method="post">
            <?php 
            $select = $db->select("seller_payment_settings");;
            $count = $select->rowCount();
            $i=0;
            while($row = $select->fetch()){
            $level_title = $db->select("seller_levels_meta",array("level_id"=>$row->level_id,"language_id"=>$adminLanguage))->fetch()->title;
            $i++;
            ?>
            <input type="hidden" name="seller_settings[<?= $i; ?>][id]" value="<?= $row->id; ?>" >
            <div class="form-group row"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Commission Percentage For <?= $level_title; ?> : </label>
              <div class="col-md-6">
                <div class="input-group">
                  <!--- input-group Starts --->
                  <input type="number" name="seller_settings[<?= $i; ?>][commission_percentage]" class="form-control" value="<?= $row->commission_percentage; ?>" required="">
                  <span class="input-group-addon"><b>%</b></span>
                </div>
                <!--- input-group Ends --->
                <small class="form-text text-muted">Percentage comission to take out from every order.</small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row"><!--- form-group row Starts --->
            <label class="col-md-3 control-label"> What day & time in a month do you want to do payouts for <?= $level_title; ?> : </label>
            <div class="col-md-3">
              <div class="input-group">
              <span class="input-group-addon"><b><i class="fa fa-calendar-check-o"></i></b></span>
              <select class="form-control" <?= ($row->payout_anyday == 1)?'disabled':''; ?> name="seller_settings[<?= $i; ?>][payout_day]" required="">
                <?php 
                foreach($days as $day){
                    $selected = ($day == $row->payout_day)?'selected':'';
                    echo "<option value='$day' $selected>$day</option>";
                }
                ?>
              </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="input-group">
              <span class="input-group-addon"><b><i class="fa fa-clock-o"></i></b></span>
              <input type="time" <?= ($row->payout_anyday == 1)?'disabled':''; ?> name="seller_settings[<?= $i; ?>][payout_time]" class="form-control" value="<?= $row->payout_time; ?>" required="">
              </div>
              <label for="payout_anyday" class="float-right">
              <span>Anyday of the month:</span>
              <input class="payout_anyday" type="checkbox" name="seller_settings[<?= $i; ?>][payout_anyday]" value="1" <?php if($row->payout_anyday == 1){ echo "checked";} ?>>
              </label>
            </div>
            </div><!--- form-group row Ends --->
            <hr>
            <?php } ?>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"></label>
              <div class="col-md-6">
                <input type="submit" name="update_seller_payment_settings" value="Update Seller Payment Settings" class="btn btn-success form-control">
              </div>
            </div>
            <!--- form-group row Ends --->
          </form><!--- form Ends --->
        </div><!--- card-body Ends --->
      </div><!--- card mb-5 Ends -->
    </div><!--- col-lg-12 Ends --->
  </div><!---  3 row Ends --->


    <div class="row">
    <!---  3 row Starts --->
    <div class="col-lg-12">
      <!--- col-lg-12 Starts --->
      <div class="card mb-5">
        <!--- card mb-5 Starts -->
        <div class="card-header">
          <!--- card-header Starts --->
          <h4 class="h4">
            <i class="fa fa-paypal"></i> Update Payoneer Settings
          </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
          <!--- card-body Starts --->
          <form action="" method="post">
            <!--- form Starts --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Enable Payoneer : </label>
              <div class="col-md-6">
                <select name="enable_payoneer" class="form-control" required="">
                  <option value="1" <?php if($enable_payoneer == 1){echo "selected";} ?>> Yes </option>
                  <option value="0" <?php if($enable_payoneer == 0){echo "selected";} ?>> No </option>
                </select>
                <small class="form-text text-muted mb-0">Allow users to withdraw using Payoneer.</small>
                <small class="form-text text-muted">In order for this to work, you need to enable manual payouts in general settings</small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"></label>
              <div class="col-md-6">
                <input type="submit" name="update_payoneer_settings" value="Update Payoneer Settings" class="btn btn-success form-control">
              </div>
            </div>
            <!--- form-group row Ends --->
          </form>
        </div>
        <!--- card-body Ends --->
      </div>
      <!--- card mb-5 Ends -->
    </div>
    <!--- col-lg-12 Ends --->
  </div>
  <!---  3 row Ends --->

  <?php 
  if($paymentGateway == 1){
    include("../plugins/paymentGateway/admin/payment_settings1.php");
  } 
  ?>

  <div class="row">
    <!---  3 row Starts --->
    <div class="col-lg-12">
      <!--- col-lg-12 Starts --->
      <div class="card mb-5">
        <!--- card mb-5 Starts -->
        <div class="card-header">
          <!--- card-header Starts --->
          <h4 class="h4">
            <i class="fa fa-paypal"></i> Update PayPal Settings
          </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
          <!--- card-body Starts --->
          <form action="" method="post">
            <!--- form Starts --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Enable PayPal : </label>
              <div class="col-md-6">
                <select name="enable_paypal" class="form-control" required="">
                  <?php if($enable_paypal == 'yes'){ ?>
                  <option value="yes"> Yes </option>
                  <option value="no"> No </option>
                  <?php }elseif($enable_paypal == 'no'){ ?>
                  <option value="no"> No </option>
                  <option value="yes"> Yes </option>
                  <?php } ?>
                </select>
                <small class="form-text text-muted">Allow users to pay using PayPal</small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> PayPal Email : </label>
              <div class="col-md-6">
                <input type="text" name="paypal_email" class="form-control" value="<?= $paypal_email; ?>">
                <small class="form-text text-muted">Enter a PayPal business email address in order to receive payments and also to transfer funds to that PayPal account .</small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> PayPal Currency Code : </label>
              <div class="col-md-6">
                <input type="text" name="paypal_currency_code" class="form-control" value="<?= $paypal_currency_code; ?>">
                <small class="form-text text-muted">
                Currency code used for PayPal payments. Complete list  <a class="text-success" href="https://developer.paypal.com/docs/classic/api/currency_codes/" target="_blank">here</a>
                </small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> PayPal App Client Id : </label>
              <div class="col-md-6">
                <input type="text" name="paypal_app_client_id" class="form-control" value="<?= $paypal_app_client_id; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> PayPal App Client Secret : </label>
              <div class="col-md-6">
                <input type="text" name="paypal_app_client_secret" class="form-control" value="<?= $paypal_app_client_secret; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> PayPal Sandbox : </label>
              <div class="col-md-6">
                <input type="radio" name="paypal_sandbox" value="on" required <?php if($paypal_sandbox=='on' ){ echo "checked"; }else{ } ?> >
                <label> On </label>
                <input type="radio" name="paypal_sandbox" value="off" required <?php if($paypal_sandbox=='off' ){ echo "checked"; }else{ } ?> >
                <label> Off </label>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> </label>
              <div class="col-md-6">
                <input type="submit" name="update_paypal_settings" class="btn btn-success form-control" value="Update PayPal Settings">
              </div>
            </div>
            <!--- form-group row Ends --->
          </form>
          <!--- form Ends --->
        </div>
        <!--- card-body Ends --->
      </div>
      <!--- card mb-5 Ends -->
    </div>
    <!--- col-lg-12 Ends --->
  </div>
  <!---  3 row Ends --->
  <div class="row">
    <!--- 4 row Starts --->
    <div class="col-lg-12">
      <!--- col-lg-12 Starts --->
      <div class="card mb-5">
        <!--- card mb-5 Starts --->
        <div class="card-header">
          <!--- card-header Starts --->
          <h4 class="h4">
            <i class="fa fa-credit-card"></i> Stripe Settings
          </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
          <!--- card-body Starts --->
          <form action="" method="post">
            <!--- form Starts --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Enable Stripe : </label>
              <div class="col-md-6">
                <select name="enable_stripe" class="form-control" required="">
                  <?php if($enable_stripe == "yes"){ ?>
                  <option value="yes"> Yes </option>
                  <option value="no"> No </option>
                  <?php }elseif($enable_stripe == "no"){ ?>
                  <option value="no"> No </option>
                  <option value="yes"> Yes </option>
                  <?php } ?>
                </select>
                <small class="form-text text-muted">Allow buyers to pay with stripe</small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Stripe Secret Key : </label>
              <div class="col-md-6">
                <input type="text" name="stripe_secret_key" class="form-control" value="<?= $stripe_secret_key; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Stripe Publishable Key : </label>
              <div class="col-md-6">
                <input type="text" name="stripe_publishable_key" class="form-control" value="<?= $stripe_publishable_key; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->


            <div class="form-group row"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Stripe Webhook Secret Key : </label>
              <div class="col-md-6">
                <input type="text" name="stripe_webhook_key" class="form-control" value="<?= $stripe_webhook_key; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Stripe Webhook Url : </label>
              <div class="col-md-6">
                <input type="text" disabled=""  class="form-control" value="<?= $site_url."/stripe_webhook"; ?>">
                <small class="text-muted">Paste This Url In Stripe In When You Creating Webhook</small>
              </div>
            </div>
            <!--- form-group row Ends --->

            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Stripe Currency Code : </label>
              <div class="col-md-6">
                <input type="text" name="stripe_currency_code" class="form-control" value="<?= $stripe_currency_code; ?>">
                <small class="form-text text-muted">Currency code. View complete list <a class="text-success" href="https://stripe.com/docs/currencies" target="_blank"> here </a> </small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"></label>
              <div class="col-md-6">
                <input type="submit" name="update_stripe_settings" class="btn btn-success form-control" value="Update Stripe Settings">
              </div>
            </div>
            <!--- form-group row Ends --->
          </form>
          <!--- form Ends --->
        </div>
        <!--- card-body Ends --->
      </div>
      <!--- card mb-5 Ends --->
    </div>
    <!--- col-lg-12 Ends --->
  </div>
  <!--- 4 row Ends --->

  <?php 
  if($paymentGateway == 1){ 
    include("../plugins/paymentGateway/admin/payment_settings2.php");
  }
  ?>

  <div class="row">
    <!---  5 row Starts --->
    <div class="col-lg-12">
      <!--- col-lg-12 Starts --->
      <div class="card mb-5">
        <!--- card mb-5 Starts -->
        <div class="card-header">
          <!--- card-header Starts --->
          <h4 class="h4">
            <i class="fa fa-paypal"></i> Update Coinpayments Settings
          </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
          <!--- card-body Starts --->
          <form action="" method="post">
            <!--- form Starts --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Enable Coinpayments : </label>
              <div class="col-md-6">
                <select name="enable_coinpayments" class="form-control" required="">
                  <?php if($enable_coinpayments == 'yes'){ ?>
                  <option value="yes"> Yes </option>
                  <option value="no"> No </option>
                  <?php }elseif($enable_coinpayments == 'no'){ ?>
                  <option value="no"> No </option>
                  <option value="yes"> Yes </option>
                  <?php } ?>
                </select>
                <small class="form-text text-muted">Allow users to pay using Coinpayments</small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Coinpayments Merchant Id : </label>
              <div class="col-md-6">
                <input type="text" name="coinpayments_merchant_id" class="form-control" value="<?= $coinpayments_merchant_id; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Coinpayments Currency Code : </label>
              <div class="col-md-6">
                <input type="text" name="coinpayments_currency_code" class="form-control" value="<?= $coinpayments_currency_code; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->
            <hr>

            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Coinpayments Private Key : </label>
                <div class="col-md-6">
                    <input type="text" name="coinpayments_private_key" class="form-control" value="<?= $coinpayments_private_key; ?>">
                </div>
            </div>
            <!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Coinpayments Public Key : </label>
                <div class="col-md-6">
                    <input type="text" name="coinpayments_public_key" class="form-control" value="<?= $coinpayments_public_key; ?>">
                </div>
            </div>
            <!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
            <label class="col-md-3 control-label"> Coinpayments Withdrawal Fee : </label>
            <div class="col-md-6">
            <select name="coinpayments_withdrawal_fee" class="form-control">
            <?php if($coinpayments_withdrawal_fee == 'sender'){ ?>
              <option value="sender"> Charge From Us </option>
              <option value="receiver"> Charge From Seller </option>
            <?php }elseif($coinpayments_withdrawal_fee == 'receiver'){ ?>
              <option value="receiver"> Charge From Seller </option>
              <option value="sender"> Charge From Us </option>
            <?php } ?>
            </select>
            </div>
            </div>

            <hr>

            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Coinpayments Ipn Secret : </label>
              <div class="col-md-6">
                <input type="text" name="coinpayments_ipn_secret" value="<?= $coinpayments_ipn_secret; ?>" class="form-control"/>
              </div>
            </div>
            <!--- form-group row Ends --->

            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Coinpayments Ipn Url : </label>
              <div class="col-md-6">
                <input type="text" value="<?= "$site_url/crypto_ipn"; ?>" class="form-control" readonly="">
                <small class="form-text text-muted">
                Paste This Url In Coinpayments Merchant Settings Ipn Url Field
                </small>
              </div>
            </div>
            <!--- form-group row Ends --->

            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> </label>
              <div class="col-md-6">
                <input type="submit" name="update_coinpayments_settings" class="btn btn-success form-control" value="Update Coinpayments Settings">
              </div>
            </div>
            <!--- form-group row Ends --->
          </form>
          <!--- form Ends --->
        </div>
        <!--- card-body Ends --->
      </div>
      <!--- card mb-5 Ends -->
    </div>
    <!--- col-lg-12 Ends --->
  </div>
  <!---  5 row Ends ---> 

  <div class="row"><!---  5 row Starts --->
    <div class="col-lg-12"><!--- col-lg-12 Starts --->
      <div class="card mb-5"><!--- card mb-5 Starts -->
        <div class="card-header"><!--- card-header Starts --->
          <h4 class="h4">
            <i class="fa fa-paypal"></i> Update Mercadopago Settings
          </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
          <!--- card-body Starts --->
          <form action="" method="post">
            <!--- form Starts --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Enable Mercadopago : </label>
              <div class="col-md-6">
                <select name="enable_mercadopago" class="form-control" required="">
                  <?php if($enable_mercadopago == '1'){ ?>
                  <option value="1"> Yes </option>
                  <option value="0"> No </option>
                  <?php }elseif($enable_mercadopago == '0'){ ?>
                  <option value="0"> No </option>
                  <option value="1"> Yes </option>
                  <?php } ?>
                </select>
                <small class="form-text text-muted">Allow users to pay using Mercadopago</small>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Mercadopago Access Token : </label>
              <div class="col-md-6">
                <input type="text" name="mercadopago_access_token" class="form-control" value="<?= $mercadopago_access_token; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Mercadopago Currency Code : </label>
              <div class="col-md-6">
                <input type="text" name="mercadopago_currency" class="form-control" value="<?= $mercadopago_currency; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->
            
            <div class="form-group row"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Mercadopago Sandbox : </label>
              <div class="col-md-6">
                <input type="radio" name="mercadopago_sandbox" value="1" required <?php if($mercadopago_sandbox=='1' ){ echo "checked"; }else{ } ?> >
                <label> On </label>
                <input type="radio" name="mercadopago_sandbox" value="0" required <?php if($mercadopago_sandbox=='0' ){ echo "checked"; }else{ } ?> >
                <label> Off </label>
              </div>
            </div><!--- form-group row Ends --->

            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> </label>
              <div class="col-md-6">
                <input type="submit" name="update_mercadopago_settings" class="btn btn-success form-control" value="Update Mercadopago Settings">
              </div>
            </div>
            <!--- form-group row Ends --->
          </form>
          <!--- form Ends --->
        </div>
        <!--- card-body Ends --->
      </div>
      <!--- card mb-5 Ends -->
    </div>
    <!--- col-lg-12 Ends --->
  </div>
  <!---  5 row Ends --->

  <div class="row">
    <!---  5 row Starts --->
    <div class="col-lg-12">
      <!--- col-lg-12 Starts --->
      <div class="card mb-5">
        <!--- card mb-5 Starts -->
        <div class="card-header">
          <!--- card-header Starts --->
          <h4 class="h4">
            <i class="fa fa-paypal"></i> Update Paystack Settings
          </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
          <!--- card-body Starts --->
          <form action="" method="post">
            <!--- form Starts --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Enable Paystack : </label>
              <div class="col-md-6">
                <select name="enable_paystack" class="form-control" required="">
                  <?php if($enable_paystack == 'yes'){ ?>
                  <option value="yes"> Yes </option>
                  <option value="no"> No </option>
                  <?php }elseif($enable_paystack == 'no'){ ?>
                  <option value="no"> No </option>
                  <option value="yes"> Yes </option>
                  <?php } ?>
                </select>
                <small class="form-text text-muted">Allow users to pay and withdraw using Paystack</small>
              </div>
            </div>
            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Paystack Public Key : </label>
                <div class="col-md-6">
                    <input type="text" name="paystack_public_key" class="form-control" value="<?= $paystack_public_key; ?>">
                </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row"><!--- form-group row Starts --->
                <label class="col-md-3 control-label"> Paystack Secret Key : </label>
                <div class="col-md-6">
                    <input type="text" name="paystack_secret_key" class="form-control" value="<?= $paystack_secret_key; ?>">
                </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> </label>
              <div class="col-md-6">
                <input type="submit" name="update_paystack_settings" class="btn btn-success form-control" value="Update Paystack Settings">
              </div>
            </div><!--- form-group row Ends --->
          </form>
          <!--- form Ends --->
        </div>
        <!--- card-body Ends --->
      </div>
      <!--- card mb-5 Ends -->
    </div>
    <!--- col-lg-12 Ends --->
  </div>
  <!---  5 row Ends --->
  
  <div class="row">
    <!---  3 row Starts --->
    <div class="col-lg-12">
      <!--- col-lg-12 Starts --->
      <div class="card mb-5">
        <!--- card mb-5 Starts -->
        <div class="card-header">
          <!--- card-header Starts --->
          <h4 class="h4">
            <i class="fa fa-paypal"></i> Update Dusupay Settings
          </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
          <!--- card-body Starts --->
          <form action="" method="post">
            <!--- form Starts --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Enable Dusupay : </label>
              <div class="col-md-6">
                <select name="enable_dusupay" class="form-control" required="">
                  <?php if($enable_dusupay == 'yes'){ ?>
                  <option value="yes"> Yes </option>
                  <option value="no"> No </option>
                  <?php }elseif($enable_dusupay == 'no'){ ?>
                  <option value="no"> No </option>
                  <option value="yes"> Yes </option>
                  <?php } ?>
                </select>
                <small class="form-text text-muted">Allow users to pay using Dusupay</small>
              </div>
            </div>

            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Dusupay Api Key : </label>
              <div class="col-md-6">
                <input type="text" name="dusupay_api_key" class="form-control" value="<?= $dusupay_api_key; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->
            
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Dusupay Secret Key : </label>
              <div class="col-md-6">
                <input type="text" name="dusupay_secret_key" class="form-control" value="<?= $dusupay_secret_key; ?>">
              </div>
            </div>
            <!--- form-group row Ends --->


            <!-- <h6 class="text-muted small mb-0">Proposal Buying</h6>
            <hr class="mt-2"> -->
            
            <div class="form-group row d-none"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Payment Method : </label>
              <div class="col-md-6">
                <select name="dusupay_method" class="form-control" required="">
                  <option value="MOBILE_MONEY"> Mobile Money </option>
                  <option value="CARD" <?= ($dusupay_method == "CARD")?"selected":""; ?>> Card </option>
                  <option value="BANK" <?= ($dusupay_method == "BANK")?"selected":""; ?>> Bank </option>
                  <option value="CRYPTO" <?= ($dusupay_method == "CRYPTO")?"selected":""; ?>> Crypto </option>
                </select>
              </div>
            </div><!--- form-group row Ends --->

            <div class="form-group row d-none"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Provider Id : </label>
              <div class="col-md-6">
                <input type="text" name="dusupay_provider_id" class="form-control" value="<?= $dusupay_provider_id; ?>">
                <small class="text-muted">
                  Click Here To Get <a class="text-success" target="_blank" href="index?get_provider_id">Provider Id</a>
                </small>
              </div>
            </div><!--- form-group row Ends --->

            <h6 class="text-muted small mb-0">User Payout/Withdraw Money</h6>
            <hr class="mt-2">

            <div class="form-group row"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Payment Method : </label>
              <div class="col-md-6">
                <select name="dusupay_payout_method" class="form-control" required="">
                  <option value="MOBILE_MONEY"> Mobile Money </option>
                  <option value="BANK" <?= ($dusupay_payout_method == "BANK")?"selected":""; ?>> Bank </option>
                </select>
              </div>
            </div><!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Provider Id : </label>
              <div class="col-md-6">
                <input type="text" name="dusupay_payout_provider_id" class="form-control" value="<?= $dusupay_payout_provider_id; ?>">
                  <small class="text-muted">
                  Click Here To Get <a class="text-success" target="_blank" href="index?get_provider_id">Provider Id</a>
                </small>
              </div>
            </div><!--- form-group row Ends --->

            <hr>

            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Dusupay Currency Code : </label>
              <div class="col-md-6">
                <input type="text" name="dusupay_currency_code" class="form-control" value="<?= $dusupay_currency_code; ?>">
                <small class="form-text text-muted">
                Currency code used for Dusupay payments and payouts. Complete list  <a class="text-success" href="https://developer.paypal.com/docs/classic/api/currency_codes/" target="_blank">here</a>
                </small>
              </div>
            </div>
            <!--- form-group row Ends --->

            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Dusupay Webhook Hash : </label>
              <div class="col-md-6">
                <input type="text" name="dusupay_webhook_hash" class="form-control" value="<?= $dusupay_webhook_hash; ?>">
                <small class="form-text text-muted">
                  <!-- <span class="text-info">Required For Only Live Payments</span> <br> -->
                  Enter Your Dusupay Wehbook Hash That You Added In Dusupay Api Settings.
                </small>
              </div>
            </div>
            <!--- form-group row Ends --->

            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Dusupay Webhook Url : </label>
              <div class="col-md-6">
                <input type="text" value="<?= "$site_url/dusupay_ipn"; ?>" class="form-control" readonly="">
                <small class="form-text text-muted">
                Paste This Url In Dusupay Api Settings WebHook Url Field
                </small>
              </div>
            </div>
            <!--- form-group row Ends --->


            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> Dusupay Sandbox : </label>
              <div class="col-md-6">
                <input type="radio" name="dusupay_sandbox" value="on" required <?php if($dusupay_sandbox=='on' ){ echo "checked"; }else{ } ?> >
                <label> On </label>
                <input type="radio" name="dusupay_sandbox" value="off" required <?php if($dusupay_sandbox=='off' ){ echo "checked"; }else{ } ?> >
                <label> Off </label>
              </div>
            </div>
            <!--- form-group row Ends --->
            <div class="form-group row">
              <!--- form-group row Starts --->
              <label class="col-md-3 control-label"> </label>
              <div class="col-md-6">
                <input type="submit" name="update_dusupay_settings" class="btn btn-success form-control" value="Update Dusupay Settings">
              </div>
            </div>
            <!--- form-group row Ends --->
          </form>
          <!--- form Ends --->
        </div>
        <!--- card-body Ends --->
      </div>
      <!--- card mb-5 Ends -->
    </div>
    <!--- col-lg-12 Ends --->
  </div>
  <!---  3 row Ends --->
  <br>
</div>
<script>
$(document).ready(function(){
  $(".payout_anyday").click(function(event){
    if(this.checked){
     $(this).parent().parent().find("input[type='time']").attr("disabled","disabled");
    $(this).parent().parent().parent().find("select").attr("disabled","disabled");
    }else{
     $(this).parent().parent().find("input[type='time']").removeAttr("disabled");
     $(this).parent().parent().parent().find("select").removeAttr("disabled");
    }
  });
  $('.processing-feeType select').change(function(){
  if($(this).val() == 'fixed'){ 
    $('.processing-feeType .input-group-addon b').html('$');
  }
  if($(this).val() == 'percentage'){ 
    $('.processing-feeType .input-group-addon b').html('%');
  }
  });
});
</script>
<?php
  if(isset($_POST['update_general_payment_settings'])){
    $rules = array(
    "days_before_withdraw" => "number|required",
    "min_proposal_price" => "number",
    "withdrawal_limit" => "number|required",
    "featured_fee" => "number|required",
    "featured_duration" => "number|required",
    "featured_proposal_while_creating" => "number",
    "processing_fee" => "number|required",
    "processing_feeType" => "required",
    );
    $val = new Validator($_POST,$rules,$messages);
    if($val->run() == false){
      Flash::add("general_payment_errors",$val->get_all_errors());
      Flash::add("form_data",$_POST);
      echo "<script> window.open('index?payment_settings','_self');</script>";
    }else{
      $data = $input->post();
      unset($data['update_general_payment_settings']);
      $update_general_payment_settings = $db->update("payment_settings",$data);
      if($update_general_payment_settings){
        $insert_log = $db->insert_log($admin_id,"general_payment_settings","","updated");
        echo "<script>
          swal({
            type: 'success',
            text: 'General Settings Updated Successfully!',
            timer: 3000,
            onOpen: function(){
              swal.showLoading()
            }
          }).then(function(){
            window.open('index?payment_settings','_self');
          });
        </script>";
      }
    }
  }

  if(isset($_POST['update_seller_payment_settings'])){
    $settings = $input->post('seller_settings');
    foreach ($settings as $key => $setting) {
      $id = $setting['id'];
      $select = $db->select("seller_payment_settings",['id'=>$id]);;
      $row = $select->fetch();
      $commission_percentage = $setting['commission_percentage'];
      $payout_day = $setting['payout_day'];
      $payout_time = $setting['payout_time'];
      @$payout_anyday = $setting['payout_anyday'];
      if(empty($payout_day)){
        $payout_day = $row->payout_day;
      }
      if(empty($payout_time)){
        $payout_time = $row->payout_time;
      }
      @$update = $db->update("seller_payment_settings",["commission_percentage"=>$commission_percentage,"payout_day"=>$payout_day,"payout_time"=>$payout_time,"payout_anyday"=>$payout_anyday],["id"=>$id]);
      echo "<script>alert_success('Seller Payment Settings Updated Successfully!','index?payment_settings');</script>"; 
    }
  }

  if(isset($_POST['update_paypal_settings'])){
    $data = $input->post();
    unset($data['update_paypal_settings']);
    $update_paypal_settings = $db->update("payment_settings",$data);
    if($update_paypal_settings){
      $insert_log = $db->insert_log($admin_id,"paypal_settings","","updated");
      echo "<script>alert_success('PayPal Settings Updated Successfully!','index?payment_settings');</script>"; 
    }
  }
  

  if(isset($_POST['update_payoneer_settings'])){
    $data = $input->post();
    unset($data['update_payoneer_settings']);
    $update_stripe_settings = $db->update("payment_settings",$data);
    if($update_stripe_settings){
      $insert_log = $db->insert_log($admin_id,"payoneer_settings","","updated");   
      echo "<script>alert_success('Payoneer Settings Updated Successfully!','index?payment_settings');</script>"; 
    } 
  }


  if(isset($_POST['update_stripe_settings'])){
    $data = $input->post();
    unset($data['update_stripe_settings']);
    $update_stripe_settings = $db->update("payment_settings",$data);
    if($update_stripe_settings){
      $insert_log = $db->insert_log($admin_id,"stripe_settings","","updated");   
      echo "<script>alert_success('Stripe Settings Updated Successfully!','index?payment_settings');</script>"; 
    }
  }
  
  if(isset($_POST['update_coinpayments_settings'])){
    $data = $input->post();
    unset($data['update_coinpayments_settings']);
    $update_coinpayments_settings = $db->update("payment_settings",$data);
    if($update_coinpayments_settings){
      $insert_log = $db->insert_log($admin_id,"coinpayments_settings","","updated");    
      echo "<script>alert_success('Coinpayments Settings Updated Successfully!','index?payment_settings');</script>"; 
    }
  } 
  
  if(isset($_POST['update_mercadopago_settings'])){
    $data = $input->post();
    unset($data['update_mercadopago_settings']);
    $update_settings = $db->update("payment_settings",$data);
    if($update_settings){
      $insert_log = $db->insert_log($admin_id,"mercadopago_settings","","updated");    
      echo "<script>alert_success('Mercadopago Settings Updated Successfully!','index?payment_settings');</script>"; 
    }
  }

  if(isset($_POST['update_paystack_settings'])){
    $data = $input->post();
    unset($data['update_paystack_settings']);
    $update_coinpayments_settings = $db->update("payment_settings",$data);
    if($update_coinpayments_settings){
      $insert_log = $db->insert_log($admin_id,"paystack_settings","","updated");
      echo "<script>alert_success('Paystack Settings Updated Successfully!','index?payment_settings');</script>"; 

    }
  }

  if(isset($_POST['update_dusupay_settings'])){
    $data = $input->post();
    unset($data['update_dusupay_settings']);
    $update_dusupay_settings = $db->update("payment_settings",$data);
    if($update_dusupay_settings){
      $insert_log = $db->insert_log($admin_id,"dusupay_settings","","updated");
      echo "<script>alert_success('Dusupay Settings Updated Successfully!','index?payment_settings');</script>"; 
    }
  }

?>
<?php } ?>