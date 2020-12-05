<?php
session_start();
require_once("includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_email = $row_login_seller->seller_email;
$login_seller_account_number = $row_login_seller->seller_m_account_number;
$login_seller_account_name = $row_login_seller->seller_m_account_name;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$withdrawal_limit = $row_payment_settings->withdrawal_limit;
$enable_dusupay = $row_payment_settings->enable_dusupay;
$dusupay_api_key = $row_payment_settings->dusupay_api_key;
$dusupay_currency_code = $row_payment_settings->dusupay_currency_code;
$dusupay_secret_key = $row_payment_settings->dusupay_secret_key;
$dusupay_sandbox = $row_payment_settings->dusupay_sandbox;

$dusupay_payout_method = $row_payment_settings->dusupay_payout_method;
$dusupay_payout_provider_id = $row_payment_settings->dusupay_payout_provider_id;

if($dusupay_sandbox == "on"){
	$dusupay_url = "http://sandbox.dusupay.com/merchant-api/payout/v2/mobile/sendFromSubAccount.json";
}elseif($dusupay_sandbox == "off"){
	$dusupay_url = "https://dusupay.com/merchant-api/payout/v2/mobile/sendFromSubAccount.json";
}

$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;

if(isset($_POST['withdraw'])){

	$amount = $input->post('amount');

	if($amount > $withdrawal_limit or $amount == $withdrawal_limit){
	
	if($amount < $current_balance or $amount == $current_balance){

		$test_mode = ($dusupay_sandbox == "on" ? ',"test_webhook_url": "#"' : '');

		$url = ($dusupay_sandbox == "on" ? 'https://dashboard.dusupay.com/api-sandbox/v1/payouts' : 'https://api.dusupay.com/v1/payouts');

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",    
		  CURLOPT_POSTFIELDS => '{
		    "api_key": "'.$dusupay_api_key.'",
		    "currency": "'.$dusupay_currency_code.'",
		    "amount": '.$amount.',
		    "method": "$dusupay_payout_method",
		    "provider_id": "$dusupay_payout_provider_id",
		    "account_number": "'.$login_seller_account_number.'",
		    "account_name": "'.$login_seller_account_name.'",
		    "account_email": "'.$login_seller_email.'",
		    "merchant_reference": "'.mt_rand().'",
		    "narration": "'.$site_name.' Payout"
		    '.$test_mode.'
		   }
		  ',

		  CURLOPT_HTTPHEADER => array(
		    "cache-control: no-cache",
		    "content-type: application/json",
		    "secret-key: $dusupay_secret_key"
		  ),

		));

		$response = curl_exec($curl);

		$err = curl_error($curl);

		curl_close($curl);

		if($err){

			echo "<script>
			alert('Sorry An error occurred During Sending Your Money To Your Mobile Money Account.');
			window.open('revenue','_self')
			</script>";
		
		}else{

			   $data = json_decode($response, TRUE);

			   if($data['code'] == "202" AND $data['status'] == "accepted"){

			   $update_seller_account = $db->query("update seller_accounts set current_balance=current_balance-:minus,withdrawn=withdrawn+:plus where seller_id='$login_seller_id'",array("minus"=>$amount,"plus"=>$amount));
				
				if($update_seller_account){

					$update_seller = $db->query("update sellers set seller_payouts=seller_payouts+1 where seller_id='$login_seller_id'");

					$date = date("F d, Y");

					$range = range('A', 'Z');
					$index = array_rand($range);
					$index2 = array_rand($range);
					$ref = "P-" . mt_rand(100000,999999) . $range[$index] . $range[$index2];

					$insert_withdrawal = $db->insert("payouts",array("seller_id"=>$login_seller_id,"ref"=>$ref,"method"=>"dusupay","amount"=>$amount,"date"=>$date,"status"=>'completed'));

				    echo "<script>alert('Your funds ($$amount) has been transferred to your mobile money account successfully.');</script>";

				    echo "<script>window.open('revenue','_self')</script>";
				
				}


			}

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