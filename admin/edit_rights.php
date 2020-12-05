<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login.php','_self');</script>";
	
}else{

?>

<?php

if(isset($_GET['edit_rights'])){
	
$admin_id = $input->get('edit_rights');

$get_rights = $db->select("admin_rights",array("admin_id" => $admin_id));
$row_rights = $get_rights->fetch();
$a_settings = $row_rights->settings;
$a_plugins = $row_rights->plugins;
$a_pages = $row_rights->pages;
$a_blog = $row_rights->blog;
$a_video_schedules = $row_rights->video_schedules;
$a_proposals = $row_rights->proposals;
$a_accounting = $row_rights->accounting;
$a_payouts = $row_rights->payouts;
$a_reports = $row_rights->reports;
$a_inbox = $row_rights->inbox;
$a_reviews = $row_rights->reviews;
$a_buyer_requests = $row_rights->buyer_requests;
$a_restricted_words = $row_rights->restricted_words;
$a_alerts = $row_rights->notifications;
$a_cats = $row_rights->cats;
$a_delivery_times = $row_rights->delivery_times;
$a_seller_languages = $row_rights->seller_languages;
$a_seller_skills = $row_rights->seller_skills;
$a_seller_levels = $row_rights->seller_levels;
$a_customer_support = $row_rights->customer_support;
$a_coupons = $row_rights->coupons;
$a_slides = $row_rights->slides;
$a_sellers = $row_rights->sellers;
$a_slides = $row_rights->slides;
$a_terms = $row_rights->terms;
$a_orders = $row_rights->orders;
$a_referrals = $row_rights->referrals;
$a_files = $row_rights->files;
$a_knowledge_bank = $row_rights->knowledge_bank;
$a_languages = $row_rights->languages;
$a_admins = $row_rights->admins;
	
}

?>

<div class="breadcrumbs">
  <div class="col-sm-4">
      <div class="page-header float-left">
          <div class="page-title">
              <h1><i class="menu-icon fa fa-users"></i> Admins</h1>
          </div>
      </div>
  </div>
  <div class="col-sm-8">
      <div class="page-header float-right">
          <div class="page-title">
              <ol class="breadcrumb text-right">
                <li class="active">Edit Rights</li>
              </ol>
          </div>
      </div>
  </div>
</div>

<style>

.checkbox, .radio {
    position: relative;
    display: block;
    margin-top: 10px;
    margin-bottom: 10px;
}


.checkbox label, .radio label {
    min-height: 20px;
    padding-left: 20px;
    margin-bottom: 0;
    font-weight: 400;
    cursor: pointer;
}


.checkbox input[type=checkbox], .checkbox-inline input[type=checkbox], .radio input[type=radio], .radio-inline input[type=radio] {
    position: absolute;
    margin-top: 4px\9;
    margin-left: -20px;
}

input[type=checkbox], input[type=radio] {
    margin: 7px 0 0;
    margin-top: 1px\9;
    line-height: normal;
}

input[type=checkbox], input[type=radio] {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    padding: 0;
}

</style>


<div class="container"><!--- container Starts --->

<div class="row mt-2"><!--- 2 row Starts --->

<div class="col-lg-12"><!--- col-lg-12 Starts --->

<div class="card"><!--- card Starts --->

<div class="card-header"><!--- card-header Starts --->

<h4 class="h4">

<i class="fa fa-money-bill-alt"></i> Edit Rights

</h4>

</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

<form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->


<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Admin Rights : </label>

<div class="col-md-6 bg-white">

    <div class="checkbox">
      <label><input type="checkbox" name="settings" <?php if($a_settings == 1){ echo "checked"; } ?> value="1">Settings</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="plugins" <?php if($a_plugins == 1){ echo "checked"; } ?> value="1">Plugins</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="pages" <?php if($a_pages == 1){ echo "checked"; } ?> value="1">Pages</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="video_schedules" <?php if($a_video_schedules == 1){ echo "checked"; } ?> value="1">Video Schedules</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="blog" <?php if($a_blog == 1){ echo "checked"; } ?> value="1">Blog</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="proposals" <?php if($a_proposals == 1){ echo "checked"; } ?> value="1">Proposals</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="accounting" <?php if($a_accounting == 1){ echo "checked"; } ?> value="1">Accounting</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="payouts" <?php if($a_payouts == 1){ echo "checked"; } ?> value="1">Payouts</label>
    </div>
    
    <div class="checkbox">
      <label><input type="checkbox" name="inbox" <?php if($a_inbox == 1){ echo "checked"; } ?> value="1">Inbox Conversations</label>
    </div>


    <div class="checkbox">
      <label><input type="checkbox" name="restricted_words" <?php if($a_restricted_words == 1){ echo "checked"; } ?> value="1"> Restricted Words </label>
    </div>


    <div class="checkbox">
      <label><input type="checkbox" name="reports" <?php if($a_reports == 1){ echo "checked"; } ?> value="1">Reports</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="reviews" <?php if($a_reviews == 1){ echo "checked"; } ?> value="1">Reviews</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="buyer_requests" <?php if($a_buyer_requests == 1){ echo "checked"; } ?> value="1">Buyer Requests</label>
    </div>



    <div class="checkbox">
      <label><input type="checkbox" name="cats" <?php if($a_cats == 1){ echo "checked"; } ?> value="1">
      Categories And Sub Categories</label>
    </div>    

    <div class="checkbox">
      <label><input type="checkbox" name="delivery_times" <?php if($a_delivery_times == 1){ echo "checked"; } ?> value="1">Delivery Times</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="seller_languages" <?php if($a_seller_languages == 1){ echo "checked"; } ?> value="1">Seller Languages</label>
    </div>

    <div class="checkbox">
      <label><input type="checkbox" name="seller_skills" <?php if($a_seller_skills == 1){ echo "checked"; } ?> value="1">Seller Skills</label>
    </div>

    <div class="checkbox">
      <label>
      <input type="checkbox" name="customer_support" <?php if($a_customer_support == 1){ echo "checked"; } ?> value="1">Customer Support
  	</label>
    </div>

    <div class="checkbox">
      <label>
      <input type="checkbox" name="coupons" <?php if($a_coupons == 1){ echo "checked"; } ?> value="1">Coupons
  	  </label>
    </div>

    <div class="checkbox">
      <label>
      <input type="checkbox" name="slides" <?php if($a_slides == 1){ echo "checked"; } ?> value="1">Slides
  	  </label>
    </div>


    <div class="checkbox">
      <label>
      <input type="checkbox" name="terms" <?php if($a_terms == 1){ echo "checked"; } ?> value="1">Terms
      </label>
    </div>

    <div class="checkbox">
      <label>
      <input type="checkbox" name="sellers" <?php if($a_coupons == 1){ echo "checked"; } ?> value="1">Sellers
  	  </label>
    </div>


    <div class="checkbox">
      <label>
      <input type="checkbox" name="orders" <?php if($a_orders == 1){ echo "checked"; } ?> value="1">Orders
  	  </label>
    </div>

    <div class="checkbox">
      <label>
      <input type="checkbox" name="referrals" <?php if($a_referrals == 1){ echo "checked"; } ?> value="1">Referrrals
  	  </label>
    </div>

    <div class="checkbox">
      <label>
      <input type="checkbox" name="files" <?php if($a_files == 1){ echo "checked"; } ?> value="1">Files
  	  </label>
    </div>

    <div class="checkbox">
      <label>
      <input type="checkbox" name="knowledge_bank" <?php if($a_knowledge_bank == 1){ echo "checked"; } ?> value="1">Knowledge Bank
      </label>
    </div>
    
    <div class="checkbox">
      <label>
      <input type="checkbox" name="languages" <?php if($a_languages == 1){ echo "checked"; } ?> value="1">Languages
  	  </label>
    </div>

</div>

</div><!--- form-group row Ends --->

<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"></label>

<div class="col-md-6">

<input type="submit" name="update" class="btn btn-success form-control" value="Update Admin Rights">

</div>

</div><!--- form-group row Ends --->

</form><!--- form Ends --->

</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div>

<?php

if(isset($_POST['update'])){

  $data = $input->post();

  $select = $db->query("SHOW COLUMNS FROM admin_rights");
  while($column = $select->fetch(PDO::FETCH_COLUMN)){

   $data[$column] = $input->post($column);

   if($data[$column] == ""){
    $data[$column] = 0;
   }

  }

  unset($data['update']);
  unset($data['id']);
  unset($data['admin_id']);
  unset($data['admins']);

  // echo "<pre>";
  // print_r($data);
  // echo "</pre>";
  // echo $admin_id;

  $update_rights = $db->update("admin_rights",$data,array("admin_id"=>$admin_id));

  if($update_rights){
    echo "<script>alert_success('Admin Rights Has Been Updated Successfully.','index?edit_rights=$admin_id');</script>";
  }

}

?>

<?php } ?>