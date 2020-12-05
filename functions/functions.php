<?php

require_once("$dir/social-config.php");
require_once("$dir/functions/filter.php");

if($notifierPlugin == 1){ 
	require_once("$dir/plugins/notifierPlugin/functions.php");
}

if(isset($_SESSION['seller_user_name'])){
	$login_seller_user_name = $_SESSION['seller_user_name'];
	$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
	$row_login_seller = $select_login_seller->fetch();
	$login_seller_id = $row_login_seller->seller_id;
}

function check_status($seller_id){
	global $db;
	global $login_seller_id;

	$select_seller = $db->select("sellers",array("seller_id" => $seller_id)); 
	$row_seller = $select_seller->fetch();
	$seller_id = $row_seller->seller_id;
	$seller_activity = $row_seller->seller_activity;
	if(isset($_SESSION['seller_user_name']) AND $seller_id == @$login_seller_id){
		return 'Online';
	}else{
	 	$current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
		$current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
		if($seller_activity > $current_timestamp){
			return 'Online';
		}else{
			return 'Offline';
		}
	}
} 

function insertSale($data){
	global $db;
	$data["date"] = date("Y-m-d");
	$sale = $db->insert("sales",$data);
	if($sale){return true;}
}

// Processing Fee
function get_percentage_amount($amount, $percentage){
	$calculate_percentage = ($percentage / 100 ) * $amount;
	return $calculate_percentage;
}

function processing_fee($amount){
	global $db;
	$get_payment_settings = $db->select("payment_settings");
	$row_payment_settings = $get_payment_settings->fetch();
	$processing_feeType = $row_payment_settings->processing_feeType;
	$processing_fee = $row_payment_settings->processing_fee;
	if($processing_feeType=="fixed") {
		return $processing_fee;
	}elseif($processing_feeType=="percentage"){
		return get_percentage_amount($amount,$processing_fee);
	}
}

/// Time Ago Function Starts ///

function time_ago($timestamp){  
  $time_ago = strtotime($timestamp);  
  $current_time = time();  
  $time_difference = $current_time - $time_ago;  
  $seconds = $time_difference;  
  $minutes      = round($seconds / 60 );           // value 60 is seconds  
  $hours           = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec  
  $days          = round($seconds / 86400);          //86400 = 24 * 60 * 60;  
  $weeks          = round($seconds / 604800);          // 7*24*60*60;  
  $months          = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60  
  $years          = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60  
  if($seconds <= 60)  {  
   return "Just Now";  
	}else if($minutes <=60)  {  
   if($minutes==1){  
     return "one minute ago";  
   } else  {  
     return "$minutes minutes ago";  
   }
 }else if($hours <=24)  {  
	if($hours==1){  
	return "an hour ago";  
	}else{  
	return "$hours hrs ago";  
	}  
 }else if($days <= 7){ 
	if($days==1)  
	{  
	return "yesterday";  
	}  
	else  
	{  
	return "$days days ago";  
	}  
 }
 //4.3 == 52/12
 else if($weeks <= 4.3){  
	if($weeks==1){  
	return "a week ago";  
	}else{  
	return "$weeks weeks ago";  
	}  
	}else if($months <=12){  
	if($months==1){  
	return "a month ago";  
	}else{  
	return "$months months ago";  
	}  
 }else{  
   if($years==1)  {  
     return "one year ago";  
   }else{  
     return "$years years ago";  
   }  
 }
}
/// Time Ago Function Ends ///

/// get_search_proposals Function Starts ///
	
	function get_search_proposals(){
		get_proposals("search");
	}

/// get_search_proposals Function Ends ///


/// get_search_pagination Function Starts ///

	function get_search_pagination(){
		get_pagination("search");
	}

/// get_search_pagination Function Ends ///


/// get_category_proposals Function Starts ///

	function get_category_proposals(){
		get_proposals("category");
	}

/// get_category_proposals Function Ends ///


/// get_category_pagination Function Starts ///

	function get_category_pagination(){
		get_pagination("category");
	}

/// get_category_pagination Function Ends ///





/// get_featured_proposals Function Starts ///

function get_featured_proposals(){

	get_proposals("featured");
	
}

/// get_featured_proposals Function Ends ///


/// get_featured_pagination Function Starts ///

function get_featured_pagination(){
	
get_pagination("featured");
	
	
}


