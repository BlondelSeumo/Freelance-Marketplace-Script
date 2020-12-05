<?php

require_once("includes/db.php");
require_once('vendor/autoload.php');

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$stripe_secret_key = $row_payment_settings->stripe_secret_key;
$stripe_webhook_key = $row_payment_settings->stripe_webhook_key;

// Set your secret key. Remember to switch to your live secret key in production!
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey($stripe_secret_key);

// You can find your endpoint's secret in your webhook settings
$endpoint_secret = $stripe_webhook_key;

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
  $event = \Stripe\Webhook::constructEvent(
    $payload, $sig_header, $endpoint_secret
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  http_response_code(400);
  exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
  // Invalid signature
  http_response_code(400);
  exit();
}

// Handle the checkout.session.completed event
if($event->type == 'checkout.session.completed'){

  $session = $event->data->object;

  // Fulfill the purchase...
  handle_checkout_session($session);

}

http_response_code(200);