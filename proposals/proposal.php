<?php

session_start();
require_once("../includes/db.php");
require_once("../social-config.php");
require_once("../functions/functions.php");
require_once("../functions/email.php");

require_once "$dir/screens/detect.php";
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

if($deviceType == "phone"){
	$proposals_stylesheet = '<link href="styles/mobile_proposals.css" rel="stylesheet">'; 
}else{
	$proposals_stylesheet = '<link href="styles/desktop_proposals.css" rel="stylesheet">'; 
}

function isHTML($string){
 if($string != strip_tags($string)){
  // is HTML
  return true;
 }else{
  // not HTML
  return false;
 }
}

// $deviceType = "phone";

$username = $input->get('username');
$select_proposal_seller = $db->select("sellers",array("seller_user_name"=>$username));
$row_proposal_seller = $select_proposal_seller->fetch();
$proposal_seller_id = $row_proposal_seller->seller_id;

$proposal_url = urlencode($input->get('proposal_url'));

if(isset($_SESSION['admin_email'])){
	$get_proposal = $db->query("select * from proposals where proposal_url=:url and proposal_seller_id='$proposal_seller_id' AND NOT proposal_status='deleted'",array("url"=>$proposal_url));
}elseif(isset($_SESSION['seller_user_name']) AND $_SESSION['seller_user_name'] == $username){
	$get_proposal = $db->query("select * from proposals where proposal_url=:url and proposal_seller_id='$proposal_seller_id' and not proposal_status in ('trash','deleted')",array("url"=>$proposal_url));
}else{
	$get_proposal = $db->query("select * from proposals where proposal_url=:url and proposal_seller_id='$proposal_seller_id' and not proposal_status in ('draft','admin_pause','pause','pending','trash','declined','modification','trash','deleted')",array("url"=>$proposal_url));
}
$count_proposal = $get_proposal->rowCount();
if($count_proposal == 0){
	echo "<script> window.open('../../index.php?not_available','_self') </script>";
}

$proposal_id = $get_proposal->fetch()->proposal_id;

// Select proposal Details From Proposal Id
$select_proposal = $db->select("proposals",array("proposal_id" => $proposal_id));
$row_proposal = $select_proposal->fetch();
$proposal_title = $row_proposal->proposal_title;
$proposal_cat_id = $row_proposal->proposal_cat_id;
$proposal_child_id = $row_proposal->proposal_child_id;
$proposal_price = $row_proposal->proposal_price;
$proposal_img1 = $row_proposal->proposal_img1;
$proposal_img2 = $row_proposal->proposal_img2;
$proposal_img3 = $row_proposal->proposal_img3;
$proposal_img4 = $row_proposal->proposal_img4;
$proposal_video = $row_proposal->proposal_video;
if($paymentGateway == 1){
	$proposal_video_type = $row_proposal->proposal_video_type;
}else{
	$proposal_video_type = "uploaded";
	if(isHTML($proposal_video)){
		$proposal_video = "";
	}
}

$proposal_desc = $row_proposal->proposal_desc;
$proposal_short_desc = strip_tags(substr($row_proposal->proposal_desc,0,160));
$proposal_tags = $row_proposal->proposal_tags;
$proposal_seller_id = $row_proposal->proposal_seller_id;
$delivery_id = $row_proposal->delivery_id;
$proposal_revisions = $row_proposal->proposal_revisions;
$proposal_rating = $row_proposal->proposal_rating;
// $proposal_enable_faqs = $row_proposal->proposal_enable_faqs;
$proposal_enable_referrals = $row_proposal->proposal_enable_referrals;
$proposal_referral_money = $row_proposal->proposal_referral_money;
$proposal_referral_code = $row_proposal->proposal_referral_code;

// Select Proposal Category
$get_cat = $db->select("categories",array('cat_id'=>$proposal_cat_id));
$proposal_cat_url = $get_cat->fetch()->cat_url;

