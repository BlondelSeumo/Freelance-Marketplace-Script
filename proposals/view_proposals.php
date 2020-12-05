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
$login_seller_vacation = $row_login_seller->seller_vacation;
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
	<title><?= $site_name; ?> - View My Proposals.</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= $site_desc; ?>">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">
   <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
	<link href="../styles/bootstrap.css" rel="stylesheet">
   <link href="../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="../styles/styles.css" rel="stylesheet">
	<link href="../styles/user_nav_styles.css" rel="stylesheet">
	<link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
	<link href="../styles/owl.carousel.css" rel="stylesheet">
	<link href="../styles/owl.theme.default.css" rel="stylesheet">
	<script type="text/javascript" src="../js/jquery.min.js"></script>
   <link href="../styles/sweat_alert.css" rel="stylesheet">
   <link href="../styles/animate.css" rel="stylesheet">
   <script type="text/javascript" src="../js/ie.js"></script>
   <script type="text/javascript" src="../js/sweat_alert.js"></script>
	<script src="https://checkout.stripe.com/checkout.js"></script>
	<?php if(!empty($site_favicon)){ ?>
      <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
   <?php } ?>

  <!-- Include the PayPal JavaScript SDK -->
  <script src="https://www.paypal.com/sdk/js?client-id=<?= $paypal_client_id; ?>&disable-funding=credit,card&currency=<?= $paypal_currency_code; ?>"></script>

</head>
<body class="is-responsive">
<?php require_once("../includes/user_header.php"); ?>
<div class="container-fluid view-proposals"><!-- container-fluid view-proposals Starts -->
<div class="row"><!-- row Starts -->
<div class="col-md-12 mt-5 mb-3"><!-- col-md-12 mt-5 mb-3 Starts -->
        <h1 class="pull-left"><?= $lang["titles"]["view_proposals"]; ?></h1>
        <label class="pull-right lead"><!-- pull-right lead Starts -->
            <?= $lang['view_proposals']['vacation_mode']; ?>
            <?php if($login_seller_vacation == "off"){ ?>
            <button id="turn_on_seller_vaction" data-toggle="button" class="btn btn-lg btn-toggle">
                <div class="toggle-handle"></div>
            </button>
            <?php }else{ ?>
            <button id="turn_off_seller_vaction" data-toggle="button" class="btn btn-lg btn-toggle active">
            <div class="toggle-handle"></div>
            </button>
            <?php } ?>
        </label><!-- pull-right lead Ends -->
        <script>
            $(document).ready(function(){
            $(document).on('click','#turn_on_seller_vaction', function(){
            seller_id = "<?= $login_seller_id; ?>";
            $.ajax({
            method:"POST",
            url: "seller_vacation_modal",
            data: { seller_id: seller_id, turn_on: 'on' }
            }).done(function(data){
            $('.append-modal').html(data);
            });
            });
            $(document).on('click','#turn_off_seller_vaction', function(){
            seller_id = "<?= $login_seller_id; ?>";
            $.ajax({
            method:"POST",
            url: "seller_vacation",
            data: { seller_id: seller_id, turn_off: 'off' }
            }).done(function(){
            $("#turn_off_seller_vaction").attr('id','turn_on_seller_vaction');
              swal({
              type: 'success',
              text: 'Vacation switched OFF.',
              padding: 40,
              });
            });
            });
            });
        </script>
