<?php
  
  session_start();
  require_once("../includes/db.php");
  
  $article_url = urlencode($input->get('article_url'));
  $get_articles = $db->select("knowledge_bank",array("article_url"=>$article_url));

  $count_article = $get_articles->rowCount();

  // if($count_article == 0){
  //   echo "<script>window.open('../knowledge_bank','_self');</script>";
  //   exit();
  // }

  $row_articles = $get_articles->fetch();
  $article_id = $row_articles->article_id;
  $article_heading = $row_articles->article_heading;
  $article_body = $row_articles->article_body;
  $right_image = $row_articles->right_image;
  $top_image = $row_articles->top_image;
  $bottom_image = $row_articles->bottom_image;
  if($lang_dir == "right"){
    $floatRight = "float-right";
  }else{
    $floatRight = "float-left";
  }

  $show_right_image = getImageUrl2("knowledge_bank","right_image",$right_image);
  $show_top_image = getImageUrl2("knowledge_bank","top_image",$top_image);
  $show_bottom_image = getImageUrl2("knowledge_bank","bottom_image",$bottom_image);

?>
<!DOCTYPE html>
<html>
  <head>
    <title> <?= $site_name; ?> - <?= $article_heading; ?> </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="../styles/bootstrap.css" rel="stylesheet">
    <link href="../styles/custom.css" rel="stylesheet">
    <!-- Custom css code from modified in admin panel --->
    <link href="../styles/styles.css" rel="stylesheet">
    <link href="../styles/knowledge_base.css" rel="stylesheet">
    <link href="../styles/categories_nav_styles.css" rel="stylesheet">
    <link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../styles/owl.carousel.css" rel="stylesheet">
    <link href="../styles/owl.theme.default.css" rel="stylesheet">
    <link href="../styles/sweat_alert.css" rel="stylesheet">
    <link href="../styles/animate.css" rel="stylesheet">
    <?php if(!empty($site_favicon)){ ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
    <script type="text/javascript" src="../js/sweat_alert.js"></script>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <style>
      .form-control{
      width: 200px;
      }
      <?php if ($lang_dir == "right") { ?>
    /*  .rtlClass{
        float: right;
      }
      .rtlClass p,span{
        float: right;
      }*/
      <?php } ?>
    </style>
    <?php require_once("../includes/external_stylesheet.php"); ?>
  </head>
  <body>
    <div class="header" 
      style="<?php if(!empty($top_image)){ ?>background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url(<?= $show_top_image; ?>);<?php } ?>"
      >
      <div class="container">
        <a class="navbar-brand logo text-success " href="<?= $site_url; ?>/index">
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
              <input type="text" name="search_query" class="form-control" value=""  placeholder="<?= $lang['placeholder']['search_questions']; ?>">
              <div class="input-group-append move-icon-up" style="cursor:pointer;">
                <button name="search_article" type="submit" class="search_button">
                <img src="../images/srch2.png" class="srch2">
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php
      if(isset($_POST['search_article'])){
        $search_query = $input->post('search_query');
        echo "<script>window.open('$site_url/search_articles?search=$search_query','_self')</script>";
      }
      ?>
    <div class="container mt-5">
      <div class="row">
        <div <?php if(!empty($right_image)){ ?>class="col-md-9"<?php }else{ ?>class="col-md-12"<?php } ?>>
          <h3 class="make-black pb-1 <?= $floatRight ?>"><i class="text-success fa fa-book"></i> <?= $article_heading; ?> </h3>
          <hr style="clear: both;">
          <p><?= $article_body; ?></p>
          <br><br>
        </div>
        <?php if(!empty($right_image)){ ?>
        <div class="col-md-3">
          <img src="<?= $show_right_image; ?>" class="img-fluid mt-5"> 
        </div>
        <?php } ?>
      </div>
    </div>
    <section class="text-center p-5" 
      <?php if(!empty($bottom_image)){ ?>
      style="background-image:url(<?= $show_bottom_image; ?>); color:white;"
      <?php }else{ ?>
      style="background-color:#F7F7F7; "
      <?php } ?>
      >
      <h1 style="font-family: 'Montserrat-Regular';"><?= $lang['single_artilce']['bottom']['title']; ?></h1>
      <h6 style="font-family:'Montserrat-Light';" class="mt-2"><?= $lang['single_artilce']['bottom']['desc']; ?></h6>
      <?php if(!empty($bottom_image)){ ?>
        <a href="../customer_support" class="mt-2 btn btn-lg btn-outline-secondary">
          <?= $lang['button']['contact_us']; ?>
        </a>
      <?php }else{ ?>
        <a href="../customer_support" class="mt-2 btn btn-lg btn-outline-success">
          <?= $lang['button']['contact_us']; ?>
        </a>
      <?php } ?>
    </section>
    <?php include "../includes/footer.php"; ?>
  </body>
</html>