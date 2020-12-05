<?php

if(isset($_GET['edit_post'])){
	
	$edit_id = $input->get('edit_post');
	$edit_post = $db->select("posts",array('id' => $edit_id));
		
	if($edit_post->rowCount() == 0){
	  echo "<script>window.open('index?dashboard','_self');</script>";
	}

	$post = $edit_post->fetch();	
	$query = $db->select('posts_meta', array('post_id' => $edit_id, 'language_id' => $adminLanguage));
	$post_meta = $query->fetch();
	
	$title = !empty($post_meta->title) ? $post_meta->title: '';
	$author = !empty($post_meta->author) ? $post_meta->author: '';
	$content = !empty($post_meta->content) ? $post_meta->content: '';	

}

?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css" rel="stylesheet">
<script type="text/javascript" src="../js/popper.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="../js/summernote.js"></script>

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
				  <li class="active">Edit Post</li>
				</ol>
		  </div>
		</div>
	</div>
</div>

<div class="container"><!--- container Starts --->
<div class="row"><!--- 2 row Starts --->
	<div class="col-lg-12"><!--- col-lg-12 Starts --->
		<div class="card"><!--- card Starts --->
			<div class="card-header"><!--- card-header Starts --->
			<i class="fa fa-money fa-fw"></i> Edit Post
			</div><!--- card-header Ends --->
			<div class="card-body"><!--- card-body Starts --->

				<form action="" method="post" enctype="multipart/form-data">
				<div class="form-group row"><!--- form-group row Starts --->
					<label class="col-md-3 control-label"> Title: </label>
					<div class="col-md-7">
						<input type="text" name="title" class="form-control" value="<?= $title; ?>" required="">
					</div>
				</div><!--- form-group row Ends --->
				<div class="form-group row"><!--- form-group row Starts --->
					<label class="col-md-3 control-label"> Category: </label>
					<div class="col-md-7">
					<select class="form-control" name="cat_id" required="">
					<?php
						$get_cats = $db->select("post_categories_meta", array('language_id' => $adminLanguage));						
						//print_r($get_cats->fetchAll());
						while($cat = $get_cats->fetch()){
							if($cat->cat_id == $post->cat_id) {
								echo "<option value='$cat->cat_id' selected>$cat->cat_name</option>";
							}else{
								echo "<option value='$cat->cat_id'>$cat->cat_name</option>";
							}
						}
					?>
					</select>
					</div>
				</div><!--- form-group row Ends --->

				<div class="form-group row"><!--- form-group row Starts --->
					<label class="col-md-3 control-label"> Author: </label>
					<div class="col-md-7">
						<input type="text" name="author" class="form-control" value="<?= $author; ?>" required=""/>
					</div>
				</div><!--- form-group row Ends --->

				<div class="form-group row"><!--- form-group row Starts --->
					<label class="col-md-3 control-label"> Post Image: </label>
					<div class="col-md-7">
						<input type="file" name="image" class="form-control">
						<br>
						<?php if(!empty($post->image)){ ?>
							<img src="<?= getImageUrl("posts",$post->image); ?>" width="100" height="55">
						<?php }else{ ?>
							<img src="../cat_images/empty-image.jpg" width="70" height="55">
						<?php } ?>
					</div>
				</div><!--- form-group row Ends --->

				<div class="form-group row"><!--- form-group row Starts --->
				<label class="col-md-3 control-label"> Content: </label>
				<div class="col-md-7">
					<textarea name="content" class="form-control" rows="4"><?= $content; ?></textarea>
				</div>
				</div><!--- form-group row Ends --->
				<div class="form-group row"><!--- form-group row Starts --->
					<label class="col-md-3 control-label"></label>
					<div class="col-md-7">
						<input type="submit" name="update" class="form-control btn btn-success" value="Update Post">
					</div>
				</div><!--- form-group row Ends --->
			</form><!--- form Ends --->

			</div><!--- card-body Ends --->
		</div><!--- card Ends --->
	</div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div><!--- container Ends --->

<script>
	$('textarea').summernote({
	  placeholder: 'Start Typing Here...',
	  height: 280,
	  toolbar: [
		  ['style', ['style']],
		  ['font', ['bold', 'italic', 'underline', 'clear']],
		  ['fontname', ['fontname']],
		  ['fontsize', ['fontsize']],
		  ['height', ['height']],
		  ['color', ['color']],
		  ['para', ['ul', 'ol', 'paragraph']],
		  ['misc', ['codeview']]
	  ],
	});
</script>

<?php

if(isset($_POST['update'])){

  
	require_once("includes/removeJava.php");

	$data = $input->post();
	$data['content'] = removeJava($_POST['content']);
	$data['date_time'] = date("F d, Y");


	$post_meta_data['author'] = $data['author'];
	$post_meta_data['title'] = $data['title'];
	$post_meta_data['content'] = $data['content'];	


	unset($data['update']);
	unset($data['author']);
	unset($data['title']);
	unset($data['content']);

	$image = $_FILES['image']['name'];
	$tmp_image = $_FILES['image']['tmp_name'];

	$allowed = array('jpeg','jpg','gif','png','webp');
	$file_extension = pathinfo($image, PATHINFO_EXTENSION);

	if(!in_array($file_extension,$allowed) & !empty($image)){
		echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
	}else{

		if(empty($image)){
			$image = $post->image;
			$isS3 = $post->isS3;
		}else{
			uploadToS3("post_images/$image",$tmp_image);
			$isS3 = $enable_s3;
		}

		$data['isS3'] = $isS3;
		$data['image'] = $image;
		$data['status'] = 1;
		$update = $db->update("posts",$data,["id"=>$post->id]);
		if($update){
			$update_post_meta = $db->update("posts_meta", $post_meta_data,["post_id" => $edit_id, "language_id" => $adminLanguage]);
			$insert_log = $db->insert_log($admin_id,"post",$post->id,"updated");
			echo "<script>alert_success('One Post has been Updated Successfully.','index?posts');</script>";
		}
	}
}

?>