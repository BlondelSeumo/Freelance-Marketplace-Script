<div class="card"><!--- card Starts --->
	<div class="card-body"><!--- card-body Starts --->
		<div class="row">
			<div class="col-md-2">
				<img src="<?= $proposal_img1; ?>" class="img-fluid d-lg-block d-md-block d-none">
			</div>
			<div class="col-md-10">
				<?php if($seller_id == $login_seller_id){ ?>
				<h1 class="text-success float-right d-lg-block d-md-block d-none"><?= showPrice($order_price); ?></h1>
				<h4>
					<?= $lang['order_details']['number']; ?> <?= $order_number; ?>
					<small>
					<a href="proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank" class="text-success">
						<?= $lang['order_details']['view_proposal']; ?>
					</a>
					</small>
				</h4>
				<p class="text-muted">
					<span class="font-weight-bold"><?= $lang['order_details']['buyer']; ?>: </span>
					<a href="<?= $buyer_user_name; ?>" target="_blank" class="seller-buyer-name mr-1 text-success">
					<?= ucfirst($buyer_user_name); ?>
					</a>
					| <span class="font-weight-bold ml-1"> <?= $lang['order_details']['status']; ?>: </span>
					<?= ucfirst($order_status); ?>
					| <span class="font-weight-bold ml-1"> <?= $lang['order_details']['date']; ?>: </span>
					<?= $order_date; ?>
					| <span class="font-weight-bold ml-1"> <?= $lang['order_details']['revisions']; ?>: </span>
					<?= ucfirst($order_revisions); ?>
					<?php if($videoPlugin == 1 AND !empty($order_minutes)){ ?>
					| <span class="font-weight-bold ml-1"> <?= $lang['order_details']['video_call_minutes']; ?>: </span>
						<?= $order_minutes." ".$lang['order_details']['minutes']; ?>
					<?php } ?>
				</p>
				<?php }elseif($buyer_id == $login_seller_id){ ?>
				<h1 class="text-success float-right d-lg-block d-md-block d-none"><?= showPrice($total); ?></h1>
				<h4><?= $proposal_title; ?></h4>
				<p class="text-muted">
					<span class="font-weight-bold"><?= $lang['order_details']['seller']; ?>: </span>
					<a href="<?= $seller_user_name; ?>" target="_blank" class="seller-buyer-name mr-1 text-success">
					<?= ucfirst($seller_user_name); ?>
					</a>
					| <span class="font-weight-bold ml-1"> <?= $lang['order_details']['number']; ?>: </span> #<?= $order_number; ?>
					| <span class="font-weight-bold ml-1"> <?= $lang['order_details']['date']; ?>: </span>
					<?= $order_date; ?>
					| <span class="font-weight-bold ml-1"> <?= $lang['order_details']['revisions']; ?>: </span>
					<?= ucfirst($order_revisions); ?>
					<?php if($order_revisions != "unlimited" AND $order_revisions != "0"){ ?>
					| <span class="font-weight-bold ml-1"> <?= $lang['order_details']['revisions_used']; ?>: </span>
					<?= ucfirst($order_revisions_used); ?>
					<?php } ?>
					<?php if($videoPlugin == 1 AND !empty($order_minutes)){ ?>
						| <span class="font-weight-bold ml-1"> <?= $lang['order_details']['video_call_minutes']; ?>: </span>
						<?= $order_minutes." ".$lang['order_details']['minutes']; ?>
					<?php } ?>
				</p>
				<?php } ?>
			</div>
		</div>
		<?php require_once("orderItems.php"); ?>
	</div><!--- card-body Ends --->
</div><!--- card Ends --->