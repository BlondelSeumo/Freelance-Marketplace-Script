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

<h1><i class="menu-icon fa fa-users"></i> Admin Logs</h1>

</div>

</div>

</div>

<div class="col-sm-8">

<div class="page-header float-right">

<div class="page-title">

<ol class="breadcrumb text-right">

</ol>

</div>

</div>

</div>

</div>

<style type="text/css">
	tbody td a{color:green !important;}
	tbody td a:hover{ color:green !important; text-decoration:underline; }
</style>

<div class="container"><!--- container Starts --->

<div class="row"><!--- 1 row Starts --->

	<div class="col-lg-12"><!--- col-lg-12 Starts --->

		<div class="p-3 mb-3"><!--- p-3 mb-3 filter-form Starts --->

			<h2 class="pb-4">Filter Admin Logs</h2>

			<form class="form-inline pb-2" method="get" action="">
				
				<input type="hidden" name="admin_logs">

				<div class="form-group">

					<label> Date : </label>
					<input type="date" name="date" value="<?= @$input->get('date'); ?>" class="form-control mb-2 mr-sm-2 ml-1 mb-sm-0">

				</div>

				<div class="form-group">

				<label> Admin : </label>

				<select name="admin_id" class="form-control mb-2 mr-sm-2 ml-1 mb-sm-0">
					<option value=""> Select Admin </option>

					<?php

					$get_admins = $db->select("admins");
					while($row_admins = $get_admins->fetch()){

						$admin_id = $row_admins->admin_id;
						$admin_name = $row_admins->admin_name;
						$selected = (isset($_GET['admin_id']) AND $admin_id == $_GET['admin_id'])?'selected':'';

						echo "<option value='$admin_id' $selected>$admin_name</option>";

					}

					?>
				</select>

				</div>

				<button type="submit" class="btn btn-success"> Filter Logs </button>

			</form>

		</div><!--- p-3 mb-3 filter-form Ends --->

	</div><!--- col-lg-12 Ends --->

</div><!--- 1 row Ends --->

<div class="row"><!--- 2 row Starts --->

<div class="col-lg-12"><!--- col-lg-12 Starts --->

<div class="card"><!--- card Starts --->

<div class="card-header"><!--- card-header Starts --->

<h4 class="h4">
	<i class="fa fa-money-bill-alt"></i> View Admin Logs
</h4>

</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

<a href="index?delete_all_logs" onclick="return confirm('Do you really want to delete all admin panel logs.')" class="btn btn-danger btn-lg float-right mb-4"><i class="fa fa-trash"></i>&nbsp;&nbsp;Delete All Logs</a>

<div class="table-responsive"><!--- table-responsive Starts --->

<table class="table table-bordered"><!--- table table-bordered table-hover Starts --->

<thead><!--- thead Starts --->

<tr>

<th>No</th>

<th>Message</th>

<th>Date</th>

<th>Delete</th>

</tr>

</thead><!--- thead Ends --->

<tbody><!--- tbody Starts --->

<?php

$per_page = 10;

if(isset($_GET['admin_logs'])){

	$page = $input->get('admin_logs');
	if($page == 0){ $page = 1; }

}else{
	$page = 1;
}

$start_from = ($page-1) * $per_page;
$i = $start_from;

// print_r($input->get());

if(isset($_GET['date']) AND !empty($_GET['date'])){
	$date = new DateTime($input->get("date"));
	$date = $date->format("F d, Y");
}else{
	$date = "";
}

if(isset($_GET['admin_id'])){
	$admin_id = $input->get("admin_id");
}else{
	$admin_id = "";
}

if((isset($_GET['date']) AND isset($_GET['admin_id'])) AND (!empty($date) AND !empty($admin_id))){
	$filter_query = "where date LIKE '%$date%' AND admin_id LIKE '%$admin_id%'";
}elseif(isset($_GET['date']) AND !empty($date)){
	$filter_query = "where date LIKE '%$date%'";
}elseif(isset($_GET['admin_id']) AND !empty($admin_id)){
	$filter_query = "where admin_id LIKE '%$admin_id%'";
}else{
	$filter_query = "";
}

// echo $filter_query;

$get_admin_logs = $db->query("select * from admin_logs $filter_query order by 1 DESC LIMIT :limit OFFSET :offset","",array("limit"=>$per_page,"offset"=>$start_from));

