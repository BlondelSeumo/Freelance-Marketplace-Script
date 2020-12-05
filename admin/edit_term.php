<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
echo "<script>window.open('login','_self');</script>";
}else{
if(isset($_GET['edit_term'])){
    $edit_id = $input->get('edit_term');
    $edit_term = $db->select("terms",array('term_id'=>$edit_id));
    if($edit_term->rowCount() == 0){
      echo "<script>window.open('index?dashboard','_self');</script>";
    }
    $row_edit = $edit_term->fetch();
    $term_id = $row_edit->term_id;
    $term_title = $row_edit->term_title;
    $term_description = $row_edit->term_description;
    $term_link = $row_edit->term_link;
}
?>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css" rel="stylesheet">
<script type="text/javascript" src="../js/popper.min.js"></script>
<script type="text/javascript" src="../js/bootstrap.js"></script>
<script type="text/javascript" src="../js/summernote.js"></script>
<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-table"></i> Terms & Conditions </h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Edit Term</li>
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
                    <h4 class="h4">Edit Term</h4>
                </div>
                <!--- card-header Ends --->
                <div class="card-body">
                    <!--- card-body Starts --->
                    <form action="" method="post">
                        <!--- form Starts --->
                        <div class="form-group row">
                            <!--- form-group row Starts --->
                            <label class="col-md-3 control-label"> Term Title : </label>
                            <div class="col-md-6">
                                <input type="text" name="term_title" class="form-control" required value="<?= $term_title; ?>">
                            </div>
                        </div>
                        <!--- form-group row Ends --->
                        <div class="form-group row"><!--- form-group row Starts --->
                            <div class="col-md-3"> 
                            <label>Term Description :</label>
                            <br>
                            <small class="text-muted p">If you enter html mode, remember to turn it off before saving or updating.</small>
                            </div>
                            <div class="col-md-6">
                                <textarea class="form-control" name="term_description" rows="7" required><?= $term_description; ?></textarea>
                            </div>
                        </div><!--- form-group row Ends --->
                        <div class="form-group row">
                            <!--- form-group row Starts --->
                            <label class="col-md-3 control-label"> Term Link : </label>
                            <div class="col-md-6">
                                <input type="text" name="term_link" class="form-control" required value="<?= $term_link; ?>">
                            </div>
                        </div>
                        <!--- form-group row Ends --->
                        <div class="form-group row">
                            <!--- form-group row Starts --->
                            <label class="col-md-3 control-label"></label>
                            <div class="col-md-6">
                                <input type="submit" name="update" class="btn btn-success form-control" value="Update Term">
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
    <script>
    $('textarea').summernote({
            placeholder: 'Start Typing Here...',
            height: 200,
            toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['height', ['height']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['misc', ['codeview']]
          ],
          });
    </script>
<?php

require_once("includes/removeJava.php");

if(isset($_POST['update'])){

    $rules = array(
    "term_title" => "required",
    "term_description" => "required",
    "term_link" => "required");
    $val = new Validator($_POST,$rules);
    if($val->run() == false){
      Flash::add("form_errors",$val->get_all_errors());
      Flash::add("form_data",$_POST);
      echo "<script> window.open(window.location.href,'_self');</script>";
    }else{

        $term_title = $input->post('term_title');
        $term_description = removeJava($_POST['term_description']);
        $term_link = $input->post('term_link');
        $update_term = $db->update("terms",array("term_title"=>$term_title,"term_title"=>$term_title,"term_description"=>$term_description,"term_link"=>$term_link),array("term_id"=>$term_id)); 
        if($update_term){
          $insert_log = $db->insert_log($admin_id,"term",$term_id,"updated");
          echo "<script>alert('One Term has been Updated.');</script>";
          echo "<script>window.open('index?view_terms','_self');</script>";
        }

    }

}
?>
</div>
<?php } ?>