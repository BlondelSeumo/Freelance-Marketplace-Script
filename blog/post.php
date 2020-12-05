<?php
  session_start();
  require_once("../includes/db.php");
  require_once("../social-config.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
  <title> <?= $site_name; ?> - <?= $lang['titles']['blog']; ?> </title>
  <base href="<?= $site_url; ?>/blog/"/>
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
  
  <script src="//platform-api.sharethis.com/js/sharethis.js#property=5c812224d11c6a0011c485fd&product=inline-share-buttons"></script>

</head>
<body class="is-responsive blog">
  
  <?php require_once("../includes/header.php"); ?>

  <header id="how_to"><!--- how_to Starts --->
   <div class="cell">
      <h2 class="text-center text-white"><?= $lang['blog']['title']; ?></h2>
      <h3 class="text-center mb-0"><?= $lang['blog']['desc']; ?></h3>
    </div>
  </header><!--- how_to Ends --->

  <br><br>
  <div class="container mb-5"><!--- container Starts --->
    <div class="row"><!--- row Starts --->
      
      <div class="col-md-8 mb-4 <?=($lang_dir == "right" ? 'order-2 order-sm-1':'')?>"><!--- col-md-8 Starts --->
        <?php include("includes/single.php"); ?>
      </div><!--- col-md-8 Ends --->

      <div class="col-md-4 <?=($lang_dir == "right" ? 'order-1 order-sm-2':'')?>"><!--- col-md-4 Starts --->
        <?php include("includes/sidebar.php"); ?>
      </div><!--- col-md-4 Ends --->

    </div><!--- row Ends --->
  </div><!--- container Ends --->
  <?php require_once("../includes/footer.php"); ?>
</body>
</html>