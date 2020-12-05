<?php
  session_start();
  require_once("includes/db.php");
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
  <head>
    <title> <?= $site_name; ?> - <?= $lang["titles"]["knowledge_bank"]; ?> </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/custom.css" rel="stylesheet">
    <!-- Custom css code from modified in admin panel --->
    <link href="styles/styles.css" rel="stylesheet">
    <link href="styles/knowledge_base.css" rel="stylesheet">
    <link href="styles/categories_nav_styles.css" rel="stylesheet">
    <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="styles/owl.carousel.css" rel="stylesheet">
    <link href="styles/owl.theme.default.css" rel="stylesheet">
    <link href="styles/sweat_alert.css" rel="stylesheet">
    <link href="styles/animate.css" rel="stylesheet">
    <?php if(!empty($site_favicon)){ ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
    <script type="text/javascript" src="js/ie.js"></script>
    <script type="text/javascript" src="js/sweat_alert.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <style>
      /*.form-control{
        width: 200px;
      }*/
    </style>
    <?php require_once("includes/external_stylesheet.php"); ?>
  </head>
  <body class="is-responsive">
    <div class="header" style="<?=($lang_dir == "right" ? 'direction: rtl;':'')?>">
      <div class="container">
        <a class="navbar-brand logo text-success" href="<?= $site_url; ?>/index">
        <?php if($site_logo_type == "image"){ ?>
        <img src="<?= $site_logo_image; ?>" width="150" style="margin-top:8%;">
        <?php }else{ ?>
        <?= $site_logo_text; ?>
        <?php } ?>
        </a>
        <div class="text-center">
          <h2 class="text-white mt-5"><?= $lang['knowledge_bank']['title']; ?></h2>
          <h4 class="text-white"><?= $lang['knowledge_bank']['desc']; ?></h4>
        </div>
        <div class="text-center reduceForm">
          <form action="" method="post">
            <div class="input-group space50">
              <input type="text" name="search_query" class="form-control" value="" required placeholder="<?= $lang['placeholder']['search_questions']; ?>">
              <div class="input-group-append move-icon-up" style="cursor:pointer;">
                <button name="search_article" type="submit" class="search_button">
                <img src="images/srch2.png" class="srch2">
                </button>
              </div>
            </div>
          </form>
          <?php
            if(isset($_POST['search_article'])){
            $search_query = $input->post('search_query');
            echo "<script>window.open('$site_url/search_articles?search=$search_query','_self')</script>";
            }
          ?>
        </div>
      </div>
    </div>
    <div class="container mt-5 mb-5">
      <div class="row" style="<?=($lang_dir == "right" ? 'direction: rtl;':'')?>">
        <?php
          $get_cats = $db->select("article_cat",array("language_id" => $siteLanguage));
          while($row_cats = $get_cats->fetch()){
          $article_cat_id = $row_cats->article_cat_id;
          $article_cat_title = $row_cats->article_cat_title;
        ?>
        <div class="col-md-6">
          <h3 class="make-black pb-1"><i class="fa fa-bars"></i> <?= $article_cat_title; ?> </h3>
          <!-- Category -->
          <?php 
            $get_articles = "select * from knowledge_bank where cat_id='$article_cat_id' AND language_id='$siteLanguage'";
            $get_articles = $db->select("knowledge_bank",array("cat_id" => $article_cat_id,"language_id" => $siteLanguage));
            $count_articles = $get_articles->rowCount();
            if($count_articles == 0){
              echo "No articles to display at the moment.";
            }
            while($row_articles = $get_articles->fetch()){
            $article_id = $row_articles->article_id;
            $article_url = $row_articles->article_url;
            $article_heading = $row_articles->article_heading;
          ?>
            <h6><a href="article/<?= $article_url; ?>" class="text-success">
              <i class="fa fa-book"></i> <?= $article_heading; ?></a>
            </h6>
          <?php } ?>
          <br><br>
        </div>
        <?php } ?>
      </div>
    </div>
    <?php include "includes/footer.php"; ?>
  </body>
</html>