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
		<a class="nav-link <?php if($package_name == "Standard"){ echo " active"; } ?>" href="#tab_<?= $package_id; ?>" style="font-size:19px; font-weight:350;" data-toggle="tab" formid="checkoutForm<?= $i; ?>">
			<?= $lang['packages'][strtolower($package_name)]; ?>
		</a>
	</li>
	<?php } ?>
	</ul>
</div>