$get_meta = $db->select("cats_meta",array("cat_id"=>$proposal_cat_id,"language_id"=>$siteLanguage));
$row_meta = $get_meta->fetch();
@$proposal_cat_title = $row_meta->cat_title;

// Select Proposal Child Category
$get_child = $db->select("categories_children",array('child_id'=>$proposal_child_id));
$proposal_child_url = $get_child->fetch()->child_url;

$get_meta = $db->select("child_cats_meta",array("child_id"=>$proposal_child_id,"language_id"=>$siteLanguage));
$row_meta = $get_meta->fetch();
@$proposal_child_title = $row_meta->child_title;

// Select Proposal Delivery Time
$get_delivery_time = $db->select("delivery_times",array('delivery_id' => $delivery_id));
$row_delivery_time = $get_delivery_time->fetch();
$delivery_proposal_title = $row_delivery_time->delivery_proposal_title;

// Select Proposal Active Orders
$select_orders = $db->select("orders",["proposal_id"=>$proposal_id,"order_active"=>"yes"]);
$proposal_order_queue = $select_orders->rowCount();

// Select Proposal Reviews Then Count Them
$proposal_reviews = array();
$select_buyer_reviews = $db->select("buyer_reviews",array("proposal_id" => $proposal_id));
$count_reviews = $select_buyer_reviews->rowCount();
while($row_buyer_reviews = $select_buyer_reviews->fetch()){
	$proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
	array_push($proposal_reviews,$proposal_buyer_rating);
}
$total = array_sum($proposal_reviews);
@$average_rating = $total/count($proposal_reviews);

// Select Proposal Seller Details
$select_proposal_seller = $db->select("sellers",array("seller_id"=>$proposal_seller_id));
$row_proposal_seller = $select_proposal_seller->fetch();
$proposal_seller_user_name = $row_proposal_seller->seller_user_name;
$proposal_seller_image = $row_proposal_seller->seller_image;
$proposal_seller_country = $row_proposal_seller->seller_country;
$proposal_seller_about = $row_proposal_seller->seller_about;
$proposal_seller_level = $row_proposal_seller->seller_level;
$proposal_seller_recent_delivery = $row_proposal_seller->seller_recent_delivery;
$proposal_seller_rating = $row_proposal_seller->seller_rating;
$proposal_seller_vacation = $row_proposal_seller->seller_vacation;
$proposal_seller_activity = $row_proposal_seller->seller_activity;
$proposal_seller_status = $row_proposal_seller->seller_status;

// Select Proposal Seller Level
@$level_title = $db->select("seller_levels_meta",array("level_id"=>$proposal_seller_level,"language_id"=>$siteLanguage))->fetch()->title;

// Update Proposal Views
if(!isset($_SESSION['seller_user_name'])){
	$update_proposal_views = $db->query("update proposals set proposal_views=proposal_views+1 where proposal_id='$proposal_id'");
}

if(isset($_SESSION['seller_user_name'])){
	$login_seller_user_name = $_SESSION['seller_user_name'];
	$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
	$row_login_seller = $select_login_seller->fetch();
	$login_seller_id = $row_login_seller->seller_id;
	if($proposal_seller_id != $login_seller_id ){
		$update_proposal_views = $db->query("update proposals set proposal_views=proposal_views+1 where proposal_id='$proposal_id'");
	}
	$select_recent_proposal = $db->select("recent_proposals",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));
	$count_recent_proposal = $select_recent_proposal->rowCount();
	if($count_recent_proposal == 1){
		if($proposal_seller_id != $login_seller_id){
			$delete_recent = $db->delete("recent_proposals",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));
			$insert_recent = $db->insert("recent_proposals",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));
		}
	}else{
		if($proposal_seller_id != $login_seller_id){
			$insert_recent = $db->insert("recent_proposals",array("seller_id"=>$login_seller_id,"proposal_id"=>$proposal_id));
		}
	}
}

