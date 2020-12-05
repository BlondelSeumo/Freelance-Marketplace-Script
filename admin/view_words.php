<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login.php','_self');</script>";
	
}else{
	

?>

<div class="breadcrumbs mb-2">
    <div class="col-sm-4">
    <div class="page-header float-left">
        <div class="page-title">
            <h1><i class="menu-icon fa fa-fax"></i> Restricted Words </h1>
        </div>
    </div>
    </div>
    <div class="col-sm-8">
    <div class="page-header float-right">
        <div class="page-title">
            <ol class="breadcrumb text-right">
                <li class="active">Restricted Words / View Words</li>
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

<h4 class="h4">

<i class="fa fa-money-bill-alt"></i> View Words

</h4>

</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

<table class="table table-bordered table-hover"><!--- table table-bordered table-hover Starts --->

<thead>

<tr>

<th>Word Id</th>

<th>Word Name</th>

<th>Edit Word</th>

<th>Delete Word</th>

</tr>

</thead>

<tbody><!--- tbody Starts --->

<?php

$i = 0;

$get_words = $db->select("spam_words");

while($row_words = $get_words->fetch()){

$id = $row_words->id;

$name = $row_words->word;

$i++;

?>

<tr>

<td><?= $i; ?></td>

<td><?= $name; ?></td>

<td>

<a class="btn btn-success text-white" href="index?edit_word=<?= $id; ?>"> <i class="fa fa-pencil"></i> Edit </a>

</td>

<td>

<a href="#" class="btn btn-danger text-white" onclick="alert_confirm('Do you really want to delete this Word permanently.','index?delete_word=<?= $id; ?>');">

<i class="fa fa-trash"></i> Delete

</a>

</td>


</tr>

<?php } ?>

</tbody><!--- tbody Ends --->

</table><!--- table table-bordered table-hover Ends --->

</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div>


<?php } ?>