<?php
  @session_start();
  require_once("includes/db.php");
  if(!isset($_SESSION['seller_user_name'])){
    echo "<script>window.open('login','_self')</script>";
  }
?>

<?php if($enable_paypal == "yes"){ ?>

<h5 class="mb-4"> <?= $lang['settings']['paypal_heading']; ?> </h5>
<form method="post" class="clearfix mb-3">
  <div class="form-group row">
    <label class="col-md-4 col-form-label"> <?= $lang['label']['paypal_email']; ?> </label>
    <div class="col-md-8">
      <input type="text" name="seller_paypal_email" value="<?= $login_seller_paypal_email; ?>" placeholder="<?= $lang['placeholder']['paypal_email']; ?>" class="form-control" required/>
    </div>
  </div>
  <button type="submit" name="submit_paypal_email" class="btn btn-success <?= $floatRight ?>">
    <?= $lang['button']['change_paypal_email']; ?>
  </button>
</form>
<?php

if(isset($_POST['submit_paypal_email'])){

  $seller_paypal_email = strip_tags($input->post('seller_paypal_email'));
  
  $update_seller = $db->update("sellers",["seller_paypal_email" => $seller_paypal_email],["seller_id" => $login_seller_id]);
  
  if($update_seller){
    echo "
    <script>
      swal({
        type: 'success',
        text: 'PayPal email updated successfully!',
        timer: 3000,
        onOpen: function(){
          swal.showLoading()
        }
      }).then(function(){
        window.open('settings?account_settings','_self')
      });
    </script>";
  }
}

}

if($paymentGateway == 1){ 
  include("plugins/paymentGateway/account_settings.php");
} 

?>

<?php if($enable_payoneer == 1){ ?>

<hr>
<h5 class="mb-4"> <?= $lang['settings']['payoneer_heading']; ?> </h5>
<form method="post" class="clearfix mb-3">
  <div class="form-group row">
    <label class="col-md-4 col-form-label"> <?= $lang['label']['payoneer_email']; ?> </label>
    <div class="col-md-8">
      <input type="text" name="seller_payoneer_email" value="<?= $login_seller_payoneer_email; ?>" placeholder="Enter payoneer email" class="form-control" required >
    </div>
  </div>
  <button type="submit" name="submit_payoneer_email"class="btn btn-success <?= $floatRight ?>">
    <?= $lang['button']['change_payoneer_email']; ?>
  </button>
</form>
<?php 
if(isset($_POST['submit_payoneer_email'])){
  $seller_payoneer_email = strip_tags($input->post('seller_payoneer_email'));
  $update_seller = $db->update("sellers",array("seller_payoneer_email" => $seller_payoneer_email),array("seller_id" => $login_seller_id));
  if($update_seller){
  echo "<script>
    swal({
      type: 'success',
      text: 'Payoneer email updated successfully!',
      timer: 3000,
      onOpen: function(){
        swal.showLoading()
      }
    }).then(function(){
        // Read more about handling dismissals
        window.open('settings?account_settings','_self')
    });</script>";
  }
}
?>

<?php } ?>

<?php if($enable_dusupay == 1){ ?>

<hr>
<h5 class="mb-4"> <?= $lang['settings']['mobile_money_heading']; ?> </h5>
<form method="post" class="clearfix mb-3">
  <div class="form-group row">
    <label class="col-md-4 col-form-label"> <?= $lang['label']['account_number']; ?> </label>
    <div class="col-md-8">
      <input type="text" name="m_account_number" value="<?= $login_seller_account_number; ?>" placeholder="<?= $lang['placeholder']['account_number']; ?>" class="form-control" required>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-md-4 col-form-label"> <?= $lang['label']['account_owner']; ?> </label>
    <div class="col-md-8">
      <input type="text" name="m_account_name" value="<?= $login_seller_account_name; ?>" placeholder="<?= $lang['placeholder']['account_owner']; ?>" class="form-control" required>
    </div>
  </div>
  <button type="submit" name="update_mobile_money" class="btn btn-success <?= $floatRight ?>">
    <?= $lang['button']['update_mobile_money']; ?>
  </button>
</form>
<?php 
  if(isset($_POST['update_mobile_money'])){
    $m_account_number = strip_tags($input->post('m_account_number'));
    $m_account_name = strip_tags($input->post('m_account_name'));
    $update_seller = $db->update("sellers",array("seller_m_account_number" => $m_account_number,"seller_m_account_name" => $m_account_name),array("seller_id" => $login_seller_id));
    if($update_seller){
      echo "<script>
      swal({
        type: 'success',
        text: 'Mobile Money Updated Successfully!',
        timer: 3000,
        onOpen: function(){
          swal.showLoading()
        }
      }).then(function(){
        window.open('settings?account_settings','_self')
      });
      </script>";
    }
  }

}

