<?php

session_start();
require_once("includes/db.php");
require_once("functions/processing_fee.php");
if(!isset($_SESSION['seller_user_name'])){
	echo "<script>window.open('login','_self')</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();

$total = 0;
$select_cart = $db->select("cart",["seller_id" => $login_seller_id]);
while($row_cart = $select_cart->fetch()){
	$proposal_price = $row_cart->proposal_price;
	$proposal_qty = $row_cart->proposal_qty;

	$get_extras = $db->select("cart_extras",["seller_id"=>$login_seller_id,"proposal_id"=>$row_cart->proposal_id]);
	while($extra = $get_extras->fetch()){
		$proposal_price += $extra->price;
	}

	$sub_total = $proposal_price * $proposal_qty;
	$total += $sub_total;

}
$processing_fee = processing_fee($total);

$count_cart = $select_cart->rowCount();
if(isset($_GET['remove_proposal'])){
	$proposal_id = $input->get('remove_proposal');
	$delete_cart_proposal = $db->delete("cart",["proposal_id"=>$proposal_id,"seller_id"=>$login_seller_id]);
	$delete_cart_extras = $db->delete("cart_extras",["proposal_id"=>$proposal_id,"seller_id"=>$login_seller_id]);
	if($delete_cart_extras){
		echo "<script>window.open('cart','_self');</script>";	
	}
}

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
	<title><?= $site_name; ?> - Cart</title>
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
	<link href="styles/owl.carousel.css" rel="stylesheet">
	<link href="styles/owl.theme.default.css" rel="stylesheet">
	<script type="text/javascript" src="js/jquery.min.js"></script>
  <?php if(!empty($site_favicon)){ ?>
  <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
</head>
<body class="is-responsive">
<?php require_once("includes/header.php"); ?>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-md-12">
			<div class="card mb-3">
				<div class="card-body">
					<h5 class="float-left mt-2"> <?= $lang['cart']['your_cart']; ?> (<?= $count_cart; ?>) </h5>
					<h5 class="float-right"> 
						<a class="btn btn-success" href="index">
							<?= $lang['button']['continue_shopping']; ?>
						</a> 
					</h5>
				</div>
      </div>
		</div>
	</div>
	<div class="row cart-add-sect" id="cart-show">
		<div class="col-md-7">
			<div class="card mb-3">
				<div class="card-body">
					<?php
					$select_cart = $db->select("cart",array("seller_id"=>$login_seller_id));
					while($row_cart = $select_cart->fetch()){
					$proposal_id = $row_cart->proposal_id;
					$proposal_price = $row_cart->proposal_price;
					$proposal_qty = $row_cart->proposal_qty;
					@$video = $row_cart->video;

					$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
					$row_proposals = $select_proposals->fetch();
					$proposal_title = $row_proposals->proposal_title;
					$proposal_url = $row_proposals->proposal_url;
					$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
					$proposal_seller_id = $row_proposals->proposal_seller_id;
					$get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
					$row_seller = $get_seller->fetch();
					$proposal_seller_user_name = $row_seller->seller_user_name;

					$cart_extras = $db->select("cart_extras",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));
					?>
					<div class="cart-proposal">
						<div class="row">
							<div class="col-lg-3 mb-2">
								<a href="proposals/<?= $proposal_seller_user_name; ?>/<?= $proposal_url; ?>">
									<img src="<?= $proposal_img1; ?>" class="img-fluid">
								</a>
							</div>
							<div class="col-lg-9">
								<a href="proposals/<?= $proposal_seller_user_name; ?>/<?= $proposal_url; ?>">
									<h6 class="text-success make-black"><?= $proposal_title; ?> </h6>
									<ul class="ml-0 mb-2" style="list-style-type: circle;">
			                    <?php
			                  	while($extra = $cart_extras->fetch()){
			                     	$name = $extra->name;
			                     	$price = $extra->price;
			                     	$proposal_price += $price;
			                    ?>
				                  <li class="font-weight-normal text-muted">
				                    <?= $name; ?> (+<span class="price"><?= showPrice($price); ?></span>)
				                  </li>
				                  <?php } ?>
				               </ul>
								</a>
								<a href="cart?remove_proposal=<?= $proposal_id; ?>" class=" text-muted remove-link">
									<i class="fa fa-times"></i> <?= $lang['cart']['remove_proposal']; ?>
								</a>
							</div>
						</div>
						<hr>
						<h6 class="clearfix">
							<?= ($video == 1)?$lang['cart']['proposal_call_minutes']:$lang['cart']['proposal_qty']; ?>
							<strong class="float-right price ml-2 mt-2">
								<?= showPrice($proposal_price*$proposal_qty); ?>
							</strong>
							<input type="text" name="quantity" class="float-right form-control quantity" min="1" data-proposal_id="<?= $proposal_id; ?>" value="<?= $proposal_qty; ?>">
						</h6>
						<hr>
					</div>
          		<?php } ?>
					<h3 class="float-right"><?= $lang['cart']['total']; ?> <?= showPrice($total); ?> </h3>
				</div>
			</div>
			<?php
				if($count_cart == 0){
					echo "<center><h3 class='pt-5'><i class='fa fa-meh-o'></i> Your cart is empty</h3></center>";
				}
			?>
		</div>
		<div class="col-md-5">
			<div class="card">
				<div class="card-body cart-order-details">
					<p>
						<?= $lang['cart']['subtotal']; ?> <span class="float-right"><?= showPrice($total); ?></span>
					</p>
					<hr>
					<p><?= $lang['cart']['apply_coupon_code']; ?></p>
					<form class="input-group" method="post">
						<input type="text" name="code" class="form-control apply-disabled" placeholder="Enter Coupon Code">
						<button type="submit" name="coupon_submit" class="input-group-addon btn btn-success">
							<?= $lang['button']['apply']; ?>
						</button>
					</form>
					<?php
					$coupon_usage = "no";
					if(isset($_POST['coupon_submit'])){
						$coupon_code = $input->post('code');
						$select_coupon = $db->select("coupons",array("coupon_code"=>$coupon_code));
						$count_coupon = $select_coupon->rowCount();
						if($count_coupon == 1){
							$row_coupon = $select_coupon->fetch();
							$coupon_proposal = $row_coupon->proposal_id;
							$coupon_limit = $row_coupon->coupon_limit;
							$coupon_used = $row_coupon->coupon_used;
							$coupon_price = $row_coupon->coupon_price;
							$coupon_type = $row_coupon->coupon_type;
							if($coupon_limit <= $coupon_used){
								$coupon_usage = "expired";
								echo "
								<script>
								$('.coupon-response').html('Your coupon code expired.').attr('class','coupon-response p-2 mt-3 bg-danger text-white');
								</script>";
							}else{
								$select_cart = $db->select("cart",array("proposal_id" => $coupon_proposal,"seller_id" => $login_seller_id,"coupon_used" => 0));
								$count_cart = $select_cart->rowCount();
								if($count_cart == 1){

									$row_cart = $select_cart->fetch();
									$proposal_price = $row_cart->proposal_price;

									if($coupon_type == "fixed_price"){
										if($coupon_price > $proposal_price){
											$coupon_price = 0;
										}else{
											$coupon_price = $proposal_price-$coupon_price;
										}
									}else{
										$numberToAdd = ($proposal_price / 100) * $coupon_price;
										$coupon_price = $proposal_price - $numberToAdd;

										$cart_extras = $db->select("cart_extras",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));

									}

									$update_coupon = $db->query("update coupons set coupon_used=coupon_used+1 where coupon_code=:c_code",array("c_code"=>$coupon_code));

									$update_cart = $db->update("cart",array("proposal_price" => $coupon_price,"coupon_used" => 1),array("proposal_id" => $coupon_proposal,"seller_id" => $login_seller_id));
									
									$coupon_usage = "used";
									echo "<script>window.open('cart?coupon_applied','_self')</script>";
								}else{
									$coupon_usage = "not_apply";
								}
							}
						}else{
							$coupon_usage = "not_valid"; 
						}
						
					}
					?>
					<hr>
					<?php if($coupon_usage == "not_valid"){ ?>
					<p class="coupon-response mt-2 p-2 bg-danger text-white"> <?= $lang['coupon_code']['not_valid']; ?> </p>
					<?php }elseif($coupon_usage == "no" & isset($_GET['coupon_applied'])){ ?>
					<p class="coupon-response mt-2 p-2 applied text-white"><?= $lang['coupon_code']['applied']; ?></p>
					<?php }elseif($coupon_usage == "expired"){ ?>
					<p class="coupon-response mt-2 p-2 bg-danger text-white"> <?= $lang['coupon_code']['expired']; ?> </p>
					<?php }elseif($coupon_usage == "not_apply"){ ?>
					<p class="coupon-response mt-2 p-2 bg-success text-white"><?= $lang['coupon_code']['not_apply']; ?></p>
					<?php } ?>
					<?php if($coupon_usage != "no"){ ?>
					<hr>
					<?php } ?>
					<p>
						<?= $lang['cart']['processing_fee']; ?> 
						<span class="float-right"><?= showPrice($processing_fee); ?></span>
					</p>
					<hr>
					<p>
						<?= $lang['cart']['total2']; ?> 
						<span class="font-weight-bold float-right"><?= showPrice($total+$processing_fee); ?></span>
					</p>
					<hr>
					<?php if($count_cart == 0){ ?>
						<h5 class="text-center"> <?= $lang['cart']['no_proposals']; ?>  </h5>
					<?php }else{ ?>
						<a href="cart_payment_options" class="btn btn-lg btn-success btn-block"><?= $lang['button']['proceed_to_payment']; ?></a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once("includes/footer.php"); ?>

<script>
$(document).ready(function(){
	$(document).on('keyup','.quantity', function(){
		var value = parseInt($(this).val(), 10);
		var min = parseInt($(this).attr("min"), 10);
		if(value < min){
			value = min;
			$(this).val(value);
		}
		if (/\D/g.test($(this).value)){ 
			$(this).val(this.value.replace(/\D/g,'1')); 
		}
		var seller_id = "<?= $login_seller_id; ?>";
		var proposal_id = $(this).data("proposal_id");
		var quantity = $(this).val();
		if(quantity != ""){
			$.ajax({
				url: "change_qty",
				method: "POST",
				data: {seller_id: seller_id, proposal_id: proposal_id, proposal_qty: quantity},
				success: function(data){
					$("#cart-show").load("cart_show");
				}
			});
		}
	});
});
</script>
</body>
</html>