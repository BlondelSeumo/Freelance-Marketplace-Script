<?php
session_start();
require_once("includes/db.php");
require_once("functions/email.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('login','_self')</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_name = $row_login_seller->seller_name;
$login_seller_email = $row_login_seller->seller_email;
$login_seller_phone = $row_login_seller->seller_phone;
$login_seller_paypal_email = $row_login_seller->seller_paypal_email;
$login_seller_payoneer_email = $row_login_seller->seller_payoneer_email;
$login_seller_image = $row_login_seller->seller_image;
$login_seller_cover_image = $row_login_seller->seller_cover_image;

$login_seller_image_s3 = $row_login_seller->seller_image_s3;
$login_seller_cover_image_s3 = $row_login_seller->seller_cover_image_s3;

$login_seller_headline = $row_login_seller->seller_headline;
$login_seller_country = $row_login_seller->seller_country;
$login_seller_city = $row_login_seller->seller_city;
$login_seller_timzeone = $row_login_seller->seller_timezone;
$login_seller_language = $row_login_seller->seller_language;
$login_seller_about = $row_login_seller->seller_about;
$login_seller_account_number = $row_login_seller->seller_m_account_number;
$login_seller_account_name = $row_login_seller->seller_m_account_name;
$login_seller_wallet = $row_login_seller->seller_wallet;
$login_seller_enable_sound = $row_login_seller->enable_sound;
$login_seller_enable_notifications = $row_login_seller->enable_notifications;
$login_seller_verification = $row_login_seller->seller_verification;

$select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $login_seller_id));
$row_seller_accounts = $select_seller_accounts->fetch();
$current_balance = $row_seller_accounts->current_balance;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$enable_payoneer = $row_payment_settings->enable_payoneer;
$enable_paypal = $row_payment_settings->enable_paypal;
$enable_dusupay = $row_payment_settings->enable_dusupay;

if($lang_dir == "right"){
	$floatRight = "";
}else{
	$floatRight = "float-right";
}

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
	<title><?= $site_name; ?> - <?= $lang["menu"]["settings"]; ?> </title>
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
	<link href="styles/owl.carousel.css" rel="stylesheet">
	<link href="styles/owl.theme.default.css" rel="stylesheet">
   	<link href="styles/sweat_alert.css" rel="stylesheet">
   	<link href="styles/animate.css" rel="stylesheet">
	<link href="styles/croppie.css" rel="stylesheet">
	<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
   	<script src="js/ie.js"></script>
   	<script type="text/javascript" src="js/sweat_alert.js"></script>
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/croppie.js"></script>
	<?php if($paymentGateway == 1){ ?>
      <script src="plugins/paymentGateway/javascript/script.js"></script>
	<?php } ?>
	<?php if(!empty($site_favicon)){ ?>
      <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
   <?php } ?>
	<script src="<?= $site_url; ?>/js/jquery.easy-autocomplete.min.js"></script>
	<link href="<?= $site_url; ?>/styles/easy-autocomplete.min.css" rel="stylesheet">
</head>
<body class="is-responsive">
<?php require_once("includes/user_header.php"); ?>
<div class="container-fluid mt-5 mb-5">
	<div class="row terms-page" style="<?=($lang_dir == "right" ? 'direction: rtl;':'')?>">
		<div class="col-md-3 mb-3">
			<div class="card">
				<div class="card-body">
					<ul class="nav nav-pills flex-column mt-2">
						<li class="nav-item">
						<a data-toggle="pill" href="#profile_settings" class="nav-link
						<?php
						if(!isset($_GET['profile_settings']) and !isset($_GET['account_settings'])){
						echo "active";
						}
						if(isset($_GET['profile_settings'])){ echo "active"; }
						?>">
						<?= $lang["titles"]["settings"]["profile_settings"]; ?>
						</a>
						</li>
						<li class="nav-item">
							<a data-toggle="pill" href="#account_settings" class="nav-link <?php if(isset($_GET['account_settings'])){ echo "active"; } ?> ">
							 <?= $lang["titles"]["settings"]["account_settings"]; ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="card">
				<div class="card-body">
					<div class="tab-content">
					<div id="profile_settings" class="tab-pane fade <?php if(!isset($_GET['profile_settings']) and !isset($_GET['account_settings'])){ echo "show active"; } if(isset($_GET['profile_settings'])){ echo "show active"; } ?>">
						<h2 class="mb-4"><?= $lang["titles"]["settings"]["profile_settings"]; ?></h2>
                  <?php require_once("profile_settings.php") ?>
					</div>
					<div id="account_settings" class="tab-pane fade <?php if(isset($_GET['account_settings'])){ echo "show active"; } ?>">
						<h2 class="mb-4"><?= $lang["titles"]["settings"]["account_settings"]; ?></h2>
						<?php require_once("account_settings.php") ?>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="insertimageModal" class="modal" role="dialog">
  <div class="modal-dialog modal-sm">
  <div class="modal-content">
    <div class="modal-header">
     Crop & Insert Image <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      <div id="image_demo" style="width:100% !important;"></div>
    </div>
    <div class="modal-footer">
      <input type="hidden" name="img_type" value="">
      <button class="btn btn-success crop_image"><?= $lang['button']['crop_image']; ?></button>
      <button type="button" class="btn btn-default" data-dismiss="modal"><?= $lang['button']['close']; ?></button>
    </div>
    </div>
  </div>
</div>
<div id="wait"></div>

<?php require_once("includes/footer.php"); ?>
</body>
</html>