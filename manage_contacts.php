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

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>

<title><?= $site_name; ?> - <?= $lang["titles"]["manage_contacts"]; ?>.</title>
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

<div class="row">

<div class="col-md-12 mt-5">

<h1> <?= $lang["titles"]["manage_contacts"]; ?> </h1>

<ul class="nav nav-tabs mt-5 mb-3"><!-- nav nav-tabs mt-5 mb-3 Starts -->

<?php

$count_my_buyers = $db->count("my_buyers",array("seller_id" => $login_seller_id));

?>

<li class="nav-item">

<a href="#my_buyers" data-toggle="tab" class="nav-link make-black 
<?php

if(!isset($_GET['my_buyers']) and !isset($_GET['my_sellers'])){ echo "active"; }

if(isset($_GET['my_buyers'])){ echo "active"; }

?>">

<?= $lang['tabs']['my_buyers']; ?> <span class="badge badge-success"><?= $count_my_buyers; ?></span>

</a>

</li>

<?php

$count_my_sellers = $db->count("my_buyers",array("buyer_id" => $login_seller_id));

?>

<li class="nav-item">

<a href="#my_sellers" data-toggle="tab" class="nav-link make-black

<?php

if(isset($_GET['my_sellers'])){

echo "active";

}

?>

">

<?= $lang['tabs']['my_sellers']; ?> <span class="badge badge-success"><?= $count_my_sellers; ?></span>

</a>

</li>

</ul>

<div class="tab-content mt-2">


<div id="my_buyers" class="tab-pane fade 
<?php

if(!isset($_GET['my_buyers']) and !isset($_GET['my_sellers'])){

echo "show active";

}

if(isset($_GET['my_buyers'])){

echo "show active";

}

?>
">

<div class="table-responsive box-table">

<h4 class="mt-3 mb-3 ml-2"> <?= $lang['manage_contacts']['my_buyers']; ?> </h4>

<table class="table table-bordered"><!-- table table-hover Starts -->

<thead>

<tr>

<th><?= $lang['th']['buyer_name']; ?></th>

<th><?= $lang['th']['completed_orders']; ?></th>

<th><?= $lang['th']['amount_spent']; ?></th>

<th><?= $lang['th']['last_order_date']; ?></th>

<th></th>

</tr>

</thead>

<tbody>

<?php

$sel_my_buyers =  $db->select("my_buyers",array("seller_id" => $login_seller_id));

while($row_my_buyers = $sel_my_buyers->fetch()){

$buyer_id = $row_my_buyers->buyer_id;

$completed_orders = $row_my_buyers->completed_orders;

$amount_spent = $row_my_buyers->amount_spent;

$last_order_date = $row_my_buyers->last_order_date;


$select_buyer = $db->select("sellers",array("seller_id" => $buyer_id));

$row_buyer = $select_buyer->fetch();

$buyer_user_name = $row_buyer->seller_user_name;

$buyer_image = getImageUrl2("sellers","seller_image",$row_buyer->seller_image);


?>

<tr>

<td>

<?php if(!empty($buyer_image)){ ?>

<img src="<?= $buyer_image; ?>" class="rounded-circle contact-image" >

<?php }else{ ?>

<img src="user_images/empty-image.png" class="rounded-circle contact-image" >

<?php } ?>

<div class="contact-title">

<h6> <?= $buyer_user_name; ?> </h6>

<a href="<?= $buyer_user_name; ?>" target="blank" class="text-success" > User Profile </a> | 

<a href="buying_history?buyer_id=<?= $buyer_id; ?>" class="text-success"> History </a>

</div>

</td>

<td><?= $completed_orders; ?></td>

<td><?= showPrice($amount_spent); ?></td>

<td>
<?= $last_order_date; ?>
</td>

<td class="text-center">

<a href="conversations/message?seller_id=<?= $buyer_id; ?>" target="blank" class="btn btn-success">

<i class="fa fa-comment"></i>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php

if($count_my_buyers == 0){

echo "<center><h3 class='pb-4 pt-4'><i class='fa fa-meh-o'></i> {$lang['manage_contacts']['no_buyers']} </h3></center>";
}



?>


</div>

</div>

<div id="my_sellers" class="tab-pane fade
<?php

if(isset($_GET['my_sellers'])){

echo "show active";

}

?>
">


<div class="table-responsive box-table">

<h4 class="mt-3 mb-3 ml-2"> <?= $lang['manage_contacts']['my_sellers']; ?> </h4>

<table class="table table-bordered">

<thead>

<tr>

<th><?= $lang['th']['seller_name']; ?></th>

<th><?= $lang['th']['completed_orders']; ?></th>

<th><?= $lang['th']['amount_spent']; ?></th>

<th><?= $lang['th']['last_order_date']; ?></th>

<th></th>

</tr>

</thead>

<tbody>

<?php

$sel_my_sellers =  $db->select("my_sellers",array("buyer_id" => $login_seller_id));

while($row_my_sellers = $sel_my_sellers->fetch()){

$seller_id = $row_my_sellers->seller_id;

$completed_orders = $row_my_sellers->completed_orders;

$amount_spent = $row_my_sellers->amount_spent;

$last_order_date = $row_my_sellers->last_order_date;


$select_seller = $db->select("sellers",array("seller_id" => $seller_id));

$row_seller = $select_seller->fetch();

$seller_image = getImageUrl2("sellers","seller_image",@$row_seller->seller_image);

$seller_user_name = @$row_seller->seller_user_name;


?>

<tr>

<td>

<?php if(!empty($seller_image)){ ?>

<img src="<?= $seller_image; ?>" class="rounded-circle contact-image" >

<?php }else{ ?>

<img src="user_images/empty-image.png" class="rounded-circle contact-image" >

<?php } ?>

<div class="contact-title">

<h6> <?= $seller_user_name; ?> </h6>

<a href="<?= $seller_user_name; ?>" target="blank" class="text-success" > User Profile </a> | 

<a href="selling_history?seller_id=<?= $seller_id; ?>" target="blank" class="text-success" > History </a>

</div>

</td>

<td><?= $completed_orders; ?></td>

<td><?= showPrice($amount_spent); ?></td>

<td>
<?= $last_order_date; ?>
</td>

<td class="text-center">

<a href="conversations/message?seller_id=<?= $seller_id; ?>" target="blank" class="btn btn-success">

<i class="fa fa-comment"></i>

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

<?php

if($count_my_sellers == 0){

   echo "<center>
   <h3 class='pb-4 pt-4'>
      <i class='fa fa-meh-o'></i> {$lang['manage_contacts']['no_sellers']} 
   </h3>
   </center>";

}

?>

</div>

</div>


</div>

</div>

</div>

</div>


<?php require_once("includes/footer.php"); ?>

</body>

</html>