<?php

require_once("includes/db.php");

require_once('vendor/autoload.php');

$get_payment_settings = $db->select("payment_settings");

$row_payment_settings = $get_payment_settings->fetch();

$stripe_secret_key = $row_payment_settings->stripe_secret_key;

$stripe_publishable_key = $row_payment_settings->stripe_publishable_key;

$stripe_currency_code = $row_payment_settings->stripe_currency_code;

$stripe = array(
  "secret_key"      => "$stripe_secret_key",
  "publishable_key" => "$stripe_publishable_key",
  "currency_code"   => "$stripe_currency_code"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);


?>