while($row_admin_logs = $get_admin_logs->fetch()){

$id = $row_admin_logs->id;
$admin_id = $row_admin_logs->admin_id;
$work = $row_admin_logs->work;
$work_id = $row_admin_logs->work_id;
$date = $row_admin_logs->date;
$status = $row_admin_logs->status;


$get_admin = $db->select("admins",array("admin_id" => $admin_id));
$row_admin = $get_admin->fetch();
$admin_email = $row_admin->admin_name;

if($work == "general_settings"){

$work = "<a href='index?general_settings' target='_blank'>General Settings</a>";

}else if ($work == "social_login_settings") {
	
$work = "<a href='index?general_settings' target='_blank'>Social Login Settings</a>";

}else if ($work == "video_chat_settings") {
	
$work = "<a href='index?general_settings' target='_blank'>Video Chat Settings</a>";

}else if ($work == "home_section") {
	
$work = "<a href='index?layout_settings' target='_blank'>Home Section</a>";

}else if ($work == "footer_link"){
	
$work = "<a href='index?layout_settings' target='_blank'>Footer Link</a>";

}else if ($work == "custom_css") {
	
$work = "<a href='index?layout_settings' target='_blank'>Custom Css</a>";

}else if ($work == "home_slide") {
	
$work = "<a href='index?layout_settings' target='_blank'>Home Section Slide</a>";

}else if ($work == "general_payment_settings") {
	
$work = "<a href='index?payment_settings' target='_blank'>General Payment Settings</a>";

}else if ($work == "paypal_settings"){
	
$work = "<a href='index?payment_settings' target='_blank'>Paypal Payment Settings</a>";

}else if ($work == "stripe_settings"){
	
$work = "<a href='index?payment_settings' target='_blank'>Stripe Payment Settings</a>";

}else if ($work == "bank_settings"){
	
$work = "<a href='index?payment_settings' target='_blank'>Bank Payment Settings</a>";

}else if ($work == "moneygram_settings"){
	
$work = "<a href='index?payment_settings' target='_blank'>Moneygram Payment Settings</a>";

}else if ($work == "2checkout_settings"){
	
$work = "<a href='index?payment_settings' target='_blank'>2Checkout Payment Settings</a>";

}else if ($work == "coinpayments_settings"){
	
$work = "<a href='index?payment_settings' target='_blank'>CoinPayments Settings</a>";

}else if ($work == "dusupay_settings"){
	
$work = "<a href='index?payment_settings' target='_blank'>Dusupay Payment Settings</a>";

}else if ($work == "smtp_settings") {
	
$work = "<a href='index?mail_settings' target='_blank'>Mail Server Settings</a>";

}else if ($work == "proposal"){
	
if($status != "deleted" or $status != "declined"){

$select_proposals = $db->select("proposals",array("proposal_id"=>$work_id));

$row_proposals = $select_proposals->fetch();

@$proposal_url = $row_proposals->proposal_url;

@$proposal_seller_id = $row_proposals->proposal_seller_id;


$get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));

$row_seller = $get_seller->fetch();

@$seller_user_name = $row_seller->seller_user_name;

}