/// get_featured_pagination Function Ends ///






/// get_top_proposals Function Starts ///

function get_top_proposals(){

get_proposals("top");

}

/// get_top_proposals Function Ends ///


/// get_top_pagination Function Starts ///

function get_top_pagination(){
	
	get_pagination("top");

}

/// get_top_pagination Function Ends ///



/// get_random_proposals Function Starts ///

function get_random_proposals(){

	get_proposals("random");
	
}

/// get_top_proposals Function Ends ///


/// get_top_pagination Function Starts ///

function get_random_pagination(){
	
	get_pagination("random");

	
}

/// get_random_pagination Function Ends ///


/// get_tag_proposals Function Starts ///
function get_tag_proposals(){

	get_proposals("tag");

}

/// get_tag_proposals Function Ends ///

/// get_tag_pagination Function Starts ///
function get_tag_pagination(){
	
	get_pagination("tag");

}
/// get_tag_pagination Function Ends ///

function addAnd($query){
	if(strlen($query) == 5){
		return "";
	}else{
		return " and";
	}
}

function freelancersQueryWhere($type){
	global $db;
	global $input;
	$online_sellers = array();
	$sellers = $db->query("select * from sellers");
	while($seller = $sellers->fetch()){
		if(check_status($seller->seller_id) == "Online"){
			array_push($online_sellers,$seller->seller_id);
		}
	}

	$where_online = array();
	$where_country = array();
	$where_level = array();
	$where_language = array();
	$values = array();
	$where_path = "";

	if(isset($_REQUEST['online_sellers'])){
		$i = 0;
		foreach($_REQUEST['online_sellers'] as $value){
			if($value != 0){
				foreach($online_sellers as $seller_id){
					$i++;
					$where_online[] = "seller_id=:seller_id_$i";
					$values["seller_id_$i"] = $seller_id;
				}
				$where_path .= "online_sellers[]=" . $value . "&";
			}
		}
	}

	if(isset($_REQUEST['seller_country'])){
		$i = 0;
		foreach($_REQUEST['seller_country'] as $value){
			$i++;
			if($value != "undefined"){
				$where_country[] = "seller_country=:seller_country_$i";
				$values["seller_country_$i"] = $value;
				$where_path .= "seller_country[]=" . $value . "&";
			}
		}
	}

	if(isset($_REQUEST['seller_level'])){
		$i = 0;
		foreach($_REQUEST['seller_level'] as $value){
			$i++;
			if($value != 0){
				$where_level[] = "seller_level=:seller_level_$i";
				$values["seller_level_$i"] = $value;
				$where_path .= "seller_level[]=" . $value . "&";
			}
		}
	}

	if(isset($_REQUEST['seller_language'])){
		$i = 0;
		foreach($_REQUEST['seller_language'] as $value){
			$i++;
			if($value != 0){
				$where_language[] = "seller_language=:seller_language_$i";
				$values["seller_language_$i"] = $value;
				$where_path .= "seller_language[]=" . $value . "&";
			}
		}
	}

	$query_where = "where";
	if(count($where_online)>0){
		$query_where .= addAnd($query_where)." (" . implode(" or ",$where_online) . ")";
	}
	if(count($where_country)>0){
		$query_where .= addAnd($query_where)." (" . implode(" or ",$where_country) . ")";
	}
	if(count($where_level)>0){
		$query_where .= addAnd($query_where)." (" . implode(" or ",$where_level) . ")";
	}
	if(count($where_language)>0){
		$query_where .= addAnd($query_where)." (" . implode(" or ",$where_language) . ")";
	}

	if($type=="query_where"){
		if($query_where!="where"){
			return $query_where;
		}
	}elseif($type=="where_path"){
		return $where_path;
	}elseif($type=="values"){
		return $values;
	}
}

