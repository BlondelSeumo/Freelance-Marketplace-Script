<?php

function get_proposals($filter_type){

   global $input;
   global $siteLanguage;
   global $db;
   global $enable_referrals;
   global $lang;
   global $dir;
   global $s_currency;
   global $login_seller_id;
   global $videoPlugin;
   global $site_url;

   $online_sellers = array();

   if($filter_type == "search"){
      $search_query = $_SESSION['search_query'];
      $s_value = "%$search_query%";
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_title like :proposal_title AND proposal_status='active'",array(":proposal_title"=>$s_value));
   }elseif($filter_type == "category"){
      if(isset($_SESSION['cat_id'])){
      $session_cat_id = $_SESSION['cat_id'];
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_cat_id=:cat_id AND proposal_status='active'",array("cat_id"=>$session_cat_id));
      }elseif(isset($_SESSION['cat_child_id'])){
      $session_cat_child_id = $_SESSION['cat_child_id'];
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_child_id=:child_id AND proposal_status='active'",array("child_id"=>$session_cat_child_id));
      }
   }elseif ($filter_type == "featured") {
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_featured='yes' AND proposal_status='active'");
   }elseif ($filter_type == "top") {
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where level_id='4' and proposal_status='active'");
   }elseif ($filter_type == "random") {
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_status='active' order by rand()");
   }elseif ($filter_type == "tag") {
      if(isset($_SESSION['tag'])){
         $tag = $_SESSION['tag'];
         $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_tags LIKE :tag AND proposal_status='active'",array("tag"=>"%$tag%"));
      }
   }

   while($row_proposals = $get_proposals->fetch()){
      $proposal_seller_id = $row_proposals->proposal_seller_id;
      $select_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
      $seller_status = $select_seller->fetch()->seller_status;
      if(check_status($proposal_seller_id) == "Online"){
         array_push($online_sellers,$proposal_seller_id);
      }
   }

   $where_online = array();
   $where_country = array();
   $where_city = array();
   $where_cat = array();
   $where_delivery_times = array();
   $where_level = array();
   $where_language = array();
   $values = array();
   if(isset($_REQUEST['online_sellers'])){
      $i = 0;
      foreach($_REQUEST['online_sellers'] as $value){
         if($value != 0){
            foreach($online_sellers as $seller_id){
               $i++;
               $where_online[] = "proposal_seller_id=:proposal_seller_id_$i";
               $values["proposal_seller_id_$i"] = $seller_id;
            }
         }
      }
   }

   if(isset($_REQUEST['instant_delivery'])){
      $instant_delivery = $_REQUEST['instant_delivery'][0];
   }else{
      $instant_delivery = 0;
   }

   if(isset($_REQUEST['order'])){
      $order_by = $_REQUEST['order'][0];
   }else{
      $order_by = "DESC";
   }

   if(isset($_REQUEST['seller_country'])){
      $i = 0;
      foreach($_REQUEST['seller_country'] as $value){
         $i++;
         if($value != "undefined"){
            $where_country[] = "sellers.seller_country=:seller_country_$i";
            $values["seller_country_$i"] = $value;
         }
      }
   }

   if(isset($_REQUEST['seller_city'])){
      $i = 0;
      foreach($_REQUEST['seller_city'] as $value){
         $i++;
         if($value != "undefined"){
            $where_city[] = "sellers.seller_city=:seller_city_$i";
            $values["seller_city_$i"] = $value;
         }
      }
   }

   if(isset($_REQUEST['cat_id'])){
      $i = 0;
      foreach($_REQUEST['cat_id'] as $value){
         $i++;
         if($value != 0){
            $where_cat[] = "proposal_cat_id=:proposal_cat_id_$i";
            $values["proposal_cat_id_$i"] = $value;
         }
      }
      // print_r($where_cat);
   }
   if(isset($_REQUEST['delivery_time'])){
      $i = 0;
      foreach($_REQUEST['delivery_time'] as $value){
         $i++;
         if($value != 0){
            $where_delivery_times[] = "delivery_id=:delivery_id_$i";
            $values["delivery_id_$i"] = $value;
         }
      }
   }
   if(isset($_REQUEST['seller_level'])){
      $i = 0;
      foreach($_REQUEST['seller_level'] as $value){
         $i++;
         if($value != 0){
            $where_level[] = "level_id=:level_id_$i";
            $values["level_id_$i"] = $value;
         }
      }
   }
   if(isset($_REQUEST['seller_language'])){
      $i=0;
      foreach($_REQUEST['seller_language'] as $value){
         $i++;
         if($value != 0){
            $where_language[] = "language_id=:language_id_$i";
            $values["language_id_$i"] = $value;
         }
      }
   }


   if($filter_type == "search"){

      $values['proposal_title'] = $s_value;
      $query_where = "where proposal_title like :proposal_title AND proposal_status='active' ";

   }elseif($filter_type == "category"){

      if(isset($_SESSION['cat_id'])){
         $query_where = "where proposal_cat_id=:cat_id AND proposal_status='active' ";
      }elseif(isset($_SESSION['cat_child_id'])){
         $query_where = "where proposal_child_id=:child_id AND proposal_status='active' ";
      }

      if(isset($_SESSION['cat_id'])){
         $values['cat_id'] = $session_cat_id;
      }elseif(isset($_SESSION['cat_child_id'])){
         $values['child_id'] = $session_cat_child_id;
      }

   }elseif($filter_type == "featured"){
      $query_where = "where proposal_featured='yes' AND proposal_status='active' ";
   }elseif ($filter_type == "top") {
      
      $topProposals = array();
      $select = $db->query("select * from top_proposals");
      while($row = $select->fetch()){
        array_push($topProposals,  $row->proposal_id);
      }

      if(empty($topProposals)){
         $query_where = "where level_id='4' and proposal_status='active' ";
      }else{
         $topProposals = implode(",", $topProposals);
         $topRatedWhere = "level_id='4' and proposal_status='active'";
         $query_where = "where proposals.proposal_id in ($topProposals) or ($topRatedWhere) ";
      }

   }elseif($filter_type == "random") {
      $query_where = "where proposal_status='active' ";
   }elseif($filter_type == "tag") {
      $query_where = "where proposal_tags LIKE :tag AND proposal_status='active'";
      $values['tag'] = "%$tag%";
   }

   if(count($where_online)>0){
      $query_where .= " and (" . implode(" or ",$where_online) . ")";
   }

   if(count($where_country)>0){
      $query_where .= " and (" . implode(" or ",$where_country) . ")";
   }

   if(count($where_city)>0){
      $query_where .= " and (" . implode(" or ",$where_city) . ")";
   }

   if(count($where_cat)>0){
      $query_where .= " and (" . implode(" or ",$where_cat) . ")";
   }
   
   if(count($where_delivery_times)>0){
      $query_where .= " and (" . implode(" or ",$where_delivery_times) . ")";
   }
   
   if(count($where_level)>0){
      $query_where .= " and (" . implode(" or ",$where_level) . ")";
   }
   
   if(count($where_language)>0){
      $query_where .= " and (" . implode(" or ",$where_language) . ")";
   }

   if($instant_delivery == 1){
      $query_where .=" and instant_deliveries.enable=1";
   }

   $per_page = 16;
   if(isset($_GET['page'])){
      $page = $input->get('page');
   }else{
      $page = 1;
   }
   $start_from = ($page-1) * $per_page;
   if($filter_type == "random"){
      $where_limit = " order by rand() LIMIT :limit OFFSET :offset";
   }else{
      $where_limit = " order by 1 $order_by LIMIT :limit OFFSET :offset";
   }
 

   // echo "select DISTINCT proposals.* from proposals JOIN sellers ON proposals.proposal_seller_id=sellers.seller_id " . $query_where . $where_limit;

   $get_proposals = $db->query("select DISTINCT proposals.* from proposals JOIN sellers ON proposals.proposal_seller_id=sellers.seller_id JOIN instant_deliveries ON proposals.proposal_id=instant_deliveries.proposal_id " . $query_where . $where_limit,$values,array("limit"=>$per_page,"offset"=>$start_from));
   $count_proposals = $get_proposals->rowCount();
 
   if($count_proposals == 0){
      
      if($filter_type == "search"){

         echo"
         <div class='col-md-12'>
            <h1 class='text-center mt-4'><i class='fa fa-meh-o'></i>{$lang['search']['no_results']}</h1>
         </div>";

      }elseif ($filter_type == "category") {
         
         if(isset($_SESSION['cat_id'])){
            echo "
            <div class='col-md-12'>
            <h1 class='text-center mt-4'><i class='fa fa-meh-o'></i> {$lang['category']['no_results']} </h1>
            </div>";
         }elseif(isset($_SESSION['cat_child_id'])){
            echo "
            <div class='col-md-12'>
            <h1 class='text-center mt-4'> <i class='fa fa-meh-o'></i> {$lang['sub_category']['no_results']} </h1>
            </div>";
         }

      }elseif ($filter_type == "tag") {
         if(isset($_SESSION['tag'])){
         echo "
         <div class='col-md-12'>
            <h1 class='text-center mt-4'><i class='fa fa-meh-o'></i> {$lang['tag_proposals']['no_results']} </h1>
         </div>";
         }
      }else{
         echo "
         <div class='col-md-12'>
            <h1 class='text-center mt-4'>
               <i class='fa fa-meh-o'></i> {$lang['search']['no_results']}
            </h1>
         </div>";
      }

   }

   while($row_proposals = $get_proposals->fetch()){

   $proposal_id = $row_proposals->proposal_id;
   $proposal_title = $row_proposals->proposal_title;
   $proposal_price = $row_proposals->proposal_price;
   if($proposal_price == 0){
   $get_p_1 = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
   $proposal_price = $get_p_1->fetch()->price;
   }
   $proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
   $proposal_video = $row_proposals->proposal_video;
   $proposal_seller_id = $row_proposals->proposal_seller_id;
   $proposal_rating = $row_proposals->proposal_rating;
   $proposal_url = $row_proposals->proposal_url;
   $proposal_featured = $row_proposals->proposal_featured;
   $proposal_enable_referrals = $row_proposals->proposal_enable_referrals;
   $proposal_referral_money = $row_proposals->proposal_referral_money;
   if(empty($proposal_video)){
      $video_class = "";
   }else{
      $video_class = "video-img";
   }
   $get_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
   $row_seller = $get_seller->fetch();
   $seller_user_name = $row_seller->seller_user_name;
   $seller_image = getImageUrl2("sellers","seller_image",$row_seller->seller_image);
   $seller_level = $row_seller->seller_level;
   $seller_status = $row_seller->seller_status;
   if(empty($seller_image)){
   $seller_image = "empty-image.png";
   }
   // Select Proposal Seller Level
   @$seller_level = $db->select("seller_levels_meta",array("level_id"=>$seller_level,"language_id"=>$siteLanguage))->fetch()->title;
   $proposal_reviews = array();
   $select_buyer_reviews = $db->select("buyer_reviews",array("proposal_id" => $proposal_id));
   $count_reviews = $select_buyer_reviews->rowCount();
   while($row_buyer_reviews = $select_buyer_reviews->fetch()){
      $proposal_buyer_rating = $row_buyer_reviews->buyer_rating;
      array_push($proposal_reviews,$proposal_buyer_rating);
   }
   $total = array_sum($proposal_reviews);
   @$average_rating = $total/count($proposal_reviews);
   $count_favorites = $db->count("favorites",array("proposal_id" => $proposal_id,"seller_id" => $login_seller_id));
   if($count_favorites == 0){
   $show_favorite_class = "proposal-favorite";
   }else{
   $show_favorite_class = "proposal-unfavorite";
   }
   ?>
   <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-3">
      <?php require("$dir/includes/proposals.php"); ?>
   </div>
   <?php 
   }

}

