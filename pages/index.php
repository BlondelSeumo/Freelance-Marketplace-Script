<?php

  require_once("../includes/db.php");
  require_once("../social-config.php");
  
  $slug = urlencode($input->get('slug'));
  if(isset($_GET['lang'])){
    $language_id = urlencode($input->get('lang'));
  }else{
    $language_id = $_SESSION['siteLanguage'];
  }

  $page = $db->select("pages",array('url'=>$slug));

  $countPage = $page->rowCount();

  if($countPage == 0){
    echo "<script>window.open('../index?not_available','_self');</script>";
    exit();
  }

  $row_page = $page->fetch();
  $page_id = $row_page->id;

  $get_meta = $db->select("pages_meta",array("page_id" => $page_id, "language_id" => $language_id));
  $row_meta = $get_meta->fetch();
  $page_title = $row_meta->title;
  $page_content = $row_meta->content;

  // echo $_GET['power'];

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
  <title><?= $site_name; ?> - <?= $page_title; ?></title>
  <base href="<?= $site_url; ?>/"/>
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
  <?php require_once("../includes/header.php"); ?>
  <div class="container mt-5 mb-5">
    <div class="row mb-4">
      <div class="col-md-12 <?= $textRight; ?>">
        <nav class="nav-breadcrumb <?= $floatRight; ?>" aria-label="breadcrumb">
          <ol class="breadcrumb bg-white pl-0">
            <li class="breadcrumb-item"><a href="<?= $site_url; ?>">Home</a></li>
            <li class="breadcrumb-item active"><?= $page_title; ?></li>
          </ol>
        </nav>
        <div class="clearfix"></div>
        <h1 class="mt-1"><?= $page_title; ?></h1>
        <p class="lead mt-4"><?= $page_content; ?></p>
      </div>
    </div>
  </div>
  <?php require_once("../includes/footer.php"); ?>
</body>
</html>