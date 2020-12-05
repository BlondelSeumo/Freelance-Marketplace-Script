<?php 
	$id = $input->get('id');
	$language_id = isset($_GET['lang']) ? $input->get('lang'): $_SESSION['siteLanguage'];

	$post = $db->select("posts",['id'=>$id])->fetch();

	$post_meta = $db->select('posts_meta', ['post_id' => $id, 'language_id' => $language_id])->fetch();

	$title = !empty($post_meta->title) ? $post_meta->title:'';
	$author = !empty($post_meta->author) ? $post_meta->author:'';
	$content = !empty($post_meta->content) ? $post_meta->content:'';

	$url = preg_replace('#[ -]+#','-', $title);

	/// Get Category Details
	$get_cat = $db->select("post_categories_meta",['cat_id'=>$post->cat_id, 'language_id' => $language_id]);
	$row_cat = $get_cat->fetch();
	$cat_name = !empty($row_cat->cat_name) ? $row_cat->cat_name:'' ;

	$comments = $db->select("post_comments",array("post_id"=>$id));
	$count_comments = $comments->rowCount();

?>
<div class="card mb-4"><!--- card Starts --->
	<div class="card-body <?= $textRight; ?>"><!--- card-body Starts --->
		
		<h1 class="h3"><?= $title; ?></h1>
		<hr>
	   <p>
	   	Published on: <span class="text-muted"><?= $post->date_time; ?></span> | 
	   	Category: <a href="index?cat_id=<?= $post->cat_id; ?>" class="text-muted"><?= $cat_name; ?></a> |
	   	Author: <a href="#" class="text-muted"><?= $author; ?></a> 
	   </p>

		<img src="<?= getImageUrl("posts",$post->image); ?>" class="img-fluid mb-3"/>
		<div class="mt-3 post-content">
			<?= $content; ?>
		</div>

		<div class="clearfix"></div>

		<div class="sharethis-inline-share-buttons mt-2 <?=($lang_dir == "right" ? 'float-left':'')?>"></div>

	</div><!--- card-body Ends --->
</div><!--- card Ends --->

<?php include("post_comments.php"); ?>

<a href="index" class="btn btn-success <?= $floatRight; ?>"> <i class="fa fa-arrow-left"></i>&nbsp; Go Back</a>