$work = ($status == "deleted" or $status == "declined") ? "<a href='#'>Proposal</a>" : "<a href='../proposals/$seller_user_name/$proposal_url' target='_blank'>Proposal</a>";

}else if($work == "order_report") {
	
$work = "<a href='#'>Order Report</a>";

}else if($work == "message_report") {
	
$work = "<a href='#'>Message Report</a>";

}else if($work == "proposal_report") {
	
$work = "<a href='#'>Proposal Report</a>";

}else if ($work == "buyer_review") {
	
$work = "<a href='#'>Buyer Review</a>";

}else if($work == "cat"){

$work = ($status == "deleted") ? "<a href='#'>Category</a>" : "<a href='index?edit_cat=$work_id'>Category</a>";

}
else if ($work == "child_cat") {
	
$work = ($status == "deleted") ? "<a href='#'>Child Cat</a>" : "<a href='index?edit_child_cat=$work_id' target='_blank'>Child Cat</a>";

}else if ($work == "delivery_time") {
	
$work = ($status == "deleted") ? "<a href='#'>Delivery Time</a>" : "<a href='index?edit_delivery_time=$work_id' target='_blank'>Delivery Time</a>";

}else if ($work == "seller_language") {
	
$work = ($status == "deleted") ? "<a href='#'>Seller Language</a>" : "<a href='index?edit_seller_language=$work_id' target='_blank'>Seller Language</a>";

}else if ($work == "seller_skill") {
	
$work = ($status == "deleted") ? "<a href='#'>Seller Skill</a>" : "<a href='index?edit_seller_skill=$work_id' target='_blank'>Seller Skill</a>";

}else if ($work == "customer_support_settings") {
	
$work = "<a href='index?customer_support_settings' target='_blank'>Customer Support Settings</a>";

}else if ($work == "support_request") {
	
$work = "<a href='index?single_request=$work_id' target='_blank'>Support Request</a>";

}else if ($work == "enquiry_type") {
	
$work = ($status == "deleted") ? "<a href='#'>Enquiry Type</a>" : "<a href='index?edit_enquiry_type=$work_id' target='_blank'>Enquiry Type</a>";

}else if ($work == "coupon") {
	
$work = ($status == "deleted") ? "<a href='#'>Coupon</a>" : "<a href='index?edit_coupon=$work_id' target='_blank'>Coupon</a>";

}else if ($work == "seller") {
	
$work = "<a href='index?single_seller=$work_id' target='_blank'>User</a>";

}else if ($work == "seller_balance") {
	
$get_seller = $db->select("sellers",array("seller_id" => $work_id));
$row_seller = $get_seller->fetch();
@$seller_user_name = $row_seller->seller_user_name;

$work = "<a href='index?single_seller=$work_id' target='_blank'> $seller_user_name Balance</a>";

}else if ($work == "order") {
	
$work = "<a href='index?single_order=$work_id' target='_blank'>Order</a>";

}else if ($work == "slide") {
	
$work = ($status == "deleted") ? "<a href='#'>Slide</a>" : "<a href='index?edit_slide=$work_id' target='_blank'>Slide</a>";

}else if ($work == "referral") {
	
$work = "<a href='#'>Referral</a>";

}else if ($work == "proposal_referral") {
	
$work = "<a href='#'>Proposal Referral</a>";

}else if ($work == "proposal_file") {
	
$work = "<a href='#' >Proposal File</a>";

}else if ($work == "inbox_file") {
	
$work = "<a href='#'>Inbox File</a>";

}else if ($work == "order_file") {
	
$work = "<a href='#'>Order File</a>";

}else if ($work == "article_cat") {
	
$work = ($status == "deleted") ? "<a href='#'>Article Category</a>" : "<a href='index?edit_article_cat=$work_id' target='_blank'>Article Category</a>";

}else if ($work == "article") {
	
$work = ($status == "deleted") ? "<a href='#' target='_blank'>Article</a>" : "<a href='index?edit_article=$work_id' target='_blank'>Article</a>";

}else if ($work == "language") {
	
$work = ($status == "deleted") ? "<a href='#' target='_blank'>Language</a>" : "<a href='index?edit_language=$work_id' target='_blank'>Language</a>";

}else if ($work == "language_settings") {
	
$work = "<a href='index?language_settings=$work_id' target='_blank'>Language Settings</a>";

}else{

$work = "<a href='#' >".ucfirst($work)."</a>";

}


if($status == "inserted"){

$status = "$status The New";

}else if($status == "deleted") {
	
$status = "$status The";

}else{

$status = "$status The";

}

$message = ucfirst($status)." $work";

$i++;

?>

<tr>

<td><?= $i; ?></td>

<td><?= ucfirst($admin_email); ?> Has <?= $message; ?>. </td>

<td><?= $date; ?></td>

<td>                                        

<a href="index?delete_log=<?= $id; ?>" class="btn btn-danger text-white">

<i class="fa fa-trash text-white"></i> <span class="text-white">Delete&nbsp;</span>

</a>

</td>

</tr>

<?php 

}

?>

</tbody><!--- tbody Ends --->

</table><!--- table table-bordered table-hover Ends --->

</div><!--- table-responsive Ends --->


<div class="d-flex justify-content-center"><!--- d-flex justify-content-center Starts --->

<ul class="pagination"><!--- pagination Starts --->

<?php
	
	$date = @$input->get("date");

	/// Now Select All From Proposals Table
	$query = $db->query("select * from admin_logs $filter_query");

	/// Count The Total Records

	$total_records = $query->rowCount();

	/// Using ceil function to divide the total records on per page

	$total_pages = ceil($total_records / $per_page);

	echo "<li class='page-item'><a href='index?admin_logs=1&date=$date&admin_id=$admin_id' class='page-link'> First Page </a></li>";

    echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?admin_logs=1&date=$date&admin_id=$admin_id'>1</a></li>";
    
    $i = max(2, $page - 5);
    
    if ($i > 2)
    
        echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
    
    for (; $i < min($page + 6, $total_pages); $i++) {
            	
    	echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?admin_logs=".$i."&date=$date&admin_id=$admin_id' class='page-link'>".$i."</a></li>";

    }
    if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}

    if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?admin_logs=$total_pages&date=$date&admin_id=$admin_id'>$total_pages</a></li>";}

    echo "<li class='page-item'><a href='index?admin_logs=$total_pages&date=$date&admin_id=$admin_id' class='page-link'>Last Page </a></li>";

?>

</ul><!--- pagination Ends --->

</div><!--- d-flex justify-content-center Ends --->


</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div><!--- container Ends --->

<?php } ?>