<?php

@session_start();

if(!isset($_SESSION['admin_email'])){

echo "<script>window.open('login','_self');</script>";

}else{

  if(isset($_GET['edit_article_cat'])){

  $edit_article = $input->get('edit_article_cat');


  $edit_cat = $db->select("article_cat",array('article_cat_id' => $edit_article));
  
    if($edit_cat->rowCount() == 0){

      echo "<script>window.open('index?dashboard','_self');</script>";

    }
    
  $row_edit = $edit_cat->fetch();

  $article_cat_title = $row_edit->article_cat_title;

  $position = $row_edit->position;


  }

?>

<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-book"></i> Article Category</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Edit Article Category</li>
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

                    <h4 class="h4">Insert Category</h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <form action="" method="post">
                        <!--- form Starts --->

                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Category Title : </label>

                            <div class="col-md-6">

                                <input type="text" name="article_cat_title" class="form-control" value="<?= $article_cat_title; ?>">

                            </div>

                        </div>


                        <div class="form-group row">
                        <!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Position : </label>

                        <div class="col-md-6">

                            <input type="radio" name="position" value="left"

                            <?php

                            if($position == "left"){

                            echo "checked='checked'";

                            }

                            ?>

                             >

                            <label> Left </label>

                            <input type="radio" name="position" value="right"

                            <?php

                            if($position == "right"){

                            echo "checked='checked'";

                            }

                            ?>

                          >

                            <label> Right </label>

                        </div>

                    </div>
                    <!--- form-group row Ends --->


                    <div class="form-group row">
                        <!--- form-group row Starts --->

                        <label class="col-md-3 control-label"></label>

                        <div class="col-md-6">

                            <input type="submit" name="update" value="Insert Category" class="btn btn-success form-control">

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

      $rules = array("article_cat_title" => "required","position" => "required");

      $messages = array("article_cat_title" => "Article Category Title Title Is Required.");

      $val = new Validator($_POST,$rules,$messages);

      if($val->run() == false){

      Flash::add("form_errors",$val->get_all_errors());

      Flash::add("form_data",$_POST);

      echo "<script> window.open(window.location.href,'_self');</script>";

      }else{


      $article_cat_title = $input->post('article_cat_title');

      $position = $input->post('position');

      $update_cat = $db->update("article_cat",array("article_cat_title"=>$article_cat_title,"position"=>$position),array("article_cat_id"=>$edit_article));

      if($update_cat){

      $insert_log = $db->insert_log($admin_id,"article_cat",$edit_article,"updated");

      echo "<script>alert('Category updated successfully.');</script>";

      echo "<script>window.open('index?view_article_cats','_self');</script>";

      }


      }

      }

?>

</div>


<?php } ?>
