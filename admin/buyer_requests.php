<?php

@session_start();

if(!isset($_SESSION['admin_email'])){

echo "<script>window.open('login','_self');</script>";

}else{

$count_all_requests = $db->count("buyer_requests");
$count_active_requests = $db->count("buyer_requests",array("request_status" => "active"));
$count_pending_requests = $db->count("buyer_requests",array("request_status" => "pending"));
$count_unapproved_requests = $db->count("buyer_requests",array("request_status" => "unapproved"));
$count_pause_requests = $db->count("buyer_requests",array("request_status" => "pause"));

if(isset($_GET['status'])){

$status = $input->get('status');

}else{

$status = "";

}

?>

<div class="breadcrumbs">

  <div class="col-sm-4">
      <div class="page-header float-left">
          <div class="page-title">
              <h1><i class="menu-icon fa fa-table"></i> Buyer Requests</h1>
          </div>
      </div>
  </div>

  <div class="col-sm-8">
      <div class="page-header float-right">
          <div class="page-title">
              <ol class="breadcrumb text-right">
                  <li class="active">Buyer Requests</li>
              </ol>
          </div>
      </div>
  </div>

</div>

<div class="container">

  <div class="row"><!--- 2 row Starts --->

      <div class="col-lg-12"><!--- col-lg-12 Starts --->

          <div class="card"><!--- card Starts --->

              <div class="card-header"><!--- card-header Starts --->

                  <h4 class="h4">View Pending Buyer Requests</h4>

              </div><!--- card-header Ends --->

              <div class="card-body"><!--- card-body Starts --->

                  <a href="index?buyer_requests" class="<?php if($status == " all "){ echo "text-muted "; } ?> mr-2">

                    All (<?= $count_all_requests; ?>)

                  </a>

                  <span class="mr-2">|</span>

                  <a href="index?buyer_requests&status=active" class="<?php if($status == " all "){ echo "text-muted "; } ?> mr-2">

                    Active (<?= $count_active_requests; ?>)

                  </a>

                  <span class="mr-2">|</span>

                  <a href="index?buyer_requests&status=pending" class="<?php if($status == " all "){ echo "text-muted "; } ?> mr-2">

                    Pending (<?= $count_pending_requests; ?>)

                  </a>

                  <span class="mr-2">|</span>

                  <a href="index?buyer_requests&status=unapproved" class="<?php if($status == " all "){ echo "text-muted "; } ?> mr-2">

                    Declined (<?= $count_unapproved_requests; ?>)

                  </a>

                  <span class="mr-2">|</span>

                  <a href="index?buyer_requests&status=pause" class="<?php if($status == " all "){ echo "text-muted "; } ?> mr-2">

                    Paused (<?= $count_pause_requests; ?>)

                  </a>

                  <div class="table-responsive mt-3"><!--- table-responsive Starts --->

                      <table class="table table-bordered"><!--- table table-bordered table-hover Starts --->

                          <thead><!--- thead Starts --->

                              <tr>

                                  <th>BUYER</th>

                                  <th>TITLE</th>

                                  <th>DESCRIPTION</th>

                                  <th>FILE</th>

                                  <th>DURATION</th>

                                  <th>BUDGET</th>

                                  <th>STATUS</th>

                                  <th>ACTION</th>

                              </tr>

                          </thead><!--- thead Ends --->

                          <tbody><!--- tbody Starts --->

                           <?php

                           $per_page = 10;

                           if(isset($_GET['buyer_requests'])){

                           $page = $input->get('buyer_requests');

                           if($page == 0){ $page = 1; }

                           }else{

                           $page = 1;

                           }

                           /// Page will start from 0 and multiply by per page

                           $start_from = ($page-1) * $per_page;

                           $search = "%$status%";

                           $get_requests = $db->query("select * from buyer_requests where request_status LIKE :request_status order by 1 DESC LIMIT :limit OFFSET :offset",array(":request_status"=>$search),array("limit"=>$per_page,"offset"=>$start_from));

                           $count_requests = $get_requests->rowCount();

                           if($count_requests == 0){

                           echo "<td colspan='8' class='text-center'>No Requests Found.</td>";

                           }

                           while($row_requests = $get_requests->fetch()){

                           $request_id = $row_requests->request_id;
                           $request_title = $row_requests->request_title;
                           $request_description = $row_requests->request_description;
                           $seller_id = $row_requests->seller_id;
                           $request_budget = $row_requests->request_budget;
                           $delivery_time = $row_requests->delivery_time;
                           $request_file = $row_requests->request_file;
                           $request_status = $row_requests->request_status;

                           $select_seller = $db->select("sellers",array("seller_id" => $seller_id));
                           $seller_user_name = $select_seller->fetch()->seller_user_name;

                           ?>

                         <tr>

                             <td>
                                 <?= $seller_user_name; ?>
                             </td>

                             <td>
                                 <?= $request_title; ?>
                             </td>

                             <td width="200">
                                 <?= $request_description; ?>
                             </td>

                             <td>

                                 <?php if(!empty($request_file)){ ?>

                                 <a href="<?= getImageUrl("buyer_requests",$request_file); ?>" download class="text-primary">

                                    <i class="fa fa-download"></i><?= $request_file; ?>

                                 </a>

                                 <?php }else{ ?> No File Attached

                                 <?php } ?>

                             </td>

                             <td><?= $delivery_time; ?></td>

                             <td>

                              <?php if(!empty($request_budget)){ ?>

                                 <?= showPrice($request_budget); ?>

                              <?php }else{ ?> No Budget Set

                              <?php } ?>

                             </td>

                             <td>
                                 <?= ucfirst($request_status); ?>
                             </td>

                             <td>

                            <?php if($request_status == "pending"){ ?>

                               <a href="index?approve_request=<?= $request_id; ?>" class="btn btn-success" onclick="return confirm('You are about to approve this request. Continue?')">
                                   <i class="fa fa-thumbs-up text-white" style="width:12px;"></i> <span class="text-white pl-1">Approve</span>
                               </a>

                               <div class="mb-2"></div>

                               <a href="index?unapprove_request=<?= $request_id; ?>" class="btn  pt-1" onclick="return confirm('You are about to delete this request perminently. Continue?')" style="background-color:red;">
                                   <i class="fa fa-thumbs-down text-white" style="width:23px;"></i> <span class="text-white">Decline</span>
                               </a>

                               <div class="mb-2"></div>

                               <?php } ?>

                                <a href="index?delete_request=<?= $request_id; ?>" class="btn btn-danger pt-1" onclick="return confirm('You are about to delete this request perminently. Continue?')">
                                    <i class="fa fa-trash text-white" style="width:23px;"></i> <span class="text-white">Delete&nbsp;</span>
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

                  <div class="d-flex justify-content-center"><!--- d-flex justify-content-center Starts --->

                      <ul class="pagination"><!--- pagination Starts --->

                        <?php

                         /// Now Select All Data From Table

                         $query = $db->query("select * from buyer_requests where request_status LIKE :request_status",array(":request_status"=>$search));

                         /// Count The Total Records 

                         $total_records = $query->rowCount();

                         /// Using ceil function to divide the total records on per page

                         $total_pages = ceil($total_records / $per_page);

                         echo "<li class='page-item'><a href='index?buyer_requests=1&status=$status' class='page-link'> First Page </a></li>";

                         echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?buyer_requests=1&status=$status'>1</a></li>";

                         $i = max(2, $page - 5);

                         if ($i > 2)

                         echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";

                         for (; $i < min($page + 6, $total_pages); $i++) {

                         echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?buyer_requests=$i&status=$status' class='page-link'>".$i."</a></li>";

                         }
                         if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}

                         if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?buyer_requests=$total_pages&status=$status'>$total_pages</a></li>";}

                         echo "<li class='page-item'><a href='index?buyer_requests=$total_pages&status=$status' class='page-link'>Last Page </a></li>";

                        ?>

                      </ul><!--- pagination Ends --->

                  </div><!--- d-flex justify-content-center Ends --->

              </div><!--- card-body Ends --->

          </div><!--- card Ends --->

      </div><!--- col-lg-12 Ends --->

  </div><!--- 2 row Ends --->

</div>

<?php } ?>