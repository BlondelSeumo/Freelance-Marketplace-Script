<?php


@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{

if(isset($_GET['edit_seller_language'])){
		
$edit_id = $input->get('edit_seller_language');

$edit_language = $db->select("seller_languages",array('language_id'=>$edit_id));

if($edit_language->rowCount() == 0){

  echo "<script>window.open('index?dashboard','_self');</script>";

}

$row_edit = $edit_language->fetch();
		
$language_id = $row_edit->language_id;

$language_title = $row_edit->language_title;
    
}
		
	
?>


<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-language"></i> Seller Language</h1>
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

                    <h4 class="h4">

                         Edit Language

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <form action="" method="post">
                        <!--- form Starts --->

                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label">Language Title : </label>

                            <div class="col-md-6">

                                <input type="text" name="language_title" class="form-control" value="<?= $language_title; ?>" required>

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-6">

                                <input type="submit" name="update_seller_language" class="btn btn-success form-control" value="Update Language">

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


    <?php

if(isset($_POST['update_seller_language'])){

$rules = array("language_title" => "required");

$messages = array("language_title" => "Seller Language Title Is Required.");

$val = new Validator($_POST,$rules,$messages);

if($val->run() == false){

Flash::add("form_errors",$val->get_all_errors());

Flash::add("form_data",$_POST);

echo "<script> window.open(window.location.href,'_self');</script>";

}else{

$language_title = $input->post('language_title');

$update_seller_language = $db->update("seller_languages",array("language_title"=>$language_title),array("language_id" => $language_id));

if($update_seller_language){

$insert_log = $db->insert_log($admin_id,"seller_language",$language_id,"updated");
	
echo "<script>alert('One Seller Language Has Been Updated.');</script>";
	
echo "<script>window.open('index?view_seller_languages','_self');</script>";
	
}

}

}

?>

</div>

<?php } ?>