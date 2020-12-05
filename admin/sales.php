<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}else{
  if(isset($_GET['from'])){
    $from = $input->get('from');
    $to = (isset($_GET['to']) AND !empty($input->get('to'))) ? $input->get('to') : date("Y-m-d");
    $filter_q = "where date Between '$from' AND '$to'";
    $filterUrl = "&from=$from&to=$to";
  }else{
    $from = "";
    $to = "";
    $filter_q = "";
    $filterUrl = "";
  }

	$totalSale = $db->query("SELECT SUM(amount) AS total FROM sales $filter_q")->fetch()->total;
	$allProfit = $db->query("SELECT SUM(profit) AS total FROM sales $filter_q")->fetch()->total;
	$expenses = $db->query("SELECT SUM(amount) AS total FROM expenses $filter_q")->fetch()->total;
	$shoppingBalance = $db->query("SELECT SUM(current_balance) AS total FROM seller_accounts")->fetch()->total;
	$netProfit = $allProfit - $expenses;

  if(empty($totalSale)){$totalSale=0;}
  if(empty($allProfit)){$allProfit=0;}
  if(empty($expenses)){$expenses=0;}
  if(empty($shoppingBalance)){$shoppingBalance=0;}
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

<div class="container" style="max-width: 1240px;"><!--- container Starts --->
<div class="row mt-2"><!--- 1 Row Starts --->
  <div class="col-xl-3 col-lg-6 col-md-6">
   <div class="card text-white border-primary mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
    <div class="card-header bg-primary">
    <div class="row">
     <div class="col-3">
     <i class="fa fa-balance-scale fa-5x"></i>
     </div>
     <div class="col-9 text-right">
       <div class="huge"><?= showPrice($totalSale); ?></div> Total Sale
     </div>
    </div>
    </div>
  </div>    
  </div>
  <div class="col-xl-3 col-lg-6 col-md-6">
    <div class="card text-white border-success mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
      <div class="card-header bg-success">
        <div class="row">
        <div class="col-3">
         <i class="fa fa-money fa-5x"></i>
        </div>
        <div class="col-9 text-right">
         <div class="huge"><?= showPrice($allProfit); ?></div>
         <div class="text-caption">Gross Profit</div>
        </div>
        </div>
      </div>
    </div>    
  </div>
  <div class="col-xl-3 col-lg-6 col-md-6">
   <div class="card text-white border-warning mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
     <div class="card-header bg-warning">
      <div class="row">
      <div class="col-3">
       <i class="fa fa-credit-card fa-5x"></i>
      </div>
      <div class="col-9 text-right">
       <div class="huge"><?= showPrice($expenses); ?></div>
       <div class="text-caption">Expenses</div>
      </div>
      </div>
     </div>        
  </div>    
  </div>
  <div class="col-xl-3 col-lg-6 col-md-6">
    <div class="card text-white border-danger mb-xl-0 mb-lg-3 mb-sm-3 mb-2">
     <div class="card-header bg-danger">
       <div class="row">
          <div class="col-3">
           <i class="fa fa-globe fa-5x"></i>
          </div>
          <div class="col-9 text-right">
           <div class="huge"><?= showPrice($netProfit); ?></div>
           <div class="text-caption">Net Profit</div>
          </div>
       </div>
     </div>
    </div>    
  </div>
</div><!--- 1 Row Ends --->

<div class="row mt-4"><!--- 2 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card"><!--- card Starts --->
<div class="card-header"><!--- card-header Starts --->
<h4 class="h4">Sales</h4>
<p class="mb-0">Accounting made simple.</p>
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->
  <div class="offset-lg-3 col-md-6">
  <form action="" method="get">
  <input type="hidden" name="sales" value="">
  <div class="input-group mb-3 mt-3 mt-lg-0">
    <input type="date" name="from" value="<?= (isset($_GET['from']))?$input->get('from'):''; ?>" max="<?= date("Y-m-d") ?>" class="form-control" placeholder="From">
    <input type="date" name="to" value="<?= (isset($_GET['to']))?$input->get('to'):''; ?>" max="<?= date("Y-m-d") ?>" class="form-control" placeholder="To">
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
  		<th>Id:</th>
  		<th>Buyer/Paid By:</th>
  		<th>Description</th>
  		<th>Amount:</th>
  		<th>Processing Fee:</th>
  		<th>Profit:</th>
  	  <th>Payment Method:</th>
  	  <th>Date:</th>
  	  <th>Actions:</th>
  	</tr>
  	</thead>
  	<tbody><!--- tbody Starts --->
  	<?php
  	$per_page = 10;
  	if(isset($_GET['sales'])){
  	  $page = $input->get('sales');
  	  if($page == 0){ 
  	    $page = 1; 
  	  }
  	}else{
  	  $page = 1;
  	}

    $i = ($page*$per_page)-10;

  	/// Page will start from 0 and multiply by per page
  	$start_from = ($page-1) * $per_page;
  	$sales = $db->query("select * from sales $filter_q order by 1 DESC LIMIT :limit OFFSET :offset","",array("limit"=>$per_page,"offset"=>$start_from));
  	while($sale = $sales->fetch()){
  		$buyer = $db->select("sellers",array("seller_id" => $sale->buyer_id))->fetch();
  		if($sale->action=="featured_fee"){
  			$proposal = $db->select("proposals",array("proposal_id" => $sale->work_id))->fetch();
  			$seller_user_name = $db->select("sellers",array("seller_id" => $proposal->proposal_seller_id))->fetch()->seller_user_name;
  		}

      if($sale->payment_method == "shopping_balance"){
        $sale->payment_method = "shopping balance";
      }elseif ($sale->payment_method == "mobile_money") {
        $sale->payment_method = "dusupay";
      }

      $i++;
  	?>
  	<tr>
  	<td>#<?= $i; ?></td>
  	<td><?= @$buyer->seller_user_name; ?></td>
  	<td>
  	<?php 
  	if($sale->action == "order_tip"){
     $get_orders = $db->select("orders",array("order_id" => $sale->work_id));
     $order_number = $get_orders->fetch()->order_number;
     echo "Payment Of Order Tip. <a class='text-primary' target='_blank' href='index?single_order=$sale->work_id'>#$order_number</a>";
  	}elseif($sale->action == "order_completed"){
     $get_orders = $db->select("orders",array("order_id" => $sale->work_id));
     $order_number = $get_orders->fetch()->order_number;
     echo "Payment Of Order Completion. <a class='text-primary' target='_blank' href='index?single_order=$sale->work_id'>#$order_number</a>";
    }else if($sale->action == "featured_fee"){
  	  $select_proposals = $db->select("proposals",array("proposal_id" => $sale->work_id));
  	  $row_proposals = $select_proposals->fetch();
  	  $proposal_title = $row_proposals->proposal_title;
  	  $proposal_url = $row_proposals->proposal_url;
  	  $proposal_seller_id = $row_proposals->proposal_seller_id;
      
  	  $select_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
  	  $seller_user_name = $select_seller->fetch()->seller_user_name;
  	  echo "Payment Of Proposal Featured Listing";
  	}elseif ($sale->action == "cart") {
  	  echo "Payment Of Whole Cart";
  	}else{
      $get_orders = $db->select("orders",array("order_id" => $sale->work_id));
      $order_number = $get_orders->fetch()->order_number;
      echo "Payment Of Order <a class='text-primary' target='_blank' href='index?single_order=$sale->work_id'>#$order_number</a>";
    }
  	?>
  	</td>
    <td><?= showPrice($sale->amount); ?></td>
    <td><?= showPrice($sale->processing_fee); ?></td>
    <td><?= showPrice($sale->profit); ?></td>
  	<td><?= ucwords($sale->payment_method); ?></td>
  	<td><?= $sale->date; ?></td>
  	<td>
    <?php if($sale->action!="featured_fee"){ ?>
    <a class="btn btn-primary text-white" target="_blank" href="index?single_order=<?=$sale->work_id; ?>">
      <i class="fa fa-info-circle"></i> View Order
    </a>
    <?php }else{ ?>
    <a class="btn btn-primary text-white" target="_blank" href="../proposals/<?=$seller_user_name; ?>/<?=$proposal->proposal_url; ?>">
      <i class="fa fa-info-circle"></i> View Proposal
    </a>
    <?php } ?>
    <div class="dropdown d-none"><!--- dropdown Starts --->
      <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown"></button>
      <div class="dropdown-menu"><!--- dropdown-menu Starts --->
        <?php if($sale->action!="featured_fee"){ ?>
        <a class="btn btn-primary text-white" href="index?single_order=<?=$sale->work_id; ?>">
          <i class="fa fa-info-circle"></i> View Order
        </a>
        <?php }else{ ?>
        <a class="btn btn-primary text-white" href="../proposals/<?=$seller_user_name; ?>/<?=$proposal->proposal_url; ?>">
          <i class="fa fa-info-circle"></i> View Proposal
        </a>
        <?php } ?>
      </div><!--- dropdown-menu Ends --->
    </div><!--- dropdown Ends --->
  	</td>
  	</tr>
  	<?php } ?>
  	<tr>
  	<td colspan="9">
  	<div class="d-flex justify-content-center">
  	<ul class="pagination mb-0">
      <?php
      /// Now Select All Data From Table
      $query = $db->query("select * from sales $filter_q order by 1 DESC");
      /// Count The Total Records 
      $total_records = $query->rowCount();
      /// Using ceil function to divide the total records on per page
      $total_pages = ceil($total_records / $per_page);
      echo "<li class='page-item'><a href='index?sales=1$filterUrl' class='page-link'>First Page</a></li>";
      echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?sales=1$filterUrl'>1</a></li>";
      $i = max(2, $page - 5);
      if($i > 2){
        echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
      }
      for(; $i < min($page + 6, $total_pages); $i++) {
        echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?sales=".$i."$filterUrl' class='page-link'>".$i."</a></li>";
      }
      if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}
      if($total_pages > 1){
        echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?sales=$total_pages$filterUrl'>$total_pages</a></li>";
      }
      echo "<li class='page-item'><a href='index?sales=$total_pages$filterUrl' class='page-link'>Last Page </a></li>";
      ?>
    </ul>
  	</div>
  	</td>
  	</tr>
  	</tbody>
  	<tbody>
  	<tr>
    <td colspan="8" class="total-row text-right"><strong>Total Sale:</strong></td>
    <td class="total-row text-center"><?= showPrice($totalSale); ?></td>

    </tr>
    <tr>
    <td colspan="8" class="total-row text-right"><strong>Gross Profit:</strong></td>
    <td class="total-row text-center"><?= showPrice($allProfit); ?></td>

    </tr>
    <tr>
    <td colspan="8" class="total-row text-right"><strong>Expenses:</strong></td>
    <td class="total-row text-center"><?= showPrice($expenses); ?></td>
    </tr>
    <tr>
    <td colspan="8" class="total-row text-right"><strong>Net Profit:</strong></td>
    <td class="total-row text-center"><?= showPrice($netProfit); ?></td>

    </tr>
    <tr>
    <td colspan="8" class="total-row text-right"><strong>Total Users Shopping Balance:</strong></td>
    <td class="total-row text-center"><?= showPrice($shoppingBalance); ?></td>
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