$get_extras = $db->select("proposals_extras",array("proposal_id"=>$proposal_id));
$count_extras = $get_extras->rowCount();

$get_faq = $db->select("proposals_faq",array("proposal_id"=>$proposal_id));
$count_faq = $get_faq->rowCount();

$favorites = $db->select("favorites",array("proposal_id"=>$proposal_id,"seller_id"=>@$login_seller_id));
$countfavorites = $favorites->rowCount();
if($countfavorites == 0){
	$show_favorite_id = "favorite_$proposal_id";
	$show_favorite_class = "dil1";
	$show_favorite_text = "Favourite";
}else{
	$show_favorite_id = "unfavorite_$proposal_id";
	$show_favorite_class = "dil";
	$show_favorite_text = "Unfavourite";
}

$cart = $db->select("cart",array("proposal_id"=>$proposal_id,"seller_id"=>@$login_seller_id));
$countcart = $cart->rowCount();

$ratings = array();
$sel_proposal_reviews = $db->select("buyer_reviews",array("proposal_id"=>$proposal_id));
while($row_proposals_reviews = $sel_proposal_reviews->fetch()){
  $proposal_buyer_rating = $row_proposals_reviews->buyer_rating;
  array_push($ratings,$proposal_buyer_rating);
}
$total = array_sum($ratings);
if($total!=0){
	$avg = $total/count($ratings);
	$proposal_rating = substr($avg,0,1);
}else{
	$proposal_rating=0;
}

if(empty($proposal_rating) or $proposal_rating=="N"){
  $proposal_rating = 0;
}

if($videoPlugin == 1){

	$proposal_videosettings = $db->select("proposal_videosettings",array('proposal_id'=>$proposal_id))->fetch();
	$enableVideo = $proposal_videosettings->enable;
	$price_per_minute = $proposal_videosettings->price_per_minute;
	$days_within_scheduled = $proposal_videosettings->days_within_scheduled;

	$get_schedule = $db->select("video_schedules",array("id"=>$days_within_scheduled));
	$schedule = $get_schedule->fetch();
	$schedule_title = @$schedule->title;

}else{
	$enableVideo = 0;
}

$show_img1 = getImageUrl2("proposals","proposal_img1",$proposal_img1);

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
<title><?= $site_name; ?> - <?= $proposal_title; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="<?= $proposal_short_desc; ?>">
<meta name="keywords" content="<?= $proposal_tags; ?>">
<meta name="author" content="<?= $proposal_seller_user_name; ?>">
<meta property="og:image" content="<?= $show_img1; ?>"/>
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
<link href="../../styles/bootstrap.css" rel="stylesheet">
<link href="../../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
<link href="../../styles/styles.css" rel="stylesheet">
<link href="../../styles/proposalStyles.css" rel="stylesheet">
<?php 
if($deviceType == "phone"){
echo '<link href="../../styles/mobile_proposals.css" rel="stylesheet">'; 
}else{
echo '<link href="../../styles/desktop_proposals.css" rel="stylesheet">'; 
}
?>
<link href="../../styles/categories_nav_styles.css" rel="stylesheet">
<link href="../../font_awesome/css/font-awesome.css" rel="stylesheet">
<link href="../../styles/owl.carousel.css" rel="stylesheet">
<link href="../../styles/owl.theme.default.css" rel="stylesheet">
<link href="../../styles/sweat_alert.css" rel="stylesheet">
<link href="../../styles/green-audio-player.css" rel="stylesheet">
<script type="text/javascript" src="../../js/sweat_alert.js"></script>
<script type="text/javascript" src="../../js/jquery.min.js"></script>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5a39d50ac9681a6c"></script>
<?php if(!empty($site_favicon)){ ?>
<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
<?php } ?>
</head>
<body class="is-responsive">
<script src="//platform-api.sharethis.com/js/sharethis.js#property=5c812224d11c6a0011c485fd&product=inline-share-buttons"></script>
<?php

	require_once("../includes/header.php");

	$show_img1 = getImageUrl2("proposals","proposal_img1",$proposal_img1);
	$show_img2 = getImageUrl2("proposals","proposal_img2",$proposal_img2);
	$show_img3 = getImageUrl2("proposals","proposal_img3",$proposal_img3);
	$show_img4 = getImageUrl2("proposals","proposal_img4",$proposal_img4);
	$show_video = getImageUrl2("proposals","proposal_video",$proposal_video);

	$img_2_extension = pathinfo($proposal_img2,PATHINFO_EXTENSION);
	$img_3_extension = pathinfo($proposal_img3,PATHINFO_EXTENSION);
	$img_4_extension = pathinfo($proposal_img4,PATHINFO_EXTENSION);

	if($deviceType == "phone"){
		include("../screens/mobile_proposal.php");
	}else{
		include("../screens/desktop_proposal.php"); 
	}

