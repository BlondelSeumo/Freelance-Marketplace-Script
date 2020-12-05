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
               <h1><i class="menu-icon fa fa-cog"></i> Settings / Insert Box</h1>
           </div>
       </div>
   </div>
   <div class="col-sm-8">
       <div class="page-header float-right">
           <div class="page-title">
               <ol class="breadcrumb text-right">
                   <li class="active">Insert Box</li>
               </ol>
           </div>
       </div>
   </div>
</div>

<div class="container"><!--- container Starts --->

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
         <li class="list-unstyled-item">
            <?= $i ?>. <?= ucfirst($error); ?>
         </li>
      <?php } ?>
   </ul>

</div><!--- alert alert-danger Ends --->

<?php } ?>

<div class="card"><!--- card Starts --->

<div class="card-header"><!--- card-header Starts --->

   <h4 class="card-title">Insert Box</h4>

</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

<form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->

   <div class="form-group row"><!--- form-group row Starts --->

   <label class="col-md-3 control-label"> Box Title : </label>

   <div class="col-md-6">

   <input type="text" name="box_title" class="form-control" required="">

   </div>

   </div><!--- form-group row Ends --->


   <div class="form-group row"><!--- form-group row Starts --->

   <label class="col-md-3 control-label"> Box Description : </label>

   <div class="col-md-6">

   <textarea name="box_desc" class="form-control" cols="6" required=""></textarea>

   </div>

   </div><!--- form-group row Ends --->


   <div class="form-group row"><!--- form-group row Starts --->

   <label class="col-md-3 control-label"> Box Image : </label>

   <div class="col-md-6">

   <input type="file" name="box_image" class="form-control" required="">

   </div>

   </div><!--- form-group row Ends --->


   <div class="form-group row"><!--- form-group row Starts --->

   <label class="col-md-3 control-label"></label>

   <div class="col-md-6">
      <input type="submit" name="submit" class="form-control btn btn-success" value="Insert Box">
   </div>

   </div><!--- form-group row Ends --->

</form><!--- form Ends --->

</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div><!--- container Ends --->

<?php

if(isset($_POST['submit'])){
	
   $rules = array(
   "box_title" => "required",
   "box_desc" => "required",
   "box_image" => "required");

   $messages = array("box_desc" => "Box description Is Required.");

   $val = new Validator($_POST,$rules,$messages);

   if($val->run() == false){

     Flash::add("form_errors",$val->get_all_errors());
     Flash::add("form_data",$_POST);

     echo "<script> window.open('index?insert_box','_self');</script>";

   }else{

      $box_title = $input->post('box_title');
      $box_desc = $input->post('box_desc');
      $box_image = $_FILES['box_image']['name'];
      $tmp_name = $_FILES['box_image']['tmp_name'];

      $allowed = array('jpeg','jpg','gif','png','tif','ico','webp');
      $file_extension = pathinfo($box_image, PATHINFO_EXTENSION);

      if(!in_array($file_extension,$allowed)){
        
         echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
        
      }else{

         uploadToS3("box_images/$box_image",$tmp_name);

         $insert_box = $db->insert("section_boxes",array("language_id" => $adminLanguage,"box_title" => $box_title,"box_desc" => $box_desc,"box_image" => $box_image,"isS3"=>$enable_s3));

         if($insert_box){

            $insert_id = $db->lastInsertId();
            $insert_log = $db->insert_log($admin_id,"section_box",$insert_id,"inserted");

            echo "<script>alert_success('One Box Successfully Inserted.','index?layout_settings');</script>";

         }
      	
      }

   }

}

?>

<?php } ?>