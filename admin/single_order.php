<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
    

if(isset($_GET['single_order'])){
	
    $order_id = $input->get('single_order');
    	
    $get_order = $db->select("orders",array("order_id" => $order_id));
    $row_order = $get_order->fetch();
    $order_number = $row_order->order_number;
    $proposal_id = $row_order->proposal_id;
    $seller_id = $row_order->seller_id;
    $buyer_id = $row_order->buyer_id;
    $order_price = $row_order->order_price;
    $order_qty = $row_order->order_qty;
    $order_date = $row_order->order_date;
    $order_duration = $row_order->order_duration;
    $order_fee = $row_order->order_fee;
    $order_description = $row_order->order_description;
    $total = $order_price+$order_fee;

    /// Select Order Proposal Details ///
    $select_proposal = $db->select("proposals",array("proposal_id" => $proposal_id));
    $row_proposal = $select_proposal->fetch();
    $proposal_title = $row_proposal->proposal_title;
    $proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposal->proposal_img1);
    $proposal_url = $row_proposal->proposal_url;

    /// Select Order Seller Details ///
    $select_seller = $db->select("sellers",array("seller_id" => $seller_id));
    $row_seller = $select_seller->fetch();
    $seller_user_name = $row_seller->seller_user_name;

    /// Select Order Buyer Details ///
    $select_buyer = $db->select("sellers",array("seller_id" => $buyer_id));
    $row_buyer = $select_buyer->fetch();
    $buyer_user_name = $row_buyer->seller_user_name;
    	
}

?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-eye"></i> Order</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Order Number: #<?= $order_number; ?></li>
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

            <h4 class="h4">
                <i class="fa fa-info-circle text-success fa-fw"></i> Order Info &nbsp; 
            </h4>

        </div><!--- card-header Ends --->

        <div class="card-body row"><!--- card-body row Starts --->

            <div class="col-md-2"><!--- col-md-2 Starts --->

                <img src="<?= $proposal_img1; ?>" class="img-fluid">

            </div><!--- col-md-2 Ends --->

            <div class="col-md-10"><!--- col-md-10 Starts --->

                <h1 class="float-right"><?= $s_currency; ?><?= $order_price; ?></h1>

                <h4>

                    <?= $proposal_title; ?>

                    <small class="h6">
                        <a href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank" class="text-success">View Proposal/Service</a>
                    </small>

                </h4>

                <p class="text-muted pt-2">

                    Seller :

                    <a href="index?single_seller=<?= $seller_id; ?>" target="_blank">
                        <?= ucfirst($seller_user_name); ?>
                    </a>

                    &nbsp; | &nbsp; Buyer :

                    <a href="index?single_seller=<?= $buyer_id; ?>" target="_blank">
                        <?= ucfirst($buyer_user_name); ?>
                    </a>

                    &nbsp; | &nbsp; Date : <?= $order_date; ?>

                </p>

            </div>
            <!--- col-md-10 Ends --->

            <div class="col-md-12">
                <!--- col-md-12 Starts --->

                <table class="order-table table mt-3">
                    <!--- order-table table mt-3 Starts --->

                    <thead><!--- thead Starts --->

                        <tr>

                            <th>Item</th>

                            <th>Quantity</th>

                            <th>Duration</th>

                            <th>Amount</th>

                        </tr>

                    </thead>
                    <!--- thead Ends --->

                    <tbody>
                        <!--- tbody Starts --->

                        <tr>

                            <td width="600"><?= $proposal_title; ?></td>

                            <td><?= $order_qty; ?></td>

                            <td><?= $order_duration; ?></td>

                            <td><?= showPrice($order_price); ?></td>

                        </tr>

                        <?php if(!empty($order_fee)){ ?>

                        <tr>

                            <td width="600">Processing Fee</td>

                            <td></td>

                            <td></td>

                            <td><?= showPrice($order_fee); ?></td>

                        </tr>

                        <?php } ?>

                        <tr>

                            <td colspan="4">

                                <span class="float-right mr-5"><b>Total : </b><?= showPrice($total); ?></span>

                            </td>

                        </tr>

                    </tbody>
                    <!--- tbody Ends --->

                </table>
                <!--- order-table table mt-3 Ends --->


                <?php if(!empty($order_description)){ ?>

                <table class="order-table table">
                    <!--- order-table table Starts --->

                    <thead>

                        <tr>

                            <th> Description </th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>
                                <?= $order_description; ?>
                            </td>

                        </tr>

                    </tbody>

                </table>
                <!--- order-table table Ends --->

                <?php } ?>

            </div>
            <!--- col-md-12 Ends --->

        </div>
        <!--- card-body row Ends --->

    </div>
    <!--- card Ends --->

</div>
<!--- col-lg-12 Ends --->

</div>
<!--- 2 row Ends --->




