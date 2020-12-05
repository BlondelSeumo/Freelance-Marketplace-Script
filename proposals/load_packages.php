<?php
session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){

echo "<script>window.open('../login','_self')</script>";

}

$proposal_id = $input->post('proposal_id');

$d_proposal_price = $input->post('d_proposal_price');

$get_p = $db->select("proposal_packages",array("proposal_id"=>$proposal_id));

while($row = $get_p->fetch()){

$package_id = $row->package_id;
$package_name = $row->package_name;
$description = $row->description;
$delivery_time = $row->delivery_time;
$price = $row->price;

?>

<div class="col-md-4 package">

<form class="package-form" method="post" enctype="multipart/form-data">

<input type="hidden" name="package_id" value="<?= $package_id; ?>">

<table class="table table-bordered js-gig-packages">

<tr>
<td><h4><?= $package_name; ?></h4></td>
</tr>
<tr>
<td><textarea class="form-control form-control-sm description-value-<?= $package_id; ?>" required=""><?= $description; ?></textarea></td>
</tr>

<?php

$get_a = $db->select("package_attributes",array("package_id"=>$package_id));

while($row_a = $get_a->fetch()){

$attribute_id = $row_a->attribute_id;

$attribute_name = $row_a->attribute_name;

$attribute_value = $row_a->attribute_value;

?>

<tr>

<td>

<small><?= $attribute_name; ?></small>

<div class="input-group">

<input class="form-control form-control-sm attribute-value-<?= $attribute_id; ?>" value="<?= $attribute_value; ?>" data-attribute="<?= $attribute_id; ?>">

<button style="font-size:15px;" class="btn btn btn-success save-attribute-<?= $attribute_id; ?> input-group-addon" data-attribute="<?= $attribute_id; ?>">
<i class="fa fa-floppy-o"></i>&nbsp;Save
</button>

<button class="btn btn btn-success delete-attribute input-group-addon" data-attribute="<?= $attribute_name; ?>">
<i class="fa fa-trash"></i>
</button>

</div>

</td>

</tr>

<?php } ?>

<tr>

<td>

<small>Delivery Time</small>
 
<div class="input-group">

<input class="form-control form-control-sm" name="delivery_time" value="<?= $delivery_time; ?>" required>

</div>
 
</td>

</tr>

<tr>

<td>

<small>Price</small>
 
<div class="input-group">

<input type="number" min="5" class="form-control form-control-sm" name="price" value="<?= $price; ?>" required>

</div>
 
</td>

</tr>

<tr>

<td align="center">
 
<button type="submit" class="btn btn btn-success">
<i class="fa fa-floppy-o"></i> Update Package Details
</button>
 
</td>

</tr>

</table>

</form>

</div>

<?php } ?>



<script>

$(document).ready(function(){
	

function load_packages(){
	
$('#wait').addClass("loader");

$.ajax({
	
method: "POST",

url: "load_packages",

data: { d_proposal_price : <?= $d_proposal_price; ?>, proposal_id: <?= $proposal_id; ?> },

success:function(data){
	
$('#wait').removeClass("loader");

$(".packages").html(data);
	
}	

});
	
}

$(".package-form").on('submit', function(event){
  
event.preventDefault();

$('#wait').addClass("loader");

$.ajax({
  
method: "POST",

url: "save_package",

data: $(this).serialize()
      
}).done(function(data){

if(data == "error"){

$('#wait').removeClass("loader");

swal({
type: 'warning',
text: 'You Must Need To Fill Out All Fields Before Updating The Package Details.'
});

}else{

$('#wait').removeClass("loader");
  
}

});

});


<?php

$get_a = $db->select("package_attributes",array("proposal_id"=>$proposal_id));

while($row_a = $get_a->fetch()){

$attribute_id = $row_a->attribute_id;

?>
$(".save-attribute-<?= $attribute_id; ?>").on('click', function(event){

$('#wait').addClass("loader");
	
event.preventDefault();

var attribute_id = $(this).data("attribute");

var attribute_value = $('.attribute-value-<?= $attribute_id; ?>').val();

$.ajax({
	
method: "POST",

url: "save_attribute",

data: { attribute_value : attribute_value, attribute_id: attribute_id },

success:function(data){
	
if(data == "error"){

$('#wait').removeClass("loader");

swal({
type: 'warning',
text: 'You Must Need To Add Value In It Before Saving.'
});

}else{

$('#wait').removeClass("loader");
  
}
	
}
      
});

});

<?php } ?>

$(".delete-attribute").on('click', function(event){
		
$('#wait').addClass("loader");
event.preventDefault();

var attribute_name = $(this).data("attribute");

var proposal_id = <?= $proposal_id; ?>;

$.ajax({
	
method: "POST",

url: "delete_attribute",

data: { proposal_id : proposal_id, attribute_name: attribute_name },

success:function(data){
		
$('#wait').removeClass("loader");
load_packages();

}
      
});


});

});

</script>