?>
<hr>
<h5 class="mb-4"> <?= $lang['settings']['bitcoin_wallet_heading']; ?> </h5>
<form method="post" class="clearfix mb-3">
  <div class="form-group row">
    <label class="col-md-4 col-form-label"> <?= $lang['label']['wallet_address']; ?> </label>
    <div class="col-md-8">
      <input type="text" name="seller_wallet" value="<?= $login_seller_wallet; ?>" placeholder="<?= $lang['placeholder']['wallet_address']; ?>" class="form-control"/>
      <small class="text-danger"><?= $lang['note']['wallet_address']; ?></small>
    </div>
  </div>
  <button type="submit" name="submit_wallet" class="btn btn-success <?= $floatRight ?>">
    <?= $lang['button']['update_wallet_address']; ?>
  </button>
</form>
<?php
  if(isset($_POST['submit_wallet'])){
  $seller_wallet = $input->post('seller_wallet');
  $update_seller = $db->update("sellers",array("seller_wallet" => $seller_wallet),array("seller_id" => $login_seller_id));
  if($update_seller){
  echo "<script>
    swal({
      type: 'success',
      text: 'Wallet Address updated successfully!',
      timer: 3000,
      onOpen: function(){
        swal.showLoading()
      }
    }).then(function(){
        window.open('settings?account_settings','_self')
    });
  </script>";
  }
  }
  ?>
