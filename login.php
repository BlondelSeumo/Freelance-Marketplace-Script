<?php

session_start();
require_once("includes/db.php");
require_once("social-config.php");

if(isset($_SESSION['seller_user_name'])){
	echo "<script> window.open('index','_self'); </script>";
}

?>
<!DOCTYPE html>

<html lang="en" class="ui-toolkit">

<head>

	<title><?= $site_name; ?> - <?= $lang['titles']['login']; ?></title>

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

<div class="container mt-5">

	<div class="row justify-content-center">

		<div class="col-lg-5 col-md-7">

			<h2 class="text-center"><?= str_replace('{site_name}',$site_name,$lang['login']['title']); ?></h2>

			<div class="box-login mt-4">

				<h2 class="text-center mb-3 mt-3"><i class="fa fa-unlock-alt" ></i></h2>

				<?php 

				$form_errors = Flash::render("login2_errors");

				if(is_array($form_errors)){

				?>

				<div class="alert alert-danger"><!--- alert alert-danger Starts --->

				<ul class="list-unstyled mb-0">
				<?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
				<li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
				<?php } ?>
				</ul>

				</div><!--- alert alert-danger Ends --->
				<?php } ?>

				<form action="" method="post">

					<div class="form-group">

						<input type="text" name="seller_user_name" class="form-control" placeholder="<?= $lang['placeholder']['username_or_email']; ?>" required>
				
	            </div>

	            <div class="form-group">

					<input type="password" name="seller_pass" class="form-control" placeholder="<?= $lang['placeholder']['password']; ?>" required>
				
	            </div>

	            <div class="form-group">

					<input type="submit" name="access" class="btn btn-success btn-block" value="<?= $lang['button']['login']; ?>" required>
				
	            </div>

				</form>
				<?php if($enable_social_login == "yes"){ ?>

				<div class="text-center pt-2 pb-2"><?= $lang['modals']['login']['or']; ?></div>

				<hr class="mb-0 mt-0">

				<div class="line mt-3"><span></span></div>

				<div class="text-center">


				<?php if(!empty($fb_app_id) & !empty($fb_app_secret)){ ?>
				<a href="#" onclick="window.location='<?= $fLoginURL ?>';" class="btn btn-primary text-white" >
					<i class="fa fa-facebook"></i> FACEBOOK
				</a>
				<?php } ?>
					
				<?php if(!empty($g_client_id) & !empty($g_client_secret)){ ?>
				<a href="#" onclick="window.location = '<?= $gLoginURL ?>';" class="btn btn-danger text-white">
					<i class="fa fa-google"></i> GOOGLE
				</a>
				<?php } ?>
				
				</div>			

				<div class="clearfix"></div>

            <?php } ?>
            
				<div class="text-center mt-3">

					<a href="#" data-toggle="modal" data-target="#register-modal">

					<i class="fa fa-user-plus"></i> <?= $lang['modals']['login']['not_registerd']; ?>

               </a>

					&nbsp; &nbsp; | &nbsp; &nbsp;

               <a href="#" data-toggle="modal" data-target="#forgot-modal">

                  <i class="fa fa-meh-o"></i>	<?= $lang['modals']['login']['forgot_password']; ?>

               </a>

             </div>
   
            </div>


		</div>

	</div>

</div>
    
<?php
    
   if(isset($_POST['access'])){
	
	$rules = array(
	"seller_user_name" => "required",
	"seller_pass" => "required");
	$messages = array("seller_user_name" => "Username Is Required.","seller_pass" => "Password Is Required.");
	$val = new Validator($_POST,$rules,$messages);

	if($val->run() == false){

		Flash::add("login2_errors",$val->get_all_errors());
		echo "<script>window.open('login','_self')</script>";

	}else{

		$seller_user_name = $input->post('seller_user_name');
		$seller_pass = $input->post('seller_pass');
		
		// $select_seller = $db->query("select * from sellers where seller_user_name=:u_name OR seller_email=:u_email",array(":u_name"=>$seller_user_name,":u_email"=>$seller_user_name));

		$select_seller = $db->query("select * from sellers where binary seller_user_name like :u_name OR seller_email=:u_email",array(":u_name"=>$seller_user_name,":u_email"=>$seller_user_name));

		$row_seller = $select_seller->fetch();
		@$hashed_password = $row_seller->seller_pass;
		@$seller_status = $row_seller->seller_status;
		$decrypt_password = password_verify($seller_pass, $hashed_password);
		
		if($decrypt_password == 0){
			
		echo "
				
		<script>
	    
         swal({
           type: 'warning',
           html: $('<div>').text('{$lang['alert']['incorrect_login']}'),
           animation: false,
           customClass: 'animated tada'
         })

	   </script>";
			
		}else{
			
		if($seller_status == "block-ban"){
				
			echo "
				
			<script>
	    
            swal({
              type: 'warning',
              html: $('<div>').text('{$lang['alert']['blocked']}'),
              animation: false,
              customClass: 'animated tada'
            });

	    	</script>";
			
		}elseif($seller_status == "deactivated"){
			echo "
			<script>
			swal({
			  type: 'warning',
			  html: $('<div>').text('{$lang['alert']['deactivated']}'),
			  animation: false,
			  customClass: 'animated tada'
			})
			</script>";
		}else{

			// $select_seller = $db->select("sellers",array("seller_user_name"=>$seller_user_name,"seller_pass"=>$hashed_password));
	    	$select_seller = $db->query("select * from sellers where seller_email=:u_email OR seller_user_name=:u_name AND seller_pass=:u_pass",array("u_email"=>$seller_user_name,"u_name"=>$seller_user_name,"u_pass"=>$hashed_password));

	    	$row_seller = $select_seller->fetch();

				if($select_seller){
					
					$update_seller = $db->update("sellers",array("seller_status"=>'online',"seller_ip"=>$ip),array("seller_id"=>$row_seller->seller_id,"seller_pass"=>$hashed_password));
//					$seller_user_name = ucfirst(strtolower($row_seller->seller_user_name));
					$seller_user_name = ucfirst($row_seller->seller_user_name);
					$_SESSION['sessionStart'] = $row_seller->seller_user_name;

					echo "
					<script>
					swal({
						type: 'success',
						text: '".str_replace('{seller_user_name}',$seller_user_name,$lang['alert']['successfully_login'])."',
						timer: 4000,
						onOpen: function(){
						swal.showLoading()
						}
					}).then(function(){
						window.open('$site_url','_self')
					})
					</script>";
				}

			}
			
		}
		
	}
	
}


    
?>

<?php require_once("includes/footer.php"); ?>

</body>

</html>