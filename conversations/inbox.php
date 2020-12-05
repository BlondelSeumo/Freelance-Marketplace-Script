<?php
session_start();
require_once("../includes/db.php");
require_once("../functions/email.php");
if(!isset($_SESSION['seller_user_name'])){
  echo "<script>window.open('../login','_self');</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
require("includes/inboxFunctions.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
  <title><?= $site_name; ?> - <?= $lang["titles"]["inbox"]; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= $site_desc; ?>">
  <meta name="keywords" content="<?= $site_keywords; ?>">
  <meta name="author" content="<?= $site_author; ?>">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
  <link href="../styles/bootstrap.css" rel="stylesheet">
  <link href="../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
  <link href="../styles/inbox-style.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel - -->
  <link href="../styles/styles.css" rel="stylesheet">
  <link href="../styles/user_nav_styles.css" rel="stylesheet">
  <link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="../styles/sweat_alert.css" rel="stylesheet">
  <link href="../styles/emoji.css" rel="stylesheet">
  <script type="text/javascript" src="../js/sweat_alert.js"></script>
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script src="https://checkout.stripe.com/checkout.js"></script>
  <script src="../js/emoji.js<?= '?v='.mt_rand(); ?>"></script>
  <?php if(!empty($site_favicon)){ ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>

  <!-- Include the PayPal JavaScript SDK -->
  <script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&disable-funding=credit,card&currency=<?= $paypal_currency_code; ?>"></script>

</head>
<body class="is-responsive">
<?php require_once("../includes/user_header-inbox.php"); ?>
<div class="container-fluid pl-md-5 pr-md-5 p-0">
  <div class="row mr-0 ml-0 mt-sm-0 mt-md-4 mb-md-4 box-inbox">
    <?php require_once("includes/sidebar.php"); ?>
    <?php require_once("includes/body.php"); ?>
  </div>
</div>
<div id="wait"></div>
<div id="upload_file_div"></div>
<div id="accept-offer-div"></div>
<div id="send-offer-div"></div>
<?php require_once("includes/javascript.php"); ?>
<?php require_once("../includes/footerInbox.php"); ?>
</body>
</html>