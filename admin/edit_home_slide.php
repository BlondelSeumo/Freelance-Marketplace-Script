<?php


@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
    
	
?>

<?php

if(isset($_GET['edit_home_slide'])){
	
   $edit_id = $input->get('edit_home_slide');
   $edit_slide = $db->select("home_section_slider",array('slide_id' => $edit_id));

   if($edit_slide->rowCount() == 0){
   echo "<script>window.open('index?dashboard','_self');</script>";
   }

   $row_edit = $edit_slide->fetch();
   $s_name = $row_edit->slide_name;
   $s_image = $row_edit->slide_image;
   $s_extension = pathinfo($s_image, PATHINFO_EXTENSION);

}

?>

<div class="breadcrumbs">
   <div class="col-sm-4">
       <div class="page-header float-left">
           <div class="page-title">
               <h1><i class="menu-icon fa fa-picture-o"></i> Slides</h1>
           </div>
       </div>
   </div>
   <div class="col-sm-8">
       <div class="page-header float-right">
           <div class="page-title">
               <ol class="breadcrumb text-right">
                   <li class="active">Edit Slide</li>
               </ol>
           </div>
       </div>
   </div>
</div>

<div class="container">

    <div class="row"><!--- 2 row Starts --->

        <div class="col-lg-12"><!--- col-lg-12 Starts --->

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

            <div class="card"><!--- card Starts --->

                <div class="card-header"><!--- card-header Starts --->

                    <h4 class="h4">Edit Slide</h4>

                </div><!--- card-header Ends --->

                <div class="card-body"><!--- card-body Starts --->

                    <form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->

                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Slide Name : </label>

                            <div class="col-md-6">

                              <input type="text" name="slide_name" class="form-control" required value="<?= $s_name; ?>">

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Slide Image : </label>

                            <div class="col-md-6">

                              <input type="file" name="slide_image" class="form-control">
                              <br>

                              <?php if($s_extension == "mp4" or $s_extension == "webm" or $s_extension == "ogg"){ ?>
                                <video width="300" height="180" controls>
                                  <source src="<?= getImageUrl("home_section_slider",$s_image); ?>" type="video/mp4">
                                </video>
                              <?php }else{ ?>
                                <img src="<?= getImageUrl("home_section_slider",$s_image); ?>" width="100" height="40">
                              <?php } ?>

                              <br>

                              <small class="text-muted">
                              if you don't successfully upload video/image, then you might have to increase your : upload_max_filesize & post_max_size
                              </small>

                            </div>

                        </div><!--- form-group row Ends --->
                     
                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-6">

                              <input type="submit" name="update" class="btn btn-success form-control" value="Update Slide">

                            </div>

                        </div><!--- form-group row Ends --->

                    </form><!--- form Ends --->

                </div><!--- card-body Ends --->

            </div><!--- card Ends --->

        </div><!--- col-lg-12 Ends --->

    </div><!--- 2 row Ends --->

</div><!--- container Ends --->

<?php

if(isset($_POST['update'])){
   
   $rules = array(
   "slide_name" => "required");
   $val = new Validator($_POST,$rules);

   if($val->run() == false){
      Flash::add("form_errors",$val->get_all_errors());
      Flash::add("form_data",$_POST);
      echo "<script> window.open(window.location.href,'_self');</script>";
   }else{

      $slide_name = $input->post('slide_name');
      $slide_image = $_FILES['slide_image']['name'];  
      $tmp_slide_image = $_FILES['slide_image']['tmp_name'];   

      $allowed = array('jpeg','jpg','gif','png','tif','ico','webp','mp4','ogg','webm');
      $file_extension = pathinfo($slide_image, PATHINFO_EXTENSION);

      if(!in_array($file_extension,$allowed) & !empty($slide_image)){
        
         echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
        
      }else{

         if(empty($slide_image)){
            $slide_image = $s_image;
            $isS3 = $row_edit->isS3;
         }else{
            uploadToS3("home_slider_images/$slide_image",$tmp_slide_image);
            $isS3 = $enable_s3;
         }

         $update_slide = $db->update("home_section_slider",array("slide_name" => $slide_name,"slide_image" => $slide_image,"isS3"=>$isS3),array("slide_id" => $edit_id));

         if($update_slide){
            $insert_log = $db->insert_log($admin_id,"home_slide",$edit_id,"updated");
            echo "<script>alert('One Slide has been Updated.');</script>";
            echo "<script>window.open('index?layout_settings','_self');</script>";
         }  

      }

   }

}

?>

<?php } ?>
