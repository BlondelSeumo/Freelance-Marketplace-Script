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
            <h1><i class="menu-icon fa fa-language"></i> Languages</h1>
          </div>
      </div>
  </div>
  <div class="col-sm-8">
      <div class="page-header float-right">
          <div class="page-title">
            <ol class="breadcrumb text-right">
              <li class="active">Add Language</li>
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

                        Add New Language

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <form action="" method="post" enctype="multipart/form-data">
                        <!--- form Starts --->

                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Language Title : </label>

                            <div class="col-md-6">

                                <input type="text" name="title" class="form-control">
                                <small><span class="text-danger">! Important</span> Language Title Can Not Be Changed After Adding.</small>

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Language Image : </label>

                            <div class="col-md-6">

                                <input type="file" name="image" class="form-control">

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-6">

                            <input type="submit" name="submit" class="btn btn-success form-control" value="Insert Language">

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

   </div>

<?php

function custom_copy($src,$dst){
  
   // open the source directory 
   $dir = opendir($src);  
  
   // Make the destination directory if not exist 
   @mkdir($dst);  
  
   // Loop through the files in source directory 
   while($file = readdir($dir)){
  
      if (($file != '.') && ($file != '..' )){
         if (is_dir($src.'/'.$file)){  
  
            // Recursively calling custom copy function 
            // for sub directory  
            custom_copy($src.'/'.$file,$dst.'/'.$file);  
   
         }else{  
            copy($src.'/'.$file,$dst.'/'.$file);  
         }  
      }  
   }  
  
   closedir($dir); 

}
  
//$src = "C:/xampp/htdocs/geeks"; 
  
//$dst = "C:/xampp/htdocs/gfg"; 
  
//custom_copy($src,$dst); 

if(isset($_POST['submit'])){
   
   $rules = array(
   "title" => "required",
   "image" => "required");

   $messages = array("title" => "Language Title Is Required.","image" => "Language Image Is Required.");
   $val = new Validator($_POST,$rules,$messages);

   if($val->run() == false){

      Flash::add("form_errors",$val->get_all_errors());
      Flash::add("form_data",$_POST);
      echo "<script> window.open('index?insert_language','_self');</script>";

   }else{

      $title = $input->post('title');
          
      $image = $_FILES['image']['name'];
      $tmp_image = $_FILES['image']['tmp_name'];

      $allowed = array('jpeg','jpg','gif','png','tif','ico','webp');
      $file_extension = pathinfo($image,PATHINFO_EXTENSION);
      if(!in_array($file_extension,$allowed)){
         echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
      }else{
          
         uploadToS3("languages_images/$image",$tmp_image);
         $check_languages = $db->count("languages",array("title"=>$title));
             
         if($check_languages == 1){
            echo "<script>alert('This Language Title Has Been Used Already. Please Use Another.');</script>";
            echo "<script>window.open('index?insert_language','_self');</script>";
         }else{

            $insert_language = $db->insert("languages",array("title"=>$title,"image"=>$image,"isS3"=>$enable_s3,"template_folder" => substr(strtolower($title),0,2)));

            if($insert_language){
                
               $insert_id = $db->lastInsertId();
               $get_cats = $db->select("categories");
               while($row_cats = $get_cats->fetch()){
                  $cat_id = $row_cats->cat_id;
                  $insert = $db->insert("cats_meta",array("cat_id"=>$cat_id,"language_id"=>$insert_id));
               }

               $get_child_cats = $db->query("select * from categories_children");
               while($row_child_cats = $get_child_cats->fetch()){
                  $child_id = $row_child_cats->child_id;
                  $child_parent_id = $row_child_cats->child_parent_id;

                  $insert = $db->insert("child_cats_meta",array("child_id"=>$child_id,"child_parent_id"=>$child_parent_id,"language_id"=>$insert_id));
               }

               $selPages = $db->query("select * from pages");
               while($page = $selPages->fetch()){
                  $page_id = $page->id;
                  $insert = $db->insert("pages_meta",array("page_id"=>$page_id,"language_id"=>$insert_id));
               }

               $posts = $db->select("posts");
               while($post = $posts->fetch()){
                  $post_id = $post->id;
                  $insert = $db->insert("posts_meta",array("post_id"=>$post_id,"language_id"=>$insert_id));
               }

               $categories = $db->select("post_categories");
               while($cat = $categories->fetch()){
                  $cat_id = $cat->id;
                  $insert = $db->insert("post_categories_meta",array("cat_id"=>$cat_id,"language_id"=>$insert_id));
               }

               $insert_log = $db->insert_log($admin_id,"language",$insert_id,"inserted");
               $file = strtolower($title);

               $template_folder = substr($file,0,2);

               if(!is_file("../language/{$file}.php")){
                  copy("../languages/english.php","../languages/{$file}.php");
                  custom_copy($dir."/emails/templates/en",$dir."/emails/templates/$template_folder");
                  if(file_exists($dir."/emails/templates/$template_folder")){
                     echo "<script>alert('One Language Has Been Inserted.');</script>";
                     echo "<script>window.open('index?view_languages','_self');</script>";
                  }

               }
               
            }
            
         }

      }

   }

}

?>

<?php } ?>