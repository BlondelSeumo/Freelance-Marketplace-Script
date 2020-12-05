<?php
session_start();
require_once("includes/db.php");
require_once("functions/processing_fee.php");
if(!isset($_SESSION['seller_user_name'])){
  echo "<script>window.open('login','_self')</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

$total = 0;
$select_cart = $db->select("cart",array("seller_id" => $login_seller_id));
while($row_cart = $select_cart->fetch()){
  $proposal_price = $row_cart->proposal_price;
  $proposal_qty = $row_cart->proposal_qty;
  $cart_extras = $db->select("cart_extras",array("seller_id"=>$login_seller_id,"proposal_id"=>$row_cart->proposal_id));
  while($extra = $cart_extras->fetch()){
    $proposal_price += $extra->price;
  }
  $sub_total = $proposal_price * $proposal_qty;
  $total += $sub_total;
}
$processing_fee = processing_fee($total);

?>

<div class="col-md-7"><!--- col-md-7 Starts --->
<div class="card mb-3"><!--- card mb-3 Starts --->
<div class="card-body"><!--- card-body Starts --->
<?php

$select_cart = $db->select("cart",array("seller_id" => $login_seller_id));
while($row_cart = $select_cart->fetch()){
  $proposal_id = $row_cart->proposal_id;
  $proposal_price = $row_cart->proposal_price;
  $proposal_qty = $row_cart->proposal_qty;
  @$video = $row_cart->video;
  
  $select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
  $row_proposals = $select_proposals->fetch();
  $proposal_title = $row_proposals->proposal_title;
  $proposal_url = $row_proposals->proposal_url;
  $proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
  $proposal_seller_id = $row_proposals->proposal_seller_id;
  
  $get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
  $row_seller = $get_seller->fetch();
  $proposal_seller_user_name = $row_seller->seller_user_name;
  $sub_total = $proposal_price * $proposal_qty;

  $cart_extras = $db->select("cart_extras",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));

?>

<div class="cart-proposal"><!--- cart-proposal Starts --->
  <div class="row"><!--- row Starts --->
    <div class="col-lg-3 mb-2"><!--- col-lg-3 mb-2 Starts --->
      <a href="proposals/<?= $proposal_url; ?>">
      <img src="<?= $proposal_img1; ?>" class="img-fluid">
      </a>
    </div><!--- col-lg-3 mb-2 Ends --->
    <div class="col-lg-9"><!--- col-lg-9 Starts --->
      <a href="proposals/<?= $proposal_url; ?>">
      <h6 class="make-black"> <?= $proposal_title; ?> </h6>
      <ul class="ml-0 mb-2" style="list-style-type: circle;">
        <?php
        while($extra = $cart_extras->fetch()){
          $name = $extra->name;
          $price = $extra->price;
          $proposal_price += $price;
        ?>
        <li class="font-weight-normal text-muted">
          <?= $name; ?> (+<span class="price"><?= $s_currency.$price; ?></span>)
        </li>
        <?php } ?>
      </ul>
      </a>
      <a href="cart?remove_proposal=<?= $proposal_id; ?>" class="remove-link text-muted">
        <i class="fa fa-times"></i> <?= $lang['cart']['remove_proposal']; ?>
      </a>
    </div><!--- col-lg-9 Ends --->
  </div><!--- row Ends --->
  <hr>
  <h6 class="clearfix">
    <?= ($video == 1)?$lang['cart']['proposal_call_minutes']:$lang['cart']['proposal_qty']; ?>
    <strong class="float-right price ml-2 mt-2"><?= showPrice($proposal_price*$proposal_qty); ?></strong>
    <input type="text" name="quantity" class="float-right form-control quantity" min="1" data-proposal_id="<?= $proposal_id; ?>" value="<?= $proposal_qty; ?>">
  </h6>
  <hr>
</div><!--- cart-proposal Ends --->
<?php } ?>

<h3 class="float-right"> Total : <?= showPrice($total); ?> </h3>
</div><!--- card-body Ends --->
</div><!--- card mb-3 Ends --->
</div><!--- col-md-7 Ends --->

<div class="col-md-5"><!--- col-md-5 Starts --->
  <div class="card">
    <div class="card-body cart-order-details">
      <p>Cart Subtotal <span class="float-right"><?= showPrice($total); ?></span></p>
      <hr>
      <p>Apply Coupon Code</p>
      <form class="input-group" method="post">
      <input type="text" name="code" class="form-control apply-disabled" placeholder="Enter Coupon Code">
      <button type="submit" name="coupon_submit" class="input-group-addon btn btn-success">Apply</button>
      </form>
      <?php
      $coupon_usage = "no";
      ?>
      <hr>
      <?php if($coupon_usage == "not_valid"){ ?>
      <p class="coupon-response mt-2 p-2 bg-danger text-white"> Your Coupon Code Is Not Valid. </p>
      <?php }elseif($coupon_usage == "no" & isset($_GET['coupon_applied'])){ ?>
      <p class="coupon-response mt-2 p-2 bg-success text-white">Your coupon code has been applied successfully.</p>
      <?php }elseif($coupon_usage == "expired"){ ?>
      <p class="coupon-response mt-2 p-2 bg-danger text-white"> Your Coupon Code Is Expired. </p>
      <?php }elseif($coupon_usage == "not_apply"){ ?>
      <p class="coupon-response mt-2 p-2 bg-success text-white"> Your coupon code does not apply to proposal/service in your cart. </p>
      <?php } ?>
      <?php if($coupon_usage != "no"){ ?>
      <hr>
      <?php } ?>
      <p>Processing Fee <span class="float-right"><?= showPrice($processing_fee); ?></span></p>
      <hr>
      <p>Total<span class="font-weight-bold float-right"><?= showPrice($total+$processing_fee); ?></span></p>
      <hr>
      <a href="cart_payment_options" class="btn btn-lg btn-success btn-block">Proceed To Payment</a>
    </div>
  </div>
</div><!--- col-md-5 Ends --->