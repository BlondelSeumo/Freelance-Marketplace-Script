<?php

session_start();

require_once("includes/db.php");

$proposal_id = $input->get('proposal_id');

$referral_code = $input->get('referral_code');

$referrer_id = $input->get('referrer_id');


$select_proposal = $db->select("proposals",array("proposal_id" => $proposal_id));

$row_proposal = $select_proposal->fetch();

$proposal_url = $row_proposal->proposal_url;

$proposal_seller_id = $row_proposal->proposal_seller_id;


$select_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));

$row_seller = $select_seller->fetch();

$seller_user_name = $row_seller->seller_user_name;


$_SESSION['r_proposal_id'] = $proposal_id;

$_SESSION['r_referral_code'] = $referral_code;

$_SESSION['r_referrer_id'] = $referrer_id;

echo "<script>window.open('$site_url/proposals/$seller_user_name/$proposal_url','_self')</script>";


?>