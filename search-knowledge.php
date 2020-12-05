<?php 

session_start();
require_once("includes/db.php");
$search = isset($_POST['q'])?$input->post('q'):'';
$search = "%$search%";

$cat = isset($_POST['cat'])?$input->post('cat'):'';

	$get_articles = $db->query("select kb.*,ac.article_cat_title from knowledge_bank as kb join article_cat as ac on ac.article_cat_id=cat_id where article_heading like :search AND kb.language_id='$siteLanguage' order by 1 DESC",array(":search" => $search));

if($cat){

	$get_articles =  $db->query("select kb.*,ac.article_cat_title from knowledge_bank as kb join article_cat as ac on ac.article_cat_id=cat_id where cat_id=:cat_id and kb.language_id='$siteLanguage' order by 1 DESC",array("cat_id"=>$cat));

}

$count_articles = $get_articles->rowCount();

$output = [];

if($count_articles > 0){

	$results = [];
	
	while($row_articles = $get_articles->fetch()){
		$results[] = $row_articles;
	}

	$output['results'] = $results;
	
	$output['count'] = count($results);

}else{

	$output['count'] = 0;

	$output['message'] = "Sorry, we couldn't find any results for your search.";

}

echo json_encode($output);

 ?>
