<?php
  session_start();
  require_once("includes/db.php");
  require_once("social-config.php");
  ?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
  <head>
    <title><?= $site_name; ?> - Terms and Conditions.</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/categories_nav_styles.css" rel="stylesheet">
    <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="styles/owl.carousel.css" rel="stylesheet">
    <link href="styles/owl.theme.default.css" rel="stylesheet">
    <?php if(!empty($site_favicon)){ ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
    <link href="styles/sweat_alert.css" rel="stylesheet">
    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="js/ie.js"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
  </head>
  <body class="is-responsive">
    <?php require_once("includes/header.php"); ?>
    <div class="container-fluid mt-5 mb-5">
      <div class="row mb-4">
        <div class="col-md-12 text-center">
          <h1>Our Policies</h1>
          <p class="lead pb-4"> Terms & Conditions, Refund Policy, Pricing & Promotion Policy. </p>
        </div>
      </div>
      <div class="row terms-page" style="<?=($lang_dir == "right" ? 'direction: rtl;':'')?>">
        <div class="col-md-3 mb-3">
          <div class="card">
            <div class="card-body">
              <ul class="nav nav-pills flex-column mt-2">
                <?php
                  $get_terms = $db->query("select * from terms where language_id='$siteLanguage' LIMIT 0,1");
                  while($row_terms = $get_terms->fetch()){
                      $term_title = $row_terms->term_title;
                      $term_link = $row_terms->term_link;
                  ?>
                <li class="nav-item">
                  <a class="nav-link active" data-toggle="pill" href="#<?= $term_link; ?>">
                  <?= $term_title; ?>
                  </a>
                </li>
                <?php } ?>
                <?php
                  $count_terms = $db->count("terms",array("language_id" => $siteLanguage));
                  $get_terms = $db->query("select * from terms where language_id='$siteLanguage' LIMIT 1,$count_terms");
                  while($row_terms = $get_terms->fetch()){
                      $term_title = $row_terms->term_title;
                      $term_link = $row_terms->term_link;
                  ?>
                <li class="nav-item">
                  <a class="nav-link" data-toggle="pill" href="#<?= $term_link; ?>">
                  <?= $term_title; ?>
                  </a>
                </li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="card">
            <div class="card-body">
              <div class="tab-content">
                <?php
                  $get_terms = $db->query("select * from terms where language_id='$siteLanguage' LIMIT 0,1");
                  while($row_terms = $get_terms->fetch()){
                      $term_title = $row_terms->term_title;
                      $term_link = $row_terms->term_link;
                      $term_description = $row_terms->term_description;
                  ?>
                <div id="<?= $term_link; ?>" class="tab-pane fade show active">
                  <h2 class="mb-4"><?= $term_title; ?></h2>
                  <p class="text-justify">
                    <?= $term_description; ?>
                  </p>
                </div>
                <?php } ?>
                <?php
                  $get_terms = $db->query("select * from terms where language_id='$siteLanguage' LIMIT 1,$count_terms");
                  while($row_terms = $get_terms->fetch()){
                      $term_title = $row_terms->term_title;
                      $term_link = $row_terms->term_link;
                      $term_description = $row_terms->term_description;
                  ?>
                <div id="<?= $term_link; ?>" class="tab-pane fade ">
                  <h1 class="mb-4"><?= $term_title; ?></h1>
                  <p class="text-justify">
                    <?= $term_description; ?>
                  </p>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php require_once("includes/footer.php"); ?>
  </body>
</html>