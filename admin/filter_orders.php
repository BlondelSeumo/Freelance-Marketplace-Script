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
                    <h1><i class="menu-icon fa fa-eye"></i> Orders</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">All Orders</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
    <div class="row mb-4">
<!--- 2 row Starts --->
<div class="col-lg-12">
<!--- col-lg-12 Starts --->
<div class="card">
    <!--- card Starts --->
    <div class="card-body">
        <!--- card-body Starts --->
        <h4 class="mb-4">
            Orders Summary
            <?php
                $count_orders = $db->count("orders");
            ?>
                <small> All Orders (<?= $count_orders; ?>) </small>
        </h4>
        <div class="row">
            <!--- row Starts --->
            <div class="text-center border-box col-md-3">
                <!--- text-center border-box col-md-3 Starts --->
                <p> Active Orders </p>
                <?php
                $count_orders = $db->count("orders",array("order_active" => "yes"));
                ?>
                    <h2>
                        <?= $count_orders; ?>
                    </h2>
            </div>
            <!--- text-center border-box col-md-3 Ends --->
            <div class="text-center border-box col-md-3">
                <!--- text-center border-box col-md-3 Starts --->
                <p> Completed Orders </p>
                <?php
                $count_orders = $db->count("orders",array("order_active" => "completed"));
                ?>
                    <h2>
                        <?= $count_orders; ?>
                    </h2>
            </div>
            <!--- text-center border-box col-md-3 Ends --->
            <div class="text-center border-box col-md-3">
                <!--- text-center border-box col-md-3 Starts --->
                <p> Delivered Orders </p>
                <?php
                $count_orders = $db->count("orders",array("order_active" => "delivered"));
                ?>
                    <h2>
                        <?= $count_orders; ?>
                    </h2>
            </div>
            <!--- text-center border-box col-md-3 Ends --->
            <div class="text-center border-box col-md-3">
                <!--- text-center border-box col-md-3 Starts --->
                <p> Cancelled Orders </p>
                <?php
                $count_orders = $db->count("orders",array("order_active" => "cancelled"));
                ?>
                    <h2>
                        <?= $count_orders; ?>
                    </h2>
            </div>
            <!--- text-center border-box col-md-3 Ends --->
        </div>
        <!--- row Ends --->
    </div>
    <!--- card-body Ends --->
</div>
<!--- card Ends --->
</div>
<!--- col-lg-12 Ends --->
</div>
<!--- 2 row Ends --->
<div class="row">
<!--- 3 row Starts --->
<div class="col-lg-12">
    <!--- col-lg-12 Starts --->
    <div class="card">
        <!--- card Starts --->
        <div class="card-header">
            <!--- card-header Starts --->
            <h4 class="h4">
                Filtered Results
            </h4>
        </div>
        <!--- card-header Ends --->
        <div class="card-body">
            <!--- card-body Starts --->
            <h3 class="text-center mb-3"> Filter Orders </h3>
            <form class="form-inline d-flex mb-4 justify-content-center" method="post" action="index?filter_orders">
                <!--- form-inline d-flex mb-4 justify-content-center Starts --->
                <div class="form-group">
                    <!--- form-group Starts --->
                    <label class="mb-2 mr-sm-2 mb-sm-0"> Enter Order Number : </label>
                    <div class="input-group">
                        <span class="input-group-addon">
                        <b>#</b>
                        </span>
                        <input type="number" name="order_number" class="form-control mb-2 mr-sm-2 mb-sm-0" placeholder="7890958" value="<?= $input->post('order_number'); ?>" required>
                    </div>
                </div>
                <!--- form-group Ends --->
                <div class="form-group">
                    <!--- form-group Starts --->
                    <input type="submit" name="submit" class="form-control btn btn-success" value="Filter Orders Again">
                </div>
                <!--- form-group Ends --->
            </form>
            <!--- form-inline d-flex mb-4 justify-content-center Ends --->
            <div class="table-responsive">
                <!--- table-responsive Starts --->
                <table class="table table-bordered">
                    <!---- table table-bordered table-hover Starts --->
                    <thead>
                        <!--- thead Starts --->
                        <tr>
                            <th> Order Number </th>
                            <th> Order Total </th>
                            <th> Order Qty </th>
                            <th> Order Date </th>
                            <th> Order Status </th>
                            <th> Order Actions </th>
                        </tr>
                    </thead>
                    <!--- thead Ends --->
                    <tbody>
        <!--- tbody Starts --->
                    <?php
                    $order_number = $input->post('order_number');
                    $sel_orders = $db->select("orders",array("order_number" => $order_number));
                    $count_orders = $sel_orders->rowCount();
                    if($count_orders == 0){
                    echo "
                    <tr>
                    <td colspan='6'>
                    <h3 class='text-center pt-2 pb-2'>No order found relating to your search.</h3>
                    </td>
                    </tr>
                    ";
                    }
                    while($row_orders = $sel_orders->fetch()){
                    $order_id = $row_orders->order_id;
                    $order_price = $row_orders->order_price;
                    $order_number = $row_orders->order_number;
                    $order_qty = $row_orders->order_qty;
                    $order_date = $row_orders->order_date;
                    $order_status = $row_orders->order_status;
                    ?>
                        <tr>
                            <td>#
                                <?= $order_number; ?>
                            </td>
                            <td><?= showPrice($order_price); ?>
                            </td>
                            <td>
                                <?= $order_qty; ?>
                            </td>
                            <td>
                                <?= $order_date; ?>
                            </td>
                            <td>
                                <?= ucfirst($order_status); ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <!--- dropdown Starts --->
                                    <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu">
                                        <!--- dropdown-menu Starts --->
                                        <a class="dropdown-item" href="index?single_order=<?= $order_id; ?>">
                                            <i class="fa fa-info-circle"></i> Order details
                                        </a>
                                        <?php if($order_status == "cancelled" or $order_status == "completed"){ ?>
                                        <?php }else{ ?>
                                        <a class="dropdown-item" href="index?cancel_order=<?= $order_id; ?>" onclick="return confirm('Do You Really Want To Cancel This Order , After Cancellation , Order Amount Will Return To Buyer.');">
                                            <i class="fa fa-ban"></i> Cancel Order
                                        </a>
                                        <?php } ?>
                                    </div>
                                    <!--- dropdown-menu Ends --->
                                </div>
                                <!--- dropdown Ends --->
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <!--- tbody Ends --->
                </table>
                <!---- table table-bordered table-hover Ends --->
            </div>
            <!--- table-responsive Ends --->
        </div>
        <!--- card-body Ends --->
    </div>
    <!--- card Ends --->
</div>
<!--- col-lg-12 Ends --->
</div>
<!--- 3 row Ends --->
</div>
<?php } ?>