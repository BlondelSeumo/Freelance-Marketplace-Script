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
      <h1><i class="menu-icon fa fa-table"></i> Withdrawals</h1>
    </div>
  </div>
  </div>
  <div class="col-sm-8">
    <div class="page-header float-right">
      <div class="page-title">
        <ol class="breadcrumb text-right">
        <li class="active">Withdrawals</li>
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
      <h4 class="h4">View Withdrawals</h4>
    </div>
    <div class="card-body">
      <div class="table-responsive">
      <table class="table table-bordered">
      <thead>
      <tr>
      <th>#Num</th>
      <th>User Name</th>
      <th>Method</th>
      <th>Amount</th>
      <th>Date</th>
      </tr>
      </thead>
      <tbody>
      <?php
        $i = 0;
        
        $get_withdrawals = $db->select("withdrawals");
        
        while($row_withdrawals = $get_withdrawals->fetch()){

        $id = $row_withdrawals->id;
        $seller_id = $row_withdrawals->seller_id;
        $method = $row_withdrawals->method;
        $amount = $row_withdrawals->amount;
        $date = $row_withdrawals->date;

        $select_seller = $db->select("sellers",array("seller_id" => $seller_id));

        $seller_user_name = $select_seller->fetch()->seller_user_name;

        $i++;

      ?>
      <tr>
        <td><?= $i; ?></td>
        <td><a href="index?single_seller=<?= $seller_id; ?>" class="text-primary"><?= $seller_user_name; ?></a></td>
        <td><?= ucwords($method); ?></td>
        <td class="font-weight-bold text-success">$<?= $amount; ?></td>
        <td><?= $date; ?></td>
      </tr>
      <?php } ?>
      </tbody>
      </table>
      </div>
    </div><!--- card-body Ends --->
  </div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div>
<?php } ?>