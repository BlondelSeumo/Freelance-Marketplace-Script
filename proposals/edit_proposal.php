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
$proposal_id = $input->get('proposal_id');
$edit_proposal = $db->select("proposals",["proposal_id"=>$proposal_id,"proposal_seller_id"=>$login_seller_id]);
if($edit_proposal->rowCount() == 0){
  echo "<script>window.open('view_proposals','_self');</script>";
}
$row_proposal = $edit_proposal->fetch();
$d_proposal_title = $row_proposal->proposal_title;
$d_proposal_url = $row_proposal->proposal_url;
$d_proposal_cat_id = $row_proposal->proposal_cat_id;
$d_proposal_child_id = $row_proposal->proposal_child_id;
$d_proposal_price = $row_proposal->proposal_price;
$d_proposal_desc = $row_proposal->proposal_desc;
$d_buyer_instruction = $row_proposal->buyer_instruction;
$d_proposal_tags = $row_proposal->proposal_tags;
$d_proposal_video = htmlspecialchars($row_proposal->proposal_video);
$d_proposal_revisions = $row_proposal->proposal_revisions;
$d_proposal_img1 = $row_proposal->proposal_img1;
$d_proposal_img2 = $row_proposal->proposal_img2;
$d_proposal_img3 = $row_proposal->proposal_img3;
$d_proposal_img4 = $row_proposal->proposal_img4;

$d_proposal_img1_s3 = $row_proposal->proposal_img1_s3;
$d_proposal_img2_s3 = $row_proposal->proposal_img2_s3;
$d_proposal_img3_s3 = $row_proposal->proposal_img3_s3;
$d_proposal_img4_s3 = $row_proposal->proposal_img4_s3;
$d_proposal_video_s3 = $row_proposal->proposal_video_s3;


$d_delivery_id = $row_proposal->delivery_id;
$d_proposal_enable_referrals = $row_proposal->proposal_enable_referrals;
$d_proposal_referral_money = $row_proposal->proposal_referral_money;
$d_proposal_status = $row_proposal->proposal_status;
if($d_proposal_price == 0){
  $get_p_1 = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
  $package_price = $get_p_1->fetch()->price;
}
if(isset($_GET['remove_video'])){
  $update_proposal = $db->update("proposals",array("proposal_video" => ''),array("proposal_id"=>$proposal_id));
  if($update_proposal){
    echo "<script>window.open('edit_proposal?proposal_id=$proposal_id','_self');</script>";
  }
}

if(isset($_GET['remove_image'])){

  if($_GET['remove_image'] == 2){
    $remove_image = "proposal_img2";
  }else if($_GET['remove_image'] == 3){
    $remove_image = "proposal_img3";
  }else if($_GET['remove_image'] == 4){
    $remove_image = "proposal_img4";
  }

  $update_proposal = $db->update("proposals",array($remove_image => ''),array("proposal_id"=>$proposal_id));
  if($update_proposal){
    echo "<script>window.open('edit_proposal?proposal_id=$proposal_id','_self');</script>";
  }

}

if($videoPlugin == 1){

  $proposal_videosettings = $db->select("proposal_videosettings",array('proposal_id'=>$proposal_id));
  $count_videosettings = $proposal_videosettings->rowCount();
  if($count_videosettings == 0){
    $db->insert("proposal_videosettings",array("proposal_id"=>$proposal_id,"enable"=>0));  
  }

  require_once("$dir/plugins/videoPlugin/proposals/checkVideo2.php");
  $checkVideo = checkVideo($d_proposal_cat_id,$d_proposal_child_id);

}else{
  $checkVideo = false;
}

// echo $checkVideo;

/// Get Category Details
$get_cat = $db->select("categories",['cat_id'=>$d_proposal_cat_id]);
$row_cat = $get_cat->fetch();
$enable_watermark = $row_cat->enable_watermark;

$get_delivery = $db->select("instant_deliveries",['proposal_id'=>$proposal_id]);
$row_delivery = $get_delivery->fetch();
$enable_delivery = $row_delivery->enable;
$delivery_message = $row_delivery->message;

