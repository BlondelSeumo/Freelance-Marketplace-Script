<?php

@session_start();

if(!isset($_SESSION['admin_email'])){

echo "<script>window.open('login','_self');</script>";

}else{

?>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote-bs4.min.css" rel="stylesheet">
  
<script type="text/javascript" src="../js/popper.min.js"></script>

<script type="text/javascript" src="../js/bootstrap.js"></script>

<script type="text/javascript" src="../js/summernote.js"></script>

<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-book"></i> Knowledge Bank</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Insert Article</li>
                        </ol>
                    </div>
                </div>
            </div>

    </div>


<div class="container">

<div class="row">

  <div class="col-md-12">

  
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

      <div class="card-header">

        <h4 class="h4">Insert New Article</h4>

      </div>

      <div class="card-body card-block">
        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

          <div class="row form-group">
            <div class="col col-md-3"><label for="text-input" class=" form-control-label">Article Heading</label></div>
            <div class="col-12 col-md-9"><input type="text" id="text-input" name="article_heading" class="form-control" required=""><small class="form-text text-muted"></small></div>
          </div>

          <div class="row form-group">
            <div class="col col-md-3"><label for="select" class=" form-control-label">Article Status</label></div>
            <div class="col-12 col-md-9">
              <select name="article_status" id="select" class="form-control" required="">
                <option value="">Please select</option>
                <option value="active">Active</option>
                <option value="draft">Not Active</option>
              </select>
            </div>
          </div>

          <div class="form-group row"><!--- form-group row Starts --->

               <label class="col-md-3 control-label"> Select Article's Category : </label>

               <div class="col-md-6">

                   <select name="cat_id" class="form-control" required>

                      <option value=""> Select Article Category </option>

                      <?php

                      $get_cats = $db->select("article_cat",array("language_id" => $adminLanguage));

                      while($row_cats = $get_cats->fetch()){

                      $article_cat_id = $row_cats->article_cat_id;

                      $article_cat_title = $row_cats->article_cat_title;

                      echo "<option value='$article_cat_id'>$article_cat_title</option>";

                      }

                      ?>

                    </select>

              </div>

          </div><!--- form-group row Ends --->


          <div class="row form-group">

            <div class="col col-md-3"><label for="textarea-input" class=" form-control-label">Article Body</label></div>
            
            <div class="col-12 col-md-9"><textarea name="article_body" id="textarea-input" rows="9" placeholder="Start Typing Here..." class="form-control"></textarea></div>
          </div>
      
          <div class="row form-group">
            <div class="col col-md-3"><label for="file-input" class=" form-control-label">Right Image (optional)</label></div>
            <div class="col-12 col-md-9"><input type="file" id="file-input" name="right_image" class="form-control-file"></div>
          </div>

          <div class="row form-group">
            <div class="col col-md-3"><label for="file-input" class=" form-control-label">Top Image (optional)</label></div>
            <div class="col-12 col-md-9"><input type="file" id="file-input" name="top_image" class="form-control-file"></div>
          </div>
          <div class="row form-group">
            <div class="col col-md-3"><label for="file-multiple-input" class=" form-control-label">Bottom Image (optional)</label></div>
            <div class="col-12 col-md-9"><input type="file" id="file-multiple-input" name="bottom_image" class="form-control-file"></div>
          </div>

          <div class="row form-group">

            <div class="col col-md-3"></div>
            <div class="col-12 col-md-9">
            <button type="submit" name="submit" class="btn btn-success">Insert Article</button>
          </div>

          </div>

        </form>
      </div>


    </div>

  </div>

</div>

</div>

<script>

$('textarea').summernote({
    placeholder: 'Start Typing Here...',
    height: 150
});

</script>

<?php

require_once("includes/removeJava.php");

include("includes/sanitize_url.php");

if(isset($_POST['submit'])){

  $rules = array(
  "article_heading" => "required",
  "article_status" => "required",
  "cat_id" => "required",
  "article_body" => "required");

  $messages = array("cat_id" => "You must need to select a category for Article.");
  $val = new Validator($_POST,$rules,$messages);

  if($val->run() == false){

    Flash::add("form_errors",$val->get_all_errors());
    Flash::add("form_data",$_POST);
    echo "<script> window.open('index?insert_article','_self');</script>";

  }else{

    $article_heading = $input->post('article_heading');

    $article_url = slug($article_heading);

    $cat_id = $input->post('cat_id');
    $article_status = $input->post('article_status');
    $article_body = removeJava($_POST['article_body']);

    $right_image = $_FILES['right_image']['name'];
    $right_image_tmp = $_FILES['right_image']['tmp_name'];

    $top_image = $_FILES['top_image']['name'];
    $top_image_tmp = $_FILES['top_image']['tmp_name'];

    $bottom_image = $_FILES['bottom_image']['name'];
    $bottom_image_tmp = $_FILES['bottom_image']['tmp_name'];

    $right_extension = pathinfo($right_image, PATHINFO_EXTENSION);
    $top_extension = pathinfo($top_image, PATHINFO_EXTENSION);
    $bottom_extension = pathinfo($bottom_image, PATHINFO_EXTENSION);

    $allowed = array('jpeg','jpg','gif','png','tif','ico','webp');

    if(!in_array($right_extension,$allowed) & !empty($right_image) or !in_array($top_extension,$allowed) & !empty($top_image) or !in_array($bottom_extension,$allowed) & !empty($bottom_image)){

      echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
      
    }else{

      if(!empty($right_image)){
        $right_image = pathinfo($right_image, PATHINFO_FILENAME);
        $right_image = $right_image."_".time().".$right_extension";
        uploadToS3("article_images/$right_image",$right_image_tmp);
      }

      if(!empty($top_image)){
        $top_image = pathinfo($top_image, PATHINFO_FILENAME);
        $top_image = $top_image."_".time().".$top_extension";
        uploadToS3("article_images/$top_image",$top_image_tmp);
      }

      if(!empty($bottom_image)){
        $bottom_image = pathinfo($bottom_image, PATHINFO_FILENAME);
        $bottom_image = $bottom_image."_".time().".$bottom_extension";
        uploadToS3("article_images/$bottom_image",$bottom_image_tmp);
      }

      $insert_article = $db->insert("knowledge_bank",array("language_id" => $adminLanguage,"cat_id"=>$cat_id,"article_url"=>$article_url,"article_heading"=>$article_heading,"article_body"=>$article_body,"right_image"=>$right_image,"top_image"=>$top_image,"bottom_image"=>$bottom_image,"right_image_s3"=>$enable_s3,"top_image_s3"=>$enable_s3,"bottom_image_s3"=>$enable_s3,"article_status"=>$article_status));

      if($insert_article){

        $insert_id = $db->lastInsertId();
        $insert_log = $db->insert_log($admin_id,"article",$insert_id,"inserted");

        echo "<script>alert('Article inserted successfully.');</script>";
        echo "<script>window.open('index?view_articles','_self');</script>";

      }

    }

  }

}

?>


<?php } ?>
