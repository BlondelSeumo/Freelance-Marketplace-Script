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
                        <h1><i class="menu-icon fa fa-gift"></i> Coupon Code </h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                             <li class="active"><a href="index?insert_coupon" class="btn btn-success">

                                            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add New Coupon</span>

                                        </a></li>
                        </ol>
                    </div>
                </div>
            </div>
    
    </div>


<div class="container">


    <div class="row">
        <!--- 2 row Starts --->

        <div class="col-lg-12">
            <!--- col-lg-12 Starts --->

            <div class="card">
                <!--- card Starts --->

                <div class="card-header">
                    <!--- card-header Starts --->

                    <h4 class="h4">

                         All Coupons

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <div class="table-responsive">
                        <!--- table-responsive Starts --->

                        <table class="table table-bordered">
                            <!--- table table-bordered table-hover Starts --->

                            <thead>
                                <!--- thead Starts --->

                                <th>Coupon Id</th>

                                <th>Coupon Title</th>

                                <th>Coupon Proposal</th>

                                <th>Coupon Code</th>

                                <th>Coupon Price</th>

                                <th>Coupon Limit</th>

                                <th>Coupon Used</th>

                                <th>Delete Coupon</th>

                                <th>Edit Coupon</th>

                            </thead>
                            <!--- thead Ends --->

                            <tbody>
                                <!--- tbody Starts --->

                                <?php

                                    $i = 0;

                                    $get_coupons = $db->select("coupons","","DESC");

                                    while($row_coupons = $get_coupons->fetch()){

                                    $coupon_id = $row_coupons->coupon_id;

                                    $coupon_title = $row_coupons->coupon_title;

                                    $coupon_price = $row_coupons->coupon_price;

                                    $coupon_type = $row_coupons->coupon_type;

                                    $coupon_code = $row_coupons->coupon_code;

                                    $coupon_limit = $row_coupons->coupon_limit;

                                    $coupon_used = $row_coupons->coupon_used;

                                    $proposal_id = $row_coupons->proposal_id;


                                    $select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));

                                    $row_proposals = $select_proposals->fetch();

                                    $proposal_title = $row_proposals->proposal_title;


                                    $i++;


                                ?>

                                    <tr>

                                        <td>
                                            <?= $i; ?>
                                        </td>

                                        <td>
                                            <?= $coupon_title; ?>
                                        </td>

                                        <td>
                                            <?= $proposal_title; ?>
                                        </td>

                                        <td>
                                            <?= $coupon_code; ?>
                                        </td>

                                        <td>
                                          <?php if($coupon_type == "fixed_price"){ ?>
                                            <?= showPrice($coupon_price); ?>
                                        <?php }else{ ?>
                                            %<?= $coupon_price; ?>
                                       <?php } ?>
                                       
                                        </td>

                                        <td>
                                            <?= $coupon_limit; ?>
                                        </td>

                                        <td>
                                            <?= $coupon_used; ?>
                                        </td>

                                        <td>

                                            <a href="index?delete_coupon=<?= $coupon_id; ?>" class="btn btn-danger">

                                                <i class="fa fa-trash text-white"></i> <span class="text-white">Delete</span>

                                            </a>

                                        </td>

                                        <td>

                                            <a href="index?edit_coupon=<?= $coupon_id; ?>" class="btn btn-success">

                                                <i class="fa fa-pencil text-white"></i> <span class="text-white"> Edit</span>

                                            </a>

                                        </td>

                                    </tr>

                                    <?php } ?>

                            </tbody>
                            <!--- tbody Ends --->

                        </table>
                        <!--- table table-bordered table-hover Ends --->

                    </div>
                    <!--- table-responsive Ends --->

                </div>
                <!--- card-body Ends --->

            </div>
            <!--- card Ends --->

        </div>
        <!--- col-lg-12 Ends --->

    </div>
    <!--- 2 row Ends --->

</div>


<?php } ?>