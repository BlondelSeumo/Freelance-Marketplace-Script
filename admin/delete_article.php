<?php

@session_start();

if(!isset($_SESSION['admin_email'])){

echo "<script>window.open('login','_self');</script>";

}else{


?>

<?php

if(isset($_GET['delete_article'])){

$article_id = $input->get('delete_article');


$delete_article = $db->delete("knowledge_bank",array('article_id' => $article_id));	

if($delete_article){

$insert_log = $db->insert_log($admin_id,"article",$article_id,"deleted");


echo "<script>alert('One article has been deleted successfully.');</script>";

echo "<script>window.open('index?view_articles','_self');</script>";

}


}

?>

<?php } ?>
