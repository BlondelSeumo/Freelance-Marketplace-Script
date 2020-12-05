<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{


$count_all_proposals = $db->query("select * from proposals where proposal_status not in ('modification','draft','deleted')")->rowCount();

$count_active_proposals = $db->count("proposals",array("proposal_status" => "active"));

$count_featured_proposals = $db->count("proposals",array("proposal_status" => "active","proposal_featured" => "yes"));

$count_pending_proposals = $db->count("proposals",array("proposal_status" => "pending"));

$count_pause_proposals = $db->query("select * from proposals where proposal_status='pause' or proposal_status='admin_pause'")->rowCount();

$count_trash_proposals = $db->count("proposals",array("proposal_status" => "trash"));	

?>

<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-table"></i> Proposals / Active Proposals</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Active Proposals</li>
                        </ol>
                    </div>
                </div>
            </div>
    
    </div>

<div class="container">

<div class="row "><!--- 1 row Starts --->

<div class="col-lg-12"><!--- col-lg-12 Starts --->

<div class="p-3 mb-3  "><!--- p-3 mb-3 filter-form Starts --->

<h2 class="pb-4">Filter Proposals/Services</h2>

<form class="form-inline pb-2" method="get" action="filter_proposals.php">

<div class="form-group">

<label> Delivery Time: </label>

<select name="delivery_id" required class="form-control mb-2 mr-sm-2 mb-sm-0">

<option value=""> Select A Delivery Time </option>

<?php

$get_delivery_times = $db->select("delivery_times");

while($row_delivery_times = $get_delivery_times->fetch()){

$delivery_id = $row_delivery_times->delivery_id;

$delivery_title= $row_delivery_times->delivery_title;
    
echo "<option value='$delivery_id'>$delivery_title</option>";
    
}

?>

</select>

</div>


<div class="form-group">

<label> Seller Level: </label>

<select name="level_id" required class="form-control mb-2 mr-sm-2 mb-sm-0">

<option value=""> Select A Seller Level </option>

<?php

$get_seller_levels = $db->select("seller_levels");

while($row_seller_levels = $get_seller_levels->fetch()){
    
$level_id = $row_seller_levels->level_id;

$level_title = $db->select("seller_levels_meta",array("level_id"=>$level_id,"language_id"=>$adminLanguage))->fetch()->title;
    
echo "<option value='$level_id'>$level_title</option>";
    
}

?>

</select>

</div>



<div class="form-group">

<label> Category: </label>

<select name="cat_id" required class="form-control mb-2 mr-sm-2 mb-sm-0">

<option value=""> Select A Category </option>

<?php

$get_categories = $db->select("categories");

while($row_categories = $get_categories->fetch()){
    
$cat_id = $row_categories->cat_id;

$get_meta = $db->select("cats_meta",array("cat_id" => $cat_id, "language_id" => $adminLanguage));

$cat_title = $get_meta->fetch()->cat_title;
    
echo "<option value='$cat_id'>$cat_title</option>";

}

?>

</select>

</div>

<button type="submit" class="btn btn-success"> Filter</button>

</form>

</div><!--- p-3 mb-3 filter-form Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 1 row Ends --->


<div class="row mt-3"><!--- 2 row mt-3 Starts --->

<div class="col-lg-12"><!--- col-lg-12 Starts --->

<div class="card"><!--- card Starts --->

<div class="card-header"><!--- card-header Starts --->

<h4 class="h4">

Proposals
</h4>

</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

<a href="index?view_proposals" class="mr-2">

All (<?= $count_all_proposals; ?>)

</a>

<span class="mr-2">|</span>


<a href="index?view_proposals_active" class="make-black font-weight-bold mr-2">

Active (<?= $count_active_proposals; ?>)

</a>

<span class="mr-2">|</span>


<a href="index?view_proposals_featured" class="mr-2">

Featured (<?= $count_featured_proposals; ?>)

</a>

<span class="mr-2">|</span>


<a href="index?view_proposals_pending" class=" mr-2">

Pending Approval (<?= $count_pending_proposals; ?>)

</a>

<span class="mr-2">|</span>



<a href="index?view_proposals_paused" class="mr-2">

Paused (<?= $count_pause_proposals; ?>)

</a>

<span class="mr-2">|</span>


<a href="index?view_proposals_trash" class="mr-2">

Trash (<?= $count_trash_proposals; ?>)

</a>


<div class="table-responsive mt-4"><!--- table-responsive mt-4 Starts --->

<table class="table  table-bordered table-striped"><!--- table table-hover table-bordered Starts --->

<thead><!--- thead Starts --->

<tr>

<th>Proposal's Title</th>

<th>Proposal's Display Image</th>

<th>Proposal's Price</th>

<th>Proposal's Category</th>

<th>Proposal's Order Queue</th>

<th>Proposal's Status</th>

<th>Proposal's Action Options</th>

</tr>

</thead><!--- thead Ends --->

<tbody><!--- tbody Starts --->

<?php


$get_proposals = $db->query("select * from proposals where proposal_status='active' order by 1 DESC");

while($row_proposals = $get_proposals->fetch()){

$proposal_id = $row_proposals->proposal_id;

$proposal_title = $row_proposals->proposal_title;

$proposal_url = $row_proposals->proposal_url;

$proposal_price = $row_proposals->proposal_price;

$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);

$proposal_cat_id = $row_proposals->proposal_cat_id;

$proposal = $row_proposals->proposal_cat_id;

$proposal_seller_id = $row_proposals->proposal_seller_id;

$proposal_status = $row_proposals->proposal_status;

$proposal_seller_id = $row_proposals->proposal_seller_id;

$proposal_featured = $row_proposals->proposal_featured;
$proposal_toprated = $row_proposals->proposal_toprated;

if($proposal_price == 0){

$proposal_price = "";

$get_p = $db->select("proposal_packages",array("proposal_id" => $proposal_id));

while($row = $get_p->fetch()){

$proposal_price .=" | $s_currency" . $row->price;

}

}else{

$proposal_price = "$s_currency" . $proposal_price;

}

$select_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));

