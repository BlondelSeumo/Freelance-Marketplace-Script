<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
    
if(isset($_GET['edit_child_cat'])){
	
    $edit_id = $input->get('edit_child_cat');
    	
    $edit_child_cat = $db->select("categories_children",array("child_id" =>$edit_id));
    if($edit_child_cat->rowCount() == 0){
      echo "<script>window.open('index?dashboard','_self');</script>";
    }
        
    $row_edit = $edit_child_cat->fetch();
    $child_id = $row_edit->child_id;
    $child_parent_id = $row_edit->child_parent_id;
    $video = $row_edit->video;

    $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id,"language_id" => $adminLanguage));
    $row_meta = $get_meta->fetch();
    $child_title = $row_meta->child_title;
    $child_desc = $row_meta->child_desc;

    $get_meta = $db->select("cats_meta",array("cat_id" => $child_parent_id, "language_id" => $adminLanguage));
    $row_meta = $get_meta->fetch();
    $cat_title = $row_meta->cat_title;



}
	
?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-cubes"></i> Categories</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Edit Sub Category</li>
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

            <div class="card">
                <!--- card Starts --->

                <div class="card-header">
                    <!--- card-header Starts --->

                    <h4 class="h4">Edit Sub Category</h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <form action="" method="post">
                        <!--- form Starts --->

                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-4 control-label"> Sub Category Title : </label>

                            <div class="col-md-6">

                                <input type="text" name="child_title" class="form-control" required value="<?= $child_title; ?>">

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-4 control-label"> Sub Category Description : </label>

                            <div class="col-md-6">

                                <textarea name="child_desc" class="form-control" required><?= $child_desc; ?></textarea>

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-4 control-label"> Select A Parent Category : </label>

                            <div class="col-md-6">

                                <select name="parent_cat" class="form-control" required>

                                    <option value="<?= $child_parent_id; ?>"> <?= $cat_title; ?> </option>

                                    <?php 

                                    $get_cats = $db->query("select * from categories where not cat_id='$child_parent_id'");

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
                          include("../plugins/videoPlugin/admin/edit_child_cat.php");
                        }
                        ?>

                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-4 control-label"></label>

                            <div class="col-md-6">

                                <input type="submit" name="update_child_cat" value="Update Sub Category" class="btn btn-success form-control">

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

if(isset($_POST['update_child_cat'])){

    $rules = array(
    "child_title" => "required",
    "child_desc" => "required",
    "parent_cat" => "required");

    $messages = array("child_title" => "Sub Category Title Is Required.","child_desc" => "Sub Category Description Is Required.","parent_cat" => "Your Must Need To Select A Parent Category.");
    $val = new Validator($_POST,$rules,$messages);

    if($val->run() == false){

        Flash::add("form_errors",$val->get_all_errors());
        Flash::add("form_data",$_POST);

        echo "<script> window.open(window.location.href,'_self');</script>";

    }else{

        $child_title = $input->post('child_title');
        $child_desc = $input->post('child_desc');
        $parent_cat = $input->post('parent_cat');
        
        if($videoPlugin == 1){
            $video = $input->post('video');
            if(empty($video)){
              $video = 0;
            }
            $update_child_cat = $db->update("categories_children",["child_parent_id" => $parent_cat,"video" => $video],["child_id" => $child_id]);
        }else{
            $update_child_cat = $db->update("categories_children",["child_parent_id" => $parent_cat],["child_id" => $child_id]);
        }
        		
        if($update_child_cat){

            $update_meta = $db->update("child_cats_meta",["child_title" => $child_title,"child_desc" => $child_desc],["child_id" => $child_id, "language_id" => $adminLanguage]);

            $update_parent = $db->update("child_cats_meta",array("child_parent_id" => $parent_cat),array("child_id" => $child_id));

            $insert_log = $db->insert_log($admin_id,"child_cat",$edit_id,"updated");

            echo "<script>alert('One Sub Category Has Been Updated.');</script>";
            echo "<script>window.open('index?view_child_cats','_self');</script>";

        }

    }

}

?>

</div>

<?php } ?>