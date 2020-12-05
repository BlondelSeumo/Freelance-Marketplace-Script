<?php

@session_start();

if(!isset($_SESSION['admin_email'])){

echo "<script>window.open('login','_self');</script>";

}else{

$count_buyer_reviews = $db->count("buyer_reviews");

$count_seller_reviews = $db->count("seller_reviews");

?>

<div class="breadcrumbs">
<div class="col-sm-4">
<div class="page-header float-left">
<div class="page-title">
    <h1><i class="menu-icon fa fa-star"></i> Reviews / Buyer Reviews</h1>
</div>
</div>
</div>
<div class="col-sm-8">
<div class="page-header float-right">
<div class="page-title">
    <ol class="breadcrumb text-right">
        <li class="active">
            <a href="index?insert_review" class="btn btn-success">

                <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Review</span>

            </a>
        </li>
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

        All Buyer Reviews

    </h4>

    </div>
    <!--- card-header Ends --->

    <div class="card-body">
        <!--- card-body Starts --->

        <a href="index?view_buyer_reviews" class="font-weight-bold mr-2">

    Buyer Reviews (<?= $count_buyer_reviews; ?>)

    </a>

        <span class="mr-2">|</span>

        <a href="index?view_seller_reviews" class="mr-2">

    Seller Reviews (<?= $count_seller_reviews; ?>)

    </a>

        <div class="table-responsive mt-4">
            <!--- table-responsive mt-4 Starts --->

            <table class="table table-bordered table-striped">
                <!--- table table-bordered table-hover table-striped Starts --->

                <thead>
                    <!--- thead Starts --->

                    <tr>

                        <th>Order No</th>

                        <th>Buyer</th>

                        <th>Proposal</th>

                        <th>Rating</th>

                        <th>Date</th>

                        <th>Delete</th>

                    </tr>

                </thead>
                <!--- thead Ends --->

                <tbody>
                    <!-- tbody Starts --->

                    <?php

    $per_page = 7;

    if(isset($_GET['view_buyer_reviews'])){

    $page = $input->get('view_buyer_reviews');

    if($page == 0){ $page = 1; }

    }else{

    $page = 1;

    }

    /// Page will start from 0 and multiply by per page

    $start_from = ($page-1) * $per_page;

    $get_buyer_reviews = $db->query("select * from buyer_reviews order by 1 DESC LIMIT :limit OFFSET :offset","",array("limit"=>$per_page,"offset"=>$start_from));

    while($row_buyer_reviews = $get_buyer_reviews->fetch()){

    $review_id = $row_buyer_reviews->review_id;

    $order_id = $row_buyer_reviews->order_id;

    $proposal_id = $row_buyer_reviews->proposal_id;

    $review_buyer_id = $row_buyer_reviews->review_buyer_id;

    $buyer_rating = $row_buyer_reviews->buyer_rating;

    $review_date = $row_buyer_reviews->review_date;


    $get_order = $db->select("orders",array("order_id" => $order_id));

    $row_order = $get_order->fetch();
        
    $order_number = @$row_order->order_number;


    $select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));

    $row_proposals = $select_proposals->fetch();

    $proposal_title = @$row_proposals->proposal_title;


    $select_buyer = $db->select("sellers",array("seller_id" => $review_buyer_id));

    $row_buyer = $select_buyer->fetch();

    $buyer_user_name = $row_buyer->seller_user_name;

    ?>

                <tr>

                    <td>

                    <?php 

                    if($order_id == "0"){

                    echo "Review Inserted By Admin  <small class='text-primary'>No Order Number</small>";

                    }else{

                    ?>

                    <a href="index?single_order=<?= $order_id; ?>">

                    #<?= $order_number; ?>

                    </a>

                            <?php } ?>

                    </td>

                    <td>

                    <a href="index?single_seller=<?= $review_buyer_id; ?>">

                        <?= $buyer_user_name; ?>

                    </a>

                    </td>

                    <td>
                        <?= $proposal_title; ?>
                    </td>

                    <td>
                        <?= $buyer_rating; ?>
                    </td>

                    <td>
                        <?= $review_date; ?>
                    </td>

                    <td>

                        <a href="index?delete_buyer_review=<?= $review_id; ?>" onclick="return confirm('Do You Really Want To Delete This Review After Deletion Seller Rating And His Proposal Rating Will Be Also Changed.')" class="btn btn-danger">

                            <i class="fa fa-trash text-white"></i> <span class="text-white">Delete Review</span>

                        </a>

                    </td>

                </tr>

                <?php } ?>

                </tbody>
                <!-- tbody Ends --->

            </table>
            <!--- table table-bordered table-hover table-striped Ends --->

        </div>
        <!--- table-responsive mt-4 Ends --->

        <div class="d-flex justify-content-center">
            <!--- d-flex justify-content-center Starts --->

            <ul class="pagination">
                <!--- pagination Starts --->

                <?php 

                /// Now Select All Data From Table

                $query = $db->select("buyer_reviews","","DESC");

                /// Count The Total Records 

                $total_records = $query->rowCount();

                /// Using ceil function to divide the total records on per page

                $total_pages = ceil($total_records / $per_page);

                echo "<li class='page-item'><a href='index?view_buyer_reviews=1' class='page-link'>First Page</a></li>";

                echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?view_buyer_reviews=1'>1</a></li>";
                
                $i = max(2, $page - 5);
                
                if ($i > 2)
                
                    echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
                
                for (; $i < min($page + 6, $total_pages); $i++) {
                
                    echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?view_buyer_reviews=".$i."' class='page-link'>".$i."</a></li>";

                }
                
                if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}

                if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?view_buyer_reviews=$total_pages'>$total_pages</a></li>";}

                echo "<li class='page-item'><a href='index?view_buyer_reviews=$total_pages' class='page-link'>Last Page </a></li>";

                ?>

            </ul>
            <!--- pagination Ends --->

        </div>
        <!--- d-flex justify-content-center Ends --->

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