<hr>
<h5 class="mb-4"> <?= $lang['settings']['real_time_notifications']; ?> </h5>
<form method="post" class="clearfix">
  <div class="form-group row mb-3">
    <label class="col-md-4 col-form-label"> <?= $lang['label']['enable_sound']; ?> </label>
    <div class="col-md-8">
      <select name="enable_sound" class="form-control">
        <?php if($login_seller_enable_sound == "yes"){ ?>
          <option value="yes"> Yes </option>
          <option value="no"> No </option>
        <?php }elseif($login_seller_enable_sound == "no"){ ?>
          <option value="no"> No </option>
          <option value="yes"> Yes </option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="form-group row mb-3">
    <label class="col-md-4 col-form-label"> <?= $lang['label']['enable_notifications']; ?> </label>
    <div class="col-md-8">
      <select name="enable_notifications" class="form-control">
        <?php if($login_seller_enable_notifications == "1"){ ?>
          <option value="1"> Yes </option>
          <option value="0"> No </option>
        <?php }elseif($login_seller_enable_notifications == "0"){ ?>
          <option value="0"> No </option>
          <option value="1"> Yes </option>
        <?php } ?>
      </select>
    </div>
  </div>
  <button type="submit" name="update_sound" class="btn btn-success mt-1 <?= $floatRight ?>">
    <?= $lang['button']['update_changes']; ?>
  </button>
</form>
<?php 
  if(isset($_POST['update_sound'])){
    $enable_sound = strip_tags($input->post('enable_sound'));
    $enable_notifications = strip_tags($input->post('enable_notifications'));
    $update_seller = $db->update("sellers",["enable_sound"=>$enable_sound,"enable_notifications"=>$enable_notifications],["seller_id"=>$login_seller_id]);
    if($update_seller){
    echo "<script>
      swal({
        type: 'success',
        text: 'Realtime notifications settings updated successfully.',
        timer: 2000,
        onOpen: function(){
          swal.showLoading()
        }
      }).then(function(){
        window.open('settings?account_settings','_self')
      });
      </script>";
    }
  }
  ?>
<hr>
<h5 class="mb-4"> <?= $lang['settings']['change_pass']; ?> </h5>
<?php 
  $form_errors = Flash::render("change_pass_errors");
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
<form method="post" class="clearfix mb-3">
  <div class="form-group row">
    <label class="col-md-4 col-form-label"> <?= $lang['label']['enter_old_pass']; ?> </label>
    <div class="col-md-8">
      <input type="text" name="old_pass" class="form-control" required=""/>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-md-4 col-form-label"> <?= $lang['label']['enter_new_pass']; ?> </label>
    <div class="col-md-8">
      <input type="text" name="new_pass" class="form-control" required=""/>
    </div>
  </div>
  <div class="form-group row">
    <label class="col-md-4 col-form-label"> <?= $lang['label']['confirm_new_pass']; ?> </label>
    <div class="col-md-8">
      <input type="text" name="new_pass_again" class="form-control" required=""/>
    </div>
  </div>
  <button type="submit" name="change_password" class="btn btn-success <?= $floatRight; ?>">
    <?= $lang['button']['change_password']; ?>
  </button>
</form>
<?php 
  if(isset($_POST['change_password'])){
  $rules = array(
  "old_pass" => "required",
  "new_pass" => "required",
  "new_pass_again" => "required");
  $messages = array("old_pass" => "Old Password Is Required.","new_pass" => "New Password Is Required.","new_pass_again"=>"New Password Again Is Required.");
  $val = new Validator($_POST,$rules,$messages);
  if($val->run() == false){
    Flash::add("change_pass_errors",$val->get_all_errors());
    Flash::add("form_data",$_POST);
    echo "<script> window.open('settings?account_settings','_self');</script>";
  }else{
    $old_pass = $input->post('old_pass');
    $new_pass = $input->post('new_pass');
    $new_pass_again = $input->post('new_pass_again');
    $get_seller = $db->select("sellers",array("seller_id"=>$login_seller_id));
    $row_seller = $get_seller->fetch();
    $hash_password = $row_seller->seller_pass;
    $decrypt_password = password_verify($old_pass,$hash_password);
    if($decrypt_password == 0){
    echo "<script>
      swal({
        type: 'warning',
        html: $('<div>').addClass('some-class').text('Your old password is invalid, please try again!.'),
        animation: false,
        customClass: 'animated tada'
      });
      </script>";
    }else{
      if($new_pass!=$new_pass_again){
       echo "<script>alert('Your New Password dose not match.');</script>";
      }else{
        $encrypted_password = password_hash($new_pass, PASSWORD_DEFAULT);
        $update_pass = $db->update("sellers",array("seller_pass"=>$encrypted_password),array("seller_id" => $login_seller_id));
        echo "<script>
        swal({
          type: 'success',
          text: 'Password updated successfully, login with your new password.',
          timer: 3000,
          onOpen: function(){
            swal.showLoading();
          }
        }).then(function(){
          window.open('logout','_self')
        });
        </script>";
      }
    }
    }
  }
  ?>
<hr>
<h5 class="mb-1"> <?= $lang['settings']['account_deactivation']; ?> </h5>
<ul class="list-unstyled <?= $floatRight ?>">
  <li class="lead mb-2">
    <strong> <?= $lang['settings']['what_happens']['title']; ?> </strong>
  </li>
  <li><i class="fa fa-hand-o-right"></i> <?= $lang['settings']['what_happens']['1']; ?> </li>
  <li><i class="fa fa-hand-o-right"></i> <?= $lang['settings']['what_happens']['2']; ?> </li>
  <li><i class="fa fa-hand-o-right"></i> <?= $lang['settings']['what_happens']['3']; ?> </li>
  <li><i class="fa fa-hand-o-right"></i> <?= $lang['settings']['what_happens']['4']; ?> </li>
</ul>
<div class="clearfix"></div>
<form method="post">
  <?php if(!$current_balance == 0){ ?>
  <div class="form-group"><!-- form-group Starts -->
    <h5 class="pt-3 pb-3"> <?= $lang['settings']['please_withdraw']; ?> </h5>
  </div><!-- form-group Ends -->
  <button type="submit" name="deactivate_account" disabled class="btn btn-danger <?= $floatRight ?>">
    <i class="fa fa-frown-o"></i> <?= $lang['button']['deactivate_account']; ?>
  </button>
  <?php }elseif($current_balance == 0){ ?>
  <div class="form-group">
    <label> Why Are You Leaving? </label>
    <select name="deactivate_reason" class="form-control" required>
      <option value=""> <?= $lang['settings']['reason']['1']; ?> </option>
      <option> <?= $lang['settings']['reason']['2']; ?> </option>
      <option> <?= $lang['settings']['reason']['3']; ?> </option>
      <option> <?= $lang['settings']['reason']['4']; ?> </option>
      <option> <?= $lang['settings']['reason']['5']; ?> </option>
      <option> <?= $lang['settings']['reason']['6']; ?> </option>
      <option> <?= $lang['settings']['reason']['7']; ?> </option>
      <option> <?= $lang['settings']['reason']['8']; ?> </option>
      <option> <?= $lang['settings']['reason']['9']; ?> </option>
      <option> <?= $lang['settings']['reason']['10']; ?> </option>
    </select>
  </div>
  <button type="submit" name="deactivate_account" class="btn btn-danger <?= $floatRight ?>">
  <i class="fa fa-frown-o"></i> <?= $lang['button']['deactivate_account']; ?>
  </button>
  <?php } ?>   
</form>
<?php
if(isset($_POST['deactivate_account'])){

  $update_seller = $db->update("sellers",array("seller_status" => 'deactivated'),array("seller_id" => $login_seller_id));
  if($update_seller){

    $sel_orders = $db->select("orders",array("seller_id" => $login_seller_id,"order_active" => "yes"));
    while($row_orders = $sel_orders->fetch()){
      $order_id = $row_orders->order_id;
      $seller_id = $row_orders->seller_id;
      $buyer_id = $row_orders->buyer_id;
      $order_price = $row_orders->order_price;
      $notification_date =  date("F d, Y");
      $purchase_date = date("F d, Y");
      $insert_notification = $db->insert("notifications",array("receiver_id" => $buyer_id,"sender_id" => $seller_id,"order_id" => $order_id,"reason" => "order_cancelled","date" => $notification_date,"status" => "unread"));

      /// sendPushMessage Starts
      $notification_id = $db->lastInsertId();
      sendPushMessage($notification_id);
      /// sendPushMessage Ends

      $insert_purchase = $db->insert("purchases",array("seller_id" => $buyer_id,"order_id" => $order_id,"amount" => $order_price,"date" => $purchase_date,"method" => "order_cancellation"));
      $update_balance = $db->update("seller_accounts",array("used_purchases" => "used_purchases-$order_price","current_balance" => "current_balance+$order_price"),array("seller_id" => $buyer_id));
      $update_orders = $db->update("orders",array("order_status" => 'cancelled',"order_active" => 'no'),array("order_id" => $order_id));
    }
    // $delete_proposals = $db->delete("proposals",array("proposal_seller_id" => $seller_id));
    $update_proposals = $db->update("proposals",array("proposal_status" => 'deleted'),array("proposal_seller_id" => $seller_id));
    unset($_SESSION['seller_user_name']);
    echo "<script>
      swal({
        type: 'success',
        text: 'Your account has been deactivated successfully. Goodbye!',
        timer: 3000,
        onOpen: function(){
        swal.showLoading()
        }
      }).then(function(){
        window.open('settings?profile_settings','');
        console.log('Account deactivated successfully');
      })
      </script>";
  }

}
?>