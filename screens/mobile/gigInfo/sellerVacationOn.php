<?php if($proposal_seller_user_name == @$_SESSION['seller_user_name']){ ?>
<h5 class="line-height-full mb-0">
	Your vacation mode has been switched to <span class="badge badge-success">ON</span>
	for this reason, no one is able to purchase any of your proposals until you switch vacation mode back to <span class="badge badge-success">OFF</span><br> Ready to switch it back off? 
	<a class="text-success" href="<?= $site_url; ?>/proposals/view_proposals.php">Click here</a>
</h5>
<?php }else{ ?>
<h5 class="line-height-full mb-0">
	Seller vacation mode has been switched to <span class="badge badge-success"> ON </span>
	At this momment, you are unable to purchase this proposal until the seller swiches vacation mode back to <span class="badge badge-success">OFF</span>. 
</h5>
<?php } ?>