?>

<div id="image-modal" class="modal fade"><!-- report-modal modal fade Starts -->
	<div class="modal-dialog" style="max-width:800px;"><!-- modal-dialog Starts -->
		<div class="modal-content"><!-- modal-content Starts -->
			<div class="modal-header"><!-- modal-header Starts -->
				Proposal Image <button class="close" data-dismiss="modal"><span>&times;</span></button>
			</div><!-- modal-header Ends -->
			<div class="modal-body text-center"><!-- modal-body p-0 Starts -->
				
				<img src="<?= $show_img1; ?>" class='img-fluid'>

			</div><!-- modal-body p-0 Ends -->
		</div><!-- modal-content Ends -->
	</div><!-- modal-dialog Ends -->
</div><!-- report-modal modal fade Ends -->

<div id="report-modal" class="modal fade"><!-- report-modal modal fade Starts -->
	<div class="modal-dialog"><!-- modal-dialog Starts -->
	<div class="modal-content"><!-- modal-content Starts -->
		<div class="modal-header p-2 pl-3 pr-3"><!-- modal-header Starts -->
			Report This Proposal <button class="close" data-dismiss="modal"><span>&times;</span></button>
		</div><!-- modal-header Ends -->
		<div class="modal-body"><!-- modal-body p-0 Starts -->
			<h6>Let us know why you would like to report this Proposal.</h6>
			<form action="" method="post">
			<div class="form-group mt-3"><!--- form-group Starts --->
			<select class="form-control float-right" name="reason" required="">
			<option value="">Select</option>
			<option>Non Original Content</option>
			<option>Inappropriate Proposal</option>
			<option>Trademark Violation</option>
			<option>Copyrights Violation</option>
			</select>
			</div><!--- form-group Ends --->
			<br>
			<br>
			<div class="form-group mt-1 mb-3"><!--- form-group Starts --->
				<label> Additional Information </label>
				<textarea name="additional_information" rows="3" class="form-control" required=""></textarea>
			</div><!--- form-group Ends --->
				<button type="submit" name="submit_report" class="float-right btn btn-sm btn-success">
					Submit Report
				</button>
			</form>
			<?php 
			if(isset($_POST['submit_report'])){

				$reason = $input->post('reason');
				$additional_information = $input->post('additional_information');
				$date = date("F d, Y");
				
				$insert = $db->insert("reports",array("reporter_id"=>$login_seller_id,"content_id"=>$proposal_id,"content_type"=>"proposal","reason"=>$reason,"additional_information"=>$additional_information,"date"=>$date));

				$insert_notification = $db->insert("admin_notifications",array("seller_id" => $login_seller_id,"content_id" => $proposal_id,"reason" => "proposal_report","date" => $date,"status" => "unread"));

				if($insert_notification){
					send_report_email("proposal",$proposal_seller_user_name,$proposal_url,$date);
					echo "<script>alert('Your Report Has Been Successfully Submitted.')</script>";
					echo "<script>window.open('$proposal_url','_self')</script>";
				}
			}
			?>
			</div><!-- modal-body p-0 Ends -->
		</div><!-- modal-content Ends -->
	</div><!-- modal-dialog Ends -->
