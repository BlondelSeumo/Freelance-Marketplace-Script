<?php
  session_start();
  require_once("../includes/db.php");
  require_once("../social-config.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
<title> <?= $site_name; ?> - <?= $lang['titles']['feedback']; ?> </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="<?= $site_desc; ?>">
<meta name="keywords" content="<?= $site_keywords; ?>">
<meta name="author" content="<?= $site_author; ?>">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
<link href="../styles/bootstrap.css" rel="stylesheet">
<link href="../styles/custom.css" rel="stylesheet">
<!-- Custom css code from modified in admin panel --->
<link href="../styles/styles.css" rel="stylesheet">
<link href="../styles/categories_nav_styles.css" rel="stylesheet">
<link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
<link href="../styles/owl.carousel.css" rel="stylesheet">
<link href="../styles/owl.theme.default.css" rel="stylesheet">
<link href="../styles/sweat_alert.css" rel="stylesheet">
<link href="../styles/animate.css" rel="stylesheet">
<?php if(!empty($site_favicon)){ ?>
	<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
<?php } ?>
<script type="text/javascript" src="../js/ie.js"></script>
<script type="text/javascript" src="../js/sweat_alert.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>
</head>
<body class="is-responsive">
  <?php require_once("../includes/header.php"); ?>
  <div class="container pt-5 pb-5"><!-- Container starts -->

	<h2>I suggest you ...</h2>

	<a href="index"><i class="fa fa-arrow-left"></i> General</a>

    <div class="row mt-2"><!--- row Starts -->

      <div class="col-md-8 mb-4 <?=($lang_dir == "right" ? 'order-2 order-sm-1':'')?>"><!--- col-md-8 Starts --->
         
         <?php include("includes/single.php"); ?>
			
      </div><!--- col-md-8 Ends --->

      <div class="col-md-4 <?=($lang_dir == "right" ? 'order-1 order-sm-2':'')?>"><!--- col-md-4 Starts --->
          
        <?php include("includes/sidebar.php"); ?>

      </div><!--- col-md-4 Ends --->

	</div><!--- row Ends -->
  </div><!-- Container Ends -->
  <?php require_once("../includes/footer.php"); ?>
</body>
</html>