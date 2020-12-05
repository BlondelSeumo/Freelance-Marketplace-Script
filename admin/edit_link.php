<?php 

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login.php','_self');</script>";
	
}else{
	
if(isset($_GET['edit_link'])){
	
$edit_id = $input->get('edit_link');


$get_footer_link = $db->select("footer_links",array("link_id"=>$edit_id,"language_id"=>$adminLanguage));

if($get_footer_link->rowCount() == 0){

  echo "<script>window.open('index?dashboard','_self');</script>";

}

$row_footer_link = $get_footer_link->fetch();

$link_id = $row_footer_link->link_id;

$link_title = $row_footer_link->link_title;

$icon_class = $row_footer_link->icon_class;

$link_section = $row_footer_link->link_section;

$link_url = $row_footer_link->link_url;

	
}
	
?>



<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-picture-o"></i> Settings/ Layout Settings / Footer Links</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Edit Link</li>
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

<h4 class="h4">

<i class="fa fa-money fa-fw"></i> Edit Link

</h4>

</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

<form action="" method="post"><!--- form Starts --->

<?php if($link_section != "categories"){ ?>

<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> FontAwesome Icon Class Name : </label>

<div class="col-md-6">

<input type="text" name="icon_class" class="form-control" required value="<?= $icon_class; ?>">

<small>
  <a target="_blank" href="https://fontawesome.com/v4.7.0/icons/" class="btn-link">Click Here</a> To View All Font Awesome Icons Class Names. “Example of sample code: fa-comments
</small>

</div>

</div><!--- form-group row Ends --->

<?php }else{ echo "<input type='hidden' name='icon_class' value=''>"; } ?> 


<?php if($link_section !== "follow"){ ?>

<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Link Title : </label>

<div class="col-md-6">

<input type="text" name="link_title" class="form-control" required value="<?= $link_title; ?>">

</div>

</div><!--- form-group row Ends --->

<?php }else{ echo "<input type='hidden' name='link_title' value=''>"; } ?>

<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"> Link Url : </label>

<div class="col-md-6">

<input type="text" name="link_url" class="form-control" required value="<?= $link_url; ?>">

</div>

</div><!--- form-group row Ends --->


<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"></label>

<div class="col-md-6">

<input type="submit" name="update_link" value="Update Link" class="btn btn-success form-control">

</div>

</div><!--- form-group row Ends --->


</form><!--- form Ends --->

</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div>

<?php

if(isset($_POST['update_link'])){

$rules = array("link_url" => "required");

$val = new Validator($_POST,$rules);

if($val->run() == false){

Flash::add("form_errors",$val->get_all_errors());

Flash::add("form_data",$_POST);

echo "<script> window.open(window.location.href,'_self');</script>";

}else{


$icon_class = $input->post("icon_class");

$link_title = $input->post("link_title");

$link_url = $input->post("link_url");


$update_link = $db->update("footer_links",array("icon_class"=>$icon_class,"link_title"=>$link_title,"link_url"=>$link_url),array("link_id"=>$link_id,"language_id"=>$adminLanguage));

if($update_link){

echo "<script>alert('One Footer Link Has Been Updated.');</script>";
	
echo "<script>window.open('index?layout_settings','_self');</script>";
	
}	


}
	
}

?>

<?php } ?>