/// freelancers page Functions Starts ///
function get_freelancers(){
	global $db;
	global $input;
	global $lang;
	global $siteLanguage;
	global $s_currency;

	$query_where = freelancersQueryWhere("query_where");
	$where_path = freelancersQueryWhere("where_path");
	$values = freelancersQueryWhere("values");

	$per_page = 5;
	if(isset($_GET['page'])){
		$page = $input->get('page');
		if($page == 0){ $page = 1; }
	}else{
		$page = 1;
	}

	$start_from = ($page-1) * $per_page;
	$where_limit = " order by seller_level DESC LIMIT $per_page OFFSET $start_from";

	if(!empty($where_path)){
		$query = "select DISTINCT sellers.* from sellers JOIN proposals ON sellers.seller_id=proposals.proposal_seller_id and proposals.proposal_status='active' $query_where$where_limit";
		$sellers = $db->query($query,$values);
	}else{
		$query = "select DISTINCT sellers.* from sellers JOIN proposals ON sellers.seller_id=proposals.proposal_seller_id and proposals.proposal_status='active' $where_limit";
		$sellers = $db->query($query);
	}

	$sellersCount = 0;
	while($seller = $sellers->fetch()){
		$sellersCount++;
		$seller_id = $seller->seller_id;
		$seller_user_name = $seller->seller_user_name;
		$seller_name = $seller->seller_name;
		$seller_headline = $seller->seller_headline;
		$seller_about = $seller->seller_about;
		$seller_image = getImageUrl2("sellers","seller_image",$seller->seller_image);
		$seller_email = $seller->seller_email;
		$seller_level = $seller->seller_level;
		$seller_register_date = $seller->seller_register_date;
		$seller_recent_delivery = $seller->seller_recent_delivery;
		$seller_country = $seller->seller_country;
		$seller_status = $seller->seller_status;
		$level_title = $db->select("seller_levels_meta",array("level_id"=>$seller_level,"language_id"=>$siteLanguage))->fetch()->title;

		$select_buyer_reviews = $db->select("buyer_reviews",array("review_seller_id"=>$seller_id)); 
		$count_reviews = $select_buyer_reviews->rowCount();
		if(!$count_reviews == 0){
		  $rattings = array();
		  while($row_buyer_reviews = $select_buyer_reviews->fetch()){
		    $buyer_rating = $row_buyer_reviews->buyer_rating;
		    array_push($rattings,$buyer_rating);
		  }
		  $total = array_sum($rattings);
		  @$average = $total/count($rattings);
		  $average_rating = substr($average ,0,1);
		}else{
		 $average = "0";  
		 $average_rating = "0";
		}
		require("includes/freelancer.php");
	}
	if($sellersCount == 0){
		echo"
		<div class='col-md-12'>
		<h1 class='text-center mt-4'><i class='fa fa-meh-o'></i> {$lang['freelancers']['no_results']} </h1>
		</div>
		";
	}
}

function get_freelancer_pagination(){
	global $db;
	global $input;
	global $lang;
	global $s_currency;

	$query_where = freelancersQueryWhere("query_where");
	$where_path = freelancersQueryWhere("where_path");
	$values = freelancersQueryWhere("values");

	$per_page = 5;

	if(!empty($where_path)){
		$query = "select DISTINCT sellers.* from sellers JOIN proposals ON sellers.seller_id=proposals.proposal_seller_id and proposals.proposal_status='active' $query_where";
		$sellers = $db->query($query,$values);
	}else{
		$query = "select DISTINCT sellers.* from sellers JOIN proposals ON sellers.seller_id=proposals.proposal_seller_id and proposals.proposal_status='active'";
		$sellers = $db->query($query);
	}

	// Count The Total Records
	$total_records = $sellers->rowCount();

	$total_pages = ceil($total_records / $per_page);
	if(isset($_GET['page'])){ 
		$page = $input->get('page'); if($page == 0){ $page = 1; }
	}else{
		$page = 1;
	}

	echo "
	<li class='page-item'>
	<a class='page-link' href='?page=1&$where_path'>{$lang['pagination']['first_page']}</a>
	</li>";

	echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='?page=1&$where_path'>1</a></li>";

	$i = max(2, $page - 5);

	if($i > 2){
	 echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
	}

	for(; $i < min($page + 6, $total_pages); $i++) {
		echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='?page=$i&$where_path' class='page-link'>".$i."</a></li>";
	}

	if($i != $total_pages and $total_pages > 1){
		echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
	}

	if($total_pages > 1){echo "<li class='page-item ".(
		$total_pages == $page ? "active" : "")."'><a class='page-link' href='?page=$total_pages&$where_path'>$total_pages</a></li>";
	}

	echo "	
	<li class='page-item'>
	<a class='page-link' href='?page=$total_pages&$where_path'>{$lang['pagination']['last_page']}</a>
	</li>";
	
}
/// freelancers page Functions Ends ///


?>