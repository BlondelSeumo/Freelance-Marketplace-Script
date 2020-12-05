<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{

?>
<div class="breadcrumbs">
<div class="col-sm-6">
  <div class="page-header float-left">
  <div class="page-title">
      <h1><i class="menu-icon fa fa-cog"></i> Settings / Application License</h1>
  </div>
  </div>
</div>
</div>
<div class="container pt-3">
<div class="row"><!--- 2 row Starts --->
  <div class="col-lg-12"><!--- col-lg-12 Starts --->
    <div class="card mb-5"><!--- card mb-5 Starts --->
      <div class="card-header"><!--- card-header Starts --->
          <h4 class="h4 mb-0"><i class="fa fa-money fa-fw"></i> Application License</h4>
      </div><!--- card-header Ends --->
      <div class="card-body p-0"><!--- card-body Starts --->
        <form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->
          <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-3"><!--- form-group row Starts --->
          <label class="col-md-3 control-label">License Key : </label>
          <div class="col-md-9 text-right">
          <?= $purchase_code; ?>
          </div>
          </div><!--- form-group row Ends --->
          <hr class="mt-0 mb-2">
          <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-2"><!--- form-group row Starts --->
          <label class="col-md-3 control-label"> License Type : </label>
          <div class="col-md-9 text-right">
          <?= $license_type; ?>
          </div>
          </div><!--- form-group row Ends --->
          <hr class="mt-0 mb-2">
          <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-2"><!--- form-group row Starts --->
          <label class="col-md-3 control-label"> License Domain : </label>
          <div class="col-md-9 text-right">
          <a href="<?= $website; ?>" target="_blank" style="color: green;"><?= $website; ?></a>
          </div>
          </div><!--- form-group row Ends --->
        </form><!--- form Ends --->
      </div><!--- card-body Ends --->
    </div><!--- card mb-5 Ends --->
  </div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
<?php } ?>