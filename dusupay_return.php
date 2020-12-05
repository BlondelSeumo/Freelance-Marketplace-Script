<?php

session_start();
require_once("includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
   echo "<script> window.open('index','_self'); </script>";
}

if(!isset($_GET['status'])){
   echo "<script> window.open('index','_self'); </script>";
}

$status = $input->get('status');
@$reference_no = $input->get('reference');

// $get_order = $db->select("dusupay_orders",['reference_no'=>$reference_no]);
// $row_order = $get_order->fetch();
// $count_order = $get_order->rowCount();

// if($count_order == 0){
//    echo "<script> window.open('index','_self'); </script>";
//    exit();
// }

if($status == "COMPLETED" or $status == "accepted" OR $status == "PENDING"){
   $message = "Your payment will be checked as soon as possible and your shopping will be confirmed. Please follow your email account.";
}elseif($status == "FAILED"){
   $message = $input->get('message');
}elseif($status == "CANCELLED"){
   $message = $input->get('message');
}elseif($status == "error"){
   $message = $input->get('message');
}else{
   // $delete = $db->delete("dusupay_orders",array('reference_no'=>$reference_no));
   // echo "<script> window.open('index','_self'); </script>";
   // exit();
}

?>
<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>

   <title><?= $site_name; ?> - Payment <?= ucfirst($status); ?></title>

   <meta charset="utf-8">
   
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="Login or register for an account on <?= $site_name; ?>, a fast growing freelance marketplace, where sellers provide their services at extremely affordable prices.">
   <meta name="keywords" content="<?= $site_keywords; ?>">
   <meta name="author" content="<?= $site_author; ?>">

   <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
   <link href="styles/bootstrap.css" rel="stylesheet">
   <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
   <link href="styles/styles.css" rel="stylesheet">
   <link href="styles/categories_nav_styles.css" rel="stylesheet">
   <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
   <link href="styles/sweat_alert.css" rel="stylesheet">
   <link href="styles/animate.css" rel="stylesheet">
   <script type="text/javascript" src="js/ie.js"></script>
   <script type="text/javascript" src="js/sweat_alert.js"></script>
   <script type="text/javascript" src="js/jquery.min.js"></script>

   <?php if(!empty($site_favicon)){ ?>
      <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
   <?php } ?>

</head>

<body class="is-responsive">

<?php require_once("includes/header.php"); ?>

<div class="container mt-5"><!--- container Starts --->

   <div class="row justify-content-center"><!--- row Starts --->

      <div class="col-lg-5 col-md-7"><!--- col-lg-5 Starts --->

         <h2 class="text-center">Payment <?= ucfirst(strtolower($status)); ?></h2>

         <div class="card mt-4 mb-5"><!--- car Starts --->

            <div class="card-body">

               <h1 class="text-center mb-3 mt-3 text-success">
                  <i class="fa-4x fa fa-check-circle"></i>
               </h1>

               <p class="lead"><?= $message; ?></p>

               <a href="index" class="text-success">
                  <i class="fa fa-arrow-left"></i> &nbsp;Go Back To Website
               </a>

            </div>

         </div><!--- car Ends --->

      </div><!--- col-lg-5 Ends --->

   </div><!--- row Ends --->

</div><!--- container Ends --->

<?php require_once("includes/footer.php"); ?>

</body>

</html>