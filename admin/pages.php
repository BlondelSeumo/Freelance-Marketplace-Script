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
		    <h1><i class="menu-icon fa fa-table"></i> Pages</h1>
		  </div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="page-header float-right">
		   <div class="page-title">
				<ol class="breadcrumb text-right">
					<li class="active">
					   <a href="index?insert_page" class="btn btn-success">
					   	<i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Page</span>
					   </a>
					</li>
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
<h4 class="h3">Pages</h4>
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->
<div class="table-responsive">
<table class="table table-bordered"><!--- table table-bordered table-hover Starts --->	
	<thead>
	<tr>
		<th>Title</th>
		<th>Language:</th>
		<th>Date Updated:</th>
		<th>Action:</th>
	</tr>
	</thead>
	<tbody><!--- tbody Starts --->
	<?php
	$selPages = $db->query("select * from pages");
	while($page = $selPages->fetch()){

	$page_meta = $db->select("pages_meta",array("page_id"=>$page->id,"language_id"=>$adminLanguage))->fetch();

	$language = $db->select("languages",array("id"=>$page_meta->language_id));
	$language_title = $language->fetch()->title;

	?>
	<tr>
	<td><?= $page_meta->title; ?></td>
	<td><?= $language_title; ?></td>
	<td><?= $page->date; ?></td>
	<td>
		<div class="dropdown"><!--- dropdown Starts --->
		  <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
		  <div class="dropdown-menu"><!--- dropdown-menu Starts --->
		    <a class="dropdown-item" href="../pages/<?= $page->url; ?>" target="_blank">
		      <i class="fa fa-eye"></i> Preview Page 
		    </a>
		    <a class="dropdown-item" href="index?edit_page=<?= $page->id; ?>">
		      <i class="fa fa-edit"></i> Edit Page 
		    </a>
		    <a class="dropdown-item" href="index?delete_page=<?= $page->id; ?>">
		    	<i class="fa fa-trash"></i> Delete Page
		    </a>
		  </div><!--- dropdown-menu Ends --->
		</div><!--- dropdown Ends --->
	</td>
	</tr>
	<?php } ?>
	</tbody>
</table>
</div>

</div><!--- card-body Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- row Ends --->
</div><!--- container Ends --->
<?php } ?>