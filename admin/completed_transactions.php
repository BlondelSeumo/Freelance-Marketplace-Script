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
      <h1><i class="menu-icon fa fa-universal-access"></i> Completed Transactions</h1>
    </div>
    </div>
  </div>
  <div class="col-sm-8">
    <div class="page-header float-right">
    <div class="page-title">
    <ol class="breadcrumb text-right">
        <li class="active">All Completed Transactions</li>
    </ol>
    </div>
    </div>
  </div>
</div>

<div class="container">
<div class="row"><!--- 2 row Starts --->
<div class="col-lg-12">
  <div class="card">
    <div class="card-header"><h4 class="h4">View Completed Transactions</h4></div>
    <div class="card-body"><!--- card-body Starts --->
      <?php
      $per_page = 7;
      if(isset($_GET['completed_transactions'])){
        $page = $input->get('completed_transactions');
      if($page == 0){ $page = 1; }
      }else{
       $page = 1;
      }
      $start_from = ($page-1) * $per_page;
      $i = $start_from;
      $payouts = $db->query("select * from payouts where status='completed' LIMIT $start_from,$per_page");
      $countPayouts = $payouts->rowCount();
      if($countPayouts == 0){
        echo "<center><h3>No Pending Payout Requests Has Been Available.</h3></center>";
      }else{
      ?>
      <div class="table-responsive"><!--- table-responsive Starts --->
        <table class="table table-bordered" style="min-height: 200px;">
          <thead>
          <tr>
            <th>Ref No</th>
            <th>Seller Name</th>
            <th>Seller Email</th>
            <th>Method</th>
            <th>Amount</th>
            <th>Actions</th>
            <th>Confirmation</th>
          </tr>
          </thead>
          <tbody>
          <?php
            $i = 0;
            while($payout = $payouts->fetch()){
            $id = $payout->id;
            $ref = $payout->ref;
            $seller_id = $payout->seller_id;
            $amount = $payout->amount;
            $method = $payout->method;
            $date = $payout->date;
            $status = $payout->status;
            $seller = $db->select("sellers",array("seller_id" => $seller_id))->fetch();
            $i++;
          ?>
            <tr>
            <td class="text-danger font-weight-bold"><?= $ref; ?></td>
            <td><?= $seller->seller_user_name; ?></td>
            <td><?= $seller->seller_email; ?></td>
            <td><?= ucfirst($method); ?></td>
            <td><?= showPrice($amount); ?></td>
            <td width="180px;">
              <div class="dropdown"><!--- dropdown Starts --->
              <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">Actions </button>
              <div class="dropdown-menu"><!--- dropdown-menu Starts --->
                <a class="dropdown-item" href="index?single_seller=<?= $seller_id; ?>">
                  <i class="fa fa-info-circle"></i> User's Details
                </a>
                <a target="_blank" class="dropdown-item" href="index?seller_login=<?= $seller->seller_user_name; ?>">
                  <i class="fa fa-sign-in"></i> Login As <?= $seller->seller_user_name; ?>
                </a>
                <?php if($seller->seller_status == "block-ban"){ ?>
                <a class="dropdown-item" href="index?unblock_seller=<?= $seller_id; ?>">
                  <i class="fa fa-unlock"></i> Already Banned! Unblock Seller?
                </a>
                <?php }else{ ?>
                <a class="dropdown-item" href="index?ban_seller=<?= $seller_id; ?>">
                  <i class="fa fa-ban"></i> Block / Ban User
                </a>
                <?php } ?>
              </div><!--- dropdown-menu Ends --->
              </div><!--- dropdown Ends --->
              </td>
              <td class="text-center text-muted">Completed</td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
      </div><!--- table-responsive Ends --->
      <div class="d-flex justify-content-center"><!--- d-flex justify-content-center Starts --->
      <ul class="pagination"><!--- pagination Starts --->
      <?php
      /// Now Select All From Listings Table
      $query = $db->query("select * from payouts");
      /// Count The Total Records
      $total_records = $query->rowCount();
      /// Using ceil function to divide the total records on per page
      $total_pages = ceil($total_records / $per_page);
      echo "
      <li class='page-item'>
      <a href='index?completed_transactions=1' class='page-link'> First Page </a>
      </li>
      ";
      for($i=$page -2; $i <=$page+8; $i++){
      if($i > 0 && $i <= $total_pages){
      if($page != $i){// 4 sau
      echo "<li class='page-item'><a href='index?completed_transactions=".$i."' class='page-link'>".$i."</a></li>";
      }else{
      echo "<li class='page-item active'><a href='#' class='page-link'>".$i."</a></li>";
      }
      }
      }
      echo "
      <li class='page-item'>
      <a href='index?completed_transactions=$total_pages' class='page-link'> Last Page </a>
      </li>
      ";
      ?>
      </ul><!--- pagination Ends --->
      </div><!--- d-flex justify-content-center Ends --->
      <?php } ?>
    </div><!--- card-body Ends --->
  </div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div>
<?php } ?>