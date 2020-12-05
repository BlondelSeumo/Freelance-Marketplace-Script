<?php

session_start();
require_once("includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('login','_self')</script>";
}

$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

?>
<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>

	<title><?= $site_name; ?> - Proposals Ordered By Your Customers</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= $site_desc; ?>">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">

	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">

	<link href="styles/bootstrap.css" rel="stylesheet">
    <link href="styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="styles/styles.css" rel="stylesheet">
	<link href="styles/user_nav_styles.css" rel="stylesheet">
	<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
	<link href="styles/owl.carousel.css" rel="stylesheet">
	<link href="styles/owl.theme.default.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.min.js"></script>

	<?php if(!empty($site_favicon)){ ?>
   
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
       
    <?php } ?>

</head>

<body class="is-responsive">

<?php require_once("includes/user_header.php"); ?>

<div class="container-fluid mt-5">

	<div class="row">

		<div class="col-md-12">

		 <h1 class="<?=($lang_dir == "right" ? 'text-right':'')?>"><?= $lang["titles"]["selling_orders"]; ?></h1>

		</div>

	</div>

	<div class="row">

		<div class="col-md-12 mt-5 mb-3">

			<ul class="nav nav-tabs flex-column flex-sm-row ">

				<li class="nav-item">
                    
                    <?php

                    $count_orders = $db->count("orders",array("seller_id" => $login_seller_id, "order_active" => 'yes'));
                    
                    ?>
                    
					<a href="#active" data-toggle="tab" class="nav-link make-black active ">

						<?= $lang['tabs']['active']; ?> <span class="badge badge-success"><?= $count_orders; ?></span>

					</a>

				</li>

				<li class="nav-item">
                    
                    <?php

                    $count_orders = $db->count("orders",array("seller_id" => $login_seller_id, "order_status" => 'delivered'));

                    ?>

					<a href="#delivered" data-toggle="tab" class="nav-link make-black">

						<?= $lang['tabs']['delivered']; ?> <span class="badge badge-success"><?= $count_orders; ?></span>
						
					</a>
					
				</li>

				<li class="nav-item">
                    
                    <?php

                    $count_orders = $db->count("orders",array("seller_id" => $login_seller_id, "order_status" => 'completed'));

                    ?>

					<a href="#completed" data-toggle="tab" class="nav-link make-black">

						<?= $lang['tabs']['completed']; ?> <span class="badge badge-success"><?= $count_orders; ?></span>

					</a>

				</li>

				<li class="nav-item">
                    
                    <?php

              		$count_orders = $db->count("orders",array("seller_id" => $login_seller_id, "order_status" => 'cancelled'));

                    ?>

					<a href="#cancelled" data-toggle="tab" class="nav-link make-black">

						<?= $lang['tabs']['cancelled']; ?> <span class="badge badge-success"><?= $count_orders; ?></span>
						
					</a>

				</li>

				<li class="nav-item">
                    
                    <?php

                    $count_orders = $db->count("orders",array("seller_id" => $login_seller_id));

                    ?>

					<a href="#all" data-toggle="tab" class="nav-link make-black">

						<?= $lang['tabs']['all']; ?> <span class="badge badge-success"><?= $count_orders; ?></span>
					
					</a>

				</li>
				
			</ul>

			<div class="tab-content">

				<div class="tab-pane fade show active" id="active">

					<?php require_once("manage_orders/order_active_selling.php") ?>

				</div>


				<div class="tab-pane" id="delivered">

					<?php require_once("manage_orders/order_delivered_selling.php") ?>

				</div>


				<div class="tab-pane" id="completed">

					<?php require_once("manage_orders/order_completed_selling.php") ?>

				</div>

				<div class="tab-pane" id="cancelled">

					<?php require_once("manage_orders/order_cancelled_selling.php") ?>

				</div>

				<div class="tab-pane" id="all">

					<?php require_once("manage_orders/order_all_selling.php") ?>

				</div>


			</div>

		</div>

	</div>

</div>

<?php require_once("includes/footer.php"); ?>

</body>

</html>