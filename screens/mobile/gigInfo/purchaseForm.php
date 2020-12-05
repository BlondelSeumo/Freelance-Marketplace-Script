
<h4 class="text-success mb-3">Order Details</h4>

<h5><i class="fa fa-clock-o"></i> &nbsp; <?= $delivery_proposal_title; ?> Delivery Time </h5>

<h5 class="mt-2 mb-3"><i class="fa fa-refresh"></i> &nbsp; 

	<?php
		if($proposal_revisions != "unlimited"){
		  echo $proposal_revisions.' Revisions';
		}else{
		  echo "Unlimited Revisions";
		}
	?>

</h5>

<span class="total-price-num d-none"><?= $proposal_price; ?></span>

<?php if(!isset($_SESSION['seller_user_name'])){ ?>
<a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-order primary mb-3">
	<i class="fa fa-shopping-cart"></i> &nbsp; <strong>Add to Cart</strong>
</a>
<a href="#" data-toggle="modal" data-target="#login-modal" class="btn btn-order">
	<strong>Order Now (<?= showPrice($proposal_price,"total-price"); ?>)</strong>
</a>
<?php 
	if($count_extras > 0){ 
		include('extras.php'); 
	} 
?>
<hr>
<div class="form-group row mb-0"><!-- form-group row Starts -->
	<label class="col-6 control-label"> Proposal's Quantity </label>
	<div class="col-6">
		<select class="form-control" name="proposal_qty">
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
		</select>
	</div>
</div><!-- form-group row Ends -->
<?php }else{ ?>
<?php if($proposal_seller_user_name == @$_SESSION['seller_user_name']){  ?>
<a class="btn btn-order" href="../edit_proposal.php?proposal_id=<?= $proposal_id; ?>">
<i class="fa fa-edit"></i> Edit Proposal
</a>
<?php }else{ ?>
<form method="post" action="../../checkout" id="checkoutForm"><!--- form Starts --->
	<input type="hidden" name="proposal_id" value="<?= $proposal_id; ?>">
  <?php if($countcart == 1){ ?>
	<button type="button" class="btn btn-order primary added mb-3">
	<i class="fa fa-shopping-cart"></i> &nbsp;<strong>Already Added</strong>
	</button>
  <?php }else{ ?>
	<button type="submit" name="add_cart" class="btn btn-order primary mb-3">
	<i class="fa fa-shopping-cart"></i> &nbsp;<strong>Add to Cart</strong>
	</button>
  <?php } ?>
	<button type="submit" name="add_order" class="btn btn-order">
		<strong>Order Now (<?= showPrice($proposal_price,"total-price"); ?>)</strong>
	</button>
	<?php 
	if($count_extras > 0){ 
		include('extras.php'); 
	} 
	?>
	<hr>
	<div class="form-group row mb-0"><!--- form-group row mb-0 Starts --->
		<label class="col-6 control-label"> Proposal's Quantity </label>
		<div class="col-6">
			<select class="form-control" name="proposal_qty">
				<option>1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
			</select>
		</div>
	</div><!--- form-group row mb-0 Ends --->
</form><!--- form Ends --->
<?php } ?>
<?php } ?>