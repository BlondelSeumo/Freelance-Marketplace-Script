<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}else{
	$expenses = $db->query("SELECT SUM(amount) AS total FROM expenses")->fetch()->total;
	if(empty($expenses)){$expenses=0;}

  if(isset($_GET['from'])){
    $from = $input->get('from');
    $to = (isset($_GET['to']) AND !empty($input->get('to'))) ? $input->get('to') : date("Y-m-d");
    $filter_q = "where date Between '$from' AND '$to'";
  }else{
    $from = "";
    $to = "";
    $filter_q = "";
  }

?>
<div class="breadcrumbs">
<div class="col-sm-4">
<div class="page-header float-left">
  <div class="page-title">
    <h1><i class="menu-icon fa fa-table"></i> Accounting</h1>
  </div>
</div>
</div>
<div class="col-sm-8">
<div class="page-header float-right">
  <div class="page-title">
		<ol class="breadcrumb text-right">
		  <li class="active">Accounting</li>
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
<h4 class="h3">Expenses</h4>
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->
<p class="h4">Add Expense</p>
<hr class="mb-1 mt-1">
<form method="post" class="mb-4">
  <div class="form-row">
    <div class="col-3">
      <label>Title</label>
      <input type="text" name="title" class="form-control" required="">
    </div>
    <div class="col-6">
      <label>Description</label>
      <textarea name="description" class="form-control" cols="100" rows="1"></textarea>
    </div>
    <div class="col">
      <label>Amount</label>
      <input type="text" name="amount" class="form-control" required="">
    </div>
    <div class="col mt-2">
    	<input type="submit" name="addExpense" class="btn btn-primary mt-4" value="Add">
    </div>
  </div>
</form>


<div class="offset-lg-3 col-md-6">
<form action="" method="get">
<input type="hidden" name="expenses" value="">
<div class="input-group mb-3 mt-3 mt-lg-0">
  <input type="date" name="from" value="<?= (isset($_GET['from']))?$input->get('from'):''; ?>" max="<?= date("Y-m-d") ?>" class="form-control" placeholder="Search Sellers">
  <input type="date" name="to" value="<?= (isset($_GET['to']))?$input->get('to'):''; ?>" max="<?= date("Y-m-d") ?>" class="form-control" placeholder="Search Sellers">
  <div class="input-group-append">
    <button class="btn btn-success" type="submit"><i class="fa fa-search"></i> Filter</button>
  </div>
</div>
</form>
</div>


<div class="table-responsive">
<table class="table table-bordered"><!--- table table-bordered table-hover Starts --->	
	<thead>
	<tr>
		<th>Title</th>
		<th>Description:</th>
		<th>Amount:</th>
		<th>Date:</th>
		<th>Action:</th>
	</tr>
	</thead>
	<tbody><!--- tbody Starts --->
	<?php

	$selExpenses = $db->query("select * from expenses $filter_q");
	while($expense = $selExpenses->fetch()){
	?>
	<tr>
	<td><?= $expense->title; ?></td>
	<td><?= $expense->description; ?></td>
	<td><?= $s_currency.$expense->amount; ?></td>
	<td><?= $expense->date; ?></td>
	<td>
	<a class="btn btn-primary text-white" href="index?delete_expense=<?=$expense->id; ?>">
		<i class="fa fa-trash"></i> Delete Expense
	</a>
	</td>
	</tr>
	<?php } ?>
	</tbody>
	<tbody>
  <tr>
  <td colspan="4" class="total-row text-right"><strong>Total Expenses:</strong></td>
  <td class="total-row text-center"><?= $s_currency.$expenses; ?></td>
  </tr>
	</tbody>
</table>
</div>

</div><!--- card-body Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- row Ends --->
</div><!--- container Ends --->
<?php } ?>

<?php

if(isset($_POST['addExpense'])){

	$rules = array(
	"title" => "required",
	"amount" => "required");

	$messages = array("title" => "Title Is Required.","amount" => "Amount Is Required.");
	$val = new Validator($_POST,$rules,$messages);

	if($val->run() == false){
		Flash::add("form_errors",$val->get_all_errors());
		Flash::add("form_data",$_POST);
		echo "<script> window.open('index?accounting','_self');</script>";
	}else{
		$title = $input->post('title');
		$desc = $input->post('description');
		$amount = $input->post('amount');
		$date = date("Y-m-d");
		$insert_cat = $db->insert("expenses",array("title"=>$title,"description"=>$desc,"amount"=>$amount,"date"=>$date));
		if($insert_cat){
		echo "<script>alert('One Expense Has Been Added.');</script>";
		echo "<script>window.open('index?expenses','_self');</script>";	
		}
	}
}

?>