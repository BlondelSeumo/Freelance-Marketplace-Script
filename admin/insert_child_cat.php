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
                <h1><i class="menu-icon fa fa-cubes"></i> Categories/Insert New</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Insert Category</li>
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

                    <h4 class="h4">Insert Sub Category</h4>

                </div><!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <form action="" method="post">
                        <!--- form Starts --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-4 control-label"> Sub Category Title : </label>

                            <div class="col-md-6">

                                <input type="text" name="child_title" class="form-control" required="">

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-4 control-label"> Sub Category Description : </label>

                            <div class="col-md-6">

                                <textarea name="child_desc" class="form-control" required=""></textarea>

                            </div>

                        </div>
                        <!--- form-group row Ends --->



                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-4 control-label"> Select A Parent Category : </label>

                            <div class="col-md-6">

                                <select name="parent_cat" class="form-control" required="">

                                    <option value=""> Select A Parent Category </option>

                                    <?php 

                                        $get_cats = $db->select("categories");
                                        while($row_cats = $get_cats->fetch()){

                                            $cat_id = $row_cats->cat_id;

                                            $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id, "language_id" => $adminLanguage));
                                            $cat_title = $get_meta->fetch()->cat_title;

                                            echo "<option value='$cat_id'>$cat_title</option>";

                                        }

                                    ?>

                                    </select>

                            </div>

                        </div>
                        <!--- form-group row Ends --->

                        <?php 
                            if($videoPlugin == 1){ 
                              include("../plugins/videoPlugin/admin/insert_child_cat.php");
                            }
                        ?>

                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-4 control-label"></label>

                            <div class="col-md-6">

                                <input type="submit" name="submit" value="Insert Sub Category" class="btn btn-success form-control">

                            </div>

                        </div><!--- form-group row Ends --->



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

include("includes/sanitize_url.php");

if(isset($_POST['submit'])){
	
    $rules = array("child_title" => "required","child_desc" => "required","parent_cat" => "required");

    $messages = array("child_title" => "Sub Category Title Is Required.","child_desc" => "Sub Category Description Is Required.","parent_cat" => "Your Must Need To Select A Parent Category.");
    $val = new Validator($_POST,$rules,$messages);

    if($val->run() == false){

        Flash::add("form_errors",$val->get_all_errors());
        Flash::add("form_data",$_POST);

        echo "<script> window.open('index?insert_child_cat','_self');</script>";

    }else{

        $child_title = $input->post('child_title');
        $child_desc = $input->post('child_desc');
        $parent_cat = $input->post('parent_cat');

        $child_url = slug($child_title);
                	
        if($videoPlugin == 1){
            $video = $input->post('video');
            if(empty($video)){
              $video = 0;
            }
            $insert_child_cat = $db->insert("categories_children",["child_url"=>$child_url,"child_parent_id" => $parent_cat,"video" => $video]);
        }else{
            $insert_child_cat = $db->insert("categories_children",["child_url"=>$child_url,"child_parent_id" => $parent_cat]);
        }

        if($insert_child_cat){
        	
            $insert_id = $db->lastInsertId();

            $get_languages = $db->select("languages");
            while($row_languages = $get_languages->fetch()){
                $id = $row_languages->id;
                $insert = $db->insert("child_cats_meta",array("child_id" => $insert_id,"language_id" => $id));
            }

            $update_meta = $db->update("child_cats_meta",array("child_title" => $child_title,"child_desc" => $child_desc),array("child_id" => $insert_id, "language_id" => $adminLanguage));
            $insert_log = $db->insert_log($admin_id,"child_cat",$insert_id,"inserted");

            echo "<script>alert('One Sub Category Has Been Inserted.');</script>";
            echo "<script>window.open('index?view_child_cats','_self');</script>";

        }

    }

}

?>



</div>

<?php } ?>