$delivery_watermark = $row_delivery->watermark;

$delivery_file = $row_delivery->file;

$get_payment_settings = $db->select("payment_settings");
$row_payment_settings = $get_payment_settings->fetch();
$min_proposal_price = $row_payment_settings->min_proposal_price;
$edited_proposals = $row_general_settings->edited_proposals;
$disable_local_video = $row_general_settings->disable_local_video;

$img_2_extension = pathinfo($d_proposal_img2,PATHINFO_EXTENSION);
$img_3_extension = pathinfo($d_proposal_img3,PATHINFO_EXTENSION);
$img_4_extension = pathinfo($d_proposal_img4,PATHINFO_EXTENSION);

?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
  <title><?= $site_name; ?> - <?= $lang["titles"]["edit_proposal"]; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= $site_desc; ?>">
  <meta name="keywords" content="<?= $site_keywords; ?>">
  <meta name="author" content="<?= $site_author; ?>">
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
  <link href="../styles/bootstrap.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css" rel="stylesheet">
  <link href="../styles/styles.css" rel="stylesheet">
  <?php if($paymentGateway == 1){ ?>
  <link href="../plugins/paymentGateway/proposals/styles/styles.css" rel="stylesheet">
  <?php } ?>
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
  <?php if($paymentGateway == 1){ ?>
    <script src="../plugins/paymentGateway/proposals/javascript/javascript.js"></script>
  <?php } ?>
  <?php if(!empty($site_favicon)){ ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
  <?php } ?>
  <script>
    var videoOrNotVideo = "<?= ($checkVideo==true)?"video":"not-video"; ?>";
    var enable_delivery = <?= $enable_delivery; ?>;
  </script>
</head>
<body class="is-responsive">
<?php require_once("../includes/user_header.php"); ?>
<?php
if($d_proposal_status == "draft"){
require_once("sections/createProposalNav.php"); 
}else{
require_once("sections/editProposalNav.php"); 
}
?>
<div class="container mt-5 mb-5"><!--- container mt-5 Starts --->
  <div class="row"><!--- row Starts --->
    <div class="col-xl-8 col-lg-10 col-md-12"><!--- col-xl-8 Starts --->
      <div class="tab-content card card-body"><!--- tab-content Starts --->
        <div class="tab-pane fade <?php if(!isset($_GET['video']) AND !isset($_GET['instant_delivery']) and !isset($_GET['publish'])){ echo " show active"; } ?>" id="overview">
          <?php include("sections/edit/overview.php"); ?>
        </div>
        <?php if($videoPlugin == 1){ ?>
          <div class="tab-pane fade <?php if(isset($_GET['video'])){ echo "show active"; } ?>" id="video">
            <?php include("../plugins/videoPlugin/proposals/sections/edit/video.php"); ?>
          </div>
        <?php } ?>
        <div class="tab-pane fade <?php if(isset($_GET['instant_delivery'])){ echo "show active"; } ?>" id="instant-delivery">
          <?php include("sections/edit/instant_delivery.php"); ?>
        </div>
        <div class="tab-pane fade" id="pricing">
         <?php include("sections/edit/pricing.php"); ?>
        </div>
        <div class="tab-pane fade" id="description">
          <?php include("sections/edit/description.php"); ?>
        </div>
        <div class="tab-pane fade" id="requirements">
          <?php include("sections/edit/requirements.php"); ?>
        </div>
        <div class="tab-pane fade" id="gallery">
          <?php include("sections/edit/gallery.php"); ?>
        </div>
        <?php if($d_proposal_status == "draft"){ ?>
        <div class="tab-pane fade <?php if(isset($_GET['publish'])){ echo "show active"; } ?>" id="publish">
          <?php include("sections/edit/publish.php"); ?>
        </div>
        <?php } ?>
        
        <input type="hidden" name="section" value="<?= (isset($_GET['instant_delivery']) ? "instant_delivery" : "overview"); ?>">
      </div><!--- tab-content Ends --->
    </div><!--- col-md-8 Ends --->
  </div><!--- row Ends --->
