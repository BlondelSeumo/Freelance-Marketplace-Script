<div class="form-group mt-3 mb-3"><!--- form-group mt-2 Starts --->
	<ul class="buyables m-b-25">
		<?php
		if($proposal_price == 0){
		  $formId = "checkoutForm$packagenum";
		}else{
		  $formId = "checkoutForm";
		}
		$i = 0;
		$total = 0;
		while($row_extras = $get_extras->fetch()){
			$id = $row_extras->id;
			$name = $row_extras->name;
			$price = $row_extras->price;
			$total += $price;
			$i++;
		?>
		<li>
			<label class="fake-check-black check-text">
				<input type="checkbox" name="proposal_extras[<?= $i; ?>]" data-packagenum="<?= $packagenum; ?>" value="<?= $id; ?>" form="<?= $formId; ?>">
				<span class="chk-img"></span>
				<span class="js-express-delivery-text">
				<?= $name; ?> (+<span class="price"><?= showPrice($price); ?></span>)
				</span>
        		<b class="num d-none"><?= $price; ?></b>
			</label>
		</li>
		<?php } ?>
	</ul>	
</div><!--- form-group mt-2 Ends --->