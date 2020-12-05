<div class="card-header pt-0 pl-3 tabs-header">
	<ul class="nav nav-tabs card-header-tabs rounded-0 justify-content-center">
	<?php
	$i=0;
	$get_p = $db->select("proposal_packages",array("proposal_id"=>$proposal_id));
	while($row = $get_p->fetch()){
	$i++;
	$package_id = $row->package_id;
	$package_name = $row->package_name;
	?>
	<li class="nav-item">
		<a class="nav-link <?=($lang_dir == "right" ? 'text-right':'')?> <?php if($package_name == "Standard"){ echo " active"; } ?>" href="#tab_<?= $package_id; ?>" data-toggle="tab" formid="checkoutForm<?= $i; ?>">
			<?= $lang['packages'][strtolower($package_name)]; ?>
		</a>
	</li>
	<?php } ?>
	</ul>
<!-- 	<ul class="nav nav-tabs card-header-tabs rounded-0 justify-content-center">
		<li class="nav-item">
		<a class="nav-link active" href="#tab_40" data-toggle="tab" formid="checkoutForm1">
			Basic		</a>
	</li>
		<li class="nav-item">
		<a class="nav-link" href="#tab_41" data-toggle="tab" formid="checkoutForm2">
			Standard		</a>
	</li>
	<li class="nav-item" style="border-left: 1px solid #ddd;">
		<a class="nav-link" href="#tab_42" data-toggle="tab" formid="checkoutForm3">
			Advance		</a>
	</li>
	</ul> -->
</div>