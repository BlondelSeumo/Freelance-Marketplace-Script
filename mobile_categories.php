<?php

session_start();

require_once("includes/db.php");

?>

<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>

	<title><?= $site_name; ?> - Categories</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= $site_desc; ?>">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">

	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">

	<link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="styles/styles.css" rel="stylesheet">
	<link href="styles/categories_nav_styles.css" rel="stylesheet">
	<link href="font_awesome/css/font-awesome.css" rel="stylesheet">

    <link href="styles/sweat_alert.css" rel="stylesheet">

    <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
    <script src="js/ie.js"></script>

    <script type="text/javascript" src="js/sweat_alert.js"></script>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	
	<?php if(!empty($site_favicon)){ ?>
   
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
       
    <?php } ?>

</head>

<body class="is-responsive">

<?php require_once("includes/header.php"); ?>

   <div class="container mt-4 mb-4 pb-4"><!-- container mt-5 Starts -->

     <h2 class="text-center mb-4"> <?= $site_name; ?> Categories </h2>

     <div class="row flex-wrap"><!-- row flex-wrap Starts -->

     <?php

         $get_categories = $db->select("categories",array("cat_featured" => "yes"));
         while($row_categories = $get_categories->fetch()){

         $cat_id = $row_categories->cat_id;
         $cat_image = getImageUrl("categories",$row_categories->cat_image);
         $cat_url = $row_categories->cat_url;

         $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id, "language_id" => $siteLanguage));
         $row_meta = $get_meta->fetch();
         $cat_title = $row_meta->cat_title;
         $cat_desc = substr($row_meta->cat_desc,0,60);

     ?>

     <div class="col-lg-4 col-md-6 col-sm-6">

         <div class="mobile-category">

             <a href="categories/<?= $cat_url; ?>">

                 <div class="ml-2 mt-3 category-picture">

                     <img src="<?= $cat_image; ?>">

                 </div>

                 <div class="category-text">

                     <p class="category-title"> <strong><?= $cat_title; ?></strong> </p>

                     <p class="mb-4 category-desc"><?= $cat_desc; ?></p>

                 </div>

             </a>

         </div>

     </div>

     <?php } ?>

     </div>

</div>

<?php require_once("includes/footer.php"); ?>

</body>

</html>