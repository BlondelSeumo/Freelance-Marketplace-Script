<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('../login','_self');</script>";
}else{
?>

<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
		  <div class="page-title">
		    <h1><i class="menu-icon fa fa-rss"></i> Blog</h1>
		  </div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="page-header float-right">
		  <div class="page-title">
				<ol class="breadcrumb text-right">
				  <li class="active">Posts</li>
				</ol>
		  </div>
		</div>
	</div>
</div>

<div class="container"><!--- container Starts --->
<div class="row"><!-- row Starts -->
<div class="col-lg-12"><!-- col-lg-12 Starts -->
<div class="card card-default"><!-- card card-default Starts -->
<div class="card-header"><!-- card-header Starts -->
<h4 class="h3 mb-0">Posts</h4>
</div><!-- card-header Ends -->
<div class="card-body"><!-- card-body Starts -->
	<p class="lead font-weight-bold"> Posts 
		<a href="index?insert_post" class="btn btn-success"> <i class="fa fa-plus-circle"></i> Add Post </a> 
	</p>
	<div class="table-responsive"><!--- table-responsive Starts -->
	<table class="table table-bordered table-hover table-striped"><!--- table table-bordered table-hover table-striped Starts -->
	<thead>
	<tr>
	<th>No</th>
	<th colspan="2">Post</th>
	<th>Actions:</th>
	</tr>
	</thead>
	<tbody>
		<?php
		$i = 0;
		$posts = $db->select("posts","","DESC");
		while($post = $posts->fetch()){
			$i++;
			$query = $db->select("posts_meta", array('post_id' => $post->id, 'language_id' => $adminLanguage));			
			$post_meta = $query->fetch();
			$title = !empty($post_meta->title) ? $post_meta->title: '';
			$author = !empty($post_meta->author) ? $post_meta->author: '';
			$content = !empty($post_meta->content) ? $post_meta->content: '';

			$url = preg_replace('#[ -]+#', '-', $title);

			/// Get Category Details
			$get_cat = $db->select("post_categories_meta",['cat_id'=>$post->cat_id, 'language_id' => $adminLanguage]);
			$row_cat = $get_cat->fetch();
			$cat_name = !empty($row_cat->cat_name) ? $row_cat->cat_name: '';

		?>
		<tr>

		<td><?= $i; ?></td>
		<td><img width="100" src="<?= getImageUrl("posts",$post->image); ?>" class="rounded"></td>
		<td width="800">
			<strong><?= $title; ?></strong>
			<p class="mt-2"><?= strip_tags(substr($content, 0,300)); ?>...</p>
			<p class="text-lead mb-0">Published on: <?= $post->date_time; ?> | Category: <?= $cat_name; ?></p>
		</td>
		<td>

			<div class="dropdown"><!--- dropdown Starts --->

	      		<button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
					<i class="fa fa-cog"></i> Actions
	    	 	</button>

	        	<div class="dropdown-menu" style="margin-left:-125px;"><!--- dropdown-menu Starts --->

					<a class="dropdown-item" href="../blog/post?id=<?= $post->id; ?>&lang=<?= $adminLanguage; ?>" target="blank">
						<i class="fa fa-eye"></i> Live Preview
					</a>

					<a class="dropdown-item" href="index?edit_post=<?= $post->id; ?>">
						<i class="fa fa-pencil"></i> Edit
					</a>
					
					<a class="dropdown-item" href="index?delete_post=<?= $post->id; ?>" onclick="if(!confirm('Are you sure you want to delete selected item.')){ return false; }">
						<i class="fa fa-trash"></i> Delete
					</a>	                
	        	
	      		</div><!--- dropdown-menu Ends --->

	    	</div><!--- dropdown Ends --->

		</td>

		</tr>
		<?php } ?>
	</tbody>
	</table><!--- table table-bordered table-hover table-striped Starts -->
	</div><!--- table-responsive Ends -->
</div><!-- card-body Ends -->
</div><!-- card card-default Ends -->
</div>
</div>
</div><!--- container Ends --->
<?php } ?>