function get_pagination($filter_type){
   global $db;
   global $input;
   global $lang;
   global $s_currency;

   $online_sellers = array();

   if($filter_type == "search"){
      $search_query = $_SESSION['search_query'];
      $s_value = "%$search_query%";
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_title like :proposal_title AND proposal_status='active'",array(":proposal_title"=>$s_value));
   }elseif($filter_type == "category"){
      if(isset($_SESSION['cat_id'])){
         $session_cat_id = $_SESSION['cat_id'];
         $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_cat_id=:cat_id AND proposal_status='active'",array("cat_id"=>$session_cat_id));
      }elseif(isset($_SESSION['cat_child_id'])){
         $session_cat_child_id = $_SESSION['cat_child_id'];
         $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_child_id=:child_id AND proposal_status='active'",array("child_id"=>$session_cat_child_id));
      }
   }elseif ($filter_type == "featured") {
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_featured='yes' AND proposal_status='active'");
   }elseif ($filter_type == "top") {
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where level_id='4' and proposal_status='active'");
   }elseif ($filter_type == "random") {
      $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_status='active' order by rand()");
   }elseif ($filter_type == "tag") {
      if(isset($_SESSION['tag'])){
         $tag = $_SESSION['tag'];
         $get_proposals = $db->query("select DISTINCT proposal_seller_id from proposals where proposal_tags LIKE :tag AND proposal_status='active'",array("tag"=>"%$tag%"));
      }
   }


   while($row_proposals = $get_proposals->fetch()){
      $proposal_seller_id = $row_proposals->proposal_seller_id;
      $select_seller = $db->select("sellers",array("seller_id" => $proposal_seller_id));
      $seller_status = $select_seller->fetch()->seller_status;
      if(check_status($proposal_seller_id) == "Online"){
         array_push($online_sellers,$proposal_seller_id);
      }
   }
   $where_online = array();
   $where_cat = array();
   $where_country = array();
   $where_city = array();
   $where_delivery_times = array();
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
               $where_online[] = "proposal_seller_id=:proposal_seller_id_$i";
               $values["proposal_seller_id_$i"] = $seller_id;
            }
            $where_path .= "online_sellers[]=" . $value . "&";
         }
      }
   }

   if(isset($_REQUEST['instant_delivery'])){
      $instant_delivery = $_REQUEST['instant_delivery'][0];
      $where_path .= "instant_delivery[]=$instant_delivery&";
   }else{
      $instant_delivery = 0;
      $where_path .= "instant_delivery[]=0&";
   }

   if(isset($_REQUEST['order'])){
      $where_path .= "order[]=" . $_REQUEST['order'][0] . "&";
   }else{
      $where_path .= "order[]=DESC&";
   }

   if(isset($_REQUEST['seller_country'])){
      $i = 0;
      foreach($_REQUEST['seller_country'] as $value){
         $i++;
         if($value != "undefined"){
            $where_country[] = "sellers.seller_country=:seller_country_$i";
            $values["seller_country_$i"] = $value;
            $where_path .= "seller_country[]=" . $value . "&";
         }
      }
   }


   if(isset($_REQUEST['seller_city'])){
      $i = 0;
      foreach($_REQUEST['seller_city'] as $value){
         $i++;
         if($value != "undefined"){
            $where_city[] = "sellers.seller_city=:seller_city_$i";
            $values["seller_city_$i"] = $value;
            $where_path .= "seller_city[]=" . $value . "&";
         }
      }
   }

   if(isset($_REQUEST['cat_id'])){
      $i = 0;
      foreach($_REQUEST['cat_id'] as $value){
         $i++;
         if($value != 0){
            $where_cat[] = "proposal_cat_id=:proposal_cat_id_$i";
            $values["proposal_cat_id_$i"] = $value;
            $where_path .= "cat_id[]=" . $value . "&";
         }
      }
   }

   if(isset($_REQUEST['delivery_time'])){
      $i = 0;
      foreach($_REQUEST['delivery_time'] as $value){
         $i++;
         if($value != 0){
            $where_delivery_times[] = "delivery_id=:delivery_id_$i";
            $values["delivery_id_$i"] = $value;
            $where_path .= "delivery_time[]=" . $value . "&";
         }
      }
   }
   if(isset($_REQUEST['seller_level'])){
      $i = 0;
      foreach($_REQUEST['seller_level'] as $value){
         $i++;
         if($value != 0){
            $where_level[] = "level_id=:level_id_$i";
            $values["level_id_$i"] = $value;
            $where_path .= "seller_level[]=" . $value . "&";
         }
      }
   }
   if(isset($_REQUEST['seller_language'])){
      $i = 0;
      foreach($_REQUEST['seller_language'] as $value){
         $i++;
         if($value != 0){
            $where_language[] = "language_id=:language_id_$i";
            $values["language_id_$i"] = $value;
            $where_path .= "seller_language[]=" . $value . "&";
         }
      }
   }
   

   if($filter_type == "search"){

      $values['proposal_title'] = $s_value;
      $query_where = "where proposal_title like :proposal_title AND proposal_status='active' ";

   }elseif($filter_type == "category"){

      if(isset($_SESSION['cat_id'])){
         $query_where = "where proposal_cat_id=:cat_id AND proposal_status='active' ";
      }elseif(isset($_SESSION['cat_child_id'])){
         $query_where = "where proposal_child_id=:child_id AND proposal_status='active' ";
      }

      if(isset($_SESSION['cat_id'])){
         $values['cat_id'] = $session_cat_id;
      }elseif(isset($_SESSION['cat_child_id'])){
         $values['child_id'] = $session_cat_child_id;
      }

   }elseif($filter_type == "featured"){
      $query_where = "where proposal_featured='yes' AND proposal_status='active' ";
   }elseif ($filter_type == "top") {
      
      $topProposals = array();
      $select = $db->query("select * from top_proposals");
      while($row = $select->fetch()){
        array_push($topProposals,  $row->proposal_id);
      }

      if(empty($topProposals)){
         $query_where = "where level_id='4' and proposal_status='active' ";
      }else{
         $topProposals = implode(",", $topProposals);
         $topRatedWhere = "level_id='4' and proposal_status='active'";
         $query_where = "where proposals.proposal_id in ($topProposals) or ($topRatedWhere) ";
      }

   }elseif ($filter_type == "random") {
      $query_where = "where proposal_status='active' ";
   }elseif ($filter_type == "tag") {
      $query_where = "where proposal_tags LIKE :tag AND proposal_status='active'";
      $values['tag'] = "%$tag%";
   }

   if(count($where_online)>0){
      $query_where .= " and (" . implode(" or ",$where_online) . ")";
   }

   if(count($where_country)>0){
      $query_where .= " and (" . implode(" or ",$where_country) . ")";
   }

   if(count($where_city)>0){
      $query_where .= " and (" . implode(" or ",$where_city) . ")";
   }

   if(count($where_cat)>0){
      $query_where .= " and (" . implode(" or ",$where_cat) . ")";
   }
   if(count($where_delivery_times)>0){
      $query_where .= " and (" . implode(" or ",$where_delivery_times) . ")";
   }
   if(count($where_level)>0){
      $query_where .= " and (" . implode(" or ",$where_level) . ")";
   }
   if(count($where_language)>0){
      $query_where .= " and (" . implode(" or ",$where_language) . ")";
   }

   if($instant_delivery == 1){
      $query_where .=" and instant_deliveries.enable=1";
   }

   $per_page = 16;
   $get_proposals = $db->query("select DISTINCT proposals.* from proposals JOIN sellers ON proposals.proposal_seller_id=sellers.seller_id JOIN instant_deliveries ON proposals.proposal_id=instant_deliveries.proposal_id " . $query_where,$values);
   $count_proposals = $get_proposals->rowCount();
   if($count_proposals > 0){
      $total_pages = ceil($count_proposals / $per_page);
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
       if ($i > 2)
           echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
       for (; $i < min($page + 6, $total_pages); $i++) {
         echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='?page=$i&$where_path' class='page-link'>".$i."</a></li>";
       }
       if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}
       if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='?page=$total_pages&$where_path'>$total_pages</a></li>";}
      echo "
      <li class='page-item'>
      <a class='page-link' href='?page=$total_pages&$where_path'>{$lang['pagination']['last_page']}</a>
      </li>";
   }
}

?>