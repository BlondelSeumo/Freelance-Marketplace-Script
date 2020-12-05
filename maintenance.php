<?php

session_start();

require_once "includes/config.php";

require_once "libs/database.php";

$get_general_settings = $db->select("general_settings");   
$row_general_settings = $get_general_settings->fetch();
$site_url = $row_general_settings->site_url;
$site_name = $row_general_settings->site_name;
$site_logo = $row_general_settings->site_logo;
$enable_maintenance_mode = $row_general_settings->enable_maintenance_mode;

if(isset($_SESSION['admin_email'])){
    echo "<script>window.open('$site_url','_self');</script>";
}

if($enable_maintenance_mode == "no"){
    echo "<script>window.open('$site_url','_self');</script>";
}

?>

<!DOCTYPE html>

<html>

<head>

    <title><?= $site_name; ?> In Maintenance Mode</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="styles/bootstrap.css" rel="stylesheet">

    <!--- stylesheet width modifications --->

    <link href="styles/custom.css" rel="stylesheet">
    <link href="font_awesome/css/font-awesome.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>

</head>

<style>
  body { text-align: center; font-size: 20px; background: #059992; color:white; padding-top: 200px;  }
  h1 { font-size: 50px; text-align: center; color:white; }
  article { margin: 0 auto; }
  p { line-height: 30px;  }
</style>

<body class="is-responsive">

<div class="container">
    
    <div class="row justify-content-center">
    
        <div class="col-lg-7 col-md-12">
        
        <article>

        <!-- <center><img src="images/<?= $site_logo; ?>" alt="" width="150"></center> -->
           
            <h1>We&rsquo;ll be back soon!</h1>
           
            <div>
                <p class="mt-3">Sorry for the inconvenience but we&rsquo;re performing some maintenance at the moment. otherwise we&rsquo;ll be back online shortly!</p>
                <p><?= $site_name; ?> &mdash; The Team</p>
            </div>
        
        </article>

        </div>
    
    </div>

</div>


</body>

</html>