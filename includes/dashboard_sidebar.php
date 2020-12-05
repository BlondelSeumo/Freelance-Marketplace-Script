<div class="card mb-3 contacts-sidebar">
	<div class="card-header">
		<h5 class="h5"><i class="fa fa-address-book "></i> <?= $lang["dashboard"]['my_contacts']; ?> </h5>
		<ul class="nav nav-tabs card-header-tabs">
			<li class="nav-item">
				<a href="#my_buyers" data-toggle="tab" class="nav-link make-black active "> <?= $lang['tabs']['my_buyers']; ?> </a>
			</li>
			<li class="nav-item">
				<a href="#my_sellers" data-toggle="tab" class="nav-link make-black"> <?= $lang['tabs']['my_sellers']; ?> </a>
			</li>
		</ul>
	</div>
	<div class="card-body">
		<div class="tab-content">
			<div id="my_buyers" class="tab-pane fade show active">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="gray"><?= $lang['th']['buyer_names']; ?></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sel_my_buyers = $db->query("select * from my_buyers where seller_id='$login_seller_id' order by 1 DESC LIMIT 0,5");
							while($row_my_buyers = $sel_my_buyers->fetch()){
								$buyer_id = $row_my_buyers->buyer_id;
								$select_buyer = $db->select("sellers",array("seller_id" => $buyer_id));
								$row_buyer = $select_buyer->fetch();
								$buyer_user_name = $row_buyer->seller_user_name;
								$buyer_image = getImageUrl2("sellers","seller_image",$row_buyer->seller_image);
							?>
							<tr>
								<td>
								<img src="<?= $buyer_image; ?>" class="rounded-circle" width="50" height="50">
								<div class="contact-title">
							    <h6 class="make-black"><?= $buyer_user_name; ?> </h6>
							    <a href="<?= $buyer_user_name; ?>" target="_blank" class="text-success">
							     User Profile 
							  	 </a>
							    <span style="color:black;">|</span>
							    <a href="conversations/message?seller_id=<?= $buyer_id; ?>" target="_blank" class="text-success">  Chat History </a>
								</div>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="my_sellers" class="tab-pane fade">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
						<tr>
						<th class="gray"><?= $lang['th']['seller_names']; ?></th>
						</tr>
						</thead>
						<tbody>
                     <?php
                		$sel_my_sellers = $db->query("select * from my_sellers where buyer_id='$login_seller_id' order by 1 DESC LIMIT 0,5");
							while($row_my_sellers = $sel_my_sellers->fetch()){
							$seller_id = $row_my_sellers->seller_id;
							$select_seller = $db->select("sellers",array("seller_id" => $seller_id));
							$row_seller = $select_seller->fetch();
							$seller_image = getImageUrl2("sellers","seller_image",@$row_seller->seller_image);
							$seller_user_name = @$row_seller->seller_user_name;
			
                     ?>
							<tr>
								<td>
									<img src="<?= $seller_image; ?>" class="rounded-circle" width="50" height="50">
									<div class="contact-title">
										<h6 class="make-black"><?= $seller_user_name; ?></h6>
										<a href="<?= $seller_user_name; ?>" target="_blank" class="text-success">
										 User Profile </a>
										  <span style="color:black;">|</span>
										<a href="conversations/message?seller_id=<?= $seller_id; ?>" target="_blank" class="text-success"> Chat History </a>
									</div>
								</td>
							</tr>
                     <?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="card rounded-0 sticky-start mb-3 card_user ">
	<div class="card-body">
		<img src="images/sales.png" class="img-fluid center-block" alt="none">
		<h4><?= $lang['sidebar']['start_selling']['title']; ?></h4>
		<p><?= $lang['sidebar']['start_selling']['desc']; ?></p>
		<button onclick="location.href='start_selling'" class="btn get_btn">
			<?= $lang['sidebar']['start_selling']['button']; ?>
		</button>
	</div>
</div>
<br>
<script>
$(document).ready(function(){
	// Sticky Code start //
	if($(window).width() < 767){

	}else{
		$(".sticky-start").sticky({
		   topSpacing:10,
		   zIndex:500,
			bottomSpacing:400,
		});
	}
	// Sticky code ends //
});
</script>
			