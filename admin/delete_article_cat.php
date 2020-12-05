<?php

@session_start();

if(!isset($_SESSION['admin_email'])){

echo "<script>window.open('login','_self');</script>";

}else{


if(isset($_GET['delete_article_cat'])){

$article_cat_id = $input->get('delete_article_cat');

$delete_article_cat = $db->delete("article_cat",array('article_cat_id' => $article_cat_id));
	
if($delete_article_cat){

$insert_log = $db->insert_log($admin_id,"article_cat",$article_cat_id,"deleted");

echo "<script>alert('One category has been deleted successfully.');</script>";

echo "<script>window.open('index?view_article_cats','_self');</script>";

}


}

?>

<?php } ?>
