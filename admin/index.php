<?php

session_start();
include("includes/db.php");
include("../functions/mailer.php");
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}

if((time() - $_SESSION['loggedin_time']) > 9800){
	echo "<script>window.open('logout?session_expired','_self');</script>";
}

if(!$_SESSION['adminLanguage']){
	$_SESSION['adminLanguage'] = 1;
}

$adminLanguage = $_SESSION['adminLanguage'];
$row_language = $db->select("languages",array("id"=>$adminLanguage))->fetch();
$currentLanguage = $row_language->title;
$admin_email = $_SESSION['admin_email'];

$get_admin = $db->query("select * from admins where admin_email=:a_email OR admin_user_name=:a_user_name",array("a_email"=>$admin_email,"a_user_name"=>$admin_email));
$row_admin = $get_admin->fetch();
$admin_id = $row_admin->admin_id;
$login_admin_id = $row_admin->admin_id;
$admin_name = $row_admin->admin_name;
$admin_user_name = $row_admin->admin_user_name;
$admin_image = $row_admin->admin_image;
$admin_country = $row_admin->admin_country;
$admin_job = $row_admin->admin_job;
$admin_contact = $row_admin->admin_contact;
$admin_about = $row_admin->admin_about;

$get_rights = $db->select("admin_rights",array("admin_id" => $admin_id));
$row_rights = $get_rights->fetch();
$a_settings = $row_rights->settings;
$a_plugins = $row_rights->plugins;
$a_pages = $row_rights->pages;
$a_blog = $row_rights->blog;
$a_feedback = $row_rights->feedback;
$a_video_schedules = $row_rights->video_schedules;
$a_proposals = $row_rights->proposals;
$a_accounting = $row_rights->accounting;
$a_payouts = $row_rights->payouts;
$a_reports = $row_rights->reports;
$a_inbox = $row_rights->inbox;
$a_reviews = $row_rights->reviews;
$a_buyer_requests = $row_rights->buyer_requests;
$a_restricted_words = $row_rights->restricted_words;
$a_alerts = $row_rights->notifications;
$a_cats = $row_rights->cats;
$a_delivery_times = $row_rights->delivery_times;
$a_seller_languages = $row_rights->seller_languages;
$a_seller_skills = $row_rights->seller_skills;
$a_seller_levels = $row_rights->seller_levels;
$a_customer_support = $row_rights->customer_support;
$a_coupons = $row_rights->coupons;
$a_slides = $row_rights->slides;
$a_sellers = $row_rights->sellers;
$a_slides = $row_rights->slides;
$a_terms = $row_rights->terms;
$a_orders = $row_rights->orders;
$a_referrals = $row_rights->referrals;
$a_files = $row_rights->files;
$a_knowledge_bank = $row_rights->knowledge_bank;
$a_currencies = $row_rights->currencies;
$a_languages = $row_rights->languages;
$a_admins = $row_rights->admins;

$get_app_license = $db->select("app_license");
$row_app_license = $get_app_license->fetch();
$purchase_code = $row_app_license->purchase_code;
$license_type = $row_app_license->license_type;
$website = $row_app_license->website;

$count_sellers = $db->count("sellers");
$count_notifications = $db->count("admin_notifications",array("status" => "unread"));
$count_orders = $db->count("orders",array("order_active" => "yes"));
$count_proposals = $db->count("proposals",array("proposal_status" => "pending"));
$count_support_tickets = $db->count("support_tickets",array("status" => "open"));
$count_requests = $db->count("buyer_requests",array("request_status" => "pending"));
$count_referrals = $db->count("referrals",array("status" => "pending"));
$count_proposals_referrals = $db->count("proposal_referrals",array("status" => "pending"));

function autoLoader($className){
  require_once("../functions/$className.php");
}
spl_autoload_register('autoLoader');

$core = new Core;
$paymentGateway = $core->checkPlugin("paymentGateway");
$videoPlugin = $core->checkPlugin("videoPlugin");
$notifierPlugin = $core->checkPlugin("notifierPlugin");

if($notifierPlugin == 1){ 
	require_once("$dir/plugins/notifierPlugin/functions.php");
}

