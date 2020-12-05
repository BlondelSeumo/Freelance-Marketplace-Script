<?php

session_start();
require_once("includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('login','_self');</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if(isset($_POST['checkout_submit_order'])){
	$proposal_id = $_SESSION['c_proposal_id'];
	$proposal_qty = $_SESSION['c_proposal_qty'];
	$amount = $_SESSION["c_sub_total"];
	$update_buyer_balance = $db->query("update seller_accounts set used_purchases=used_purchases+:plus,current_balance=current_balance-:minus where seller_id='$login_seller_id'",array("plus"=>$amount,"minus"=>$amount));
	if($update_buyer_balance){
		$_SESSION['checkout_seller_id'] = $login_seller_id;
		$_SESSION['proposal_id'] = $proposal_id;
		$_SESSION['proposal_qty'] = $proposal_qty;
		$_SESSION['proposal_price'] = $amount;
		$_SESSION['proposal_delivery'] = $_SESSION['c_proposal_delivery'];
		$_SESSION['proposal_revisions'] = $_SESSION['c_proposal_revisions'];
		if(isset($_SESSION['c_proposal_extras'])){
			$_SESSION['proposal_extras'] = $_SESSION['c_proposal_extras'];
		}
		if(isset($_SESSION['c_proposal_minutes'])){
			$_SESSION['proposal_minutes'] = $_SESSION['c_proposal_minutes'];
		}
		$_SESSION['method'] = "shopping_balance";
		echo "<script>window.open('order','_self');</script>";
	}
}


if(isset($_POST['cart_submit_order'])){
	$select_cart =  $db->select("cart",array("seller_id" => $login_seller_id));
	$count_cart = $select_cart->rowCount();
	
   $reference_no = mt_rand();
   $sub_total = 0;
   $select_cart =  $db->select("cart",array("seller_id" => $login_seller_id));
   $count_cart = $select_cart->rowCount();
   while($row_cart = $select_cart->fetch()){
      $proposal_id = $row_cart->proposal_id;
      $proposal_price = $row_cart->proposal_price;
      $proposal_qty = $row_cart->proposal_qty;
      $delivery_id = $row_cart->delivery_id;
      $revisions = $row_cart->revisions;
      $video = $row_cart->video;

      $get_extras = $db->select("cart_extras",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));
      while($row_extra = $get_extras->fetch()){
         $price = $row_extra->price;
         $proposal_price += $price;
      }

      $cart_total = $proposal_price*$proposal_qty;
      $sub_total += $cart_total;

      $o_data = [
         "reference_no"=>$reference_no,
         "buyer_id"=>$login_seller_id,
         "content_id"=>$proposal_id,
         "price"=>$proposal_price,
         "qty"=>$proposal_qty,
         "delivery_id"=>$delivery_id,
         "revisions"=>$revisions,
         "total"=>$cart_total,
         "type"=>'cart_item',
      ];

      if($video == 1){
         $o_data['video'] = 1;
      }

      $db->insert("temp_orders",$o_data);
      $insert_id = $db->lastInsertId();

      $get_extras = $db->select("cart_extras",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));
      while($row_extra = $get_extras->fetch()){
         $name = $row_extra->name;
         $price = $row_extra->price;
         $insert_extra = $db->insert("temp_extras",array("reference_no"=>$reference_no,"buyer_id"=>$login_seller_id,"item_id"=>$insert_id,"proposal_id"=>$proposal_id,"name"=>$name,"price"=>$price));
      }
      
   }

	$amount = $sub_total;
	$update_balance = $db->query("update seller_accounts set used_purchases=used_purchases+:plus,current_balance=current_balance-:minus where seller_id='$login_seller_id'",array("plus"=>$amount,"minus"=>$amount));
	if($update_balance){
		$_SESSION['cart_seller_id'] = $login_seller_id;
		$_SESSION['reference_no'] = $reference_no;
		$_SESSION['method'] = "shopping_balance";
		echo "<script>window.open('order','_self');</script>";
	}
}

if(isset($_POST['pay_featured_proposal_listing'])){
	$proposal_id = $_SESSION['f_proposal_id'];
	$get_payment_settings = $db->select("payment_settings");
	$row_payment_settings = $get_payment_settings->fetch();
	$amount = $row_payment_settings->featured_fee;
	$update_balance = $db->query("update seller_accounts set used_purchases=used_purchases+:plus,current_balance=current_balance-:minus where seller_id='$login_seller_id'",array("plus"=>$amount,"minus"=>$amount));
	if($update_balance){
		$_SESSION['proposal_id'] = $proposal_id;
		$_SESSION['method'] = "shopping_balance";
		echo "<script>window.open('$site_url/proposals/featured_proposal','_self')</script>";
	}
}

if(isset($_POST['view_offers_submit_order'])){
	$offer_id = $_SESSION['c_offer_id'];
	$select_offers = $db->select("send_offers",array("offer_id"=>$offer_id));
	$row_offers = $select_offers->fetch();
	$amount = $row_offers->amount;
	$update_balance = $db->query("update seller_accounts set used_purchases=used_purchases+:plus,current_balance=current_balance-:minus where seller_id='$login_seller_id'",array("plus"=>$amount,"minus"=>$amount));
	if($update_balance){
		$_SESSION['offer_id'] = $offer_id;
		$_SESSION['offer_buyer_id'] = $login_seller_id;
		$_SESSION['method'] = "shopping_balance";
		echo "<script>window.open('order','_self');</script>";
	}
}

if(isset($_POST['message_offer_submit_order'])){
	$offer_id = $_SESSION['c_message_offer_id'];
	$select_offers = $db->select("messages_offers",array("offer_id"=>$offer_id));
	$row_offers = $select_offers->fetch();
	$amount = $row_offers->amount;
	$update_balance = $db->query("update seller_accounts set used_purchases=used_purchases+:plus,current_balance=current_balance-:minus where seller_id='$login_seller_id'",array("plus"=>$amount,"minus"=>$amount));
	if($update_balance){
		$_SESSION['message_offer_id'] = $offer_id;
		$_SESSION['message_offer_buyer_id'] = $login_seller_id;
		$_SESSION['method'] = "shopping_balance";
		echo "<script>window.open('order','_self');</script>";
	}
}

?>