<div class="row mt-4"><!--- 3 row Starts --->

    <div class="col-lg-12"><!--- col-lg-12 Starts --->

        <div class="card"><!--- card Starts --->

            <div class="card-header"><!--- card-header Starts --->

                <h4 class="h4">

                    <i class="fa fa-money-bill-alt"></i> Order Conversation Between Buyer & Seller

                </h4>

            </div><!--- card-header Ends --->

            <div class="card-body"><!--- card-body Starts --->

                <?php

                    $get_order_conversations = $db->select("order_conversations",array("order_id" => $order_id));
                    $count_order_conversations = $get_order_conversations->rowCount();
                    if($count_order_conversations == 0){
                        echo "<h3 class='text-center'>This Order Has No Conversations.</h3>";
                    }

                    while($row_order_conversations = $get_order_conversations->fetch()){

                    $c_id = $row_order_conversations->c_id;
                    $sender_id = $row_order_conversations->sender_id;
                    $message = $row_order_conversations->message;
                    $status = $row_order_conversations->status;
                    $file = $row_order_conversations->file;
                    $date = $row_order_conversations->date;

                    $select_sender = $db->select("sellers",array("seller_id" => $sender_id));
                    $row_sender  = $select_sender->fetch();
                    $sender_image = getImageUrl2("sellers","seller_image",$row_sender->seller_image);
                    $sender_user_name = $row_sender->seller_user_name;

                    if($seller_id == $sender_id){
                        $receiver_name = "Buyer";
                    }else{
                        $receiver_name = "Seller";
                    }

                    ?>

                    <?php if($status == "message"){ ?>

                    <div class="message-div">
                        <!--- message-div Starts --->

                        <?php if(!empty($sender_image)){ ?>
                           <img src="<?= $sender_image; ?>" class="message-image">
                        <?php }else{ ?>
                           <img src="../user_images/empty-image.png" class="message-image">
                        <?php } ?>

                        <h5><?= $sender_user_name; ?></h5>

                        <p class="message-desc">

                            <?= $message; ?>

                            <?php if(!empty($file)){ ?>

                           <a href="<?= "includes/download?order_id=$order_id&c_id=$c_id"; ?>" download class="d-block mt-2 ml-1">
                              <i class="fa fa-download"></i> <?= $file; ?>
                           </a>

                           <?php }else{ ?>

                           <?php } ?>

                        </p>

                        <p class="float-right text-muted"><?= $date; ?> </p><br>

                    </div><!--- message-div Ends --->

                    <?php }elseif($status == "delivered"){ ?>

                    <h2 class="mt-3 mb-4" align="center"> Order Delivered </h2>

                    <div class="message-div">
                        <!--- message-div Starts --->

                        <?php if(!empty($sender_image)){ ?>

                        <img src="<?= $sender_image; ?>" class="message-image">

                        <?php }else{ ?>

                        <img src="../user_images/empty-image.png" class="message-image">

                        <?php } ?>

                        <h5>
                            <?= $sender_user_name; ?>
                        </h5>

                        <p class="message-desc">

                            <?= $message; ?>

                            <?php if(!empty($file)){ ?>

                            <a href="<?= "includes/download?order_id=$order_id&c_id=$c_id"; ?>" download class="d-block mt-2 ml-1">
                            <i class="fa fa-download"></i> <?= $file; ?>
                            </a>

                            <?php }else{ ?>

                            <?php } ?>

                        </p>

                        <p class="float-right text-muted">
                            <?= $date; ?> </p><br>

                    </div>
                    <!--- message-div Ends --->


                    <?php }elseif($status == "revision"){ ?>


                    <h2 class="mt-3 mb-4" align="center"> Revision Request By
                        <?= $sender_user_name; ?> </h2>


                    <div class="message-div">
                        <!--- message-div Starts --->

                        <?php if(!empty($sender_image)){ ?>

                        <img src="<?= $sender_image; ?>" class="message-image">

                        <?php }else{ ?>

                        <img src="../user_images/empty-image.png" class="message-image">

                        <?php } ?>

                        <h5>
                            <?= $sender_user_name; ?>
                        </h5>

                        <p class="message-desc">

                            <?= $message; ?>

                            <?php if(!empty($file)){ ?>

                            <a href="<?= "includes/download?order_id=$order_id&c_id=$c_id"; ?>" download class="d-block mt-2 ml-1 text-primary">

<i class="fa fa-download"></i> <?= $file; ?>

</a>

                            <?php }else{ ?>

                            <?php } ?>

                        </p>

                        <p class="float-right text-muted">
                            <?= $date; ?> </p><br>

                    </div>
                    <!--- message-div Ends --->


                    <?php }elseif($status == "cancellation_request"){ ?>




                    <h2 class="mt-3 mb-4" align="center"> Order Cancellation Request By
                        <?= $sender_user_name; ?> </h2>


                    <div class="message-div">
                        <!--- message-div Starts --->

                        <?php if(!empty($sender_image)){ ?>

                        <img src="<?= $sender_image; ?>" class="message-image">

                        <?php }else{ ?>

                        <img src="../user_images/empty-image.png" class="message-image">

                        <?php } ?>

                        <h5>
                            <?= $sender_user_name; ?>
                        </h5>

                        <p class="message-desc">

                            <?= $message; ?>

                            <?php if(!empty($file)){ ?>

                           <a href="<?= "includes/download?order_id=$order_id&c_id=$c_id"; ?>" download class="d-block mt-2 ml-1">
                              <i class="fa fa-download"></i> <?= $file; ?>
                           </a>

                            <?php }else{ ?>

                            <?php } ?>

                        </p>

                        <p class="float-right text-muted">
                            <?= $date; ?> </p><br>

                    </div>
                    <!--- message-div Ends --->

                    <div class="order-status-message">
                        <!--- order-status-message Starts --->

                        <i class="fa fa-times fa-3x text-danger"></i>

                        <h5 class="text-danger">

                            <?php if($sender_id == $buyer_id){ ?> Seller has not yet accepted cancellation request from buyer.

                            <?php }elseif($sender_id == $seller_id){ ?> Buyer has not yet accepted cancellation request from seller.

                            <?php } ?>

                        </h5>

                    </div>
                    <!--- order-status-message Ends --->

                    <?php }elseif($status == "decline_cancellation_request"){ ?>

                    <h2 class="mt-3 mb-4" align="center"> Order Cancellation Request By
                        <?= $sender_user_name; ?> </h2>

                    <div class="message-div"><!--- message-div Starts --->

                        <?php if(!empty($sender_image)){ ?>

                        <img src="<?= $sender_image; ?>" class="message-image">

                        <?php }else{ ?>

                        <img src="../user_images/empty-image.png" class="message-image">

                        <?php } ?>

                        <h5>
                            <?= $sender_user_name; ?>
                        </h5>

                        <p class="message-desc">

                            <?= $message; ?>

                            <?php if(!empty($file)){ ?>

                           <a href="<?= "includes/download?order_id=$order_id&c_id=$c_id"; ?>" download class="d-block mt-2 ml-1">

                              <i class="fa fa-download"></i> <?= $file; ?>

                           </a>

                            <?php }else{ ?>

                            <?php } ?>

                        </p>

                        <p class="float-right text-muted">
                            <?= $date; ?> </p><br>

                    </div>
                    <!--- message-div Ends --->

                    <div class="order-status-message">
                        <!--- order-status-message Starts --->

                        <i class="fa fa-times fa-3x text-danger"></i>

                        <h5 class="text-danger">

                            Cancellation Request Declined By
                            <?= $receiver_name; ?>

                        </h5>

                    </div>
                    <!--- order-status-message Ends --->


                    <?php }elseif($status == "accept_cancellation_request"){ ?>



                    <h2 class="mt-3 mb-4" align="center"> Order Cancellation Request By
                        <?= $sender_user_name; ?> </h2>


                    <div class="message-div">
                        <!--- message-div Starts --->

                        <?php if(!empty($sender_image)){ ?>

                        <img src="<?= $sender_image; ?>" class="message-image">

                        <?php }else{ ?>

                        <img src="../user_images/empty-image.png" class="message-image">

                        <?php } ?>

                        <h5>
                            <?= $sender_user_name; ?>
                        </h5>

                        <p class="message-desc">

                            <?= $message; ?>

                            <?php if(!empty($file)){ ?>

                            <a href="<?= "includes/download?order_id=$order_id&c_id=$c_id"; ?>" download class="d-block mt-2 ml-1">

<i class="fa fa-download"></i> <?= $file; ?>

</a>

                            <?php }else{ ?>

                            <?php } ?>

                        </p>

                        <p class="float-right text-muted">
                            <?= $date; ?> </p><br>

                    </div>
                    <!--- message-div Ends --->

                    <div class="order-status-message" align="center">
                        <!--- order-status-message Starts --->

                        <i class="fa fa-times fa-3x text-danger" style="position:relative; left:70px;"></i>

                        <h5 class="text-danger">Order Cancelled By Mutual Agreement. </h5>

                        <p> Order Was Cancelled By Mutual Agreement Between Seller and Buyer. </p>

                    </div>
                    <!--- order-status-message Ends --->


                    <?php }elseif($status == "cancelled_by_customer_support"){ ?>



                    <div class="order-status-message">
                        <!--- order-status-message Starts --->

                        <i class="fa fa-times fa-3x text-danger"></i>

                        <h5 class="text-danger"> Order Cancelled By Customer Support. </h5>

                        <p> Payment for this order was refunded to buyer <?= $site_name; ?> Balance. </p>

                    </div>
                    <!--- order-status-message Ends --->



                    <?php } ?>


                    <?php } ?>

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