</div><!--- container mt-5 Ends --->

<?php require_once("sections/insertimageModal.php"); ?>
<div id="featured-proposal-modal"></div>

<?php 
  if($paymentGateway == 1){
    include("../plugins/paymentGateway/proposals/addVideoModal.php");
  }
?>

<script>
$(document).ready(function(){

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var e = ""+e.target+"";
    var e = e.replace('<?= $site_url; ?>/proposals/edit_proposal?proposal_id=<?= $proposal_id; ?>#', '');
    var e = e.replace('<?= $site_url; ?>/proposals/edit_proposal?proposal_id=<?= $proposal_id; ?>&video#', '');
    var e = e.replace('<?= $site_url; ?>/proposals/edit_proposal?proposal_id=<?= $proposal_id; ?>&instant_delivery#', '');
    $("input[type='hidden'][name='section']").val(e);
  });



  function check_proposal(form_data){
    form_data.append('proposal_id',<?= $proposal_id; ?>);
      $.ajax({
        method: "POST",
        url: "ajax/check/overview",
        data: form_data,
        dataType: 'json',
        async: false,cache: false,contentType: false,processData: false
      }).done(function(data){
        console.log(data);
        if(data === true){
          $('#wait').removeClass("loader");        
          overViewRequest(form_data, data);        
        }else{        
          processOverViewRequest(form_data, data);
        }  
      });
  }

  function processOverViewRequest(form_data, status){    
    form_data.append('change_status', status);
    $.ajax({
      method: "POST",
      url: "ajax/save_proposal",
      data: form_data,
      async: false,cache: false,contentType: false,processData: false
    }).done(function(data){      
      if(data == "video" || data == "not-video"){
        videoOrNotVideo = data;
      }

      $('#wait').removeClass("loader");
      if(data == "error"){
        swal({type: 'warning',text: 'You Must Need To Fill Out All Fields Before Updating The Details.'});
      }else if(data == "error_title"){
        swal({type: 'warning',text: 'Opps! Your Already Made A Proposal With Same Title Try Another.'});
      }else if(data == "error_img"){
        swal({type: 'warning',text: 'You Must Need To Add At Least 1 Image In Proposal To Continue.'});
      }else if(data != "error" || data != "error_img"){
        swal({
          type: 'success',
          text: 'Details Saved.',
          timer: 1000,
          onOpen: function(){
            swal.showLoading();
          }
        }).then(function(){
          var section = $("input[type='hidden'][name='section']");
          var current_section = $("input[type='hidden'][name='section']").val();

          if(data == "video"){
            $('#tabs a[href="#instant-delivery"]').addClass('d-none');
            $('#tabs a[href="#pricing"]').addClass('d-none');
            $('#tabs a[href="#video"]').removeClass('d-none');
            $('#tabs a[href="#requirements"]').removeClass('d-none');
            enable_delivery = 0;
          }else if(data == "not-video"){
            $('#tabs a[href="#instant-delivery"]').removeClass('d-none');
            $('#tabs a[href="#pricing"]').removeClass('d-none');
            $('#tabs a[href="#video"]').addClass('d-none');
          }

          if(current_section == "overview"){
            $('#overview').removeClass('show active');
            if(data == "video"){
              section.val("video");
              <?php if($d_proposal_status == "draft"){ ?>
                $('#video').addClass('show active');
                $('#tabs a[href="#video"]').addClass('active');
              <?php }else{ ?> 
                $('.nav a[href="#video"]').tab('show'); 
              <?php } ?>
            }else{
              section.val("instant-delivery");
              <?php if($d_proposal_status == "draft"){ ?>
                $('#instant-delivery').addClass('show active');
                $('#tabs a[href="#instant-delivery"]').addClass('active');
              <?php }else{ ?> 
                $('.nav a[href="#instant-delivery"]').tab('show'); 
              <?php } ?>
            }
          }else if(current_section == "description"){
            if(enable_delivery == 1){
              section.val("gallery");
            }else{
              section.val("requirements");
            }
            <?php if($d_proposal_status == "draft"){ ?>
              
              $('#description').removeClass('show active');
              
              if(enable_delivery == 1){
                $('#gallery').addClass('show active');
                $('#tabs a[href="#gallery"]').addClass('active');
              }else{
                $('#requirements').addClass('show active');
                $('#tabs a[href="#requirements"]').addClass('active');
              }

            <?php }else{ ?>

              if(enable_delivery == 1){
                $('.nav a[href="#gallery"]').tab('show');
              }else{
                $('.nav a[href="#requirements"]').tab('show');
              }
            
            <?php } ?>
          }else if(current_section == "requirements"){
            section.val("gallery");
            <?php if($d_proposal_status == "draft"){ ?>
              $('#requirements').removeClass('show active');
              $('#gallery').addClass('show active');
              $('#tabs a[href="#gallery"]').addClass('active');
            <?php }else{ ?> 
              $('.nav a[href="#gallery"]').tab('show'); 
            <?php } ?>
          }else if(current_section == "gallery"){
            <?php if($d_proposal_status == "draft"){ ?>
              $('#gallery').removeClass('show active');
              $('#publish').addClass('show active');
              $('#tabs a[href="#publish"]').addClass('active');
            <?php } ?>
          }
          
        });
      }
    });

  }

  function overViewRequest(form_data, status=false){    
    form_data.append('proposal_id',<?= $proposal_id; ?>);
    if(status == true){
      swal({
          title: "Are you sure?",
          text: "You have made some changes, your porposal would be set as pending state.",
          // text: "You have made some changes, your porposal would be set as pending state. and admin have to approve it again.",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, I understand continue.",
          closeOnConfirm: false
      }).then(function (isConfirm) {          
          if(isConfirm.dismiss == 'cancel'){            
            return;
          }else if (isConfirm.value == true){
            processOverViewRequest(form_data, status);
          }
      });
    }
  }


  $(".proposal-form").on('submit', function(event){    
    event.preventDefault();
    var form_data = new FormData(this);
    form_data.append('proposal_id',<?= $proposal_id; ?>);
    $('#wait').addClass("loader");
    check_proposal(form_data);

  });

  <?php if($d_proposal_enable_referrals == "no"){ ?>  
    $(".proposal_referral_money input").attr("min","0");
    $('.proposal_referral_money').hide();
  <?php } ?>

  $(".proposal_enable_referrals").change(function(){
    var value = $(this).val();
    if(value == "yes"){
    $(".proposal_referral_money input").attr("min","1");
    $('.proposal_referral_money').show();
    }else if(value == "no"){
    $('.proposal_referral_money').hide();
    $(".proposal_referral_money input").attr("min","0");
    }
  });

  $(document).on("click",".pricing", function(event){
    var value = $(this).val();
    if(this.checked){

      $('.packages').hide();
      $('.add-attribute').hide();
      $('.proposal-price').show();
      // $('.proposal-price input[name="proposal_price"]').attr('min',<?= $min_proposal_price; ?>);
      $('.packages input[name="proposal_packages[1][price]"]').attr('min',0);
      $('.packages input[name="proposal_packages[2][price]"]').attr('min',0);
      $('.packages input[name="proposal_packages[3][price]"]').attr('min',0);

    }else{

      $('.packages').show();
      $('.add-attribute').show();
      $('.proposal-price').hide();
      $('.proposal-price input[name="proposal_price"]').val(0);
      $('.proposal-price input[name="proposal_price"]').attr('min',0);
      $('.packages input[name="proposal_packages[1][price]"]').attr('min',<?= $min_proposal_price; ?>);
      $('.packages input[name="proposal_packages[2][price]"]').attr('min',<?= $min_proposal_price; ?>);
      $('.packages input[name="proposal_packages[3][price]"]').attr('min',<?= $min_proposal_price; ?>);

    }
  });

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

  $('textarea[name="proposal_desc"]').summernote({
    placeholder: 'Write Your Description Here.',
    height: 200,
  });

});
</script>
<?php require_once("../includes/footer.php"); ?>
<script src="../js/tagsinput.js"></script>
</body>
</html>