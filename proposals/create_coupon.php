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
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
<title><?= $site_name; ?> - <?= $lang["titles"]["create_coupon"]; ?></title>
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
<?php require_once("../includes/user_header.php"); ?>
<div class="container mb-4 mt-4"><!--- container Starts --->
<div class="row">
<div class="col-md-10">
        <div class="card"><!--- card Starts --->
            <div class="card-header"><!--- card-header Starts --->
                <h4 class="h4"><?= $lang['create_coupon']['title']; ?></h4>
            </div>
            <!--- card-header Ends --->
            <div class="card-body">
                <!--- card-body Starts --->
                <form action="" method="post">
                    <!--- form Starts --->
                    <div class="form-group row">
                        <!--- form-group row Starts --->
                        <label class="col-md-3 control-label"> <?= $lang['label']['coupon_title']; ?> </label>
                        <div class="col-md-6">
                            <input type="text" name="coupon_title" class="form-control" required>
                        </div>
                    </div>
                    <!--- form-group row Ends --->
                    <div class="form-group row"><!--- form-group row Starts --->
                      <label class="col-md-3 control-label"> <?= $lang['label']['coupon_price']; ?> </label>
                      <div class="col-md-3">
                        <select name="coupon_type" class="coupon-type form-control" required>
                          <option value="fixed_price"> Fixed Price </option>
                          <option value="discount_price"> Discount Percentage </option>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <div class="input-group">
                          <span class="input-group-addon"> <b><?= $s_currency; ?></b> </span>
                          <input type="number" name="coupon_price" class="form-control" value="1" min="1" required>
                        </div>  
                      </div>
                    </div><!--- form-group row Ends --->
                    <div class="form-group row">
                        <!--- form-group row Starts --->
                        <label class="col-md-3 control-label"> <?= $lang['label']['coupon_code']; ?> </label>
                        <div class="col-md-6">
                            <input type="text" name="coupon_code" class="form-control" required>
                        </div>
                    </div>
                    <!--- form-group row Ends --->
                    <div class="form-group row">
                        <!--- form-group row Starts --->
                        <label class="col-md-3 control-label"> <?= $lang['label']['coupon_limit']; ?> </label>
                        <div class="col-md-6">
                           <input type="number" name="coupon_limit" class="form-control" value="1" min="1" required>
                        </div>
                    </div>
                    <!--- form-group row Ends --->
                    <div class="form-group row">
                        <!--- form-group row Starts --->
                        <label class="col-md-3 control-label"> <?= $lang['label']['select_proposal']; ?> </label>
                        <div class="col-md-6">
                            <select name="proposal_id" class="form-control" required>
                              <option value=""> Select A Proposal/Service to Apply Coupon </option>
                              <?php 
                                $get_proposals = $db->select("proposals",array("proposal_status"=>'active',"proposal_seller_id"=>$login_seller_id));
                                while($row_proposals = $get_proposals->fetch()){
                                  $proposal_id = $row_proposals->proposal_id;
                                  $proposal_title = $row_proposals->proposal_title;
                                  echo "<option value='$proposal_id'>$proposal_title</option>";
                                }
                              ?>
                          </select>
                        </div>
                    </div>
                    <!--- form-group row Ends --->
                    <div class="form-group row">
                        <!--- form-group row Starts --->
                        <label class="col-md-3 control-label"></label>
                        <div class="col-md-6">
                          <input type="submit" name="submit" class="btn btn-success form-control" value="<?= $lang['button']['create_coupon']; ?>">
                        </div>
                    </div>
                    <!--- form-group row Ends --->
                </form>
                <!--- form Ends --->
            </div>
            <!--- card-body Ends --->
        </div>
        <!--- card Ends --->
</div><!--- col-md-10 Ends --->
</div><!--- row Ends --->
</div><!--- container Ends --->
<script>
$(document).ready(function(){
  $('.coupon-type').change(function(){
  if($(this).val() == 'fixed_price'){ 
    $('.input-group-addon b').html('$');
  }
  if($(this).val() == 'discount_price'){ 
    $('.input-group-addon b').html('%');
  }
  });  
});
</script>
<?php
if(isset($_POST['submit'])){
  $rules = array(
  "coupon_title" => "required",
  "coupon_price" => "number|required",
  "coupon_type" => "required",
  "coupon_code" => "required",
  "coupon_limit" => "number|required",
  "proposal_id" => "required");
  $messages = array("proposal_id" => "You must need to select a proposal for coupon.");
  $val = new Validator($_POST,$rules,$messages);
  if($val->run() == false){
    Flash::add("form_errors",$val->get_all_errors());
    Flash::add("form_data",$_POST);
    echo "<script> window.open('index?insert_coupon','_self');</script>";
  }else{
    $proposal_id = $input->post('proposal_id');
    $coupon_code = $input->post('coupon_code');
    $data = $input->post();
    unset($data['submit']);
    $check_coupons = $db->count("coupons",array("coupon_code" => $coupon_code));
    if($check_coupons == 1){
      echo "<script>alert('Coupon Code Has Been Applied Already.');</script>";
    }else{
      $insert_coupon = $db->insert("coupons",$data);
      if($insert_coupon){
      $insert_id = $db->lastInsertId();
      echo "<script>alert('Coupon code created successfully.');</script>";
      echo "<script>window.open('view_coupons?proposal_id=$proposal_id','_self');</script>";
      } 
    }
  }
}
?>
<?php require_once("../includes/footer.php"); ?>
<script src="../js/tagsinput.js"></script>
</body>
</html>