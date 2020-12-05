<?php 

$form_errors = Flash::render("form_errors");
$form_data = Flash::render("form_data");
if (empty($form_data)) {
  $form_data = $input->post();
}

?>

<form action="#" method="post" class="proposal-form"><!--- form Starts -->

<div class="form-group row"><!--- form-group row Starts --->
<div class="col-md-3"><?= $lang['label']['proposal_title']; ?></div>
<div class="col-md-9"><textarea name="proposal_title" rows="3" required="" placeholder="I Will" class="form-control"></textarea></div>
<small class="form-text text-danger"><?= ucfirst(@$form_errors['proposal_description']); ?></small>
</div><!--- form-group row Ends --->

<div class="form-group row"><!--- form-group row Starts --->
<div class="col-md-3"> <?= $lang['label']['category']; ?> </div>
<div class="col-md-9">
<select name="proposal_cat_id" id="category" class="form-control mb-3"  required="">
<option value="" class="hidden"> Select A Category </option>
<?php 
  $get_cats = $db->select("categories");
  while($row_cats = $get_cats->fetch()){
  $cat_id = $row_cats->cat_id;
  $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id, "language_id" => $siteLanguage));
  $cat_title = $get_meta->fetch()->cat_title;
?>
<option <?php if(@$form_data['proposal_cat_id'] == $cat_id){ echo "selected"; } ?> value="<?= $cat_id; ?>"> <?= $cat_title; ?> </option>
<?php } ?>
</select>
<small class="form-text text-danger"><?= ucfirst(@$form_errors['proposal_cat_id']); ?></small>
<select name="proposal_child_id" id="sub-category" class="form-control" required="">
<option value="" class="hidden"> Select A Sub Category </option>
<?php if(@$form_data['proposal_child_id']){ ?>
<?php
  $get_c_cats = $db->select("categories_children",array("child_parent_id"=> $form_data['proposal_cat_id']));
  while($row_c_cats = $get_c_cats->fetch()){
  $child_id = $row_c_cats->child_id;
  $get_meta = $db->select("child_cats_meta",array("child_id"=>$child_id,"language_id"=>$siteLanguage));
  $row_meta = $get_meta->fetch();
  $child_title = $row_meta->child_title;
  echo "<option ".($form_data['proposal_cat_id'] == $child_id ? "selected" : "")." value='$child_id'> $child_title </option>";
  }
?>
<?php } ?>
</select>
</div>
</div><!--- form-group row Ends --->

<div class="form-group row d-none"><!--- form-group row Starts --->
<!-- <div class="col-md-3"><?= $lang['label']['delivery_time']; ?></div>
<div class="col-md-9">
<select name="delivery_id" class="form-control" required="">
<?php
$get_delivery_times = $db->select("delivery_times");
while($row_delivery_times = $get_delivery_times->fetch()){
$delivery_id = $row_delivery_times->delivery_id;
$delivery_proposal_title = $row_delivery_times->delivery_proposal_title;
?>
<option value="<?= $delivery_id; ?>" <?php if(@$form_data['delivery_id'] == $delivery_proposal_title){ echo "selected"; } ?>><?= $delivery_proposal_title; ?></option>
<?php } ?>
</select>
</div>
<small class="form-text text-danger"><?= ucfirst(@$form_errors['delivery_id']); ?></small> -->
</div><!--- form-group row Ends --->


<div class="form-group row d-none"><!--- form-group row Starts --->
  <!-- <div class="col-md-3"><?= $lang['label']['proposal_revisions']; ?></div>
  <div class="col-md-9">
    <select name="proposal_revisions" class="form-control" required="">
    <?php 
      foreach ($revisions as $key => $rev) {
        echo "<option value='$key'>$rev</option>";
      }
    ?>
    </select>
    <small class="muted">Set this to 0 if this proposal is for instant delivery.</small>
  </div>
  <small class="form-text text-danger"><?= ucfirst(@$form_errors['proposal_revisions']); ?></small> -->
</div><!--- form-group row Ends --->


<?php if($enable_referrals == "yes"){ ?>

<div class="form-group row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> <?= $lang['label']['enable_referrals']; ?> </label>
<div class="col-md-9">
<select name="proposal_enable_referrals" class="proposal_enable_referrals form-control">
<?php if(@$form_data['proposal_enable_referrals'] == "yes"){ ?>
<option value="yes"> Yes </option>
<option value="no"> No </option>
<?php }else{ ?>
<option value="no"> No </option>
<option value="yes"> Yes </option>
<?php } ?>
</select>
<small>If enabled, other users can promote your proposal by sharing it on different platforms.</small>
<small class="form-text text-danger"><?= ucfirst(@$form_errors['proposal_enable_referrals']); ?></small>
</div>
</div><!--- form-group row Ends --->

<div class="form-group row proposal_referral_money"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> <?= $lang['label']['promotion_commission']; ?> </label>
<div class="col-md-9">
<input type="number" name="proposal_referral_money" class="form-control" min="1" value="<?= @$form_data['proposal_referral_money']; ?>" placeholder="Figure should be in percentage e.g 20">
<small>Figure should be in percentage. E.g 20 is the same as 20% from the sale of this proposal.</small>
<br>
<small> When another user promotes your proposal, how much would you like that user to get from the sale? (in percentage) </small>
</div>
</div><!--- form-group row Ends --->
<?php } ?>

