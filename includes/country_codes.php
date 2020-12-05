<select name="country_code" class="form-control border-right-0">

	<?php 

	$get_countries = $db->select("countries");
	while($row_countries = $get_countries->fetch()){
	
	$id = $row_countries->id;
	$country = $row_countries->name;
	$code = $row_countries->code;

	if(!empty($code)){
	
	?>

	<option value="+<?= $code; ?>" <?= ($country == @$login_seller_country)?"selected":""; ?>><?= $country; ?> (+<?= $code ?>)</option>

	<?php } ?>

	<?php } ?>

</select>