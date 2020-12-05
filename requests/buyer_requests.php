<?php
  session_start();
  
  require_once("../includes/db.php");
  
  if(!isset($_SESSION['seller_user_name'])){
  
  echo "<script>window.open('../login','_self')</script>";
  
  }
  
  $login_seller_user_name = $_SESSION['seller_user_name'];
  $select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
  $row_login_seller = $select_login_seller->fetch();
  $login_seller_id = $row_login_seller->seller_id;
  $login_seller_offers = $row_login_seller->seller_offers;
  
  $request_child_ids = array();
  $select_proposals = $db->query("select DISTINCT proposal_child_id from proposals where proposal_seller_id='$login_seller_id' and proposal_status='active'");
  while($row_proposals = $select_proposals->fetch()){
   $proposal_child_id = $row_proposals->proposal_child_id;
   array_push($request_child_ids, $proposal_child_id);
  }
  
  $where_child_id = array();
  foreach($request_child_ids as $child_id){
   $where_child_id[] = "child_id=" . $child_id; 
  }
  
  if(count($where_child_id) > 0){
   $requests_query = " and (" . implode(" or ", $where_child_id) . ")";
   $child_cats_query = "(" . implode(" or ", $where_child_id) . ")";
  }
  $relevant_requests = $row_general_settings->relevant_requests;
  
  ?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
  <head>
    <title><?= $site_name; ?> - Recent Buyer Requests.</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= $site_desc; ?>">
    <meta name="keywords" content="<?= $site_keywords; ?>">
    <meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
    <link href="../styles/bootstrap.css" rel="stylesheet">
    <link href="../styles/custom.css" rel="stylesheet">
    <!-- Custom css code from modified in admin panel --->
    <link href="../styles/styles.css" rel="stylesheet">
    <link href="../styles/user_nav_styles.css" rel="stylesheet">
    <link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../styles/sweat_alert.css" rel="stylesheet">
    <script type="text/javascript" src="../js/sweat_alert.js"></script>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <?php if(!empty($site_favicon)){ ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
  </head>
<body class="is-responsive">
  <?php require_once("../includes/user_header.php"); ?>
  <div class="container-fluid">
    <div class="row buyer-requests">
      <div class="col-md-12 mt-5">
        <h1 class="col-md-9 float-left">
        <?= $lang["titles"]["buyer_requests"]; ?>
        <h1>
        <div class="col-md-3 float-right">
          <div class="input-group">
            <input type="text" id="search-input"  placeholder="Search Buyer Requests" class="form-control" >
            <span class="input-group-btn">
            <button class="btn btn-success"> <i class="fa fa-search"></i> </button>
            </span>
          </div>
        </div>
      </div>
      <div class="col-md-12 mt-4">
        <h5 class="text-right mr-3">
          <i class="fa fa-list-alt"></i> <?= $login_seller_offers; ?> Offers Left Today
        </h5>
        <div class="clearfix"></div>
        <ul class="nav nav-tabs mt-3">
          <!-- nav nav-tabs Starts -->
          <li class="nav-item">
            <a href="#active-requests" data-toggle="tab" class="nav-link active make-black">
            <?= $lang['tabs']['active2']; ?> <span class="badge badge-success"> 
            <?php 
              $i_requests = 0;
              $i_send_offers = 0;
              if($relevant_requests == "no"){ $requests_query = ""; }
              if(!empty($requests_query) or $relevant_requests == "no"){
              $get_requests = $db->query("select * from buyer_requests where request_status='active'" . $requests_query . " AND NOT seller_id='$login_seller_id' order by request_id DESC");
              while($row_requets = $get_requests->fetch()){
              $request_id = $row_requets->request_id;
              $count_offers = $db->count("send_offers",array("request_id" => $request_id,"sender_id" => $login_seller_id));
              if($count_offers == 1){
              $i_send_offers++;
              }
              $i_requests++;
              }
              }
              ?>
            <?= $i_requests-$i_send_offers; ?>
            </span>
            </a>
          </li>
          <?php $count_offers = $db->count("send_offers",array("sender_id" => $login_seller_id)); ?>
          <li class="nav-item">
            <a href="#sent-offers" data-toggle="tab" class="nav-link make-black">
            <?= $lang['tabs']['offers_sent']; ?> <span class="badge badge-success"> <?= $count_offers; ?>  </span>
            </a>
          </li>
        </ul>
        <div class="tab-content mt-4">
          <div id="active-requests" class="tab-pane fade show active">
            <div class="table-responsive box-table">
              <h3 class="float-left ml-2 mt-3 mb-3"> Buyer Requests </h3>
              <select id="sub-category" class="form-control float-right sort-by mt-3 mb-3 mr-3">
                <option value="all"> All Subcategories </option>
                <?php
                  if(count($where_child_id) > 0){
                  $get_c_cats = $db->query("select * from categories_children where ".$child_cats_query);
                  while($row_c_cats = @$get_c_cats->fetch()){
                  $child_id = $row_c_cats->child_id;
                  $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $siteLanguage));
                  $row_meta = $get_meta->fetch();
                  $child_title = $row_meta->child_title;
                  echo "<option value='$child_id'> $child_title </option>";
                  }
                  }
                  ?>
              </select>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Request</th>
                    <th>Offers</th>
                    <th>Date</th>
                    <th>Duration</th>
                    <th>Budget</th>
                  </tr>
                </thead>
                <tbody id="load-data">
                  <?php 

                    if(!empty($requests_query) or $relevant_requests == "no"){
                    $select_requests = $db->query("select * from buyer_requests where request_status='active'".$requests_query." AND NOT seller_id='$login_seller_id' order by 1 DESC");
                    $count_requests = $select_requests->rowCount();
                    while($row_requests = $select_requests->fetch()){
                    $request_id = $row_requests->request_id;
                    $seller_id = $row_requests->seller_id;
                    $cat_id = $row_requests->cat_id;
                    $child_id = $row_requests->child_id;
                    $request_title = $row_requests->request_title;
                    $request_description = $row_requests->request_description;
                    $delivery_time = $row_requests->delivery_time;
                    $request_budget = $row_requests->request_budget;
                    $request_file = $row_requests->request_file;
                    $request_date = $row_requests->request_date;
                    $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                    $row_meta = $get_meta->fetch();
                    $cat_title = $row_meta->cat_title;
                    $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $siteLanguage));
                    $row_meta = $get_meta->fetch();
                    $child_title = $row_meta->child_title;

                    $select_request_seller = $db->select("sellers",array("seller_id" => $seller_id));
                    $row_request_seller = $select_request_seller->fetch();
                    $request_seller_user_name = $row_request_seller->seller_user_name;
                    $request_seller_image = getImageUrl2("sellers","seller_image",$row_request_seller->seller_image);

                    $count_send_offers = $db->count("send_offers",array("request_id" => $request_id));
                    $count_offers = $db->count("send_offers",array("request_id" => $request_id,"sender_id" => $login_seller_id));

                    if($count_offers == 0){

                    ?>
                  <tr id="request_tr_<?= $request_id; ?>">
                    <td width="1000">
                      <a href="../<?= $request_seller_user_name; ?>">
                        <?php if(!empty($request_seller_image)){ ?>
                          <img src="<?= $request_seller_image; ?>" class="request-img rounded-circle" >
                        <?php }else{ ?>
                          <img src="../user_images/empty-image.png" class="request-img rounded-circle" >
                        <?php } ?>
                      </a>
                      <div class="request-description">
                        
                        <h6> 
                          <a href="../<?= $request_seller_user_name; ?>"><?= $request_seller_user_name; ?></a> 
                        </h6>

                        <h5 class="text-success"> <?= $request_title; ?> </h5>
                        <p class="lead mb-2"> <?= $request_description; ?> </p>
                        <?php if(!empty($request_file)){ ?>
                        <a href="<?= getImageUrl("buyer_requests",$request_file); ?>" download>
                        <i class="fa fa-arrow-circle-down"></i> <?= $request_file; ?>
                        </a>
                        <?php } ?>
                        <ul class="request-category">
                          <li> <?= $cat_title; ?> </li>
                          <li> <?= $child_title; ?> </li>
                        </ul>
                      </div>
                    </td>
                    <td><?= $count_send_offers; ?></td>
                    <td> <?= $request_date; ?> </td>
                    <td> 
                      <?= $delivery_time; ?> 
                      <a href="#" class="remove-link text-danger remove_request_<?= $request_id; ?>"> Remove Request </a>
                    </td>
                    <td class="text-success font-weight-bold">
                      <?php if(!empty($request_budget)){ ?> 
                      <?= showPrice($request_budget); ?>
                      <?php }else{ ?> ----- <?php } ?>
                      <br>
                      <?php if($login_seller_offers == "0"){ ?>
                      <button class="btn btn-success btn-sm mt-4 send_button_<?= $request_id; ?>" data-toggle="modal" data-target="#quota-finish"><?= $lang['button']['send_offer']; ?></button>
                      <?php }else{ ?>
                      <button class="btn btn-success btn-sm mt-4 send_button_<?= $request_id; ?>"><?= $lang['button']['send_offer']; ?></button>
                      <?php } ?>
                    </td>
                    <script>
                      $(".remove_request_<?= $request_id; ?>").click(function(event){
                      event.preventDefault();
                      $("#request_tr_<?= $request_id; ?>").fadeOut().remove();
                      });
                      <?php if($login_seller_offers == "0"){ ?>
                      <?php }else{ ?>
                      $(".send_button_<?= $request_id; ?>").click(function(){
                      request_id = "<?= $request_id; ?>";
                      $.ajax({
                      method: "POST",
                      url: "send_offer_modal",
                      data: {request_id: request_id}
                      })
                      .done(function(data){
                      $(".append-modal").html(data);
                      });
                      });
                      <?php } ?>
                    </script>
                  </tr>
                  <?php } } } ?>
                </tbody>
              </table>
              <?php
                if(empty($count_requests)){
                echo"<center><h3 class='pb-4 pt-4'><i class='fa fa-frown-o'></i> No requests that matches any of your proposals/services yet!</h3></center>";
                }
              ?>
            </div>
          </div>
          <div id="sent-offers" class="tab-pane fade">
            <div class="table-responsive box-table">
              <h3 class="ml-2 mt-3 mb-3"> OFFERS SUBMITTED </h3>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Request</th>
                    <th>Duration</th>
                    <th>Price</th>
                    <th>Your Request</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  
                    $select_offers = $db->select("send_offers",array("sender_id"=>$login_seller_id),"DESC");
                    $count_offers = $select_offers->rowCount();
                    while($row_offers = $select_offers->fetch()){
                    $request_id = $row_offers->request_id;
                    $proposal_id = $row_offers->proposal_id;
                    $description = $row_offers->description;
                    $delivery_time = $row_offers->delivery_time;
                    $amount = $row_offers->amount;

                    $select_proposals = $db->select("proposals",array("proposal_id" => $proposal_id));
                    $row_proposals = $select_proposals->fetch();
                    $proposal_title = @$row_proposals->proposal_title;

                    $get_requests = $db->select("buyer_requests",array("request_id"=>$request_id));
                    $row_requests = $get_requests->fetch();
                    $request_id = $row_requests->request_id;
                    $seller_id = $row_requests->seller_id;
                    $cat_id = $row_requests->cat_id;
                    $child_id = $row_requests->child_id;
                    $request_title = $row_requests->request_title;
                    $request_description = $row_requests->request_description;

                    $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id, "language_id" => $siteLanguage));
                    $row_meta = $get_meta->fetch();
                    $cat_title = $row_meta->cat_title;

                    $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $siteLanguage));
                    $row_meta = $get_meta->fetch();
                    $child_title = $row_meta->child_title;

                    $select_request_seller = $db->select("sellers",array("seller_id" => $seller_id));
                    $row_request_seller = $select_request_seller->fetch();
                    $request_seller_user_name = $row_request_seller->seller_user_name;
                    $request_seller_image = getImageUrl2("sellers","seller_image",$row_request_seller->seller_image);

                    ?>
                  <tr>
                    <td width="1000">
                      <?php if(!empty($request_seller_image)){ ?>
                      <img src="<?= $request_seller_image; ?>" class="request-img rounded-circle mt-0" >
                      <?php }else{ ?>
                      <img src="../user_images/empty-image.png" class="request-img rounded-circle mt-0" >
                      <?php } ?>
                      <div class="request-description">
                        <h6> <?= $request_seller_user_name; ?> </h6>
                        <h5 class="text-success"> <?= $request_title; ?> </h5>
                        <p class="lead mb-2"> <?= $request_description; ?> </p>
                        <?php if(!empty($request_file)){ ?>
                        <a href="<?= getImageUrl("buyer_requests",$request_file); ?>" download>
                        <i class="fa fa-arrow-circle-down"></i> <?= $request_file; ?>
                        </a>
                        <?php } ?>
                        <ul class="request-category">
                          <li> <?= $cat_title; ?> </li>
                          <li> <?= $child_title; ?> </li>
                        </ul>
                      </div>
                    </td>
                    <td> <?= $delivery_time; ?> </td>
                    <td> <?= $s_currency; ?><?= $amount; ?>  </td>
                    <td>
                      <strong> <?= $proposal_title; ?></strong>
                      <p><br>
                        <?= $description; ?>
                      </p>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
              <?php
                if($count_offers == 0){
                echo"<center><h3 class='pb-4 pt-4'><i class='fa fa-meh-o'></i> You've sent no offers yet!</h3></center>";
                }
                ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="append-modal"></div>
    <div id="quota-finish" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title h5"><i class="fa fa-frown-o fa-move-up"></i> Request Quota Reached</h5>
            <button class="close" data-dismiss="modal"> &times; </button>
          </div>
          <div class="modal-body">
            <center>
              <h5>You can only send a max of 10 offers per day. Today you've maxed out. Try again tomorrow. </h5>
            </center>
          </div>
          <div class="modal-footer">
            <button class="btn btn-success" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
    $('#search-input').keyup(function(){
    var search = $(this).val();
    $('#load-data').html("");
    $.ajax({
    url:"load_search_data",
    method:"POST",
    data:{search:search},
    success:function(data){
    $('#load-data').html(data);
    }
    });
    });
    $('#sub-category').change(function(){
    var child_id = $(this).val();
    $('#load-data').html("");
    $.ajax({
    url:"load_category_data",
    method:"POST",
    data:{child_id:child_id},
    success:function(data){
    $('#load-data').html(data);
    }
    });
    });
    });
  </script>
  <?php require_once("../includes/footer.php"); ?>
</body>
</html>