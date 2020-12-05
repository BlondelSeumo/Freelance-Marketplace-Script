<?php

$per_page = 5;
if(isset($_GET['page'])){
	$page = $input->get('page');
	if($page == 0){ $page = 1; }
}else{
	$page = 1;
}

/// Page will start from 0 and multiply by per page
$start_from = ($page-1) * $per_page;

if(isset($_GET['search'])){
	$search = $input->get('search');
	$posts = $db->query("select * from posts where title like :title order by 1 DESC LIMIT :limit OFFSET :offset",["title"=>"%$search%"],array("limit"=>$per_page,"offset"=>$start_from));
}else if(isset($_GET['cat_id'])){
	$cat_id = $input->get('cat_id');
	$search = "";
	$posts = $db->query("select * from posts where cat_id=:cat_id order by 1 DESC LIMIT :limit OFFSET :offset",["cat_id"=>$cat_id],array("limit"=>$per_page,"offset"=>$start_from));
}else if(isset($_GET['author'])){
	$author = $input->get('author');
	$search = "";
	$posts = $db->query("select * from posts where author=:author order by 1 DESC LIMIT :limit OFFSET :offset",["author"=>$author],array("limit"=>$per_page,"offset"=>$start_from));
}else{
	$search = "";
	$posts = $db->query("select * from posts order by 1 DESC LIMIT :limit OFFSET :offset","",array("limit"=>$per_page,"offset"=>$start_from));
}

$count_posts = $posts->rowCount();

if($count_posts == 0){
	echo "<h2 class='h3 text-center bg-white p-5'>No Posts Found.</h2>";
}

while($post = $posts->fetch()){

$post_meta = $db->select('posts_meta', ['post_id' => $post->id, 'language_id' => $siteLanguage])->fetch();

$url = preg_replace('#[ -]+#','-', $post_meta->title);
$content = substr(strip_tags($post_meta->content),0,250);

/// Get Category Details

$get_cat = $db->select("post_categories_meta",['cat_id'=>$post->cat_id,'language_id' => $siteLanguage]);
$row_cat = $get_cat->fetch();
$cat_name = !empty($row_cat->cat_name) ? $row_cat->cat_name:'' ;

?>

<div class="card mb-4"><!--- card Starts --->
	<div class="card-body row">
		<div class="col-lg-4 col-md-12 <?=($lang_dir == "right" ? 'order-lg-2 order-md-1':'')?>">
			<a href="<?= $post->id; ?>/<?= $url; ?>">
				<img src="<?= getImageUrl("posts",$post->image); ?>" class="img-fluid mb-3"/>
		   </a>
		</div>
	   <div class="col-lg-8 col-md-12 <?=($lang_dir == "right" ? 'order-lg-1 order-md-2':'')?>">
		   <h5 class="mt-0 mb-2 <?= $textRight; ?>"><?= $post_meta->title; ?></h5>
		   <p class="small mb-1 <?= $textRight; ?>">
		   	<span class="text-muted">Published on:</span> <?= $post->date_time; ?> | 
		   	<span class="text-muted">Category:</span> 
		   	<a href="index?cat_id=<?= $post->cat_id; ?>"><?= $cat_name; ?></a> | 
		   	<span class="text-muted">Author:</span> 
		   	<a href="#"><?= $post_meta->author; ?></a> 
		   </p>
		   <p class="post-content <?= $textRight; ?>"><?= $content; ?>...</p>
		   <a href="<?= $post->id; ?>/<?= $url; ?>" class="btn btn-success float-right">Read More</a>
	   </div>
	</div>
</div><!--- card Ends --->

<?php } ?>

<?php if(!isset($_GET['cat_id']) AND !isset($_GET['author'])){ ?>

<nav class="nav justify-content-center"> 

	<ul class="pagination"><!--- pagination Starts --->
     <?php
      
      /// Now Select All From Order Table

		if(isset($_GET['search'])){
			$query = $db->query("select * from posts where title like :title",["title"=>"%$search%"]);
		}else if(isset($_GET['cat_id'])){
			$query = $db->query("select * from posts where cat_id=:cat_id",["cat_id"=>$cat_id]);
		}else if(isset($_GET['author'])){
			$query = $db->query("select * from posts where author=:author",["author"=>$author]);
		}else{
			$query = $db->query("select * from posts order by 1 DESC");
		}

		/// Count The Total Records
		$total_records = $query->rowCount();
		/// Using ceil function to divide the total records on per page
		$total_pages = ceil($total_records / $per_page);

		echo "<li class='page-item'><a href='index?search=$search&page=1' class='page-link'>{$lang['pagination']['first_page']}</a></li>";

		echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?search=$search&page=1'>1</a></li>";

		$i = max(2, $page - 5);

		if($i > 2){
		echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
		}

		for (; $i < min($page + 6, $total_pages); $i++) {
		echo "<li class='page-item"; 
		if($i == $page){ echo " active "; } 
		echo "'><a href='index?search=$search&page=".$i."' class='page-link'>".$i."</a></li>";
		}

		if ($i != $total_pages and $total_pages > 1){
			echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
		}

		if($total_pages > 1){
		echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?search=$search&page=$total_pages'>$total_pages</a></li>";
		}

		echo "<li class='page-item'><a href='index?search=$search&page=$total_pages' class='page-link'>{$lang['pagination']['last_page']}</a></li>";

     ?>
   </ul><!--- pagination Ends --->

</nav>

<?php } ?>