<?php
session_start();
require_once("../includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('../login','_self')</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
$login_seller_level = $row_login_seller->seller_level;
$login_seller_language = $row_login_seller->seller_language;

$enable_unlimited_revisions = $row_general_settings->enable_unlimited_revisions;
$revisions = array(0,1,2,3,4,5,6,7,8,9,10);

if($enable_unlimited_revisions == 1){
  $revisions['unlimited'] = "Unlimited Revisions";
}

?>

<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
<title><?= $site_name; ?> - <?= $lang["titles"]["create_proposal"]; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="<?= $site_desc; ?>">
<meta name="keywords" content="<?= $site_keywords; ?>">
<meta name="author" content="<?= $site_author; ?>">
<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
<link href="../styles/bootstrap.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css" rel="stylesheet">
<link href="../styles/styles.css" rel="stylesheet">
<link href="../styles/user_nav_styles.css" rel="stylesheet">
<link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
<link href="../styles/owl.carousel.css" rel="stylesheet">
<link href="../styles/owl.theme.default.css" rel="stylesheet">
<link href="../styles/tagsinput.css" rel="stylesheet" >
<link href="../styles/sweat_alert.css" rel="stylesheet">
<link href="../styles/animate.css" rel="stylesheet">
<link href="../styles/croppie.css" rel="stylesheet">
<link href="../styles/create-proposal.css" rel="stylesheet">
<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
<script src="../js/ie.js"></script>
<script type="text/javascript" src="../js/sweat_alert.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/croppie.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
<?php if(!empty($site_favicon)){ ?>
<link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
<?php } ?>
</head>
<body class="is-responsive">
<?php 
require_once("../includes/user_header.php"); 

if($seller_verification != "ok"){

  echo "
    <div class='alert alert-danger rounded-0 mt-0 text-center'>
      Please confirm your email to use this feature.
    </div>
  ";

}else{

?>

<?php require_once("sections/createProposalNav.php"); ?>

<div class="container mt-5 mb-5"><!--- container Starts --->
  <div class="row"><!--- row Starts --->
    <div class="col-xl-8 col-lg-11 col-md-12"><!--- col-xl-8 Starts --->
      <div class="tab-content card card-body"><!--- tab-content Starts --->
        <div class="tab-pane fade show active" id="overview">
          <?php include("sections/create/overview.php"); ?>
        </div>
      </div><!--- tab-content Ends --->
    </div><!--- col-xl-8 Ends --->
  </div><!--- row Ends --->
</div><!--- container Ends --->

<script>
$(document).ready(function(){
  $('.proposal_referral_money').hide();
  <?php if(@$form_data['proposal_enable_referrals'] == "yes"){ ?>
  $('.proposal_referral_money').show();
  <?php } ?>

  $(".proposal_enable_referrals").change(function(){
    var value = $(this).val();
    if(value == "yes"){
    $(".proposal_referral_money input").attr("required","");
    $('.proposal_referral_money').show();
    }else if(value == "no"){
    $(".proposal_referral_money input").removeAttr("required");
    $('.proposal_referral_money').hide();
    }
  });

  <?php if(@$form_data['proposal_child_id']){ ?>

  <?php }else{ ?>

  $("#sub-category").hide();	

  <?php } ?>

  $("#category").change(function(){
    $("#sub-category").show();
    var category_id = $(this).val();
    $.ajax({
    url:"fetch_subcategory",
    method:"POST",
    data:{category_id:category_id},
    success:function(data){
    $("#sub-category").html(data);
    }
    });
  });

 	$image_crop = $('#image_demo').croppie({
    enableExif: true,
    viewport: {
      width:540,
      height:300,
      type:'square' //circle
    },
    boundary:{
      width:100,
      height:400
    }    
  });

	function crop(data){
    var reader = new FileReader();
    reader.onload = function (event) {
      $image_crop.croppie('bind',{
      url: event.target.result
      }).then(function(){
      console.log('jQuery bind complete');
      });
    }
    reader.readAsDataURL(data.files[0]);
    $('#insertimageModal').modal('show');
    $('input[type=hidden][name=img_type]').val($(data).attr('name'));
	}

	$(document).on('change','input[type=file]:not(#v_file)', function(){
    var size = $(this)[0].files[0].size; 
    var ext = $(this).val().split('.').pop().toLowerCase();
    if($.inArray(ext,['jpeg','jpg','gif','png']) == -1){
    alert('Your File Extension Is Not Allowed.');
    $(this).val('');
    }else{
      crop(this);
    }
	});

  $('.crop_image').click(function(event){
    $('#wait').addClass("loader");
    var name = $('input[type=hidden][name=img_type]').val();
    $image_crop.croppie('result', {
      type: 'canvas',
      size: 'viewport'
    }).then(function(response){
      $.ajax({
        url:"crop_upload",
        type: "POST",
        data:{image: response,name: $('input[type=file][name='+name+']').val().replace(/C:\\fakepath\\/i,'')},
        success:function(data){
          $('#wait').removeClass("loader");
          $('#insertimageModal').modal('hide');
          $('input[type=hidden][name='+ name +']').val(data);
        }
      });
    });
  });

  $('textarea[name="proposal_desc"]').summernote({
      placeholder: 'Write Your Description Here.',
      height: 150,
      toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'clear']],
      ['fontname', ['fontname']],
      ['fontsize', ['fontsize']],
      ['height', ['height']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'picture']],
    ],
  });

});
</script>

<?php } ?>

<?php require_once("../includes/footer.php"); ?>
<script src="../js/tagsinput.js"></script>

</body>
</html>