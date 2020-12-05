<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	

if(isset($_GET['delete_child_cat'])){
	
$child_id = $input->get('delete_child_cat');


$delete_meta = $db->delete("child_cats_meta",array('child_id' => $child_id));

$delete_child_cat = $db->delete("categories_children",array('child_id' => $child_id));

if($delete_child_cat){	

$insert_log = $db->insert_log($admin_id,"child_cat",$child_id,"deleted");

echo "<script>alert('Sub category deleted successfully.');</script>";
	
echo "<script>window.open('index?view_child_cats','_self');</script>";
	
}


}

?>

<?php } ?>