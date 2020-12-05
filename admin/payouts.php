<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
  echo "<script>window.open('login','_self');</script>";
}else{
  if(isset($_GET['status'])){
    $payoutStatus = $input->get('status');
  }else{
    $payoutStatus = "";
  }

?>
<div class="breadcrumbs">
  <div class="col-sm-4">
  <div class="page-header float-left">
    <div class="page-title">
      <h1><i class="menu-icon fa fa-table"></i> Payouts</h1>
    </div>
  </div>
  </div>
  <div class="col-sm-8">
    <div class="page-header float-right">
      <div class="page-title">
        <ol class="breadcrumb text-right">
        <li class="active"><?= ucfirst($payoutStatus); ?> Payouts</li>
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
      <h4 class="h4">View <?= ucfirst($payoutStatus); ?> Payouts</h4>
    </div>
    <div class="card-body">
      <h3 class="text-center mb-3"> Search Payouts </h3>
      <form class="form-inline d-flex mb-4 justify-content-center" method="get" action="">
        <!--- form-inline d-flex mb-4 justify-content-center Starts --->
        <input type="hidden" name="payouts">
        <?= (!empty($payoutStatus))?"<input type='hidden' name='status' value='$payoutStatus'>":""; ?>
        <div class="form-group"><!--- form-group Starts --->
          <label class="mb-2 mr-sm-2 mb-sm-0"> Enter Ref No : </label>
          <div class="input-group">
          <span class="input-group-addon"><b>P-</b></span>
          <input type="text" name="ref" class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="320154PR" value="<?php if(isset($_GET['ref'])){ echo $input->get('ref'); } ?>">
          </div>
        </div><!--- form-group Ends --->
        <div class="form-group"><!--- form-group Starts --->
          <input type="submit" class="form-control btn btn-success" value="Search">
        </div>
        <!--- form-group Ends --->
      </form>
      <!--- form-inline d-flex mb-4 justify-content-center Ends --->
      <table class="table table-bordered">
      <thead>
      <tr>
      <th>No</th>
      <th>Ref No</th>
      <th>Seller Name</th>
      <th>Method</th>
      <th>Amount</th>
      <th>Date</th>
      <th>Status</th>
      <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      <?php

        $per_page = 7;
        if(isset($_GET['payouts'])){
          $page = $input->get('payouts');
          if($page == 0){
            $page = 1; 
          }
        }else{
          $page = 1;
        }
        $i = ($page*$per_page)-7;
        $where = "status LIKE :status";
        if(!empty($_GET['ref'])){
          $payoutRef = $input->get('ref');
          $where .= " and ref='p-$payoutRef'";
        }else{ 
          $payoutRef = "";
        }
        $start_from = ($page-1) * $per_page;
        $get_withdrawals = $db->query("select * from payouts where $where order by 1 DESC LIMIT $start_from,$per_page",array("status"=>"%$payoutStatus%"));
        $count_withdrawls = $get_withdrawals->rowCount();
        if($count_withdrawls == 0){
          if(!empty($_GET['ref'])){
            echo "<tr> <td colspan='8' class='text-center'>No ".ucfirst($payoutStatus)." Payout Requests Found.</td> </tr>";
          }else{
            echo "<tr> <td colspan='8' class='text-center'>No ".ucfirst($payoutStatus)." Payout Requests.</td> </tr>";
          }
        }
        while($row_withdrawals = $get_withdrawals->fetch()){
        $id = $row_withdrawals->id;
        $ref = $row_withdrawals->ref;
        $seller_id = $row_withdrawals->seller_id;
        $method = $row_withdrawals->method;
        $amount = $row_withdrawals->amount;
        $date = $row_withdrawals->date;
        $status = $row_withdrawals->status;
        $select_seller = $db->select("sellers",array("seller_id" => $seller_id));
        $seller_user_name = $select_seller->fetch()->seller_user_name;

        if($method == "moneygram"){
          $m_text = "Moneygram";
        }elseif($method == "bank_transfer"){
          $m_text = "Bank Transfer";
        }else{
          $m_text = ucfirst($method);
        }
        
        $i++;
      ?>
      <tr>
        <td><?= $i; ?></td>
        <td class="text-danger font-weight-bold"><?= $ref; ?></td>
        <td><a href="index?single_seller=<?= $seller_id; ?>" class="text-primary"><?= $seller_user_name; ?></a></td>
        <td><?= ucfirst($m_text); ?></td>
        <td class="font-weight-bold text-success"><?= showPrice($amount); ?></td>
        <td><?= $date; ?></td>
        <td><?= ucfirst($status); ?></td>
        <td>
        <div class="dropdown"><!--- dropdown Starts --->
          <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">Actions</button>
            <div class="dropdown-menu"><!--- dropdown-menu Starts --->
              <?php if($status == "pending"){ ?>
              <a href="<?= ($method == "moneygram" ? "index?approve_moneygram=$id" : "index?approve_payout=$id") ?>" onclick="return confirm('Are You Sure You Want To Approve This Payout.')" class="dropdown-item">
                <i class="fa fa-thumbs-up"></i> Approve Payout
              </a>
              <a href="index?decline_payout=<?= $id; ?>" class="dropdown-item">
                <i class="fa fa-thumbs-down"></i> Decline Payout
              </a>
              <?php } ?>
              <a href="index?single_seller=<?= $seller_id; ?>" class="dropdown-item">
              <i class="fa fa-info-circle"></i> User's Details
              <?php
              // if($method == "payoneer"){ 
              //   echo "View Seller payoneer Details"; 
              // }elseif ($method == "paypal"){
              //   echo "View Seller Paypal Details";
              // } elseif ($method == "paypal"){
              //   echo "View Seller Paypal Details";
              // } 
              ?>
              </a>
            </div><!--- dropdown-menu Ends --->
          </div><!--- dropdown Ends --->
        </td>
      </tr>
      <?php } ?>
      </tbody>
      </table>
      <div class="d-flex justify-content-center"><!--- d-flex justify-content-center Starts --->
        <ul class="pagination"><!--- pagination Starts --->
        <?php
          /// Now Select All From Proposals Table
          $query = $db->query("select * from payouts where $where",array("status"=>"%".$payoutStatus."%"));
          /// Count The Total Records 
          $total_records = $query->rowCount();
          /// Using ceil function to divide the total records on per page
          $total_pages = ceil($total_records / $per_page);
          echo "<li class='page-item'><a href='index?payouts=1&status=$payoutStatus' class='page-link'> First Page </a></li>";
          echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?payouts=1&status=$payoutStatus'>1</a></li>";
          $i = max(2, $page - 5);
          if ($i > 2)
            echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
          for (; $i < min($page + 6, $total_pages); $i++) {
            echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?payouts=".$i."&status=$payoutStatus' class='page-link'>".$i."</a></li>";
          }
          if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}
          if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?payouts=$total_pages&status=$payoutStatus'>$total_pages</a></li>";}
          echo "<li class='page-item'><a href='index?payouts=$total_pages&status=$payoutStatus' class='page-link'>Last Page </a></li>";
        ?>
        </ul><!--- pagination Ends --->
    </div><!--- d-flex justify-content-center Ends --->
    </div><!--- card-body Ends --->
  </div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->
</div>
<?php } ?>