</div>
<div class="append-modal"></div>
		<div class="col-md-12">
			<a href="create_proposal" class="btn btn-success pull-right">
            <i class="fa fa-plus-circle"></i> <?= $lang['button']['add_new_proposal']; ?>
			</a>
			<div class="clearfix"></div>
			<ul class="nav nav-tabs flex-column flex-sm-row mt-4">
            <?php
               $count_proposals = $db->count("proposals",array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'active'));
            ?>
				<li class="nav-item">
					<a href="#active-proposals" data-toggle="tab" class="nav-link active make-black">
			         <?= $lang['tabs']['active_proposals']; ?> <span class="badge badge-success"><?= $count_proposals; ?></span>
					</a>
				</li>
            <?php
               $count_proposals = $db->query("select * from proposals where proposal_seller_id=$login_seller_id and (proposal_status='pause' or proposal_status='admin_pause')")->rowCount();
            ?>
				<li class="nav-item">
					<a href="#pause-proposals" data-toggle="tab" class="nav-link make-black">
						<?= $lang['tabs']['pause_proposals']; ?> <span class="badge badge-success"><?= $count_proposals; ?></span>
					</a>
				</li>
            <?php
				$count_proposals = $db->count("proposals",array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'pending'));
            ?>
				<li class="nav-item">
					<a href="#pending-proposals" data-toggle="tab" class="nav-link make-black">
					<?= $lang['tabs']['pending_proposals']; ?> <span class="badge badge-success"><?= $count_proposals; ?></span>
					</a>
				</li>
            <?php
				$count_proposals = $db->count("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>'modification'));
            ?>
				<li class="nav-item">
					<a href="#modification-proposals" data-toggle="tab" class="nav-link make-black">
					<?= $lang['tabs']['requires_modification']; ?> <span class="badge badge-success"><?= $count_proposals; ?></span>
					</a>
				</li>
            <?php
               $count_proposals = $db->count("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>'draft'));
            ?>
				<li class="nav-item">
					<a href="#draft-proposals" data-toggle="tab" class="nav-link make-black">
					<?= $lang['tabs']['draft']; ?> <span class="badge badge-success"><?= $count_proposals; ?></span>
					</a>
				</li>
            <?php
				  $count_proposals = $db->count("proposals",array("proposal_seller_id" => $login_seller_id, "proposal_status" => 'declined'));
            ?>
				<li class="nav-item">
					<a href="#declined-proposals" data-toggle="tab" class="nav-link make-black">
					<?= $lang['tabs']['declined']; ?> <span class="badge badge-success"><?= $count_proposals; ?></span>
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<div id="active-proposals" class="tab-pane fade show active">
                   <div class="table-responsive box-table mt-4">
						<table class="table table-bordered">
							<thead>
								<tr>
								<th><?= $lang['th']['proposal_title']; ?></th>
								<th><?= $lang['th']['proposal_price']; ?></th>
								<th><?= $lang['th']['views']; ?></th>
								<th><?= $lang['th']['orders']; ?></th>
								<th><?= $lang['th']['actions']; ?></th>
								</tr>
							</thead>
							<tbody>
                       <?php
                        $select_proposals = $db->select("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>'active'));
                        $count_proposals = $select_proposals->rowCount();
                        while($row_proposals = $select_proposals->fetch()){
                        $proposal_id = $row_proposals->proposal_id;
                        $proposal_title = $row_proposals->proposal_title;
                        $proposal_views = $row_proposals->proposal_views;
                        $proposal_price = $row_proposals->proposal_price;
								if($proposal_price == 0){
								$get_p = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
								$proposal_price = $get_p->fetch()->price;
								}
                        $proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
                        $proposal_url = $row_proposals->proposal_url;
                        $proposal_featured = $row_proposals->proposal_featured;
								$count_orders = $db->count("orders",array("proposal_id"=>$proposal_id));
                       ?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td class="text-success"> <?= showPrice($proposal_price); ?> </td>
									<td><?= $proposal_views; ?></td>
									<td><?= $count_orders; ?></td>
									<td class="text-center">
										<div class="dropdown">
										<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
										<div class="dropdown-menu">
										<a href="<?= $login_seller_user_name; ?>/<?= $proposal_url; ?>" class="dropdown-item"> Preview </a>
                              <?php if($proposal_featured == "no"){ ?>
										<a href="#" class="dropdown-item" id="featured-button-<?= $proposal_id; ?>">Make Proposal Featured</a>
                              <?php }else{ ?>
                              <a href="#" class="dropdown-item text-success">Already Featured </a>
                              <?php } ?>
										<a href="pause_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Deactivate Proposal</a>
                              <a href="view_coupons?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> View Coupons</a>
                              <a href="view_referrals?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> View Referrals</a>
										<a href="edit_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Edit </a>
										<a href="delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Delete </a>
										</div>
										</div>
										<script>
										$("#featured-button-<?= $proposal_id; ?>").click(function(){
										proposal_id = "<?= $proposal_id; ?>";
										$.ajax({
										  method: "POST",
										  url: "pay_featured_listing",
										  data: {proposal_id: proposal_id }
										}).done(function(data){
										$("#featured-proposal-modal").html(data);	
										});	
										});
										</script>
									</td>
								</tr>
                                <?php } ?>
							</tbody>
						</table>
                       <?php
                            if($count_proposals == 0){
                                echo "<center><h3 class='pt-4 pb-4'><i class='fa fa-meh-o'></i> You currently have no proposals/services to sell.</h3></center>";
                            }
                       ?>
					</div>
				</div>
					<div id="pause-proposals" class="tab-pane fade show">
                   <div class="table-responsive box-table mt-4">
						<table class="table table-bordered">
							<thead>
								<tr>
								<th><?= $lang['th']['proposal_title']; ?></th>
								<th><?= $lang['th']['proposal_price']; ?></th>
								<th><?= $lang['th']['views']; ?></th>
								<th><?= $lang['th']['orders']; ?></th>
								<th><?= $lang['th']['actions']; ?></th>
								</tr>
							</thead>
							<tbody>
                       <?php
                       $select_proposals = $db->query("select * from proposals where proposal_seller_id=$login_seller_id and (proposal_status='pause' or proposal_status='admin_pause')");
                       $count_proposals = $select_proposals->rowCount();
                       while($row_proposals = $select_proposals->fetch()){
                       $proposal_id = $row_proposals->proposal_id;
                       $proposal_title = $row_proposals->proposal_title;
                       $proposal_views = $row_proposals->proposal_views;
                       $proposal_price = $row_proposals->proposal_price;
         					if($proposal_price == 0){
         					$get_p = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
         					$proposal_price = $get_p->fetch()->price;
         					}
                       $proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
                       $proposal_url = $row_proposals->proposal_url;
                       $proposal_featured = $row_proposals->proposal_featured;
                       $proposal_status = $row_proposals->proposal_status;
					        
                       $count_orders = $db->count("orders",array("proposal_id"=>$proposal_id));
                        
                        if($proposal_status == "admin_pause"){
                           $onclick = <<<EOT
                           onclick="return confirm('{$lang['view_proposals']['admin_pause_proposal']}')"
EOT;
                        }else{
                           $onclick = "";
                        }

                       ?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td class="text-success"> <?= showPrice($proposal_price); ?> </td>
									<td><?= $proposal_views; ?></td>
									<td><?= $count_orders; ?></td>
									<td class="text-center">
										<div class="dropdown">
										<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
										<div class="dropdown-menu">
										<a href="<?= $login_seller_user_name; ?>/<?= $proposal_url; ?>" class="dropdown-item"> Preview </a>
										<a 
                              href="activate_proposal?proposal_id=<?= $proposal_id; ?>" 
                              class="dropdown-item"
                              <?= $onclick; ?>
                              > 
                              Activate
                              </a>
										<a href="view_referrals?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> View Referrals</a>
										<a href="edit_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Edit </a>
										<a href="delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Delete </a>
										</div>
										</div>
									</td>
								</tr>
                              <?php } ?>
							</tbody>
						</table>
                       <?php
                            if($count_proposals == 0){
                                echo "<center><h3 class='pt-4 pb-4'><i class='fa fa-smile-o'></i> You currently have no paused proposals/services.</h3></center>";
                            }
                       ?>
					</div>
				</div>
				<div id="pending-proposals" class="tab-pane fade show">
                   <div class="table-responsive box-table mt-4">
						<table class="table table-bordered">
							<thead>
								<tr>
								<th><?= $lang['th']['proposal_title']; ?></th>
								<th><?= $lang['th']['proposal_price']; ?></th>
								<th><?= $lang['th']['views']; ?></th>
								<th><?= $lang['th']['orders']; ?></th>
								<th><?= $lang['th']['actions']; ?></th>
								</tr>
							</thead>
							<tbody>
                                <?php
									$select_proposals = $db->select("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>'pending'));
									$count_proposals = $select_proposals->rowCount();
                                    while($row_proposals = $select_proposals->fetch()){
                                    $proposal_id = $row_proposals->proposal_id;
                                    $proposal_title = $row_proposals->proposal_title;
                                    $proposal_views = $row_proposals->proposal_views;
                                    $proposal_price = $row_proposals->proposal_price;
									if($proposal_price == 0){
									$get_p = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
									$proposal_price = $get_p->fetch()->price;
									}
                                    $proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
                                    $proposal_url = $row_proposals->proposal_url;
                                    $proposal_featured = $row_proposals->proposal_featured;
									$count_orders = $db->count("orders",array("proposal_id"=>$proposal_id));
                                ?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td class="text-success"> <?= showPrice($proposal_price); ?> </td>
									<td><?= $proposal_views; ?></td>
									<td><?= $count_orders; ?></td>
									<td class="text-center">
										<div class="dropdown">
										<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
										<div class="dropdown-menu">
										<a href="<?= $login_seller_user_name; ?>/<?= $proposal_url; ?>" class="dropdown-item"> Preview </a>
										<a href="edit_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Edit </a>
										<a href="delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Delete </a>
										</div>
										</div>
									</td>
								</tr>
                              <?php } ?>
							</tbody>
						</table>
                        <?php
                            if($count_proposals == 0){
                                echo "<center><h3 class='pt-4 pb-4'><i class='fa fa-smile-o'></i> You currently have no proposals/services pending.</h3></center>";
                            }
                       ?>
					</div>
				</div>
         	<div id="modification-proposals" class="tab-pane fade show">
                   <div class="table-responsive box-table mt-4">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th><?= $lang['th']['modification_proposal_title']; ?></th>
									<th><?= $lang['th']['modification_message']; ?></th>
									<th><?= $lang['th']['actions']; ?></th>
								</tr>
							</thead>
							<tbody>
                        <?php
									$select_proposals = $db->select("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>'modification'));
                                    $count_proposals = $select_proposals->rowCount();
                                    while($row_proposals = $select_proposals->fetch()){
                                    $proposal_id = $row_proposals->proposal_id;
                                    $proposal_title = $row_proposals->proposal_title;
                                    $proposal_url = $row_proposals->proposal_url;
									$select_modification = $db->select("proposal_modifications",array("proposal_id"=>$proposal_id));
                                    $row_modification = $select_modification->fetch();
                                    $modification_message = $row_modification->modification_message;
                                ?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td> <?= $modification_message; ?></td>
									<td class="text-center">
										<div class="dropdown">
										<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
										<div class="dropdown-menu">
										<a href="submit_approval?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Submit For Approval </a>
										<a href="<?= $login_seller_user_name; ?>/<?= $proposal_url; ?>" class="dropdown-item"> Preview </a>
										<a href="edit_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Edit </a>
										<a href="delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Delete </a>
										</div>
										</div>
									</td>
								</tr>
                                <?php } ?>
							</tbody>
						</table>
                       <?php
                            if($count_proposals == 0){
                                echo "<center><h3 class='pt-4 pb-4'><i class='fa fa-smile-o'></i> You currently have no modifications requested.</h3></center>";
                            }
                       ?>
					</div>
				</div>
				<div id="draft-proposals" class="tab-pane fade show">
                   <div class="table-responsive box-table mt-4">
						<table class="table table-bordered">
							<thead>
								<tr>
								<th><?= $lang['th']['proposal_title']; ?></th>
								<th><?= $lang['th']['proposal_price']; ?></th>
								<th><?= $lang['th']['views']; ?></th>
								<th><?= $lang['th']['orders']; ?></th>
								<th><?= $lang['th']['actions']; ?></th>
								</tr>
							</thead>
							<tbody>
                                <?php
									$select_proposals = $db->select("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>'draft'));
									$count_proposals = $select_proposals->rowCount();
                                    while($row_proposals = $select_proposals->fetch()){
                                    $proposal_id = $row_proposals->proposal_id;
                                    $proposal_title = $row_proposals->proposal_title;
                                    $proposal_views = $row_proposals->proposal_views;
                                    $proposal_price = $row_proposals->proposal_price;
									if($proposal_price == 0){
									$get_p = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
									$proposal_price = $get_p->fetch()->price;
									}
                                    $proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
                                    $proposal_url = $row_proposals->proposal_url;
                                    $proposal_featured = $row_proposals->proposal_featured;
									$count_orders = $db->count("orders",array("proposal_id"=>$proposal_id));
                                ?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td class="text-success"> <?= showPrice($proposal_price); ?> </td>
									<td><?= $proposal_views; ?></td>
									<td><?= $count_orders; ?></td>
									<td class="text-center">
										<div class="dropdown">
										<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
										<div class="dropdown-menu">
										<a href="edit_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Edit </a>
										<a href="delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Delete </a>
										</div>
										</div>
									</td>
								</tr>
                              <?php } ?>
							</tbody>
						</table>
                       <?php
                        if($count_proposals == 0){
                          echo "<center><h3 class='pt-4 pb-4'><i class='fa fa-smile-o'></i> You currently have no proposals/services in draft.</h3></center>";
                        }
                       ?>
					</div>
				</div>
				<div id="declined-proposals" class="tab-pane fade show">
                   <div class="table-responsive box-table mt-4">
						<table class="table table-bordered">
							<thead>
								<tr>
								<th><?= $lang['th']['proposal_title']; ?></th>
								<th><?= $lang['th']['proposal_price']; ?></th>
								<th><?= $lang['th']['views']; ?></th>
								<th><?= $lang['th']['orders']; ?></th>
								<th><?= $lang['th']['actions']; ?></th>
								</tr>
							</thead>
							<tbody>
                        <?php
									$select_proposals = $db->select("proposals",array("proposal_seller_id"=>$login_seller_id,"proposal_status"=>'declined'));
									$count_proposals = $select_proposals->rowCount();
									while($row_proposals = $select_proposals->fetch()){
									$proposal_id = $row_proposals->proposal_id;
									$proposal_title = $row_proposals->proposal_title;
									$proposal_views = $row_proposals->proposal_views;
									$proposal_price = $row_proposals->proposal_price;
									if($proposal_price == 0){
									$get_p = $db->select("proposal_packages",array("proposal_id" => $proposal_id,"package_name" => "Basic"));
									$proposal_price = $get_p->fetch()->price;
									}
									$proposal_img1 = getImageUrl2("proposals","proposal_img1",$row_proposals->proposal_img1);
									$proposal_url = $row_proposals->proposal_url;
									$proposal_featured = $row_proposals->proposal_featured;
									$count_orders = $db->count("orders",array("proposal_id"=>$proposal_id));
								?>
								<tr>
									<td class="proposal-title"> <?= $proposal_title; ?> </td>
									<td class="text-success"> <?= showPrice($proposal_price); ?> </td>
									<td><?= $proposal_views; ?></td>
									<td><?= $count_orders; ?></td>
									<td class="text-center">
										<div class="dropdown">
										<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>
										<div class="dropdown-menu">
										<a href="delete_proposal?proposal_id=<?= $proposal_id; ?>" class="dropdown-item"> Delete </a>
										</div>
										</div>
									</td>
								</tr>
                              <?php } ?>
							</tbody>
						</table>
                        <?php
                            if($count_proposals == 0){
                                echo "<center><h3 class='pt-4 pb-4'><i class='fa fa-smile-o'></i> You currently have no proposals/services declined.</h3></center>";
                            }
                       ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="featured-proposal-modal"></div>
<?php require_once("../includes/footer.php"); ?>
</body>
</html>