?>
<!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Admin Panel - Control Your Entire Site.</title>
	<meta name="description" content="With the GigToDoScript admin panel, controlling your website has never been eassier.">
	<meta name="author" content="GigToDoScript">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" href="apple-icon.png">
	<link rel="stylesheet" href="assets/css/normalize.css">
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link href="../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="assets/css/themify-icons.css">
	<link rel="stylesheet" href="assets/css/flag-icon.min.css">
	<link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
	<link rel="stylesheet" href="assets/scss/style.css">
    <style>
        .d-none-on-backend-precessing{
        	display: table !important;
        }
    </style>
	<?php if(!empty($site_favicon)){ ?>
		<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
	<?php } ?>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800'rel='stylesheet'type='text/css'>
	<link rel="stylesheet" href="assets/css/sweat_alert.css" >
	<script type="text/javascript" src="assets/js/ie.js"></script>
	<script type="text/javascript" src="assets/js/sweat_alert.js"></script>
	<script src="../js/jquery.min.js"></script>
	<script>
	
	function alert_error(text){
		Swal('',text,'error');
	}

	function alert_success(text,url){
	  swal({
	  type: 'success',
	  timer : 3000,
	  text: text,
	  onOpen: function(){
	    swal.showLoading()
	  }
	  }).then(function(){
	  	if(url != ""){
			window.open(url,'_self');
		}
	  });
	}
	
	function alert_error(text,url){
	  swal({
		type: 'error',
		timer: 3000,
		text: text,
		onOpen: function(){
		 swal.showLoading()
		}
	  }).then(function(){
	  	if(url != ""){
			window.open(url,'_self');
		}
	  });
	}
	
	function alert_confirm(text,url){
		swal({
		  text: text,
		  type: 'warning',
		  showCancelButton: true 
		}).then((result) => {
			if(result.value){
			  if(url != ""){ window.open(url,'_self'); }
			}
		});
	}

	</script>
</head>
<body>
  <script src="assets/js/minimal.js"></script>
  <!-- Left Panel -->
  <aside id="left-panel" class="d-none-on-backend-precessing left-panel">
		<nav class="navbar navbar-expand-sm navbar-default">
		  <div class="navbar-header">
		    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu">
		        <i class="fa fa-bars"></i>
		    </button>
		    <a class="navbar-brand" href="index?dashboard">
		    <?= $site_name; ?> <span class="badge badge-success p-2 font-weight-bold">ADMIN</span>
			</a>
		    <a class="navbar-brand hidden" href="./"><span class="badge badge-success pt-2 pb-2">A</span></a>
		  </div>
		  <div id="main-menu" class="main-menu collapse navbar-collapse">
		      <?php include("includes/sidebar.php"); ?>
		  </div>
		</nav>
      <div class="container clearfix">
  </aside>


  <!-- Left Panel -->

  <!-- Right Panel -->
  <div id="right-panel" class="right-panel">

	<!-- Header-->
	<header id="header" class="header">
		<div class="header-menu"><?php include("includes/admin_header.php"); ?></div>
	</header>
  	<!-- Header-->

	<?php

	if(empty($purchase_code) or empty($license_type) or empty($website)){
		include("proceed.php");
	}else{
		include("includes/body.php");
	}

	?>
      <style>
          .d-none-on-backend-precessing{
              display: table-cell !important;
          }
      </style>
<div class="container clearfix">
<div class="row">
<div id="languagePanel" class="bg-light col-md-12 p-2 pb-0 mb-0"><!--- languagePanel Starts --->
	<div class="row">
	<div class="col-md-6"><!--- col-md-6 Starts --->
	<p class="col-form-label font-weight-normal mb-0 pb-0">Current Selected Language: <strong><?= $currentLanguage; ?></strong></p>
	</div><!--- col-md-6 Ends --->
	<div class="col-md-6 float-right"><!--- col-md-6 Starts --->
	<div class="form-group row mb-0 pb-0"><!--- form-group row Starts --->
		<label class="col-md-2"></label>
		<label class="col-md-4 col-form-label"> Change Language: </label>
		<div class="col-md-6">
		<select id="languageSelect" class="form-control">
			<?php
			$get_languages = $db->select("languages");
			while($row_languages = $get_languages->fetch()){
			$id = $row_languages->id;
			$title = $row_languages->title;
			?>
			<option data-url="<?= "$site_url/admin/index?change_language=$id"; ?>" <?php if($id == $_SESSION["adminLanguage"]){ echo "selected"; } ?>>
			<?= $title; ?>
			</option>
	    	<?php } ?>
		</select>
		</div>
	</div><!--- form-group row Ends --->
	</div><!--- col-md-6 Ends -->
	</div>
</div><!--- languagePanel Ends --->
</div>
</div>

<br><br><br>
<script>
$(document).ready(function(){
	$("#languageSelect").change(function(){
		var url = $("#languageSelect option:selected").data("url");
		window.location.href = url;
	});
});
</script>
</div><!-- Right Panel -->
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/plugins.js"></script>
</body>
</html>