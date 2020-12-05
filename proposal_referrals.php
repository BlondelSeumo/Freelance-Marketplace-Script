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

$login_seller_referral = $row_login_seller->seller_referral;


$referral_money = $row_general_settings->referral_money;

?>
<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>

<title><?= $site_name; ?> - <?= $lang["titles"]["my_proposal_referrals"]; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="<?= $site_desc; ?>">
<meta name="keywords" content="<?= $site_keywords; ?>">
<meta name="author" content="<?= $site_author; ?>">

<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">

<link href="styles/bootstrap.css" rel="stylesheet">
<link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
<link href="styles/styles.css" rel="stylesheet">
<link href="styles/user_nav_styles.css" rel="stylesheet">
<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.min.js"></script>
    
<?php if(!empty($site_favicon)){ ?>
	<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
<?php } ?>

</head>

<body class="is-responsive">

<?php require_once("includes/user_header.php"); ?>

<div class="container-fluid">

<div class="row justify-content-center">

<div class="col-lg-9 col-md-10 mt-5 mb-5">

<div class="card rounded-0">

<div class="card-body">

<h1> <?= $lang["titles"]["my_proposal_referrals"]; ?> </h1>
    
<p><?= $lang['proposal_referrals']['desc']; ?> </p>

<p class="lead text-danger"><?= $lang['proposal_referrals']['note']; ?> </p>

<div class="row">

<div class="col-md-4 mb-3">

<div class="card text-white border-success">

<div class="card-header text-center bg-success">

<div class="display-4"><?php

$select = $db->query("SELECT SUM(comission) AS total FROM proposal_referrals where referrer_id='$login_seller_id' AND status='approved'");

$total = $select->fetch()->total;

$total = $total > 0 || $total!==null ? $total : "0";

echo showPrice($total);

?>
</div>

<div class="font-weight-bold"><?= $lang['referrals']['approved']; ?></div>

</div>

</div>

</div>

<div class="col-md-4 mb-3">

<div class="card text-white border-secondary">

<div class="card-header text-center bg-secondary">

<div class="display-4"> <?php

$select = $db->query("SELECT SUM(comission) AS total FROM proposal_referrals where referrer_id='$login_seller_id' AND status='pending'");

$total = $select->fetch()->total;

$total = $total > 0 || $total!==null ? $total : "0";

echo showPrice($total);

?>

</div>

   <div class="font-weight-bold"><?= $lang['referrals']['pending']; ?></div>

</div>

</div>

</div>

<div class="col-md-4 mb-3">

<div class="card text-white border-danger">

<div class="card-header text-center bg-danger">

<div class="display-4"> <?php

$select = $db->query("SELECT SUM(comission) AS total FROM proposal_referrals where referrer_id='$login_seller_id' AND status='declined'");

$total = $select->fetch()->total;

$total = $total > 0 || $total!==null ? $total : "0";

echo showPrice($total);

?>

</div>

   <div class="font-weight-bold"><?= $lang['referrals']['declined']; ?></div>

</div>

</div>

</div>

</div>


<div class="table-responsive border border-secondary rounded" style="overflow-x:hidden; overflow-y:hidden;">

<table class="table table-bordered">

<thead>

<tr class="card-header">

<th><?= $lang['th']['owner']; ?></th>

<th><?= $lang['th']['buyer']; ?></th>

<th><?= $lang['th']['proposal']; ?></th>

<th><?= $lang['th']['purchase_date']; ?></th>

<th><?= $lang['th']['your_commission']; ?></th>

<th><?= $lang['th']['status']; ?></th>

</tr>

</thead>

<tbody>

<?php

$sel_referrals = $db->select("proposal_referrals",array("referrer_id" => $login_seller_id),"DESC");

$count_referrals = $sel_referrals->rowCount();

if($count_referrals == 0){

echo "

<tr>

<td class='text-center' colspan='6'>

<h3 class='pb-2 pt-2'>
<i class='fa fa-meh-o'></i>  {$lang['proposal_referrals']['no_referrals']}
</h3>

</td>

</tr>

";	

}else{

while($row_referrals = $sel_referrals->fetch()){

$proposal_id = $row_referrals->proposal_id;

$seller_id = $row_referrals->seller_id;

$buyer_id = $row_referrals->buyer_id;

$comission = $row_referrals->comission;

$date = $row_referrals->date;

$status = $row_referrals->status;


$select_seller = $db->select("sellers",array("seller_id" => $seller_id));

$row_seller = $select_seller->fetch();

$seller_user_name = $row_seller->seller_user_name;	



$select_buyer = $db->select("sellers",array("seller_id" => $buyer_id));

$row_buyer = $select_buyer->fetch();

$buyer_user_name = $row_buyer->seller_user_name;



$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));

$row_proposals = $select_proposals->fetch();

$proposal_title = $row_proposals->proposal_title;

?>

<tr>

<td><?= $seller_user_name; ?></td>

<td><?= $buyer_user_name; ?></td>

<td><?= $proposal_title; ?></td>

<td><?= $date; ?></td>

<td><?= $s_currency; ?><?= $comission; ?></td>

<td class="font-weight-bold

<?php

if($status == "approved"){

echo "text-success";

}elseif($status == "pending"){

echo "text-secondary";

}elseif($status == "declined"){

echo "text-danger";

}

?>
"> <?= $status; ?> 

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

<?php require_once("includes/footer.php"); ?>

</body>

</html>