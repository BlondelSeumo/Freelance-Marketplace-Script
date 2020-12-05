<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{


$get_app = $db->select("app_info");
$row_app = $get_app->fetch();
$current_version = $row_app->version;

?>
<div class="breadcrumbs">
  <div class="col-sm-6">
    <div class="page-header float-left">
      <div class="page-title">
        <h1><i class="menu-icon fa fa-cog"></i> Add Plugin / Upload Plugin </h1>
      </div>
    </div>
  </div>
</div>
<div class="container pt-3">
<div class="row mb-4"><!--- 2 row Starts --->
  <div class="col-lg-12"><!--- col-lg-12 Starts --->
  <div class="card mb-5"><!--- card mb-5 Starts --->
    <div class="card-header"><!--- card-header Starts --->
      <h4 class="h4 mb-0"> <i class="fa fa-money fa-fw"></i> Upload Plugin </h4>
    </div><!--- card-header Ends --->
    <div class="card-body p-0"><!--- card-body Starts --->
      <form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->
        <div class="form-group row mb-0 pl-3 pr-3 pb-2 pt-3"><!--- form-group row Starts --->
          <label class="col-md-6 control-label"> Upload File: </label>
          <div class="col-md-6">
            <input type="file" required="" class="form-control form-control-sm mt-0" name="zip_file" accept=".zip">
          </div>
        </div><!--- form-group row Ends --->
        <hr class="mt-0 mb-3">
        <div class="form-group row mb-4"><!--- form-group row Starts --->
          <label class="col-md-3 control-label"></label>
          <div class="col-md-6 pl-4 pr-4">
            <input type="submit" name="uploadPlugin" value="Install Now" class="btn btn-success form-control">
          </div>
        </div><!--- form-group row Ends --->
      </form><!--- form Ends --->
      </div><!--- card-body Ends --->
    </div><!--- card mb-5 Ends --->
  </div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div><!--- container pt-3 Ends --->
<?php

  function getStringBetween($string, $start, $end, $index=1){
      if ($index <= 0) return '';
      $string = ' ' . $string;
      $ini = 0;
      $x = 1;
      while ($x <= $index) {
        $ini = strpos($string, $start, $ini + 1);
        if ($ini == 0) return '';
        $x++;
      }
      $ini += strlen($start);
      $len = strpos($string, $end, $ini) - $ini;
      $string = substr($string, $ini, $len);
      return str_replace(array("\n","\r"),"", $string);
  }

  if(isset($_POST["uploadPlugin"])){
  // Here uploadPlugin Code

    function rrmdir($path) {
      // Open the source directory to read in files
      $i = new DirectoryIterator($path);
      foreach($i as $f) {
        if($f->isFile()) {
          unlink($f->getRealPath());
        } else if(!$f->isDot() && $f->isDir()) {
          rrmdir($f->getRealPath());
        }
      }
      @rmdir($path);
    }

    $zip_file = $_FILES['zip_file']['name'];
    $zip_file_tmp = $_FILES['zip_file']['tmp_name'];

    $allowed = array('zip');
    $file_extension = pathinfo($zip_file, PATHINFO_EXTENSION);
    if(!in_array($file_extension,$allowed) & !empty($zip_file)){
      echo "<script>
      swal({
      type: 'warning',
      text: 'You can only upload a zipped folder.',
      timer: 3000,
      onOpen: function(){
      swal.showLoading()
      }
      }).then(function(){

      if (window.open('index?add_plugin','_self')) {}

      })
      </script>";  
    }else{
      
      if(move_uploaded_file($zip_file_tmp,"files/$zip_file")){
        
        $zip = "files/$zip_file";
        $zip_obj = new ZipArchive;
        if($zip_obj->open($zip)){
          $zip_obj->extractTo('updator/');
          $zip_obj->close();  
        }

        @$readme = file_get_contents("updator/readme.txt");
        $pluginName = getStringBetween($readme,"Plugin Name: ","\n");
        $folderName = getStringBetween($readme,"Folder: ","\n");
        $c_version = getStringBetween($readme,"Compatible Gigtodo Version: ","\n");
        
        if(!file_exists("updator/plugin.sql") or !file_exists("updator/files.zip")){

          rrmdir("updator");
          @mkdir("updator");
          unlink($zip);

          echo "<script>alert_error('Please upload the correct zipped folder.','index?add_plugin');</script>"; 
        }else{

          if(!is_dir("../plugins/$folderName")){

            if($c_version <= $current_version){

              $command = file_get_contents('updator/plugin.sql');
              $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME."",DB_USER,DB_PASS);

              /* execute multi query */
              $run = $pdo->prepare($command); 
              if($run->execute()){
                
                $zip_obj = new ZipArchive;
                
                if($zip_obj->open("updator/files.zip")){
                  $zip_obj->extractTo('../plugins/');
                  $zip_obj->close();
                  rrmdir("updator");
                  @mkdir("updator");
                  unlink($zip);
                }
                
                echo "<script>alert_success('$pluginName Has Been Successfully installed on your website.','index?plugins');</script>"; 
              }

            }else{
              echo "<script>alert_success('This Plugin Will Only Work On Gigtodo $c_version And Above $c_version','index?plugins');</script>"; 
            }

          }else{
            rrmdir("updator");
            @mkdir("updator");
            unlink($zip);
            echo "<script>alert_error('$pluginName Has Already Been Existed In Your website','index?add_plugin');</script>"; 
          }

        }
      }

    }

  }

}