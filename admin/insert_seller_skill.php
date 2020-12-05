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
                        <h1><i class="menu-icon fa fa-flash"></i> Seller Skills / Insert</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Add New Seller Skill</li>
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

                        Add New Skill

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <form action="" method="post">
                        <!--- form Starts --->

                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Seller Skill Title : </label>

                            <div class="col-md-6">

                                <input type="text" name="skill_title" class="form-control" required="">

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-6">

                                <input type="submit" name="submit" class="btn btn-success form-control" value="Add Skill">

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

if(isset($_POST['submit'])){

    $rules = array("skill_title" => "required");
    $messages = array("skill_title" => "Seller Skill Title Is Required.");
    $val = new Validator($_POST,$rules,$messages);

    if($val->run() == false){

        Flash::add("form_errors",$val->get_all_errors());
        Flash::add("form_data",$_POST);
        echo "<script> window.open('index?insert_seller_skill','_self');</script>";

    }else{

        $data = $input->post();
        unset($data['submit']);

        $insert_seller_skill = $db->insert("seller_skills",$data);
        if($insert_seller_skill){

            $insert_id = $db->lastInsertId();
            $insert_log = $db->insert_log($admin_id,"seller_skill",$insert_id,"inserted");
                
            echo "<script>alert('One Seller Skill Has Been Inserted.');</script>";
            echo "<script>window.open('index?view_seller_skills','_self');</script>";

        }

    }

}

?>

</div>

<?php } ?>