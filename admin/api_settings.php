<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

$get_api_settings = $db->select("api_settings");
$row_api_settings = $get_api_settings->fetch();
$enable_s3 = $row_api_settings->enable_s3;
$s3_access_key = $row_api_settings->s3_access_key;
$s3_access_sceret = $row_api_settings->s3_access_sceret;
$s3_bucket = $row_api_settings->s3_bucket;
$s3_region = $row_api_settings->s3_region;
$s3_domain = $row_api_settings->s3_domain;

$get_api = $db->select("currency_converter_settings");
$row_api = $get_api->fetch();
$enable_converter = $row_api->enable;
$api_key = $row_api->api_key;
$main_currency = $row_api->main_currency;
$server = $row_api->server;

?>

  <div class="breadcrumbs">
      <div class="col-sm-4">
          <div class="page-header float-left">
              <div class="page-title">
                  <h1><i class="menu-icon fa fa-cog"></i> Settings / API Settings</h1>
              </div>
          </div>
      </div>
      <div class="col-sm-8">
          <div class="page-header float-right">
              <div class="page-title">
                  <ol class="breadcrumb text-right">
                      <li class="active">API Settings</li>
                  </ol>
              </div>
          </div>
      </div>
  </div>

<div class="container pt-3">

   <div class="row"><!--- 2 row Starts --->

     <div class="col-lg-12"><!--- col-lg-12 Starts --->

         <div class="card mb-5"><!--- card mb-5 Starts --->

             <div class="card-header"><!--- card-header Starts --->

                 <h4 class="h4">

                     <i class="fa fa-money fa-fw"></i> Amazon S3 Settings

                 </h4>

             </div><!--- card-header Ends --->

        	<div class="card-body"><!--- card-body Starts --->

               <form action="" method="post"><!--- form Starts --->

               <div class="form-group row"><!-- form-group row Starts -->

               <label class="col-md-3 control-label"> Enable S3 : </label>

               <div class="col-md-6">

               <input type="checkbox" name="enable_s3" id="enable_s3" value="1" <?php if($enable_s3 == 1){ echo "checked"; } ?>>
               <label for="enable_s3">Enable storing images/files on Amazon S3</label>

               </div>

               </div><!-- form-group row Ends -->

               <div class="form-group row"><!-- form-group row Starts -->

               <label class="col-md-3 control-label"> Access Key : </label>

               <div class="col-md-6">
                  <input type="text" name="s3_access_key" class="form-control" value="<?= $s3_access_key; ?>">
               </div>

               </div><!-- form-group row Ends -->

               <div class="form-group row"><!-- form-group row Starts -->

               <label class="col-md-3 control-label"> Access Sceret : </label>

               <div class="col-md-6">
                  <input type="text" name="s3_access_sceret" class="form-control" value="<?= $s3_access_sceret; ?>">
               </div>

               </div><!-- form-group row Ends -->

               <div class="form-group row"><!-- form-group row Starts -->

               <label class="col-md-3 control-label"> Bucket Name : </label>

               <div class="col-md-6">
                  <input type="text" name="s3_bucket" class="form-control" value="<?= $s3_bucket; ?>">
               </div>

               </div><!-- form-group row Ends -->

               <div class="form-group row"><!-- form-group row Starts -->

               <label class="col-md-3 control-label"> Bucket Region Code : </label>

               <div class="col-md-6">
                  <input type="text" name="s3_region" class="form-control" value="<?= $s3_region; ?>">
               </div>

               </div><!-- form-group row Ends -->

               <div class="form-group row"><!-- form-group row Starts -->

               <label class="col-md-3 control-label"> S3 Domain : </label>

               <div class="col-md-6">

               <input type="text" name="s3_domain" class="form-control" value="<?= $s3_domain; ?>">

               <small class="form-text text-muted">
                Replace the default s3 domain and path with your Cloudfront domain or any domain.<br>
                Note: leave this field empty to use default s3 domain.
               </small>

               </div>

               </div><!-- form-group row Ends -->

               <div class="form-group row"><!--- form-group row Starts --->

                  <label class="col-md-3 control-label"></label>

                  <div class="col-md-6">
                    <input type="submit" name="update_api_settings" value="Update Api Settings" class="btn btn-success form-control"/>
                  </div>

               </div><!--- form-group row Ends --->

               </form><!--- form Ends --->

            </div><!--- card-body Ends --->

         </div><!--- card mb-5 Ends --->

      </div><!--- col-lg-12 Ends --->

   </div><!--- 2 row Ends --->



  <div class="row"><!--- 1 row Starts --->
    <div class="col-lg-12"><!--- col-lg-12 Starts --->
      <div class="card mb-5"><!--- card mb-5 Starts --->
        <div class="card-header"><!--- card-header Starts --->
          <h4 class="h4"><i class="fa fa-money"></i> Currency Covert Settings </h4>
        </div><!--- card-header Ends --->
        <div class="card-body"><!--- card-body Starts --->
          <form method="post" enctype="multipart/form-data"><!--- form Starts --->

            <div class="form-group row"><!--- form-group row Starts --->
            <label class="col-md-3 control-label"> Enable Currency Converter : </label>
            <div class="col-md-6">
              <select name="enable" class="form-control" required="">
                <option value="1"> Yes </option>
                <option value="0" <?= ($enable_converter == 0)?'selected':''; ?>> No </option>
              </select>
              <small class="form-text text-muted">Enable or disable currency converter on the website.</small>
            </div>
            </div><!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
            <label class="col-md-3 control-label"> Api Key : </label>
            <div class="col-md-6">
              <input type="text" name="api_key" class="form-control" value="<?= $api_key; ?>" required=""/>
              <small>
                <a class="btn-link" href="https://www.currencyconverterapi.com/" target="_blank">You Can Get Api Key From Here.</a>
              </small>
            </div>
            </div><!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
            <label class="col-md-3 control-label"> Default Currency Code : </label>
            <div class="col-md-6">
              <input type="text" name="main_currency" class="form-control" value="<?= $main_currency; ?>" required=""/>
              <small class="text-muted">Enter Your Website Default Currency Code Here.</small>
            </div>
            </div><!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
            <label class="col-md-3 control-label"> Server Url : </label>
            <div class="col-md-6">
              <input type="text" name="server" placeholder="Server Url" class="form-control" value="<?= $server; ?>">
              <!-- <small class="form-text text-muted">Select Your Server</small>
              <small>
                <a class="btn-link" href="https://www.currencyconverterapi.com/" target="_blank">You Can Get Api Key From Here.</a>
              </small> -->
            </div>
            </div><!--- form-group row Ends --->

            <div class="form-group row"><!--- form-group row Starts --->
            <label class="col-md-3 control-label"></label>
            <div class="col-md-6">
              <input type="submit" name="update_coverter_settings" class="form-control btn btn-success" value="Update Currency Converter Settings">
            </div>
            </div><!--- form-group row Ends --->

          </form><!--- form Ends --->
        </div><!--- card-body Ends --->
      </div><!--- card mb-5 Ends --->
    </div><!--- col-lg-12 Ends --->
  </div><!--- 1 row Starts --->

  <?php
   
    if($videoPlugin == 1){ 
      include("../plugins/videoPlugin/admin/general_settings.php");
    }

    if($notifierPlugin == 1){ 
      include("../plugins/notifierPlugin/admin/api_settings.php");
    }

  ?>

</div><!--- container Ends --->

<?php

if(isset($_POST['update_api_settings'])){

    $data = $input->post();
    unset($data['update_api_settings']);

    if(isset($_POST["enable_s3"])){
      $data['enable_s3'] = 1;
    }else{
      $data['enable_s3'] = 0;
    }

    $update_api_settings = $db->update("api_settings",$data);

    if($update_api_settings){

      echo "<script>alert_success('Amazon S3 Settings Updated Successfully','index?api_settings');</script>";

    }

}

if(isset($_POST['update_coverter_settings'])){
  $data = $input->post();
  unset($data['update_coverter_settings']);
  $update_settings = $db->update("currency_converter_settings",$data);
  if($update_settings){
    $insert_log = $db->insert_log($admin_id,"currency_converter_settings","","updated");
    echo "<script>alert_success('Currency Converter Settings has been updated successfully.','index?api_settings');</script>";
  }
}


?>

<?php } ?>