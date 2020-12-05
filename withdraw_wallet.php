<?php
session_start();
include("includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_email = $row_login_seller->seller_email;
$login_seller_wallet = $row_login_seller->seller_wallet;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$enable_coinpayments = $row_payment_settings->enable_coinpayments;
$coinpayments_public_key = $row_payment_settings->coinpayments_public_key;
$coinpayments_private_key = $row_payment_settings->coinpayments_private_key;
$coinpayments_currency_code = $row_payment_settings->coinpayments_currency_code;
$coinpayments_withdrawal_fee = $row_payment_settings->coinpayments_withdrawal_fee;
$withdrawal_limit = $row_payment_settings->withdrawal_limit;
if($coinpayments_withdrawal_fee == "sender"){
$fee = 1;
}else{
$fee = 0;
}

$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;

if(isset($_POST['withdraw'])){

  $amount = $input->post('amount');

  if($amount > $withdrawal_limit or $amount == $withdrawal_limit){
  if($amount < $current_balance or $amount == $current_balance){

  function withdraw($req = array()){
    global $amount;
    global $login_seller_id;
    global $coinpayments_currency_code;
    global $login_seller_wallet;
    global $coinpayments_public_key;
    global $coinpayments_private_key;
    global $fee;

    $req['version'] = 1; 
    $req['cmd'] = "create_withdrawal"; 
    $req['amount'] = $amount; 
    $req['currency'] = "LTCT"; 
    $req['currency2'] = $coinpayments_currency_code; 
    $req['address'] = $login_seller_wallet;
    $req['add_tx_fee'] = $fee;
    $req['auto_confirm'] = 1;
    $req['key'] = $coinpayments_public_key; 
    $req['format'] = 'json'; //supported values are json and xml 
     
    // Generate the query string
    $post_data = http_build_query($req, '', '&'); 
     
    // Calculate the HMAC signature on the POST data 
    $hmac = hash_hmac('sha512', $post_data, $coinpayments_private_key); 
     
    // Create cURL handle and initialize (if needed) 
    static $ch = NULL; 
    if ($ch === NULL) { 
      $ch = curl_init('https://www.coinpayments.net/api.php'); 
      curl_setopt($ch, CURLOPT_FAILONERROR, TRUE); 
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    } 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: '.$hmac)); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
     
    // Execute the call and close cURL handle      
    $data = curl_exec($ch);                 
    // Parse and return data if successful. 
    if ($data !== FALSE) { 
        if (PHP_INT_SIZE < 8 && version_compare(PHP_VERSION, '5.4.0') >= 0) { 
          // We are on 32-bit PHP, so use the bigint as string option. If you are using any API calls with Satoshis it is highly NOT recommended to use 32-bit PHP 
          $dec = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING); 
        } else { 
          $dec = json_decode($data, TRUE); 
        } 
        if ($dec !== NULL && count($dec)) { 
          return $dec; 
        } else { 
          // If you are using PHP 5.5.0 or higher you can use json_last_error_msg() for a better error message 
          return array('error' => 'Unable to parse JSON result ('.json_last_error().')'); 
        } 
    } else { 
      return array('error' => 'cURL error: '.curl_error($ch)); 
    }
  }

  $withdraw = withdraw();

  if($withdraw['error'] == "ok"){

    $update_seller_account = $db->query("update seller_accounts set current_balance=current_balance-:minus,withdrawn=withdrawn+:plus where seller_id='$login_seller_id'",array("minus"=>$amount,"plus"=>$amount));

    if($update_seller_account){

    $update_seller = $db->query("update sellers set seller_payouts=seller_payouts+1 where seller_id='$login_seller_id'");

    $date_time = date("M d, Y H:i:s");
    $range = range('A', 'Z');
    $index = array_rand($range);
    $index2 = array_rand($range);
    $ref = "P-" . mt_rand(100000,999999) . $range[$index] . $range[$index2];

    $insert_withdrawal = $db->insert("payouts",array("seller_id"=>$login_seller_id,"ref"=>$ref,"method"=>"bitcoin wallet","amount"=>$amount,"date"=>$date_time,"status"=>'completed'));

    echo "<script>alert('Your ($$amount) in bitcoin has been transferred to your bitcoin Wallet successfully.');</script>";
        
    echo "<script>window.open('$site_url/revenue','_self')</script>";

    }

  }else{
    echo "<script>alert('Sorry An error occurred During Sending Your Money To Your Bitcoin Wallet.');</script>";
    echo "<script>window.open('$site_url/revenue','_self')</script>";
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

?>