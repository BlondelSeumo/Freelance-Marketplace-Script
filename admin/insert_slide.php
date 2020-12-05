<?php

@session_start();

if(!isset($_SESSION['admin_email'])){

echo "<script>window.open('login','_self');</script>";

}else{

?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-picture-o"></i> Slides </h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Add New Slide</li>
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
            <h4 class="h4">
                Insert New Slide <br>
                <small>This slide will show in logged in homepage</small>
            </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
            <!--- card-body Starts --->
            <form action="" method="post" enctype="multipart/form-data">
                <!--- form Starts --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Slide Name : </label>
                    <div class="col-md-6">
                        <input type="text" name="slide_name" class="form-control">
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Slide Description : </label>
                    <div class="col-md-6">
                        <textarea name="slide_desc" class="form-control"></textarea>
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Slide Image/Video : </label>
                    <div class="col-md-6">
                        <input type="file" name="slide_image" class="form-control" required>
                        <small class="text-muted">
                          if you don't successfully upload video/image, then you might have to increase your :
                          upload_max_filesize & post_max_size
                        </small>
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Slide Url : </label>
                    <div class="col-md-6">
                        <input type="text" name="slide_url" class="form-control"><br>
                        <span class="text-muted">Include the protocol "http://" or "https://" e.g https://www.example.com </span>
                    </div>
                </div>
                <!--- form-group row Ends --->
                <div class="form-group row">
                    <!--- form-group row Starts --->
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-6">
                        <input type="submit" name="submit" class="btn btn-success form-control" value="Add Slide">
                    </div>
                </div>
                <!--- form-group row Ends --->
            </form>
            <!--- form Ends --->
        </div>
        <!--- card-body Ends --->
    </div>
    <!--- card Ends --->
</div>
<!--- col-lg-12 Ends --->
</div>
<!--- 2 row Ends --->

</div><!--- Container Ends --->

<?php

if(isset($_POST['submit'])){

   // $rules = array(
   //  "slide_name" => "required",
   //  "slide_desc" => "required",
   //  "slide_url" => "required",
   //  "slide_image" => "required");

   // $val = new Validator($_POST,$rules);

   // if($val->run() == false){

   //    Flash::add("form_errors",$val->get_all_errors());
   //    Flash::add("form_data",$_POST);
   //    echo "<script> window.open('index?insert_slide','_self');</script>";

   // }else{

      $slide_name = $input->post('slide_name');
      $slide_desc = $input->post('slide_desc');
      $slide_url = $input->post('slide_url');

      $slide_image = $_FILES['slide_image']['name'];	
      $tmp_slide_image = $_FILES['slide_image']['tmp_name'];	

      $allowed = array('jpeg','jpg','gif','png','tif','ico','webp','mp4','ogg','webm');
      $file_extension = pathinfo($slide_image, PATHINFO_EXTENSION);

      if(!in_array($file_extension,$allowed)){
         echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
      }else{

         uploadToS3("slides_images/$slide_image",$tmp_slide_image);

         $insert_slide = $db->insert("slider",array("language_id" => $adminLanguage,"slide_name" => $slide_name, "slide_desc" => $slide_desc,"slide_image" => $slide_image,"slide_url" => $slide_url,"isS3"=>$enable_s3));

         if($insert_slide){
            $insert_id = $db->lastInsertId();
            $insert_log = $db->insert_log($admin_id,"slide",$insert_id,"inserted");
            echo "<script>alert('One Slide has been Inserted.');</script>";
            echo "<script>window.open('index?view_slides','_self');</script>";
         }

      }

  // }

}

?>

<?php } ?>