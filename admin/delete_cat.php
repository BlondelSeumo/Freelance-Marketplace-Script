<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	

?>

<?php

if(isset($_GET['delete_cat'])){
	
$cat_id = $input->get('delete_cat');
	

$delete_child_cats = $db->delete("categories_children",array('child_parent_id' => $cat_id));

$delete_meta = $db->delete("child_cats_meta",array('child_parent_id' => $cat_id));
	
$delete_meta = $db->delete("cats_meta",array('cat_id' => $cat_id));;
	
$delete_cat = $db->delete("categories",array('cat_id' => $cat_id));


if($delete_cat){

$insert_log = $db->insert_log($admin_id,"cat",$cat_id,"deleted");

	
echo "<script>alert('One Category Has Been Deleted Successfully.');</script>";
	
echo "<script>window.open('index?view_cats','_self');</script>";

}


}

?>

<?php } ?>