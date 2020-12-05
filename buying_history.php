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
$buyer_id = $input->get('buyer_id');

$select_buyer = $db->select("sellers",array("seller_id" => $buyer_id));
$row_buyer = $select_buyer->fetch();
$buyer_user_name = $row_buyer->seller_user_name;
$buyer_image = $row_buyer->seller_image;

?>
<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>

<title><?= $site_name; ?> - Proposals Ordered By Users.</title>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="keywords" content="<?= $site_desc; ?>">
<meta name="author" content="<?= $site_author; ?>">

<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">

<link href="styles/bootstrap.css" rel="stylesheet">
<link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
<link href="styles/styles.css" rel="stylesheet">
<link href="styles/user_nav_styles.css" rel="stylesheet">
<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
<link href="styles/owl.carousel.css" rel="stylesheet">
<link href="styles/owl.theme.default.css" rel="stylesheet">

<?php if(!empty($site_favicon)){ ?>

<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
   
<?php } ?>
	
<script type="text/javascript" src="js/jquery.min.js"></script>

</head>

<body class="is-responsive">

<?php require_once("includes/user_header.php"); ?>

<?php

$sel_orders = $db->select("orders",array("seller_id" => $login_seller_id,"buyer_id" => $buyer_id),"DESC");

$count_orders = $sel_orders->rowCount();

?>

<div class="container-fluid mt-5">

<div class="row">

<div class="col-md-12">

<h2>Sales To <a href="<?= $buyer_user_name; ?>"><?= ucfirst($buyer_user_name); ?></a> </h2>

<h6><?= $count_orders; ?> Results Found</h6>

</div>

</div>

<div class="row">

<div class="col-md-12 mt-1 mb-3">

<div class="table-responsive box-table mt-3">

	<table class="table table-bordered">

		<thead>
			
			<tr>

				<th>ORDER SUMMARY</th>
				<th>ORDER DATE</th>
				<th>DUE ON</th>
				<th>TOTAL</th>
				<th>STATUS</th>

			</tr>

		</thead>

		<tbody>

			<?php

			while($row_orders = $sel_orders->fetch()){

			$order_id = $row_orders->order_id;
			$proposal_id = $row_orders->proposal_id;
			$order_price = $row_orders->order_price;
			$order_status = $row_orders->order_status;
			$order_number = $row_orders->order_number;
			$order_duration = intval($row_orders->order_duration);
			$order_date = $row_orders->order_date;
			$order_due = date("F d, Y", strtotime($order_date . " + $order_duration days"));

			$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
			$row_proposals = $select_proposals->fetch();
			$proposal_title = $row_proposals->proposal_title;
			$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);

			?>

			<tr>

				<td>

					<a href="order_details?order_id=<?= $order_id; ?>" class="make-black">
						<img class="order-proposal-image" src="<?= $proposal_img1; ?>">
						<p class="order-proposal-title"><?= $proposal_title; ?></p>	
					</a>
					
				</td>

				<td><?= $order_date; ?></td>
				<td><?= $order_due; ?></td>
				<td><?= showPrice($order_price); ?></td>
				
				<td><button class="btn btn-success"><?= ucwords($order_status); ?></button></td>

			</tr>
            
         <?php } ?>
			
		</tbody>

	</table>
    
    <?php
            
    if($count_orders == 0){
        
        echo "<center><h3 class='pb-4 pt-4'><i class='fa fa-meh-o'></i> No gigs sold at the momment.</h3></center>";
    
    }
        
	?>

	</div>

	</div>

	</div>

	</div>

	<?php require_once("includes/footer.php"); ?>

	</body>

	</html>