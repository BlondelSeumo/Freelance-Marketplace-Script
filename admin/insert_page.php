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
		<h1><i class="menu-icon fa fa-table"></i> Pages</h1>
	</div>
</div>
</div>
<div class="col-sm-8">
<div class="page-header float-right">
	<div class="page-title">
		<ol class="breadcrumb text-right">
			<li class="active">Insert Page</li>
		</ol>
	</div>
</div>
</div>
</div>

<div class="container"><!--- container Starts --->
<div class="row"><!--- 2 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card"><!--- card Starts --->
<div class="card-header"><!--- card-header Starts --->
<h4 class="h3">Insert Page</h4>
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->

<form action="" method="post"><!--- form Starts --->
	<div class="form-group row"><!--- form-group row Starts --->
		<label class="col-md-3 control-label"> Page Title : </label>
		<div class="col-md-8">
			<input type="text" name="title" class="form-control" required>
		</div>
	</div><!--- form-group row Ends --->
	<div class="form-group row"><!--- form-group row Starts --->
		<div class="col-md-3"> 
			<label>Page Content:</label>
			<br>
			<small class="text-muted p">If you enter html mode, remember to turn it off before saving or updating.</small>
		</div>
		<div class="col-md-8">
			<textarea class="form-control" name="content" rows="13" required></textarea>
		</div>
	</div><!--- form-group row Ends --->
	<div class="form-group row"><!--- form-group row Starts --->
		<label class="col-md-3 control-label"> Page Url : </label>
		<div class="col-md-8">
			<input type="text" name="url" class="form-control" required>
		</div>
	</div><!--- form-group row Ends --->

	<div class="form-group row"><!--- form-group row Starts --->
		<label class="col-md-3 control-label"></label>
		<div class="col-md-8">
			<input type="submit" name="insert" class="btn btn-success form-control" value="Insert Page">
		</div>
	</div><!--- form-group row Ends --->
</form>

</div><!--- card-body Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- row Ends --->
</div><!--- container Ends --->
<script>
$('textarea').summernote({
	placeholder: 'Start Typing Here...',
	height: 280,
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
if(isset($_POST['insert'])){
	require_once("includes/removeJava.php");

	function sanitizeUrl($string, $space="-"){
		 
		if(preg_match('/[اأإء-ي]/ui', $string)){
			return urlencode($string);
		}else{
			$turkcefrom = array("/Ğ/","/Ü/","/Ş/","/İ/","/Ö/","/Ç/","/ğ/","/ü/","/ş/","/ı/","/ö/","/ç/");
			$turkceto = array("G","U","S","I","O","C","g","u","s","i","o","c");

			$string = utf8_encode($string);
			if(function_exists('iconv')) {
				// $string = iconv('UTF-8', 'ASCII//TRANSLIT', mb_strtolower($string));
			}

			$string = preg_replace("/[^a-zA-Z0-9 \-]/", "", $string);
			$string = trim(preg_replace("/\\s+/", " ", $string));
			$string = strtolower($string);
			$string = str_replace(" ", $space, $string);

			$string = preg_replace("/[^0-9a-zA-ZÄzÜŞİÖÇğüşıöç]/"," ",$string);
			$string = preg_replace($turkcefrom,$turkceto,$string);
			$string = preg_replace("/ +/"," ",$string);
			$string = preg_replace("/ /","-",$string);
			$string = preg_replace("/\s/","",$string);
			$string = strtolower($string);
			$string = preg_replace("/^-/","",$string);
			$string = preg_replace("/-$/","",$string);
			return $string;
		}
		
	}

	$title = $input->post('title');
	$content = removeJava($_POST['content']);
	$url = sanitizeUrl($input->post('url'));
	
	$date = date("F d, Y");
	$data = array("url"=>$url,"date"=>$date);
	$insert = $db->insert("pages",$data); 
	if($insert){

		$insert_id = $db->lastInsertId();

		$get_languages = $db->select("languages");
		while($row_languages = $get_languages->fetch()){
			$id = $row_languages->id;
			$insert = $db->insert("pages_meta",array("page_id"=>$insert_id,"language_id"=>$id));
		}

		$update_meta = $db->update("pages_meta",array("title" => $title,"content" => $content),array("page_id" => $insert_id, "language_id" => $adminLanguage));

		$insert_log = $db->insert_log($admin_id,"page",$insert_id,"updated");
		
		echo "<script>alert('One Page has been Inserted Successfully.');</script>";
		echo "<script>window.open('index?pages','_self');</script>";
	}
}
?>
<?php } ?>