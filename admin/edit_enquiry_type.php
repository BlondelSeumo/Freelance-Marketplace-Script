<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
    
	
?>

<?php

if(isset($_GET['edit_enquiry_type'])){
	
$edit_id = $input->get('edit_enquiry_type');
			
$edit_enquiry_type = $db->select("enquiry_types",array('enquiry_id'=>$edit_id));

if($edit_enquiry_type->rowCount() == 0){

  echo "<script>window.open('index?dashboard','_self');</script>";

}

$row_edit = $edit_enquiry_type->fetch();
	
$enquiry_id = $row_edit->enquiry_id;

$enquiry_title = $row_edit->enquiry_title;

	
}

?>


<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-phone-square"></i> Support Settings</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Edit Enquiry</li>
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

                        Edit Enquiry Type

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <form action="" method="post">
                        <!--- form Starts --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Enquiry Type Title : </label>

                            <div class="col-md-6">

                                <input type="text" name="enquiry_title" class="form-control" value="<?= $enquiry_title; ?>" required>

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-6">

                                <input type="submit" name="update" class="btn btn-success form-control" value="Update Enquiry Type">

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

if(isset($_POST['update'])){
	
$rules = array("enquiry_title" => "required");

$messages = array("enquiry_title" => "Enquiry Type Title Is Required.");

$val = new Validator($_POST,$rules,$messages);

if($val->run() == false){

Flash::add("form_errors",$val->get_all_errors());

Flash::add("form_data",$_POST);

echo "<script> window.open(window.location.href,'_self');</script>";

}else{


$enquiry_title = $input->post('enquiry_title');
	
$update_enquiry_type = $db->update("enquiry_types",array("enquiry_title"=>$enquiry_title),array("enquiry_id"=>$enquiry_id));

if($update_enquiry_type){

$insert_log = $db->insert_log($admin_id,"enquiry_type",$enquiry_id,"updated");

echo "<script>alert('One Enquiry Type Has been Updated.');</script>";
	
echo "<script>window.open('index?view_enquiry_types','_self');</script>";

	
}

}	

}

?>

        <?php } ?>