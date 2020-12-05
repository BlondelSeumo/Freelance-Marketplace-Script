<?php

@session_start();

if(isset($_POST['proposal_id'])){
	require_once("../../../includes/db.php");
	$proposal_id = $input->post('proposal_id');
}

$get_p_1 = $db->select("proposal_packages",array("proposal_id"=>$proposal_id,"package_name"=>'Basic'));
$row_1 = $get_p_1->fetch();
$get_p_2 = $db->select("proposal_packages",array("proposal_id"=>$proposal_id,"package_name"=>'Standard'));
$row_2 = $get_p_2->fetch();
$get_p_3 = $db->select("proposal_packages",array("proposal_id"=>$proposal_id,"package_name"=>'Advance'));
$row_3 = $get_p_3->fetch();

$prices = array(5,10,15,20,25,50,60,70,80,90,100);
$revisions = array(0,1,2,3,4,5,6,7,8,9,10);

$enable_unlimited_revisions = $row_general_settings->enable_unlimited_revisions;
if($enable_unlimited_revisions == 1){
  $revisions['unlimited'] = "Unlimited Revisions";
}

?>
<table class="table table-bordered packages">
<thead>
<tr>
  <th></th>
  <th>Basic</th>
  <th>Standard</th>
  <th>Advance</th>
