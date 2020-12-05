<?php

session_start();

include("includes/db.php");
require_once("functions/mailer.php");

if(!isset($_SESSION['seller_user_name'])){
	
echo "<script>window.open('login','_self')</script>";
	
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_email = $row_login_seller->seller_email;

$site_email_address = $row_general_settings->site_email_address;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$withdrawal_limit = $row_payment_settings->withdrawal_limit;

$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;

if(isset($_POST['withdraw'])){

	$amount = $input->post('amount');

	if($amount > $withdrawal_limit or $amount == $withdrawal_limit){

		if($amount < $current_balance or $amount == $current_balance){

			$date = date("F d, Y");

			$method = $input->post('method');

			$fee = "0";

			$range = range('A', 'Z');
			$index = array_rand($range);
			$index2 = array_rand($range);
			$ref = "P-" . mt_rand(100000,999999) . $range[$index] . $range[$index2];

			$insert_withdrawal = $db->insert("payouts",array("seller_id"=>$login_seller_id,"ref"=>$ref,"method"=>$method,"amount"=>$amount,"date"=>$date,"status"=>'pending'));

			if($insert_withdrawal){
				
				$insert_id = $db->lastInsertId();

				$update_seller_account = $db->query("update seller_accounts set current_balance=current_balance-:minus,withdrawn=withdrawn+:plus where seller_id='$login_seller_id'",array("minus"=>$amount,"plus"=>$amount));

				$update_seller = $db->query("update sellers set seller_payouts=seller_payouts+1 where seller_id='$login_seller_id'");

				$insert_notification = $db->insert("admin_notifications",array("seller_id" => $login_seller_id,"content_id" => $insert_id,"reason" => "payout_request","date" => $date,"status" => "unread"));

				$get_admins = $db->select("admins");
				while($row_admins = $get_admins->fetch()){
					$admin_id = $row_admins->admin_id;
					$admin_name = $row_admins->admin_name;
					$admin_email = $row_admins->admin_email;

					$data = [];
					$data['template'] = "payout_request";
					$data['to'] = $admin_email;
					$data['subject'] = "$site_name - You just received a new seller payout request.";
					$data['user_name'] = $admin_name;
					$data['seller_user_name'] = $login_seller_user_name;

					send_mail($data);

				}
				
				echo "<script>window.open('withdrawal_requests','_self')</script>";
			
			}

		}else{
			
			echo "<script>alert('Opps! the amount you entered is higher than your current balance.');</script>";
			echo "<script>window.open('revenue','_self')</script>";
				
		}
	
	}else{
	
		echo "<script>alert('Minimum withdrawal amount is $$withdrawal_limit Dollars.');</script>";
		echo "<script>window.open('revenue','_self')</script>";
		
	}

}else{

	echo "<script>window.open('revenue','_self')</script>";

}