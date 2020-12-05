<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{


if(isset($_GET['language_settings'])){
		
$edit_id = $input->get('language_settings');

$edit_language = $db->select("languages",array('id' => $edit_id));
        
$row_edit = $edit_language->fetch();
        
$id = $row_edit->id;

$title = $row_edit->title;
    
$file = strtolower($title);

$file = "../languages/$file.php";

}

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
                    <li class="active"> Language Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>


<div class="container">

<div class="row"><!--- 2 row Starts --->

<div class="col-lg-12"><!--- col-lg-12 Starts --->

<div class="card"><!--- card Starts --->

<div class="card-header"><!--- card-header Starts --->
	<i class="fa fa-money fa-fw"></i> Language Settings For <strong><?= $title; ?></strong>
</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

<p class="lead">Enter Your Custom Language Variables For Site Here.</p>

<form method="post" enctype="multipart/form-data"><!--- form Starts --->

<div class="form-group">

<textarea name="data" class="form-control" rows="20" required=""><?= file_get_contents($file); ?></textarea>

</div>

<div class="form-group row"><!--- form-group row Starts --->

<label class="col-md-3 control-label"></label>

<div class="col-md-6">

<input type="submit" name="update_language" class="form-control btn btn-primary" value="Update Language Settings">

</div>

</div><!--- form-group row Ends --->

</form><!--- form Ends --->

</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div><!--- container Ends --->

<?php

if(isset($_POST['update_language'])){		
    
$menu_lang = $_POST['data'];

$handle = fopen($file, "w");

if($handle){
 
fwrite($handle, $menu_lang);
fclose($handle);

$insert_log = $db->insert_log($admin_id,"language_settings",$id,"updated");

echo "<script>alert('Language Settings Has Been Updated.');</script>";

echo "<script>window.open('index?view_languages','_self');</script>";

}
       
}
    
?>

<?php } ?>
