<div id="add" class="modal fade" ><!-- add modal fade Starts -->
  <div class="modal-dialog"><!-- modal-dialog Starts -->
  <div class="modal-content"><!-- modal-content Starts -->
  <div class="modal-header"><!-- modal-header Starts -->
  <h5 class="modal-title"> Add Coupon </h5> 
  <button class="close" data-dismiss="modal"> <span> &times; </span> </button>
  </div><!-- modal-header Ends -->
  <div class="modal-body"><!-- modal-body Starts -->
  <form action="" method="post"><!--- form Starts --->
  <div class="form-group row"><!--- form-group row Starts --->
  <label class="col-md-4 control-label"> Coupon Title : </label>
  <div class="col-md-8">
  <input type="text" name="coupon_title" class="form-control" required>
  </div>
  </div><!--- form-group row Ends --->

  <div class="form-group row"><!--- form-group row Starts --->
    <label class="col-md-4 control-label"> Coupon Price : </label>
    <div class="col-md-4 pr-0"><!--- col-md-4 Starts --->
      <select name="coupon_type" class="coupon-type form-control" required>
        <option value="fixed_price"> Fixed Price </option>
        <option value="discount_price"> Discount Percentage </option>
      </select>
    </div><!--- col-md-4 Ends --->
    <div class="col-md-4"><!--- col-md-4 Starts --->
     <div class="input-group">
        <span class="input-group-addon"> <b><?= $s_currency; ?></b> </span>
        <input type="number" name="coupon_price" class="form-control" value="1" min="1" required>
     </div>  
    </div><!--- col-md-4 Ends --->
  </div><!--- form-group row Ends --->

  <div class="form-group row"><!--- form-group row Starts --->
  <label class="col-md-4 control-label"> Coupon Code : </label>
  <div class="col-md-8">
  <input type="text" name="coupon_code" class="form-control" required>
  </div>
  </div><!--- form-group row Ends --->
  <div class="form-group row"><!--- form-group row Starts --->
  <label class="col-md-4 control-label"> Coupon Limit : </label>
  <div class="col-md-8">
  <input type="number" name="coupon_limit" class="form-control" value="1" min="1">
  </div>
  </div><!--- form-group row Ends --->
  <div class="form-group row"><!--- form-group row Starts --->
  <label class="col-md-4 control-label"></label>
  <div class="col-md-8">
  <input type="submit" name="add" class="btn btn-success form-control" value="Insert Coupon">
  </div>
  </div><!--- form-group row Ends --->
  </form><!--- form Ends --->
  </div><!-- modal-body Ends -->
  </div><!-- modal-content Ends -->
  </div><!-- modal-dialog Ends -->
</div><!-- add modal fade Ends -->

<?php
$proposal_id = $input->get('proposal_id');
$get_coupons = $db->select("coupons",array("proposal_id"=>$proposal_id));
while($row_coupons = $get_coupons->fetch()){
$coupon_id = $row_coupons->coupon_id;
?>
<div id="edit-<?= $coupon_id; ?>" class="modal fade" ><!-- edit modal fade Starts -->
<div class="modal-dialog"><!-- modal-dialog Starts -->
<div class="modal-content"><!-- modal-content Starts -->
<div class="modal-header"><!-- modal-header Starts -->
<h5 class="modal-title"> Edit Coupon </h5> 
<button class="close" data-dismiss="modal"> <span> &times; </span> </button>
</div><!-- modal-header Ends -->
<div class="modal-body"><!-- modal-body Starts -->
  <form action="" method="post"><!--- form Starts --->
    <input type="hidden" name="coupon_id" value="<?= $coupon_id; ?>">
    <div class="form-group row"><!--- form-group row Starts --->
    <label class="col-md-4 control-label"> Coupon Title : </label>
    <div class="col-md-8">
    <input type="text" name="coupon_title" class="form-control" required value="<?= $row_coupons->coupon_title; ?>">
    </div>
    </div><!--- form-group row Ends --->
    <div class="form-group row"><!--- form-group row Starts --->
      <label class="col-md-4 control-label"> Coupon Price : </label>
      <div class="col-md-4 pr-0">
        <select name="coupon_type" class="coupon-type form-control">
          <?php if($row_coupons->coupon_type == "fixed_price"){ ?>
          <option value="fixed_price"> Fixed Price </option>
          <option value="discount_price"> Discount Percentage </option>
          <?php }else{ ?>
          <option value="discount_price"> Discount Percentage </option>
          <option value="fixed_price"> Fixed Price </option>
          <?Php } ?>
        </select>
      </div>
      <div class="col-md-4">
        <div class="input-group">
          <?php if($row_coupons->coupon_type == "fixed_price"){ ?>
          <span class="input-group-addon"><b><?= $s_currency; ?></b></span>
          <input type="number" name="coupon_price" class="form-control" value="<?= $row_coupons->coupon_price; ?>" min="1" required>
          <?php }else{ ?> 
          <span class="input-group-addon"><b>%</b></span>
          <input type="number" name="coupon_price" class="form-control" value="<?= $row_coupons->coupon_price; ?>" min="1" required>
          <?php } ?>
        </div>
      </div>
    </div><!--- form-group row Ends --->
    <div class="form-group row"><!--- form-group row Starts --->
    <label class="col-md-4 control-label"> Coupon Code : </label>
    <div class="col-md-8">
    <input type="text" name="coupon_code" class="form-control" required value="<?= $row_coupons->coupon_code; ?>">
    </div>
    </div><!--- form-group row Ends --->
    <div class="form-group row"><!--- form-group row Starts --->
    <label class="col-md-4 control-label"> Coupon Limit : </label>
    <div class="col-md-8">
    <input type="number" name="coupon_limit" class="form-control" value="<?= $row_coupons->coupon_limit; ?>" min="1">
    </div>
    </div><!--- form-group row Ends --->
    <div class="form-group row"><!--- form-group row Starts --->
    <label class="col-md-4 control-label"></label>
    <div class="col-md-8">
    <input type="submit" name="update" class="btn btn-success form-control" value="Update Coupon">
    </div>
    </div><!--- form-group row Ends --->
  </form><!--- form Ends --->
</div><!-- modal-body Ends -->
</div><!-- modal-content Ends -->
</div><!-- modal-dialog Ends -->
</div><!-- edit modal fade Ends -->
<?php } ?>