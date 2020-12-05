<?php

session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
echo "<script>window.open('../login','_self')</script>";
	
}

echo "<option value=''> {$lang['placeholder']['select_sub_category']} </option>";

$category_id =  $input->post('category_id');

$get_c_cats = $db->select("categories_children",array("child_parent_id" => $category_id));

while($row_c_cats = $get_c_cats->fetch()){
	
$child_id = $row_c_cats->child_id;


$get_meta = $db->select("child_cats_meta",array("child_id" => $child_id,"language_id" => $siteLanguage));

$row_meta = $get_meta->fetch();

$child_title = $row_meta->child_title;


echo "<option value='$child_id'> $child_title </option>";
	
}

?>