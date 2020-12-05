<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

if(isset($_GET['edit_language'])){
		
$edit_id = $input->get('edit_language');
$edit_language = $db->select("languages",array('id' => $edit_id));
if($edit_language->rowCount() == 0){
  echo "<script>window.open('index?dashboard','_self');</script>";
}

$row_edit = $edit_language->fetch();
$id = $row_edit->id;
$title = $row_edit->title;
$image = $row_edit->image;
$l_image = $row_edit->image;
$default_lang = $row_edit->default_lang;
$direction = $row_edit->direction;
$isS3 = $row_edit->isS3;
    
}	
	
?>

<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-cubes"></i> Languages </h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active"> Edit Language</li>
                        </ol>
                    </div>
                </div>
            </div>
    
    </div>


<div class="container">

    <div class="row">
        <!--- 2 row Starts --->

        <div class="col-lg-12">
            <!--- col-lg-12 Starts --->

        <?php 

        $form_errors = Flash::render("form_errors");

        $form_data = Flash::render("form_data");

        if(is_array($form_errors)){

        ?>

        <div class="alert alert-danger"><!--- alert alert-danger Starts --->
          
        <ul class="list-unstyled mb-0">
        <?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
        <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
        <?php } ?>
        </ul>

        </div><!--- alert alert-danger Ends --->

        <?php } ?>

            <div class="card">
                <!--- card Starts --->

                <div class="card-header">
                    <!--- card-header Starts --->

                    <h4 class="h4">Edit Language</h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <form action="" method="post" enctype="multipart/form-data">
                        <!--- form Starts --->

                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label">Language Title : </label>

                            <div class="col-md-6">

                                <input type="text" disabled="" name="title" class="form-control" value="<?= $title; ?>" required>
                                <small><span class="text-danger">! Important</span> Language Title Can Not Be Changed After Adding.</small>
                            </div>

                        </div>
                        <!--- form-group row Ends --->



                        <div class="form-group row"><!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Language Image : </label>

                        <div class="col-md-6">

                        <input type="file" name="image" class="form-control" />

                        <br>

                        <?php if(!empty($image)){ ?>

                        <img src="<?= getImageUrl("languages",$image); ?>" width="70" height="55">

                        <?php }else{ ?>

                        <img src="../languages/images/empty-image.jpg" width="70" height="55">

                        <?php } ?>

                        </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Default Language : </label>

                        <div class="col-md-6">
                        
                        <select name="default_lang" class="form-control">
                            <option value="1" <?= ($default_lang == 1 ? "selected" : "") ?>>Yes</option>
                            <option value="0" <?= ($default_lang == 0 ? "selected" : "") ?>>No</option>
                        </select>

                        </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Language Direction : </label>

                        <div class="col-md-6">
                        
                        <input id="radio-1" type="radio" name="direction" value="left" required

                        <?php if($direction == "left"){ echo "checked='checked'"; } ?>>

                        <label for="radio-1"> Left To Right </label>

                        <input id="radio-2" type="radio" name="direction" value="right" required

                        <?php if($direction == "right"){ echo "checked='checked'"; } ?>>

                        <label for="radio-2"> Right To Left </label>

                        </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-6">

                                <input type="submit" name="update_language" class="btn btn-success form-control" value="Update Language">

                            </div>

                        </div><!--- form-group row Ends --->

                    </form><!--- form Ends --->

                </div><!--- card-body Ends --->

            </div><!--- card Ends --->

        </div><!--- col-lg-12 Ends --->

    </div><!--- 2 row Ends --->

	</div>

<?php

if(isset($_POST['update_language'])){

   $default_lang = $input->post('default_lang');
   $direction = $input->post('direction');

   $image = $_FILES['image']['name'];
   $tmp_image = $_FILES['image']['tmp_name'];
       
   $allowed = array('jpeg','jpg','gif','png','tif','ico','webp');
     
   $file_extension = pathinfo($image, PATHINFO_EXTENSION);

   if(!in_array($file_extension,$allowed) & !empty($image)){
     
   echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";

   }else{

      if(empty($image)){
        $image = $l_image;
      }else{
        uploadToS3("languages_images/$image",$tmp_image);
        $isS3 = $enable_s3;
      }
          
      if($default_lang == 1){ $db->update("languages",["default_lang"=> 0]); }

      $update_language = $db->update("languages",array("default_lang"=>$default_lang,"direction" => $direction,"image"=>$image,"isS3"=>$isS3),array("id" => $id));
              
      if($update_language){

         $insert_log = $db->insert_log($admin_id,"language",$id,"updated");
         echo "<script>alert('One Language Has Been Updated.');</script>";
         echo "<script>window.open('index?view_languages','_self');</script>";

      }

   }

}
    
?>

<?php } ?>
