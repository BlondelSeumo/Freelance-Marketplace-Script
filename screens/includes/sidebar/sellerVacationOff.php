<?php 
if($enableVideo == 0 and $proposal_price == 0){
	$packagenum=0;
	$get_p = $db->select("proposal_packages",array("proposal_id"=>$proposal_id));
	while($row = $get_p->fetch()){
	$packagenum++;
	$package_id = $row->package_id;
	$package_name = $row->package_name;
	$delivery_time = $row->delivery_time;
	$price = $row->price;
	$priceClass = "total-price-$packagenum";
?>
	<div class="tab-pane fade show <?php if($package_name=="Standard"){echo" active";} ?>" id="tab_<?= $package_id; ?>">
		<div class="purchase-form"><?php include('purchaseFormPackages.php'); ?></div>
		<?php include('extras.php'); ?>
	</div>
	<?php } ?>
<?php include('quantity.php'); ?>
<?php 
}elseif($enableVideo == 0 and $proposal_price != 0){
$packagenum = 0;
$priceClass = "total-price";
?>
<div class="purchase-form"><?php include('purchaseForm.php'); ?></div>
<?php
}elseif($enableVideo == 1){ 
$packagenum = 0;
$priceClass = "total-price";
?>
<div class="purchase-form"><?php include($dir.'/plugins/videoPlugin/proposals/videoPlugin.php'); ?></div>
<?php } ?>