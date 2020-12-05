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
                         <h1><i class="menu-icon fa fa-cog"></i> Settings / Insert card</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Insert card</li>
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

<h4 class="card-title">Insert card</h4>

</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

<form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->


<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Card Title : </label>

<div class="col-md-6">

<input type="text" name="card_title" class="form-control" required="">

</div>

</div><!--- form-group row Ends --->


<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Card Description : </label>

<div class="col-md-6">

<textarea name="card_description" class="form-control" cols="6" required=""></textarea>

</div>

</div><!--- form-group row Ends --->



<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Card Link : </label>

<div class="col-md-6">

<input type="text" name="card_link" class="form-control" value="" required="">

</div>

</div><!--- form-group row Ends --->


<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Card Image : </label>

<div class="col-md-6">

<input type="file" name="card_image" class="form-control" required="">

</div>

</div><!--- form-group row Ends --->

<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"></label>

<div class="col-md-6">

<input type="submit" name="submit" class="form-control btn btn-success" value="Insert card">

</div>

</div><!--- form-group row Ends --->


</form><!--- form Ends --->

</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->
    
</div>

<?php

if(isset($_POST['submit'])){

   $rules = array(
   "card_title" => "required",
   "card_description" => "required",
   "card_link" => "required",
   "card_image" => "required");

   $val = new Validator($_POST,$rules);

   if($val->run() == false){

   Flash::add("form_errors",$val->get_all_errors());

   Flash::add("form_data",$_POST);

   echo "<script> window.open('index?insert_card','_self');</script>";

   }else{

      $card_title = $input->post('card_title');
      $card_desc = $input->post('card_description');
      $card_link = $input->post('card_link');
      	
      $card_image = $_FILES['card_image']['name'];
      $tmp_name = $_FILES['card_image']['tmp_name'];

      $allowed = array('jpeg','jpg','gif','png','tif','ico','webp');

      $file_extension = pathinfo($card_image, PATHINFO_EXTENSION);

      if(!in_array($file_extension,$allowed)){

      echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";

      }else{

         uploadToS3("card_images/$card_image",$tmp_name);

         $insert_card = $db->insert("home_cards",array("language_id" => $adminLanguage,"card_title" => $card_title,"card_desc" => $card_desc,"card_link" => $card_link,"card_image" => $card_image,"isS3"=>$enable_s3));

         if($insert_card){
        	
            $insert_id = $db->lastInsertId();
            $insert_log = $db->insert_log($admin_id,"section_card",$insert_id,"inserted");

            echo "<script>
               alert_success('One Card has been Inserted Successfully.','index?layout_settings');
            </script>";

         }
      	
      }

   }

}

?>

<?php

}

?>