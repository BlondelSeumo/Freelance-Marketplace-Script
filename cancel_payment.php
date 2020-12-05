<?php

session_start();
require_once("includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
   echo "<script> window.open('index','_self'); </script>";
}

if(!isset($_GET['reference_no']) AND !isset($_GET['token']) AND !isset($_GET['id'])){
   echo "<script> window.open('index','_self'); </script>";
}

if(isset($_GET['reference_no'])){
   $reference_no = $input->get('reference_no');
   $get_order = $db->select("temp_orders",['reference_no'=>$reference_no,'buyer_id'=>$login_seller_id]);
}if(isset($_GET['token'])){
   $token = $input->get('token');
   $get_order = $db->select("temp_orders",['reference_no'=>$token,'buyer_id'=>$login_seller_id]);
}elseif(isset($_GET['id'])){
   $id = $input->get('id');
   $get_order = $db->select("temp_orders",['id'=>$id,'buyer_id'=>$login_seller_id]);
}

$row_order = $get_order->fetch();
$count_order = $get_order->rowCount();

if($count_order == 0){
   echo "<script> window.open('index','_self'); </script>";
   exit();
}

$reference_no = $row_order->reference_no;
$buyer_id = $row_order->buyer_id;
$content_id = $row_order->content_id;
$qty = $row_order->qty;
$price = $row_order->price;
$delivery_id = $row_order->delivery_id;
$type = $row_order->type;

if($type == "proposal"){

   $select_proposals = $db->select("proposals",array("proposal_id" => $content_id));
   $row_proposals = $select_proposals->fetch();
   $proposal_url = $row_proposals->proposal_url;
   $proposal_seller_id = $row_proposals->proposal_seller_id;

   $select_proposal_seller = $db->select("sellers",array("seller_id"=>$proposal_seller_id));
   $row_proposal_seller = $select_proposal_seller->fetch();
   $proposal_seller_user_name = $row_proposal_seller->seller_user_name;

   $c_url = "$site_url/proposals/$proposal_seller_user_name/$proposal_url";

}

if($type == "featured_listing"){
   $c_url = "$site_url/proposals/view_proposals";
}

if($type == "cart"){
   $c_url = "$site_url/cart_payment_options";
}

if($type == "message_offer"){
   $c_url = "$site_url/conversations/inbox";
}

if($type == "request_offer"){

   $select_offers = $db->select("send_offers",array("offer_id" => $content_id));
   $row_offers = $select_offers->fetch();
   $request_id = $row_offers->request_id;

   $c_url = "$site_url/requests/view_offers?request_id=$request_id";

}

if($type == "orderTip"){
   $c_url = "$site_url/order_details?order_id=$content_id";;
}

if($type == "cart"){
   $delete_cart = $db->delete("temp_orders",array("reference_no" => $content_id, "type"=>"cart_item"));
   $delete_cart_extras = $db->delete("temp_extras",array("reference_no" => $content_id));
}

$db->delete("temp_orders",array('reference_no'=>$reference_no));

if($type == "orderExtendTime"){
   echo "<script>window.close();</script>";
}else{
   echo "<script>window.open('$c_url','_self');</script>";
}