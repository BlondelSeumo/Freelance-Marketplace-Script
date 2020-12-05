<?php

session_start();

require_once("includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
echo "<script>window.open('login','_self')</script>";
    
}


$get_payment_settings = $db->select("payment_settings");

$row_payment_settings = $get_payment_settings->fetch();

$withdrawal_limit = $row_payment_settings->withdrawal_limit;

$paypal_email = $row_payment_settings->paypal_email;

$paypal_currency_code = $row_payment_settings->paypal_currency_code;

$paypal_app_client_id = $row_payment_settings->paypal_app_client_id;

$paypal_app_client_secret = $row_payment_settings->paypal_app_client_secret;

$paypal_sandbox = $row_payment_settings->paypal_sandbox;


if($paypal_sandbox == "on"){
	$mode = "sandbox";
}elseif($paypal_sandbox == "off"){
	$mode = "live";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_paypal_email = $row_login_seller->seller_paypal_email;


$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;

require_once('vendor/autoload.php');

use PayPal\Rest\ApiContext;

use PayPal\Auth\OAuthTokenCredential;

use PayPal\Exception\PayPalConnectionException;

//Api Setup

$api = new ApiContext(

new OAuthTokenCredential(

"$paypal_app_client_id",

"$paypal_app_client_secret"

)

);

$api->setConfig([

"mode" => "$mode"

]);


if(isset($_POST['withdraw'])){

$amount = $input->post('amount');


if($amount > $withdrawal_limit or $amount == $withdrawal_limit){

if($amount < $current_balance or $amount == $current_balance){

	
$payouts = new PayPal\Api\Payout();

$senderBatchHeader = new PayPal\Api\PayoutSenderBatchHeader();


$senderBatchHeader->setSenderBatchId(uniqid())->setEmailSubject("You Have Paypal Payout Payment From $site_name");

$senderItem = new \PayPal\Api\PayoutItem();

$senderItem->setRecipientType("Email")

->setReceiver("$login_seller_paypal_email")

->setAmount(new \PayPal\Api\Currency(
'{
	"value":"' . $amount . '",
	"currency":"' . $paypal_currency_code . '"
}'
));
	
$payouts->setSenderBatchHeader($senderBatchHeader)

->addItem($senderItem);
	
// ### Create Payout

try{
	
//$payouts->create(null, $api);

if($payouts->create(null, $api)){

//// $update_seller_account = $db->update("seller_accounts",array("current_balance"=>"current_balance-$amount","withdrawn" => "withdrawn+$amount"),array("seller_id" => $login_seller_id));

$update_seller_account = $db->query("update seller_accounts set current_balance=current_balance-:minus,withdrawn=withdrawn+:plus where seller_id='$login_seller_id'",array("minus"=>$amount,"plus"=>$amount));

if($update_seller_account){

$update_seller = $db->query("update sellers set seller_payouts=seller_payouts+1 where seller_id='$login_seller_id'");

$date = date("F d, Y");

$range = range('A', 'Z');
$index = array_rand($range);
$index2 = array_rand($range);
$ref = "P-".mt_rand(100000,999999).$range[$index].$range[$index2];

$insert_withdrawal = $db->insert("payouts",array("seller_id"=>$login_seller_id,"ref"=>$ref,"method"=>"paypal","amount"=>$amount,"date"=>$date,"status"=>'completed'));

echo "<script>alert('Your funds ($$amount) has been transferred to your paypal account successfully.');</script>";
	
echo "<script>window.open('$site_url/revenue','_self')</script>";

}


}
	
}catch(Exception $ex){

// echo "<pre>";
// 	print_r($ex);
// echo "</pre>";

echo "<script>
	alert('Sorry An error occurred During Sending Your Money To Your Paypal Account.');
	window.open('revenue','_self')
</script>";
	
}


}else{
	
echo "<script>alert('Opps! the amount you entered is higher than your current balance.');</script>";
	
echo "<script>window.open('revenue','_self')</script>";
	
}

	
}else{
	
echo "<script>alert('Minimum withdrawal amount is $$withdrawal_limit Dollars.');</script>";
	
echo "<script>window.open('revenue','_self')</script>";
	
}

	
}

?>