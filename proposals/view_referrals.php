<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){

echo "<script>window.open('login','_self')</script>";

}

$login_seller_user_name = $_SESSION['seller_user_name'];

$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));

$row_login_seller = $select_login_seller->fetch();

$login_seller_id = $row_login_seller->seller_id;

$login_seller_referral = $row_login_seller->seller_referral;


$referral_money = $row_general_settings->referral_money;

$proposal_id = $input->get('proposal_id');

$get_proposal = $db->select("proposals",array("proposal_id"=>$proposal_id,"proposal_seller_id"=>$login_seller_id));

if($get_proposal->rowCount() == 0){

echo "<script>window.open('view_proposals','_self');</script>";

}

?><!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>

<title><?= $site_name; ?> - My Referrals</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="GigToDo is a fast growing freelance marketplace, where sellers provide their services at extremely affordable prices">
<meta name="keywords" content="freelance, freelancer, gigs, onlinejobs, proposals, sellers, buyers">
<meta name="author" content="<?= $site_author; ?>">

<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
<link href="../styles/bootstrap.css" rel="stylesheet">
<link href="../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
<link href="../styles/styles.css" rel="stylesheet">
<link href="../styles/user_nav_styles.css" rel="stylesheet">
<link href="../font_awesome/css/font-awesome.css" rel="stylesheet">

<script type="text/javascript" src="../js/jquery.min.js"></script>

</head>

<body class="is-responsive">

<?php require_once("../includes/user_header.php"); ?>

<div class="container-fluid">

<div class="row">

<div class="col-lg-10 col-md-10 mt-5 mb-5">

<div class="card rounded-0">

<div class="card-body">

<h1> View Proposals Referrals </h1>

<div class="row">

<div class="col-md-4 mb-3">

<div class="card text-white border-success">

<div class="card-header text-center bg-success">

<div class="display-4"> <?= $s_currency; ?>

<?php

$select = $db->query("SELECT SUM(comission) AS total FROM proposal_referrals where proposal_id=:p_id AND status='approved'",array("p_id"=>$proposal_id));

$total = $select->fetch()->total;

echo $total > 0 || $total!==null ? $total : "0";

?>

</div>

<div class="font-weight-bold">Approved <small>Earnings</small></div>


</div>

</div>

</div>

<div class="col-md-4 mb-3">

<div class="card text-white border-secondary">

<div class="card-header text-center bg-secondary">

<div class="display-4"> <?= $s_currency; ?>

<?php

$select = $db->query("SELECT SUM(comission) AS total FROM proposal_referrals where proposal_id=:p_id AND status='pending'",array("p_id"=>$proposal_id));

$total = $select->fetch()->total;

echo $total > 0 || $total!==null ? $total : "0";

?>

</div>

<div class="font-weight-bold">Pending</div>


</div>

</div>

</div>

<div class="col-md-4 mb-3">

<div class="card text-white border-danger">

<div class="card-header text-center bg-danger">

<div class="display-4"> <?= $s_currency; ?>

<?php

$select = $db->query("SELECT SUM(comission) AS total FROM proposal_referrals where proposal_id=:p_id AND status='declined'",array("p_id"=>$proposal_id));

$total = $select->fetch()->total;

echo $total > 0 || $total!==null ? $total : "0";

?>

</div>

<div class="font-weight-bold">Declined</div>


</div>

</div>

</div>

</div>

<div class="table-responsive border border-secondary rounded" style="overflow-x:hidden; overflow-y:hidden;">

<table class="table table-bordered">

<thead>

<tr class="card-header">

<th>Referrer Username</th>

<th>Buyer Username</th>

<th>Order No</th>

<th>Purcahse Date</th>

<th>Referrer Commision</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

$sel_referrals =  $db->select("proposal_referrals",array("proposal_id" => $proposal_id),"DESC");

$count_referrals = $sel_referrals->rowCount();

if($count_referrals == 0){

echo "

<tr>

<td class='text-center' colspan='6'>

<h3 class='pb-2 pt-2'><i class='fa fa-meh-o'></i> No One Have Not Refered Anyone Yet.</h3>

</td>

</tr>";	

}else{

while($row_referrals = $sel_referrals->fetch()){

$order_id = $row_referrals->order_id;

$referrer_id = $row_referrals->referrer_id;

$buyer_id = $row_referrals->buyer_id;

$comission = $row_referrals->comission;

$date = $row_referrals->date;

$status = $row_referrals->status;


$select_referred = $db->select("sellers",array("seller_id" => $referrer_id));

$referred_user_name = $select_referred->fetch()->seller_user_name;


$sel_buyer = $db->select("sellers",array("seller_id" => $buyer_id));

$buyer_user_name = $sel_buyer->fetch()->seller_user_name;


$get_order = $db->select("orders",array("order_id" => $order_id));

$row_order = $get_order->fetch();
    
$order_number = @$row_order->order_number;

?>

<tr>

<td><?= $referred_user_name; ?></td>

<td><?= $buyer_user_name; ?></td>

<td>
<a class="btn-link" href="../order_details.php?order_id=<?= $order_id; ?>"># <?= $order_number; ?></a>
</td>

<td><?= $date; ?></td>

<td><?= showPrice($comission); ?></td>

<td class="font-weight-bold<?php

if($status == "approved"){

echo "text-success";

}elseif($status == "pending"){

echo "text-secondary";

}elseif($status == "declined"){

echo "text-danger";

}

?>"> <?= $status; ?> 

</td>

</tr>

<?php } } ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

</div>



</div>

<?php require_once("../includes/footer.php"); ?>

</body>

</html>