<?php

session_start();
require_once("../includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('../login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

$request_id = $input->get('request_id');
$get_requests = $db->select("buyer_requests",array("request_id" => $request_id,"seller_id"=>$login_seller_id,"request_status" => "active"));
if($get_requests->rowCount() == 0){ echo "<script>window.open('manage_requests','_self');</script>"; }
$row_requests = $get_requests->fetch();
$request_id = $row_requests->request_id;
$cat_id = $row_requests->cat_id;
$child_id = $row_requests->child_id;
$request_description = $row_requests->request_description;
$request_date = $row_requests->request_date;
$request_budget = $row_requests->request_budget;
$request_delivery_time = $row_requests->delivery_time;

$get_meta = $db->select("cats_meta",array("cat_id" => $cat_id,"language_id" => $siteLanguage));
$row_meta = $get_meta->fetch();
$request_cat_title = $row_meta->cat_title;
$get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $siteLanguage));
$row_meta = $get_meta->fetch();
$request_child_title = $row_meta->child_title;
$get_offers = $db->select("send_offers",array("request_id" => $request_id, "status" => 'active'));
$count_offers = $get_offers->rowCount();

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
	<title><?= $site_name; ?> - View Offers.</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= $site_desc; ?>">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
	<link href="../styles/bootstrap.css" rel="stylesheet">
	<link href="../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="../styles/styles.css" rel="stylesheet">
	<link href="../styles/user_nav_styles.css" rel="stylesheet">
	<link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script src="https://checkout.stripe.com/checkout.js"></script>
	<?php if(!empty($site_favicon)){ ?>
	<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
	<?php } ?>

	<!-- Include the PayPal JavaScript SDK -->
	<script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&disable-funding=credit,card&currency=<?= $paypal_currency_code; ?>"></script>

</head>
<body class="is-responsive">
<?php require_once("../includes/user_header.php"); ?>
<div class="container mt-4 mb-4">
	<div class="row view-offers">
		<h2 class="mb-3 ml-3"> View Offers (<?= $count_offers; ?>) </h2>
		<div class="col-md-12">
			<div class="card mb-4 rounded-0">
				<div class="card-body">
					<h5 class="font-weight-bold"> Request Description: </h5>
					<p class="offer-p"><?= $request_description; ?></p>
					<p class="offer-p">
					<i class="fa fa-money"></i> <span> Request Budget: </span><span class="text-muted"> <?= showPrice($request_budget); ?> </span><br>
					<i class="fa fa-calendar"></i> <span> Request Date: </span><span class="text-muted"> <?= $request_date; ?></span> <br>
					<i class="fa fa-clock-o"></i> <span> Request Duration: </span><span class="text-muted">  <?= $request_delivery_time; ?> </span>  <br>
					<i class="fa fa-archive"></i> <span> Request Category: </span><span class="text-muted"> <?= $request_cat_title; ?> / <?= $request_child_title; ?> </span>
					</p>
				</div>
			</div>
      		<?php if($count_offers == "0"){ ?>
			<div class="card rounded-0 mb-3">
				<div class="card-body">
					<h3 class="text-center"> <i class="fa fa-frown-o"></i> Unfortunately, no offers yet. Please wait a little longer.</h3>
				</div>
			</div>
			<?php }else{ ?>
			<?php 
			while($row_offers = $get_offers->fetch()){
			$offer_id = $row_offers->offer_id;
			$proposal_id = $row_offers->proposal_id;
			$description = $row_offers->description;
			$delivery_time = $row_offers->delivery_time;
			$amount = $row_offers->amount;
			$sender_id = $row_offers->sender_id;
			$select_sender = $db->select("sellers",array("seller_id" => $sender_id));
			$row_sender = $select_sender->fetch();
			$sender_user_name = $row_sender->seller_user_name;
			$sender_level = $row_sender->seller_level;
			$sender_image = $row_sender->seller_image;
			$sender_status = $row_sender->seller_status;
			
			$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
			$row_proposals = $select_proposals->fetch();
			$proposal_title = $row_proposals->proposal_title;
			$proposal_url = $row_proposals->proposal_url;
			$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
			?>
			<div class="card rounded-0 mb-3">
				<div class="card-body">
					<div class="row">
						<div class="col-md-2">
							<img src="<?= $proposal_img1; ?>" class="img-fluid" >
						</div>
						<div class="col-md-7">
							<h5 class="mt-md-0 mt-2">
								<a href="../proposals/<?= $sender_user_name; ?>/<?= $proposal_url; ?>" class="text-success"> 
									<?= $proposal_title; ?>
								</a>
							</h5>
							<p class="mb-1">
								<?= $description; ?>
							</p>
							<p class="offer-p">
								<i class="fa fa-money"></i> Offer Budget: <span class="font-weight-normal text-muted"> <?= showPrice($amount); ?> </span><br>
								<i class="fa fa-calendar"></i> Offer Duration: <span class="font-weight-normal text-muted"> <?= $delivery_time; ?> </span>
							</p>
						</div>
						<div class="col-md-3 responsive-border pt-md-0 pt-3">
							<div class="offer-seller-picture">
								
								<a href="../<?= $sender_user_name; ?>" target="_blank">
									<?php if(!empty($sender_image)){ ?>
	                  <img src="../user_images/<?= $sender_image; ?>" class="rounded-circle" >
	                <?php }else{ ?>
	                  <img src="../user_images/empty-image.png" class="rounded-circle" >
	                <?php } ?>
                </a>

                <?php if($sender_level == 2){ ?>
                <img src="../images/level_badge_1.png" class="level-badge" >
                <?php }elseif($sender_level == 3){ ?>
                <img src="../images/level_badge_2.png" class="level-badge" >
                <?php }elseif($sender_level == 4){ ?>
                <img src="../images/level_badge_3.png" class="level-badge" >
                <?php } ?>
							</div>
							<div class="offer-seller mb-4">
								<p class="font-weight-bold mb-1">
								<?= $sender_user_name; ?>  <small class="text-success"> <?= $sender_status; ?>  </small>
								</p>
								<p class="user-link">
									<a href="../<?= $sender_user_name; ?>" class="text-success" target="blank"> User Profile </a>
								</p>
							</div>
							<a href="../conversations/message?seller_id=<?= $sender_id; ?>&offer_id=<?= $offer_id; ?>" class="btn btn-sm btn-success rounded-0">
								Contact Now
							</a>
							<button id="order-button-<?= $offer_id; ?>" class="btn btn-sm btn-success rounded-0">
								Order Now
							</button>
						</div>
					</div>
					<script>
	          $("#order-button-<?= $offer_id; ?>").click(function(){
	            request_id = "<?= $request_id; ?>";
	            offer_id = "<?= $offer_id; ?>";
	            $.ajax({
	              method: "POST",
	              url: "offer_submit_order",
	              data: {request_id: request_id, offer_id: offer_id}
	            }).done(function(data){
	               $("#append-modal").html(data);
	            });	
	          });
          </script>
				</div>
			</div>
      <?php } ?>
      <?php } ?>
		</div>
	</div>
</div>

<div id="append-modal"></div>
<?php require_once("../includes/footer.php"); ?>
</body>
</html>