<div class="form-group row"><!--- form-group row Starts --->
<div class="col-md-3"><?= $lang['label']['tags']; ?></div>
<div class="col-md-9">
  <input type="text" name="proposal_tags" class="form-control" data-role="tagsinput">
  <small class="form-text text-danger"><?= ucfirst(@$form_errors['proposal_tags']); ?></small>
</div>
</div><!--- form-group row Ends --->
<div class="form-group text-right mb-0"><!--- form-group Starts --->
  <a href="view_proposals" class="btn btn-secondary"><?= $lang['button']['cancel']; ?></a>
  <input class="btn btn-success" type="submit" name="submit" value="<?= $lang['button']['save_continue']; ?>">
</div><!--- form-group Starts --->

</form><!--- form Ends -->
<?php 

function insertPackages($proposal_id){
  global $db;
  $insertPackage1 = $db->insert("proposal_packages",array("proposal_id"=>$proposal_id,"package_name"=>'Basic',"price"=>5));
  $insertPackage2 = $db->insert("proposal_packages",array("proposal_id"=>$proposal_id,"package_name"=>'Standard',"price"=>10));
  $insertPackage3 = $db->insert("proposal_packages",array("proposal_id"=>$proposal_id,"package_name"=>'Advance',"price"=>15));
  if($insertPackage3){return true;}
}

include("sanitize_url.php");

if(isset($_POST['submit'])){

  $rules = array(
  "proposal_title" => "required",
  "proposal_cat_id" => "required",
  "proposal_child_id" => "required",
  "proposal_tags" => "required",);

  $messages = array("proposal_cat_id" => "you must need to select a category","proposal_child_id" => "you must need to select a child category","proposal_enable_referrals"=>"you must need to enable or disable proposal referrals.","proposal_img1"=>"Proposal Image 1 Is Required.");
  $val = new Validator($_POST,$rules,$messages);

  if($val->run() == false){
    Flash::add("form_errors",$val->get_all_errors());
    Flash::add("form_data",$_POST);
    echo "<script> window.open('create_proposal','_self');</script>";
  }else{
    $proposal_title = $input->post('proposal_title');

    $sanitize_url = proposalUrl($proposal_title);
    $select_proposals = $db->select("proposals",array("proposal_seller_id" => $login_seller_id,"proposal_url" => $sanitize_url));
    $count_proposals = $select_proposals->rowCount();
    if($count_proposals ==  1){
      echo "<script>
      swal({
      type: 'warning',
      text: 'Opps! Your Already Made A Proposal With Same Title Try Another.',
      })</script>";
    }else{
      $proposal_referral_code = mt_rand();
      $get_general_settings = $db->select("general_settings");   
      $row_general_settings = $get_general_settings->fetch();
      $proposal_email = $row_general_settings->proposal_email;
      $site_email_address = $row_general_settings->site_email_address;
      $site_logo = $row_general_settings->site_logo;

      $data = $input->post();
      unset($data['submit']);
      $data['proposal_url'] = $sanitize_url;
      $data['proposal_seller_id'] = $login_seller_id;
      $data['proposal_featured'] = "no";
      if($enable_referrals == "no"){ 
        $data['proposal_enable_referrals'] = "no"; 
      }

      $data['proposal_price'] = 0;
      $data['delivery_id'] = $db->query("select * from delivery_times")->fetch()->delivery_id;
      $data['level_id'] = $login_seller_level;
      $data['language_id'] = $login_seller_language;
      $data['proposal_status'] = "draft";

      $insert_proposal = $db->insert("proposals",$data);

      if($insert_proposal){

        $proposal_id = $db->lastInsertId();
        
        $db->insert("instant_deliveries",["proposal_id"=>$proposal_id]);

        if($videoPlugin == 1){
          $cat_id = $input->post("proposal_cat_id");
          $child_id = $input->post("proposal_child_id");
          include("$dir/plugins/videoPlugin/proposals/checkVideo.php");
        }else{
          $redirect = "instant_delivery";
        }

        insertPackages($proposal_id);

        echo "<script>
        swal({
          type: 'success',
          text: 'Details Saved.',
          timer: 2000,
          onOpen: function(){
            swal.showLoading()
          }
        }).then(function(){
          window.open('edit_proposal?proposal_id=$proposal_id&$redirect','_self')
        });
        </script>";
      }
    }

  }

}

?>