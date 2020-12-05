<?php

session_start();
require_once("includes/db.php");
require_once("social-config.php");

?>
<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>

  <title> <?= $site_name; ?> - <?= $lang["titles"]["start_selling"]; ?> </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= $site_desc; ?>">
  <meta name="keywords" content="<?= $site_keywords; ?>">
  <meta name="author" content="<?= $site_author; ?>">

  <link href="styles/bootstrap.css" rel="stylesheet">
  
  <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
    
  <link href="styles/styles.css" rel="stylesheet">
  
  <link href="styles/categories_nav_styles.css" rel="stylesheet">
  
  <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
  
  <link href="styles/owl.carousel.css" rel="stylesheet">
  
  <link href="styles/owl.theme.default.css" rel="stylesheet">
    
  <link href="styles/sweat_alert.css" rel="stylesheet">
    
  <link href="styles/animate.css" rel="stylesheet">
          
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>

  <?php if(!empty($site_favicon)){ ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>

  <style>
    .swal2-popup .swal2-styled.swal2-confirm {
      background-color: #28a745;
    }
  </style>

</head>

<body class="is-responsive">

<?php require_once("includes/header.php"); ?>

<header id="start_selling">
  
   <h2 class="text-center text-white"><?= $lang['start_selling']['title']; ?></h2>
   <h3 class="text-center text-white"><?= $lang['start_selling']['desc']; ?></h3>
     
   <?php if(isset($_SESSION['seller_user_name'])){ ?>

      <div class="text-center btn_start_selling">
         <a href="proposals/create_proposal" class="btn btn-success btn-lg btn_start_selling">
            <i class="fa fa-pencil-square-o"></i> <?= $lang["start_selling"]['create_proposal']; ?>
         </a>
      </div>

   <?php } ?>
 
   <?php if(!isset($_SESSION['seller_user_name'])){ ?>
    
   <div class="text-center btn_start_selling">
      <button data-toggle="modal" data-target="#register-modal" class="btn btn-success btn-lg btn_start_selling">
         <i class="fa fa-user-plus"></i> <?= $lang["start_selling"]['create_account']; ?>
      </button>
   </div>
     
   <?php } ?>  
  
</header>
<br><br>

<section id="start_selling_body">
   
   <div class="container">
    
      <h2 class="text-center pb-5 pt-5"><?= $lang["start_selling"]['title2']; ?></h2>
    
      <div class="row row-1">
         
         <div class="col-md-4">
           
            <img src="images/comp/create-icon.png">
            <h3 class="pb-4"><?= $lang['start_selling']['column_1']['title']; ?></h3>
            <p><?= $lang['start_selling']['column_1']['desc']; ?></p>
            
         </div>
                
         <div class="col-md-4">

            <img src="images/comp/approve-icon.png">
            <h3 class="pb-4"><?= $lang['start_selling']['column_2']['title']; ?></h3>
            <p><?= $lang['start_selling']['column_2']['desc']; ?></p>

         </div>
        
         <div class="col-md-4">

            <img src="images/comp/receive-icon.png">
            <h3 class="pb-4"><?= $lang['start_selling']['column_3']['title']; ?></h3>
            <p><?= $lang['start_selling']['column_3']['desc']; ?></p>
            
         </div>
        
    </div>

    <br/><br/><hr><br/><br/>
    
    <span style="padding: 200px; margin:200px;"></span>
    
    <div class="row row-2">
          
         <div class="col-md-4">
           
            <img src="images/comp/delivered-icon.png">
            <h3 class="pb-4"><?= $lang['start_selling']['column_4']['title']; ?></h3>
            <p><?= $lang['start_selling']['column_4']['desc']; ?></p>

         </div>
                
         <div class="col-md-4">

            <img src="images/comp/rate-icon.png">
            <h3 class="pb-4"><?= $lang['start_selling']['column_5']['title']; ?></h3>
            <p><?= $lang['start_selling']['column_5']['desc']; ?></p>
            
         </div>
        
         <div class="col-md-4">

            <img src="images/comp/earn-icon.png">

            <h3 class="pb-4"><?= $lang['start_selling']['column_6']['title']; ?></h3>
            <p><?= $lang['start_selling']['column_6']['desc']; ?></p>

         </div>
        
      </div>
    
    </div>

    <br/>
    <br/>
    <br/>
    
</section>

<?php if(isset($_SESSION['seller_user_name'])){ ?>

<div class="text-center btn_start_selling">
   <a href="proposals/create_proposal" class="btn btn-success btn-lg btn_start_selling">
      <i class="fa fa-pencil-square-o"></i> Create A Proposal
   </a>
</div>
  
<?php } ?>
 
<?php if(!isset($_SESSION['seller_user_name'])){ ?>
 
<div class="text-center btn_start_selling">
   <button data-toggle="modal" data-target="#register-modal" class="btn btn-success btn-lg btn_start_selling">
      <i class="fa fa-user-plus"></i> Create An Account
   </button>
</div> 
    
<?php } ?>  
   
<div class="pb-5"></div><br><br>

<?php require_once("includes/footer.php"); ?>

</body>

</html>