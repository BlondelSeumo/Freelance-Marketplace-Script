<?php

session_start();

require_once("includes/db.php");

?>

<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>

<title> <?= $site_name; ?> - Search Articles </title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="<?= $site_desc; ?>">
<meta name="keywords" content="<?= $site_keywords; ?>">
<meta name="author" content="<?= $site_author; ?>">


<link href="styles/bootstrap.css" rel="stylesheet">

<link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->

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
<script type="text/javascript" src="js/sweat_alert.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>


<style>

.form-control{

width: 200px;
}


</style>

</head>

<body class="is-responsive">

<div class="header">

<div class="container">

<a class="navbar-brand logo text-success " href="<?= $site_url; ?>">
       
       <?php if($site_logo_type == "image"){ ?>

            <img src="<?= $site_url; ?>/images/<?= $site_logo_image; ?>" width="150" style="margin-top:8%;">

            <?php }else{ ?>

            <?= $site_logo_text; ?>

            <?php } ?>
       
   </a>


<div class="text-center">

<h2 class="text-white mt-5">KNOWLEDGE BANK FOR <?= strtoupper($site_name);?></h2>

<h4 class="text-white">Everything you need to know</h4>

</div>

<div class="text-center reduceForm">

<form action="" method="post">

<div class="input-group space50">

<input type="text" name="search_query" required class="form-control" value="<?= $input->get('search'); ?>"  placeholder="Search Questions">

<div class="input-group-append move-icon-up" style="cursor:pointer;">

<button name="search_article" type="submit" class="search_button">

<img src="images/srch2.png" class="srch2">

</button>

</div>

</div>

</form>

<?php

if(isset($_POST['search_article'])){

$search_query = $input->post('search');

echo "<script>window.open('$site_url/search_articles.php?search=$search_query','_self')</script>";

}

?>

</div>

</div>

</div>

<div class="container mt-5">

<div class="row">

<div class="col-md-6">

<h3 class="make-black pb-1"><i class="fa fa-bars"></i> Search Results For <?= $input->get('search'); ?> </h3> <!-- Category -->

<?php 

$search = $input->get('search');

$get_articles = $db->query("select * from knowledge_bank where article_heading like :search AND language_id='$siteLanguage' order by 1 DESC",array("search"=>"%$search%"));

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

<i class="fa fa-book"></i> <?= $article_heading; ?></a></h6>

<?php } ?>


<br><br>

</div>

</div>

</div>

<?php include "includes/footer.php"; ?>

</body>

</html>