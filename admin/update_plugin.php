<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{

$plugin_id = $input->get('update_plugin');
$plugins = $db->query("select * from plugins where id='$plugin_id'");
$plugin = $plugins->fetch();
$pluginName = $plugin->name;
$folder = $plugin->folder;
$current_version = $plugin->version;

?>
<div class="breadcrumbs">
  <div class="col-sm-6">
    <div class="page-header float-left">
      <div class="page-title">
        <h1><i class="menu-icon fa fa-cog"></i> Update Plugin / <?= $pluginName; ?> </h1>
      </div>
    </div>
  </div>
</div>
<div class="container pt-3">
<div class="row mb-4"><!--- 2 row Starts --->
  <div class="col-lg-12"><!--- col-lg-12 Starts --->
  <div class="card mb-5"><!--- card mb-5 Starts --->
    <div class="card-header"><!--- card-header Starts --->
      <h4 class="h4 mb-0"> <i class="fa fa-money fa-fw"></i> Update Plugin / <?= $pluginName; ?> </h4>
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
            <input type="submit" name="updatePlugin" value="Update <?= $pluginName; ?>" class="btn btn-success form-control">
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

  if(isset($_POST["updatePlugin"])){
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

    // Geting Uploaded Zip File
    $zip_file = $_FILES['zip_file']['name'];
    $zip_file_tmp = $_FILES['zip_file']['tmp_name'];
    $allowed = array('zip');
    $file_extension = pathinfo($zip_file, PATHINFO_EXTENSION);

    if(!in_array($file_extension,$allowed) & !empty($zip_file)){

    	// If User do not upload zip file shows this
      echo "<script>
      swal({
      type: 'warning',
      text: 'You can only upload a zipped folder.',
      timer: 3000,
      onOpen: function(){
      swal.showLoading()
      }
      }).then(function(){
      if (window.open('index?update_plugin=$plugin_id','_self')) {}
      })
      </script>";

    }else{
    	// move uploaded zip file to admin files folder
      if(move_uploaded_file($zip_file_tmp,"files/$zip_file")){

      	// extract moved file to admin updator folder
        $zip = "files/$zip_file";
        $zip_obj = new ZipArchive;
        if($zip_obj->open($zip)){
          $zip_obj->extractTo('updator/');
          $zip_obj->close();  
        }

        // read readme.txt file
        @$readme = file_get_contents("updator/readme.txt");
      	$version = getStringBetween($readme,"Version: ","\n");
      	$c_version = getStringBetween($readme,"Compatible Version: ","\n");

        if(!file_exists("updator/readme.txt") or !file_exists("updator/update.sql") or !file_exists("updator/files.zip")){
          rrmdir("updator");
          @mkdir("updator");
          unlink($zip);
          echo "<script>alert_error('Please upload the correct zipped folder.','index?update_plugin=$plugin_id');</script>"; 
        }else{

        	if($current_version < $version){
          	if($c_version == $current_version){

		          $command = file_get_contents('updator/update.sql');
		          $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME."",DB_USER,DB_PASS);
		          /* execute multi query */
		          $run = $pdo->prepare($command); 
		          if($run->execute()){

		          	if(file_exists("updator/delete_files.txt")){
		              $list = file('updator/delete_files.txt');
		              foreach($list as $file){ 
		                unlink("../plugins/$folder/$file"); 
		              }
		            }

		            $zip_obj = new ZipArchive;
		            if($zip_obj->open("updator/files.zip")){
		              $zip_obj->extractTo("../plugins/$folder/");
		              $zip_obj->close();
		              rrmdir("updator");
		              @mkdir("updator");
		              unlink($zip);
		            }
		            echo "<script>alert_success('$pluginName Has Been Successfully Updated on your website.','index?plugins');</script>";
		          }

          	}else{
          		rrmdir("updator");
	            @mkdir("updator");
	            unlink($zip);
	            echo "<script>alert_error('This Update File Will Only Work On Plugin Version:$c_version.','index?update_plugin=$plugin_id');</script>";
          	}
          }else if($current_version > $version){
          	rrmdir("updator");
	          @mkdir("updator");
	          unlink($zip);
	          echo "<script>alert_error('Sorry,you cannot downgrade a version.','index?plugins');</script>";
          }else if($current_version == $version){
	          rrmdir("updator");
	          @mkdir("updator");
	          unlink($zip);
	          echo "<script>alert_error('You already have this version installed.','index?update_plugin=$plugin_id');</script>";
	        }

        }
      }

    }
  }
}
?>