<div class="row d-lg-flex d-md-flex d-none"><!--- row d-lg-flex Starts --->
	<div class="col-md-12">
		<table class="table table-bordered mt-3">
			<thead>
				<tr>

					<th><?= $lang['order_details']['item']; ?></th>
					<th><?= $lang['order_details']['quantity']; ?></th>

					<?php
					if($videoPlugin == 1){
						$extendTime = $db->select("order_extend_time",array("order_id"=>$order_id))->rowCount();
					}else{
						$extendTime = 0;
					}
					if($extendTime==0){ 
					?>
						<th><?= $lang['order_details']['duration']; ?></th>
					<?php }else{?>
						<th><?= $lang['order_details']['duration/price_per_minute']; ?></th>
					<?php } ?>

					<th><?= $lang['order_details']['amount']; ?></th>
					
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="font-weight-bold" width="600">
						<?= $proposal_title; ?>
						<?php
							$get_extras = $db->select("order_extras",array("order_id"=>$order_id));
							$count_extras = $get_extras->rowCount();
							if($count_extras > 0){
						?>
						<ul class="ml-0" style="list-style-type: circle;">
							<?php
							while($row_extras = $get_extras->fetch()){
								$id = $row_extras->id;
								$name = $row_extras->name;
								$price = $row_extras->price;
							?>
							<li class="font-weight-normal text-muted">
								<?= $name; ?> (+<span class="price"><?=  showPrice($price); ?></span>)
							</li>
							<?php } ?>
						</ul>
						<?php } ?>
					</td>
					<td><?= $order_qty; ?></td>
					<td><?= $order_duration; ?></td>
					<td>
					<?php if($seller_id == $login_seller_id){ ?>
					<?=  showPrice($order_price); ?>
					<?php }elseif($buyer_id == $login_seller_id){ ?>
					<?= showPrice($order_price); ?>
					<?php } ?>
					</td>
				</tr>
				<?php 
					if($videoPlugin == 1){
						require_once("plugins/videoPlugin/orderExtendTimeRow.php");
					}
				?>
				<?php if($buyer_id == $login_seller_id){  ?>
					<?php if(!empty($order_fee)){ ?>
					<tr>
						<td><?= $lang['order_details']['processing_fee']; ?></td>
						<td></td>
						<td></td>
						<td><?= showPrice($order_fee); ?></td>
					</tr>
					<?php } ?>
				<?php } ?>
				<tr>
					<td colspan="4">
						<span class="float-right mr-4">
						<strong><?= $lang['order_details']['total']; ?> : </strong>
						<?php if($seller_id == $login_seller_id){ ?>
							<?= showPrice($order_price); ?>
						<?php }elseif($buyer_id == $login_seller_id){ ?> 
							<?= showPrice($total); ?>
						<?php } ?>
						</span>
					</td>
				</tr>
			</tbody>
		</table>
		<?php if(!empty($order_desc)){ ?>
		<table class="table">
			<thead>
				<tr> <th><?= $lang['order_details']['description']; ?></th> </tr>
			</thead>
			<tbody>
				<tr>
					<td width="600"><?= $order_desc; ?></td>
				</tr>
			</tbody>
		</table>
		<?php } ?>
	</div>
</div><!--- row d-lg-flex Ends --->