</tr>
</thead>
<tbody>
	<form action="#" method="post" class="pricing-form" id="pricing-form">
		<input type="hidden" name="proposal_packages[1][package_id]" form="pricing-form" value="<?= $row_1->package_id; ?>">
		<input type="hidden" name="proposal_packages[2][package_id]" form="pricing-form" value="<?= $row_2->package_id; ?>">
		<input type="hidden" name="proposal_packages[3][package_id]" form="pricing-form" value="<?= $row_3->package_id; ?>">

		<tr>
			<td>Description</td>
			<td class="p-0"><textarea name="proposal_packages[1][description]" form="pricing-form" class="form-control" placeholder="Description" rows="3"><?= $row_1->description; ?></textarea></td>
			<td class="p-0"><textarea name="proposal_packages[2][description]" form="pricing-form" class="form-control" placeholder="Description" rows="3"><?= $row_2->description; ?></textarea></td>
			<td class="p-0"><textarea name="proposal_packages[3][description]" form="pricing-form" class="form-control" placeholder="Description" rows="3"><?= $row_3->description; ?></textarea></td>
		</tr>
		<?php
		$i = 0;
		$get_a = $db->select("package_attributes",array("package_id"=>$row_1->package_id));
		while($row_a = $get_a->fetch()){
		$a_id = $row_a->attribute_id;
		$a_name = $row_a->attribute_name;
		$a_value = $row_a->attribute_value;
		$i++;
		?>
		<tr>
			
			<td>
				<span><?= $a_name; ?></span>
				<a href="#" data-attribute="<?= $a_name; ?>" class="edit-attribute float-right">
					<i class="fa fa-pencil float-right" style="font-size: 12px;"></i>
				</a>
			</td>

			<td class="p-0"> 
			<input type="hidden" name="package_attributes[<?= $i; ?>][attribute_id]" form="pricing-form" value="<?= $a_id; ?>">
			<input type="text"name="package_attributes[<?= $i; ?>][attribute_value]" form="pricing-form" class="form-control" value="<?= $a_value; ?>"> 
			<i class="fa fa-trash delete-attribute" data-attribute="<?= $a_name; ?>"></i>
			</td>
			<?php
			$get_v = $db->query("select * from package_attributes where proposal_id='$proposal_id' and attribute_name='$a_name' and not attribute_id='$a_id'");
			while($row_v = $get_v->fetch()){
			$id = $row_v->attribute_id;
			$value = $row_v->attribute_value;
			$i++;
			?>
			<td class="p-0"> 
			<input type="hidden" name="package_attributes[<?= $i; ?>][attribute_id]" form="pricing-form" value="<?= $id; ?>">
			<input type="text"name="package_attributes[<?= $i; ?>][attribute_value]" form="pricing-form" class="form-control" value="<?= $value; ?>">
			<i class="fa fa-trash delete-attribute" data-attribute="<?= $a_name; ?>"></i>
			</td>
			<?php } ?>
		</tr>
		<?php } ?>

		<tr class="delivery-time">
			<td>Delivery Time</td>
			<td class="p-0">
			<select name="proposal_packages[1][delivery_time]" class="form-control">
			<?php
			$get_delivery_times = $db->select("delivery_times");
			while($row_delivery_times = $get_delivery_times->fetch()){
			$delivery_time = $row_delivery_times->delivery_proposal_title;
			echo "<option value='".intval($delivery_time)."' ".(intval($delivery_time) == $row_1->delivery_time ? "selected" : "").">$delivery_time</option>";
			}
			?>
			</select>
			</td>
			<td class="p-0">
			<select name="proposal_packages[2][delivery_time]" form="pricing-form" class="form-control">
			<?php
			$get_delivery_times = $db->select("delivery_times");
			while($row_delivery_times = $get_delivery_times->fetch()){
			$delivery_time = $row_delivery_times->delivery_proposal_title;
			echo "<option value='".intval($delivery_time)."' ".(intval($delivery_time) == $row_2->delivery_time ? "selected" : "").">$delivery_time</option>";
			}
			?>
			</select></td>
			<td class="p-0">
			<select name="proposal_packages[3][delivery_time]" form="pricing-form" class="form-control">
			<?php
			$get_delivery_times = $db->select("delivery_times");
			while($row_delivery_times = $get_delivery_times->fetch()){
			$delivery_time = $row_delivery_times->delivery_proposal_title;
			echo "<option value='".intval($delivery_time)."' ".(intval($delivery_time) == $row_3->delivery_time ? "selected" : "").">$delivery_time</option>";
			}
			?>
			</select>
			</td>
		</tr>

		<tr>
			<td>Revisions</td>
			<td class="p-0">
			<select name="proposal_packages[1][revisions]" form="pricing-form" class="form-control">
			<?php 
			foreach ($revisions as $key => $rev) {
				echo "<option value='$key'".($key == $row_1->revisions ? "selected" : "").">$rev</option>";
			}
			?>
			</select>
			</td>
			<td class="p-0">
			<select name="proposal_packages[2][revisions]" form="pricing-form" class="form-control">
			<?php 
			foreach ($revisions as $key => $rev) {
				echo "<option value='$key'".($key == $row_2->revisions ? "selected" : "").">$rev</option>";
			}
			?>
			</select>
			</td>
			<td class="p-0">
			<select name="proposal_packages[3][revisions]" form="pricing-form" class="form-control">
			<?php 
			foreach ($revisions as $key => $rev) {
				echo "<option value='$key'".($key == $row_3->revisions ? "selected" : "").">$rev</option>";
			}
			?>
			</select>
			</td>
		</tr>

		<tr>
			<td>Price</td>
			<td class="p-0">

			<input type="number" min='<?= $min_proposal_price; ?>' required name="proposal_packages[1][price]" form="pricing-form" value="<?= $row_1->price; ?>" class="form-control">
			</td>
			<td class="p-0">
			<input type="number" min='<?= $min_proposal_price; ?>' required name="proposal_packages[2][price]" form="pricing-form" value="<?= $row_2->price; ?>" class="form-control">
			</td>
			<td class="p-0">

			<input type="number" min='5' required name="proposal_packages[3][price]" form="pricing-form" value="<?= $row_3->price; ?>" class="form-control">
			</td>
		</tr>
	</form>
</tbody>
</table>

<!-- Modal -->
<div class="modal fade" id="edit-modal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
			<h5 class="modal-title">Edit Attribute Name</h5>
      	<button type="button" class="close" data-dismiss="modal">
         	<span>&times;</span>
      	</button>
      </div>
      <div class="modal-body">
			<form action="" method="post" class="update-attribute">
				<input type="hidden" name="name" value="">
				<div class="form-group">
					<input type="text" class="form-control" name="new_name" placeholder="Attribute Name"/>
				</div>
				<div class="form-group text-center mb-0">
					<input type="submit" class="btn btn-success" value="Update Attribute Name"/>
				</div>
			</form>
      </div>
    </div>
  </div>
</div>