$seller_user_name = $select_seller->fetch()->seller_user_name;


$select_orders = $db->query("select * from orders where proposal_id='$proposal_id' AND NOT order_status='complete' AND proposal_id='$proposal_id' AND NOT order_status='cancelled'");

$proposal_order_queue = $select_orders->rowCount();


$get_meta = $db->select("cats_meta",array("cat_id" => $proposal_cat_id, "language_id" => $adminLanguage));

$cat_title = $get_meta->fetch()->cat_title;

?>

<tr>

<td><?= $proposal_title; ?></td>

<td>

<img src="<?= $proposal_img1; ?>" width="70" height="60">

</td>

<td><?= $proposal_price; ?></td>

<td><?= $cat_title; ?></td>

<td><?= $proposal_order_queue; ?></td>

<td><?= ucfirst($proposal_status); ?></td>

<td>

<a title="View Proposal" href="../proposals/<?= $seller_user_name; ?>/<?= $proposal_url; ?>" target="_blank">

<i class="fa fa-eye"></i>

</a>
    

<?php if($proposal_featured == "yes"){ ?>

    <a class="text-success" title="Remove Proposal From Featured Listing." href="index?remove_feature_proposal=<?= $proposal_id; ?>"/>
        <i class="fa fa-star-half-o"></i>
    </a>

<?php }else{ ?>

    <a href="index?feature_proposal=<?= $proposal_id; ?>" title="Make Your Proposal Featured">
        <i class="fa fa-star"></i>
    </a>

<?php } ?>

<?php if($proposal_toprated == 0){ ?>
<a href="index?toprated_proposal=<?= $proposal_id; ?>" title="Make Your Proposal Top Rated">
<i class="fa fa-heart" aria-hidden="true"></i>
</a>
<?php }else{ ?>
<a class="text-danger" href="index?removetoprated_proposal=<?= $proposal_id; ?>" title="Remove Proposal From Top Rated Listing.">
<i class="fa fa-heartbeat" aria-hidden="true"></i>
</a>
<?php } ?>


<a title="Pause/Deactivate Proposal" href="index?pause_proposal=<?= $proposal_id; ?>">

<i class="fa fa-pause-circle"></i> 
</a>



<a title="Delete Proposal" href="index?move_to_trash=<?= $proposal_id; ?>">

<i class="fa fa-trash"></i>

</a>

</td>


</tr>

<?php } ?>

</tbody><!--- tbody Ends --->

</table><!--- table table-hover table-bordered Ends --->
    
    <?php if($count_active_proposals == 0){
    
        echo "<center><h3 class='pt-3 pb-3'> No active Proposals at the momment</h3></center>";
      
     }
    
    
    
    
    ?>

</div><!--- table-responsive mt-4 Ends --->


</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row mt-3 Ends --->
    
</div>

<?php } ?>