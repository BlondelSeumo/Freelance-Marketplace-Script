<?php


@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
    
    	
if(isset($_GET['edit_seller_level'])){
	
$edit_id = $input->get('edit_seller_level');
		
$edit_level = $db->select("seller_levels",array('level_id'=>$edit_id));

if($edit_level->rowCount() == 0){

  echo "<script>window.open('index?dashboard','_self');</script>";

}

$row_edit = $edit_level->fetch();
	
$level_id = $row_edit->level_id;

@$level_title =  $db->select("seller_levels_meta",array("level_id"=>$level_id,"language_id"=>$adminLanguage))->fetch()->title;


$count_meta = $db->select("seller_levels_meta",array("level_id"=>$level_id,"language_id"=>$adminLanguage))->rowCount();

	
}	

?>


<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-flash"></i> Seller Levels / Edit</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Edit Seller Level</li>
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

                <h4 class="h4">Edit Level</h4>

            </div><!--- card-header Ends --->

            <div class="card-body"><!--- card-body Starts --->

                <form action="" method="post"><!--- form Starts --->

                    <div class="form-group row"><!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Seller Level Title : </label>

                        <div class="col-md-6">

                            <input type="text" name="level_title" class="form-control" value="<?= $level_title; ?>" required>

                        </div>

                    </div><!--- form-group row Ends --->


                    <div class="form-group row"><!--- form-group row Starts --->

                        <label class="col-md-3 control-label"></label>

                        <div class="col-md-6">

                            <input type="submit" name="update_seller_level" class="btn btn-success form-control" value="Update Seller Level">

                        </div>

                    </div><!--- form-group row Ends --->


                </form><!--- form Ends --->

            </div><!--- card-body Ends --->

        </div><!--- card Ends --->

    </div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->


<?php

    if(isset($_POST['update_seller_level'])){

        $rules = array("level_title" => "required");
        $messages = array("level_title" => "Seller Level Title Is Required.");
        $val = new Validator($_POST,$rules,$messages);

        if($val->run() == false){

            Flash::add("form_errors",$val->get_all_errors());
            Flash::add("form_data",$_POST);
            echo "<script> window.open(window.location.href,'_self');</script>";

        }else{

            $level_title = $input->post('level_title');

            if($count_meta == 1){
                $update_meta = $db->update("seller_levels_meta",array("title"=>$level_title),array("level_id"=>$level_id,"language_id"=>$adminLanguage));
        	}else{
                $update_meta = $db->insert("seller_levels_meta",array("language_id"=>$adminLanguage,"level_id"=>$level_id,"title"=>$level_title));
        	}

            if($update_meta){
                $insert_log = $db->insert_log($admin_id,"seller_level",$level_id,"updated");
                echo "<script>alert('One Seller Level Has Been Updated.');</script>";
                echo "<script>window.open('index?view_seller_levels','_self');</script>";
            }

        }

    }

?>

</div>

<?php } ?>