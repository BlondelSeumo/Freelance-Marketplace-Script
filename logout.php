<?php
  session_start();
  require_once("includes/db.php");
  if(!isset($_SESSION['seller_user_name'])){
    echo "<script> window.open('index','_self') </script>";
  }
?>
<html lang="en" class="ui-toolkit">
<head>
  <title> Logging out.. </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
  <link href="styles/bootstrap.css" rel="stylesheet">
  <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
  <link href="styles/styles.css" rel="stylesheet">
  <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
  <link href="styles/owl.carousel.css" rel="stylesheet">
  <link href="styles/owl.theme.default.css" rel="stylesheet">
  <link href="styles/sweat_alert.css" rel="stylesheet">
  <!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
  <script src="js/ie.js"></script>    
  <script type="text/javascript" src="js/sweat_alert.js"></script>
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <style>
    body *, 
    body *:before, 
    body *:after {
        -webkit-box-sizing: border-box !important;
        -ms-box-sizing: border-box !important;
        -moz-box-sizing: border-box !important;
        -o-box-sizing: border-box !important;
        box-sizing: border-box !important;
    }
    .swal2-icon.swal2-success .swal2-success-ring {
      position: absolute;
      top: 0.1em;
      left: 0em;
    }
  </style>
</head>
<body style="background-color: #2c3e50;"></body>
</html>
<?php
  $seller_user_name = $_SESSION['seller_user_name'];
  $login_seller_status = $row_login_seller->seller_status;
  if($login_seller_status != "block-ban" AND $login_seller_status != "deactivated"){
    $update_seller_status = $db->update("sellers",array("seller_status"=>'offline'),array("seller_user_name"=>$seller_user_name));
  }
  unset($_SESSION['seller_user_name']);
  echo "
  <script>
    
    swal({
      type: 'success',
      text: '{$lang['alert']['logout']}',
      timer: 3000,
      onOpen: function(){
        swal.showLoading()
      }
    }).then(function(){
      window.open('index','_self')
    });
  </script>
  ";
?>
