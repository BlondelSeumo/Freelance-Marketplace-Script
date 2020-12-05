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
                    <h1><i class="menu-icon fa fa-universal-access"></i> Referrals</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">All Referrals</li>
                    </ol>
                </div>
            </div>
        </div>

    </div>


    <div class="container">
    
    <div class="row"><!--- 2 row Starts --->

<div class="col-lg-12">
    <!--- col-lg-12 Starts --->

    <div class="card">
        <!--- card Starts --->

        <div class="card-header">
            <!--- card-header Starts --->

            <h4 class="h4">

                View All Referrals

            </h4>

        </div>
        <!--- card-header Ends --->

        <div class="card-body">
            <!--- card-body Starts --->

            <div class="table-responsive">
                <!--- table-responsive Starts --->

                <table class="table table-bordered ">
                    <!--- table table-bordered table-hover Starts --->

                    <thead>
                        <!--- thead Starts --->

                        <tr>

                            <th>No.</th>

                            <th>Referred By</th>

                            <th>Referred</th>

                            <th>Comission</th>

                            <th> Ip Address</th>

                            <th> Sign Up Date</th>

                            <th> Status</th>

                            <th> Actions </th>

                        </tr>

                    </thead>
                    <!--- thead Ends --->

                    <tbody>
                        <!--- tbody Starts --->

                        <?php

                            $i = 0;

                            $get_referrals = $db->select("referrals",array("status" => "pending"));

                            while($row_referrals = $get_referrals->fetch()){

                            $referral_id = $row_referrals->referral_id;

                            $seller_id = $row_referrals->seller_id;

                            $referred_id = $row_referrals->referred_id;

                            $comission = $row_referrals->comission;

                            $date = $row_referrals->date;

                            $ip = $row_referrals->ip;

                            $status = $row_referrals->status;


                            $select_seller = $db->select("sellers",array("seller_id" => $seller_id));

                            $seller_user_name = $select_seller->fetch()->seller_user_name;


                            $select_referred = $db->select("sellers",array("seller_id" => $referred_id));

                            $referred_user_name = $select_referred->fetch()->seller_user_name;
                            
                            $i++;

                            ?>

                            <tr>

                                <td>
                                    <?= $i; ?>
                                </td>

                                <td>
                                    <?= $seller_user_name; ?>
                                </td>

                                <td>
                                    <?= $referred_user_name; ?>
                                </td>

                                <td>
                                    <?= showPrice($comission); ?>
                                </td>

                                <td>
                                    <?= $ip; ?>
                                </td>

                                <td>
                                    <?= $date; ?>
                                </td>

                                <td>
                                    <?= $status; ?>
                                </td>

                                <td width="180px;">

                                    <div class="dropdown"><!--- dropdown Starts --->

                                        <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">

                                        Actions

                                        </button>

                                        <div class="dropdown-menu">

                                            <a class="dropdown-item" href="index?approve_referral=<?= $referral_id; ?>" onclick="return confirm('Are you sure you want to approve this referral? if so, <?= showPrice($comission); ?>.00 will be added to <?= $seller_user_name; ?> shopping balance.');">

                                        <i class="fa fa-thumbs-up"></i> Approve 

                                        </a>


                                            <a class="dropdown-item" href="index?decline_referral=<?= $referral_id; ?>" onclick="return confirm('Are you sure you want to decline this referral? if so, <?= $seller_user_name; ?> will receive no commision.');">

                                            <i class="fa fa-ban"></i> Decline

                                            </a>


                                        </div>

                                    </div><!--- dropdown Ends --->

                                </td>

                            </tr>

                            <?php } ?>

                    </tbody><!--- tbody Ends --->

                </table><!--- table table-bordered table-hover Ends --->

            </div><!--- table-responsive Ends --->

        </div><!--- card-body Ends --->

    </div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div>

<?php } ?>