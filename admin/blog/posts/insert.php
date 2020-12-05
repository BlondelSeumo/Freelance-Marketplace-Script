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
				  <li class="active">Insert Post</li>
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
					<i class="fa fa-money fa-fw"></i> Insert Post
				</div><!--- card-header Ends --->
				<div class="card-body"><!--- card-body Starts --->
					<form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->

						<div class="form-group row"><!--- form-group row Starts --->
							<label class="col-md-3 control-label"> Title: </label>
							<div class="col-md-7">
								<input type="text" name="title" class="form-control" required="">
							</div>
						</div><!--- form-group row Ends --->

						<div class="form-group row"><!--- form-group row Starts --->
							<label class="col-md-3 control-label"> Category: </label>
							<div class="col-md-7">
								<select class="form-control" name="cat_id" required="">
								<?php

								$get_cats = $db->select("post_categories_meta",['language_id'=>$adminLanguage]);

								while($cat = $get_cats->fetch()){
									echo "<option value='$cat->id'>$cat->cat_name</option>";
								}
									
								?>
								</select>
							</div>
						</div><!--- form-group row Ends --->

						<div class="form-group row"><!--- form-group row Starts --->
							<label class="col-md-3 control-label"> Author: </label>
							<div class="col-md-7">
								<input type="text" name="author" class="form-control" required="">
							</div>
						</div><!--- form-group row Ends --->

						<div class="form-group row"><!--- form-group row Starts --->
							<label class="col-md-3 control-label"> Post Image: </label>
							<div class="col-md-7">
								<input type="file" name="image" class="form-control" required=""/>
							</div>
						</div><!--- form-group row Ends --->

						<div class="form-group row"><!--- form-group row Starts --->
							<label class="col-md-3 control-label"> Content: </label>
							<div class="col-md-7">
								<textarea name="content" class="form-control" rows="4" required=""></textarea>
							</div>
						</div><!--- form-group row Ends --->

						<div class="form-group row"><!--- form-group row Starts --->
							<label class="col-md-3 control-label"></label>
							<div class="col-md-7">
								<input type="submit" name="insert_post" class="form-control btn btn-success" value="Insert Post">
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
if(isset($_POST['insert_post'])){
  
	require_once("includes/removeJava.php");

	$data = $input->post();
	$data['content'] = removeJava($_POST['content']);
	$data['date_time'] = date("F d, Y");

	$post_meta['title'] = $data['title'];
	$post_meta['author'] = $data['author'];
	$post_meta['content'] = $data['content'];	

	unset($data['insert_post']);
	unset($data['title']);
	unset($data['author']);
	unset($data['content']);

	$image = $_FILES['image']['name'];
	$tmp_image = $_FILES['image']['tmp_name'];

	$allowed = array('jpeg','jpg','gif','png','webp');
	$file_extension = pathinfo($image, PATHINFO_EXTENSION);

	if(!in_array($file_extension,$allowed) & !empty($image)){
		echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";	  
	}else{
		
		uploadToS3("post_images/$image",$tmp_image);
		
		$data['isS3'] = $enable_s3;
		$data['image'] = $image;
		$data['status'] = 1;
		$insert = $db->insert("posts",$data);
		if($insert){
			$insert_id = $db->lastInsertId();
			$get_languages = $db->select("languages");
			while($row_languages = $get_languages->fetch()){
				$id = $row_languages->id;
				$insert = $db->insert("posts_meta",array("post_id"=>$insert_id,"language_id"=>$id));
			}
			$update_meta = $db->update("posts_meta",$post_meta,array("post_id" => $insert_id, "language_id" => $adminLanguage));
			$insert_log = $db->insert_log($admin_id,"post",$insert_id,"inserted");
			echo "<script>alert_success('One Post has been Inserted Successfully.','index?posts');</script>";
		}			
	}
}

?>