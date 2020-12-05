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
    <h1><i class="menu-icon fa fa-table"></i> Plugins</h1>
  </div>
</div>
</div>
<div class="col-sm-8">
<div class="page-header float-right">
  <div class="page-title">
		<ol class="breadcrumb text-right">
		  <li class="active">Plugins</li>
		</ol>
  </div>
</div>
</div>
</div>

<div class="container"><!--- container Starts --->

<div class="row mt-4"><!--- 2 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card"><!--- card Starts --->
<div class="card-header"><!--- card-header Starts --->
<h4>Plugins <a href="index.php?add_plugin" class="btn btn-primary rounded">Add New</a></h4>
<!-- <p class="mb-0"></p> -->
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->

<div class="table-responsive">
<table class="table table-bordered"><!--- table table-bordered table-hover Starts --->
	<thead>
	<tr>
		<th>No:</th>
    <th>Name:</th>
		<th>Description</th>
    <th>Version</th>
    <th>Author</th>
	  <th>Actions:</th>
	</tr>
	</thead>
	<tbody><!--- tbody Starts --->
  <?php
  $i=0;
  
  $plugins = $db->query("select * from plugins");
  while($plugin = $plugins->fetch()){
  $i++;

  ?>
  <tr>
  <td><?= $i ?></td>
  <td><?= $plugin->name; ?></td>
  <td width="450"><?= $plugin->description; ?></td>
  <td><?= $plugin->version; ?></td>
  <td width="150"><a href="<?= $plugin->author_url; ?>" target="_blank" class="text-primary"><?= $plugin->author; ?></a></td>
  <td>
  <div class="dropdown"><!--- dropdown Starts --->
  <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>
    <div class="dropdown-menu" style="margin-left:-100px;"><!--- dropdown-menu Starts --->

      <a class="dropdown-item" href="index?update_plugin=<?=$plugin->id; ?>">
        <i class="fa fa-pencil-square-o"></i> Update Plugin
      </a>
      <?php if($plugin->status== 0){ ?>
      <a class="dropdown-item" href="index?activate_plugin=<?=$plugin->id; ?>">
        <i class="fa fa-plug"></i> Activate Plugin
      </a>
      <?php }else{ ?>
      <a class="dropdown-item" href="index?deactivate_plugin=<?=$plugin->id; ?>">
        <i class="fa fa-power-off"></i> Deactivate Plugin
      </a>
      <?php } ?>

      <a class="dropdown-item" onclick="return confirm('Are You Sure You Want To Delete This Plugin And Its Data.')" href="index?delete_plugin=<?=$plugin->id; ?>&folder=<?= $plugin->folder; ?>" >
        <i class="fa fa-trash"></i> Delete Plugin
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