</div><!-- report-modal modal fade Ends -->

<script type="text/javascript" src="../../js/green-audio-player.min.js"></script>

<script>
	document.addEventListener('DOMContentLoaded', function() {
   	new GreenAudioPlayer('.audio-player .player-1', { showTooltips: true, showDownloadButton: false, enableKeystrokes: true });

		<?php if(!empty($proposal_img3)){ ?>
   	new GreenAudioPlayer('.audio-player .player-2', { showTooltips: true, showDownloadButton: false, enableKeystrokes: true });
   	<?php } ?>

   	<?php if(!empty($proposal_img4)){ ?>
   	new GreenAudioPlayer('.audio-player .player-3', { showTooltips: true, showDownloadButton: false, enableKeystrokes: true });
   	<?php } ?>

	});
</script>

<script type="text/javascript">

$(document).ready(function(){

	$(".carousel-item").mouseover(function(){
		$(this).find(".slide-fullscreen").css('opacity',1);
	});

	$(".carousel-item").mouseleave(function(){
		$(this).find(".slide-fullscreen").css('opacity',0);
	});

	$(".slide-fullscreen").click(function(){
		var action = $(this).data('action');
		var img = $('#image-modal .modal-body img');
		if(action == "img-1"){
			img.attr("src", "<?= $show_img1; ?>");
		}else if(action == "img-2"){
			img.attr("src", "<?= $show_img2; ?>");
		}else if(action == "img-3"){
			img.attr("src", "<?= $show_img3; ?>");
		}else if(action == "img-4"){
			img.attr("src", "<?= $show_img4; ?>");
		}

		$("#image-modal").modal('show');

	});

	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
	  var newForm = e.target.getAttribute("formid"); // newly activated tab
	  var prevForm = e.relatedTarget.getAttribute("formid"); // previous active tab
	  $("select[form="+prevForm+"]").attr('form',newForm);
	  $("input[form="+prevForm+"]").attr('form',newForm);
	})

	function convert_price(amount,name){
		$.ajax({
		method: "POST",
		url: "../ajax/convert_price",
		data: { amount: amount },
		success:function(data){
			$(name).html(data);
			$('#wait').removeClass("loader");
		}
		});
	}

	function changePrice(name,price,checked){
		
		var value = $(name+'-num').first().text();
		var num = parseFloat(value);
		var calc = parseFloat(num)+parseFloat(price);
		var calc_minus = parseFloat(num)-parseFloat(price);
		if(checked){
			amount = calc;
			$(name+'-num').html(calc);
		}else{
			amount = calc_minus;
			$(name+'-num').html(calc_minus);
		}

		convert_price(amount,name);

	}

	$(".buyables li label input").click(function(event){
		$('#wait').addClass("loader");
		var id = $(this).data("packagenum");
		var price = parseFloat($(this).parent().find(".num").text());
		<?php if($proposal_price == 0){ ?>
			changePrice('.total-price-'+id,price,this.checked);
		<?php }else{ ?>
			changePrice('.total-price',price,this.checked);
		<?php } ?>
	});

	<?php if($enableVideo == 1){ ?>
	$("form input[name='proposal_minutes']").keyup(function(event){
		if($(this).val() != 0){
			$('#wait').addClass("loader");
			var quantity = $(this).val();
			var price = <?= $proposal_price; ?>;
			var calc = price*quantity;
			convert_price(calc,".total-price");
		}
	});
	<?php } ?>
	
	$('#good').hide();
	$('#bad').hide();
});
</script>
<?php 
	include("../screens/includes/proposal_footer.php");
	include("../includes/footer.php");
?>
</body>
</html>