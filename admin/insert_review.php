<?php


@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	
?>

<script src="assets/js/jquery.barrating.min.js"></script>

<link rel="stylesheet" href="assets/css/css-stars.css">


<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-star"></i> Reviews / Insert Review</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Insert Review</li>
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

            <?php 

            $form_errors = Flash::render("form_errors");

            $form_data = Flash::render("form_data");

            if(is_array($form_errors)){

            ?>

            <div class="alert alert-danger"><!--- alert alert-danger Starts --->
                
            <ul class="list-unstyled mb-0">
            <?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
            <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
            <?php } ?>
            </ul>

            </div><!--- alert alert-danger Ends --->

            <?php } ?>

            <div class="card">
                <!--- card Starts --->

                <div class="card-header">
                    <!--- card-header Starts --->

                    <h4 class="h4">Insert Review</h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <form method="post">
                        <!--- form Starts --->

                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Select Review Proposal </label>

                            <div class="col-md-6">

                                <select name="proposal_id" class="form-control" required>

                                    <option value=""> Select Review Proposal </option>

                                    <?php 

                                    $get_proposals = $db->select("proposals",array("proposal_status"=>'active'));

                                    while($row_proposals = $get_proposals->fetch()){

                                    $proposal_id = $row_proposals->proposal_id;

                                    $proposal_title = $row_proposals->proposal_title;

                                    echo "<option value='$proposal_id'>$proposal_title</option>";

                                    }

                                    ?>

                                </select>

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Select Review Seller </label>

                            <div class="col-md-6">

                                <select name="seller_id" class="form-control" required>

                                    <option value=""> Select Review Seller </option>

                                    <?php 

                                    $get_sellers = $db->query("select * from sellers where not seller_status='deactivated' and not seller_status='block-ban'");

                                    while($row_sellers = $get_sellers->fetch()){

                                    $seller_id = $row_sellers->seller_id;

                                    $seller_user_name = $row_sellers->seller_user_name;

                                    echo "<option value='$seller_id'>$seller_user_name</option>";

                                    }

                                    ?>

                                </select>

                            </div>

                        </div>
                        <!--- form-group row Ends --->



                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Select Review Rating </label>

                            <div class="col-md-6">

                                <select name="review_rating" id="rating">

                                    <option value="1"> 1 </option>

                                    <option value="2"> 2 </option>

                                    <option value="3"> 3 </option>

                                    <option value="4"> 4 </option>

                                    <option value="5"> 5 </option>

                                </select>

                                <script>
                                    
                                    $('#rating').barrating({

                                        theme: 'css-stars'

                                    });
                                </script>

                            </div>

                        </div>
                        <!--- form-group row Ends --->



                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Review Comment </label>

                            <div class="col-md-6">

                                <textarea class="form-control" name="review_comment" placeholder="Enter Review Comment ..." rows="10"></textarea>

                            </div>

                        </div>
                        <!--- form-group row Ends --->



                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-6">

                                <input type="submit" name="insert_review" value="Insert Review" class="btn btn-success form-control">

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                    </form>
                    <!--- form Ends --->

                </div>
                <!--- card-body Ends --->

            </div>
            <!--- card Ends --->

        </div>
        <!--- col-lg-12 Ends --->

    </div>
    <!--- 2 row Ends --->

<?php

if(isset($_POST['insert_review'])){
	

$rules = array(
"proposal_id" => "required",
"seller_id" => "required",
"review_rating" => "required");

$messages = array(
    "proposal_id" => "You Must Need To Select A Proposal For Review.",
    "seller_id" => "You Must Need To Select A Seller For Review.",
);

$val = new Validator($_POST,$rules,$messages);

if($val->run() == false){

Flash::add("form_errors",$val->get_all_errors());

Flash::add("form_data",$_POST);

echo "<script> window.open('index?insert_review','_self');</script>";

}else{


$proposal_id = $input->post('proposal_id');

$review_seller_id = $input->post('seller_id');

$review_rating = $input->post('review_rating');

$review_comment = $input->post('review_comment');

$date = date("M d Y");
	
	
$select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));

$row_proposals = $select_proposals->fetch();
	
$proposal_seller_id = $row_proposals->proposal_seller_id;
	

$select_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));

$row_seller = $select_seller->fetch();

$proposal_seller_rating = $row_seller->seller_rating;

	
$insert_review = $db->insert("buyer_reviews",array("proposal_id"=>$proposal_id,"review_buyer_id"=>$review_seller_id,"buyer_rating"=>$review_rating,"buyer_review"=>$review_comment,"review_seller_id"=>$proposal_seller_id,"review_date" =>$date));

$insert_id = $db->lastInsertId();


$ratings = array();
	
$sel_proposal_reviews = $db->select("buyer_reviews",array("proposal_id" => $proposal_id));
		
while($row_proposals_reviews = $sel_proposal_reviews->fetch()){
	
	$proposal_buyer_rating = $row_proposals_reviews->buyer_rating;
	
	array_push($ratings,$proposal_buyer_rating);
	
}
	
array_push($ratings,$review_rating);
	
$total = array_sum($ratings);
	
$avg = $total/count($ratings);
	
$updated_propoasl_rating = substr($avg,0,1);


if($review_rating == "5"){
	
if($proposal_seller_rating == "100"){
	
}else{
	
$update_seller_rating = $db->update("sellers",array("seller_rating" => "seller_rating+7"),array("seller_id" => $proposal_seller_id));
	
$update_seller_rating = $db->query("update sellers set seller_rating=seller_rating+7 where seller_id=:s_id",array("s_id"=>$proposal_seller_id));  

}
	
	
}elseif($review_rating == "4"){
	
if($proposal_seller_rating == "100"){
	
}else{

$update_seller_rating = $db->query("update sellers set seller_rating=seller_rating+2 where seller_id=:s_id",array("s_id"=>$proposal_seller_id));  

}
	
	
}elseif($review_rating == "3"){

$update_seller_rating = $db->query("update sellers set seller_rating=seller_rating-3 where seller_id=:s_id",array("s_id"=>$proposal_seller_id));  


}elseif($review_rating == "2"){
	
$update_seller_rating = $db->query("update sellers set seller_rating=seller_rating-5 where seller_id=:s_id",array("s_id"=>$proposal_seller_id));  

	
}elseif($review_rating == "1"){

$update_seller_rating = $db->query("update sellers set seller_rating=seller_rating-7 where seller_id=:s_id",array("s_id"=>$proposal_seller_id));  

}
	
	
$update_proposal = $db->update("proposals",array("proposal_rating" => $updated_propoasl_rating),array("proposal_id" => $proposal_id));
	
if($update_proposal){

$insert_log = $db->insert_log($admin_id,"buyer_review",$insert_id,"inserted");
    
echo "<script>alert('Your Review Has Been Inserted.');</script>";
	
echo "<script>window.open('index?view_buyer_reviews','_self');</script>";
	
}


}
	
}

?>

</div>

<?php } ?>