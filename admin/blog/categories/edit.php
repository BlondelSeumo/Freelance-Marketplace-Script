<?php
@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('../../login','_self');</script>";
	
}else{
    
if(isset($_GET['edit_post_cat'])){
	
$edit_id = $input->get('edit_post_cat');
$edit_cat = $db->select("post_categories",array('id' => $edit_id));
	
if($edit_cat->rowCount() == 0){
  echo "<script>window.open('index?dashboard','_self');</script>";
}

$cat = $edit_cat->fetch();


$get_meta = $db->select("post_categories_meta",array("cat_id" => $edit_id, "language_id" => $adminLanguage));
$row_meta = $get_meta->fetch();

$show_image = getImageUrl("post_categories",$cat->cat_image);

}

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
				  <li class="active">Edit Category</li>
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
				<i class="fa fa-money fa-fw"></i> Edit Category
				</div><!--- card-header Ends --->
				<div class="card-body"><!--- card-body Starts --->
					<form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->

						<div class="form-group row"><!--- form-group row Starts --->
							<label class="col-md-3 control-label"> Category Name: </label>
							<div class="col-md-6">
								<input type="text" name="cat_name" class="form-control" value="<?= $row_meta->cat_name; ?>" required="">
							</div>
						</div><!--- form-group row Ends --->

		            <div class="form-group row"><!--- form-group row Starts --->

			            <label class="col-md-3 control-label"> Category Image : </label>
			            <div class="col-md-6">
				            <input type="file" name="cat_image" class="form-control">
				            <br>
				            <?php if(!empty($cat->cat_image)){ ?>
				            	<img src="<?= $show_image; ?>" width="70" height="55">
				            <?php }else{ ?>
				            	<img src="../blog_cat_images/empty-image.jpg" width="70" height="55">
				            <?php } ?>
			            </div>

		            </div><!--- form-group row Ends --->

						<div class="form-group row"><!--- form-group row Starts --->
							<label class="col-md-3 control-label"></label>
							<div class="col-md-6">
								<input type="submit" name="update" class="form-control btn btn-success" value="Update Category">
							</div>
						</div><!--- form-group row Ends --->

					</form><!--- form Ends --->
				</div><!--- card-body Ends --->
			</div><!--- card Ends --->
		</div><!--- col-lg-12 Ends --->
	</div><!--- 2 row Ends --->
</div>

<?php 
	
if(isset($_POST['update'])){

	$data = $input->post();
	$data['date_time'] = date("F d, Y");
	unset($data['update']);

	$cat_image = $_FILES['cat_image']['name'];
	$tmp_cat_image = $_FILES['cat_image']['tmp_name'];

	$allowed = array('jpeg','jpg','gif','png','svg','tif','ico','webp');
	$file_extension = pathinfo($cat_image, PATHINFO_EXTENSION);

	if(!in_array($file_extension,$allowed) & !empty($cat_image)){
	  
		echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
	  
	}else{
			
		if(empty($cat_image)){
			$cat_image = $cat->cat_image;
			$isS3 = $cat->isS3;
		}else{
         uploadToS3("blog_cat_images/$cat_image",$tmp_cat_image);
         $isS3 = $enable_s3;
      	}

    	$update = $db->update("post_categories", array('cat_image' => $cat_image, 'isS3' => $isS3 ),array("id" => $cat->id));

    	unset($data['date_time']);
    	unset($data['cat_image']);
      
		$update = $db->update("post_categories_meta",$data,array(
			"cat_id" => $row_meta->cat_id,
			'language_id' => $adminLanguage
		) );
		if($update){
			$insert_log = $db->insert_log($admin_id,"post_cat",$row_meta->cat_id,"updated");
			echo "<script>alert_success('One Post Category has been Updated Successfully.','index?post_categories');</script>";
		}
	}
}

?>

<?php } ?>