<?php

session_start();
require_once("../includes/db.php");
require_once("../functions/functions.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('../login','_self')</script>";
}

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$featured_fee = $row_payment_settings->featured_fee;
$featured_duration = $row_payment_settings->featured_duration;

$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$approve_proposals = $row_general_settings->approve_proposals;

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

if(isset($_SESSION['proposal_id'])){
	$payment_method = $_SESSION['method'];
	$proposal_id = $_SESSION['proposal_id'];
	$f_createProposal = $_SESSION['f_createProposal'];
	$update_proposal = $db->update("proposals",array("proposal_featured"=>'yes'),array("proposal_id"=>$proposal_id));
	if($update_proposal){

		$p_date = date("F d, Y");

		unset($_SESSION['f_proposal_id'],$_SESSION['f_createProposal'],$_SESSION['proposal_id'],$_SESSION['featured_listing'],$_SESSION['method']);

		if(($approve_proposals == "no" and $f_createProposal == 1) or $f_createProposal == 0 ){
			$end_date = date("F d, Y h:i:s", strtotime(" + $featured_duration days"));
			$insert_featured = $db->insert("featured_proposals",array("proposal_id"=>$proposal_id,"end_date"=>$end_date));
		}

		if($payment_method == "shopping_balance"){
			$processing_fee = 0;
			$adminProfit = $featured_fee;
		}else{
			$processing_fee = processing_fee($featured_fee);
			$adminProfit = $featured_fee+$processing_fee;
		}

		$insert_purchase = $db->insert("purchases",array("seller_id"=>$login_seller_id,"order_id"=>$proposal_id,"reason"=>"featured_listing","amount"=>$adminProfit,"date"=>$p_date,"method"=>$payment_method));

		// Insert Sale Here
		$sale = array("buyer_id" => $login_seller_id,"work_id" => $proposal_id,"payment_method" => $payment_method,"amount" => $featured_fee,"profit"=> $adminProfit,"processing_fee"=>$processing_fee,"action"=>"featured_fee","date"=>date("F d, Y h:i A"));

		if(insertSale($sale)){
			echo "<script>alert('Congrats, Your Proposal has been feature listed on this website.')</script>";
			if($f_createProposal == 0){
				echo "<script>window.open('view_proposals.php','_self');</script>";
			}elseif($f_createProposal == 1){
				echo "<script>window.open('$site_url/proposals/edit_proposal?proposal_id=$proposal_id&publish=1','_self');</script>";
			}
		}
	}
}