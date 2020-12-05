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

?>

<!DOCTYPE html>
<html lang="en" class="ui-toolkit">

<head>

    <title><?= $site_name; ?> - <?= $lang["titles"]["manage_requests"]; ?></title>
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
    <link href="../styles/sweat_alert.css" rel="stylesheet">
    <link href="../styles/animate.css" rel="stylesheet">

    <script type="text/javascript" src="../js/sweat_alert.js"></script>
    <script type="text/javascript" src="../js/jquery.min.js"></script>

    <?php if(!empty($site_favicon)){ ?>   
      <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
   <?php } ?>

</head>

<body class="is-responsive">

<?php require_once("../includes/user_header.php"); ?>

<div class="container-fluid mt-5">

    <div class="row">

        <div class="col-md-12 mb-4">

            <h1 class="pull-left"> <?= $lang["titles"]["manage_requests"]; ?> </h1>

            <a href="post_request" class="btn btn-success pull-right">
                <i class="fa fa-plus-circle"></i> Post New Request
            </a>

        </div>

        <div class="col-md-12">

            <ul class="nav nav-tabs flex-column flex-sm-row  mt-4">
                
                <?php

                $count_requests = $db->count("buyer_requests",array("seller_id" => $login_seller_id, "request_status" => 'active'));

                ?>

                <li class="nav-item">

                    <a href="#active" data-toggle="tab" class="nav-link active make-black">
                    
                        <?= $lang['tabs']['active_requests']; ?> <span class="badge badge-success"><?= $count_requests; ?></span>
                    
                    </a>
                    
                </li>
                
                <?php

                    $count_requests = $db->count("buyer_requests",array("seller_id" => $login_seller_id, "request_status" => 'pause'));

                ?>

                <li class="nav-item">

                    <a href="#pause" data-toggle="tab" class="nav-link make-black">

                        <?= $lang['tabs']['pause_requests']; ?> <span class="badge badge-success"><?= $count_requests; ?></span>
                    
                    </a>
                    
                </li>
                
                <?php

                    $count_requests = $db->count("buyer_requests",array("seller_id" => $login_seller_id, "request_status" => 'pending'));

                ?>


                <li class="nav-item">

                    <a href="#pending" data-toggle="tab" class="nav-link make-black">

                        <?= $lang['tabs']['pending_approval']; ?> <span class="badge badge-success"><?= $count_requests; ?></span>
                    
                    </a>
                    
                </li>
                
                <?php

                    $count_requests = $db->count("buyer_requests",array("seller_id" => $login_seller_id, "request_status" => 'unapproved'))

                ?>

                <li class="nav-item">

                    <a href="#unapproved" data-toggle="tab" class="nav-link make-black">

                        <?= $lang['tabs']['unapproved']; ?> <span class="badge badge-success"><?= $count_requests; ?></span>
                    
                    </a>
                
                </li>
                
            </ul>

            <div class="tab-content mt-4">

                <div id="active" class="tab-pane fade show active">

                    <div class="table-responsive box-table">

                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th><?= $lang['th']['title']; ?></th>

                                    <th><?= $lang['th']['description']; ?></th>

                                    <th><?= $lang['th']['date']; ?></th>

                                    <th><?= $lang['th']['offers']; ?></th>

                                    <th><?= $lang['th']['budget']; ?></th>

                                    <th><?= $lang['th']['actions']; ?></th>

                                </tr>

                                </thead>

                                <tbody>
                                    
                        <?php

                        $get_requests = $db->select("buyer_requests",array("seller_id" => $login_seller_id,"request_status" => "active"),"DESC");
                        $count_requests = $get_requests->rowCount();

                        while($row_requests = $get_requests->fetch()){

                        $request_id = $row_requests->request_id;
                        $request_title = $row_requests->request_title;
                        $request_description = $row_requests->request_description;
                        $request_date = $row_requests->request_date;
                        $request_budget = $row_requests->request_budget;

                        $count_offers = $db->count("send_offers",array("request_id" => $request_id, "status" => 'active'));

                        ?>

                            <tr>

                                <td> <?= $request_title; ?> </td>

                                <td><?= $request_description; ?></td>

                                <td> <?= $request_date; ?> </td>

                                <td> <?= $count_offers; ?> </td>

                                <td class="text-success"> <?= showPrice($request_budget); ?> </td>

                                <td class="text-center">

                                    <div class="dropdown">

                                    <button class="btn btn-success dropdown-toggle" data-toggle="dropdown"></button>

                                    <div class="dropdown-menu">

                                    <a href="view_offers?request_id=<?= $request_id; ?>" target="blank" class="dropdown-item">View Offers</a>

                                    <a href="pause_request?request_id=<?= $request_id; ?>" class="dropdown-item">
                                        Pause
                                    </a>

                                    <a href="delete_request?request_id=<?= $request_id; ?>" class="dropdown-item">
                                        Delete
                                    </a>

                                    </div>

                                    </div>

                                </td>

                            </tr>
                                
                        <?php } ?>

                        </tbody>

                    </table>
                        
                    <?php
                  
                    if($count_requests == 0){
                        
                        echo "<center>
                           <h3 class='pt-4 pb-4'>
                              <i class='fa fa-meh-o'></i> {$lang['manage_requests']['no_active']}
                           </h3>
                        </center>";
                    }
                    
                    ?>

                    </div>

                </div>

                <div id="pause" class="tab-pane fade">

                    <div class="table-responsive box-table">

                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th><?= $lang['th']['title']; ?></th>

                                    <th><?= $lang['th']['description']; ?></th>

                                    <th><?= $lang['th']['date']; ?></th>

                                    <th><?= $lang['th']['offers']; ?></th>

                                    <th><?= $lang['th']['budget']; ?></th>

                                    <th><?= $lang['th']['actions']; ?></th>

                                </tr>

                            </thead>

                            <tbody>
                                
                                <?php

                                $get_requests = $db->select("buyer_requests",array("seller_id" => $login_seller_id,"request_status" => "pause"),"DESC");
                            
                                $count_requests = $get_requests->rowCount();

                                while($row_requests = $get_requests->fetch()){

                                $request_id = $row_requests->request_id;

                                $request_title = $row_requests->request_title;

                                $request_description = $row_requests->request_description;

                                $request_date = $row_requests->request_date;

                                $request_budget = $row_requests->request_budget;



                                $count_offers = $db->count("send_offers",array("request_id" => $request_id, "status" => 'active'));


                                ?>


                                <tr>

                                    <td> <?= $request_title; ?> </td>

                                    <td>
                                        <?= $request_description; ?>
                                    </td>

                                    <td> <?= $request_date; ?></td>

                                    <td><?= $count_offers; ?> </td>

                                    <td class="text-success"> <?= showPrice($request_budget); ?> </td>

                                    <td class="text-center">

                                    <div class="dropdown">

                                    <button class="btn btn-success dropdown-toggle" data-toggle="dropdown" ></button>

                                        <div class="dropdown-menu">

                                            <a href="active_request?request_id=<?= $request_id; ?>" class="dropdown-item">
                                            Activate
                                            </a>

                                            <a href="delete_request?request_id=<?= $request_id; ?>" class="dropdown-item">
                                            Delete
                                            </a>

                                        </div>

                                    </div>

                                    </td>

                                </tr>
                                
                        <?php } ?>

                        </tbody>

                        </table>
                        
                        <?php
                        
                          if($count_requests == 0){
                              
                              echo "<center>
                              <h3 class='pt-4 pb-4'><i class='fa fa-smile-o'></i> {$lang['manage_requests']['no_pause']} </h3>
                              </center>";
                          }
                        
                        
                        
                        ?>

                    </div>

                    </div>

                    <div id="pending" class="tab-pane fade">

                    <div class="table-responsive box-table">

                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th><?= $lang['th']['title']; ?></th>

                                    <th><?= $lang['th']['description']; ?></th>

                                    <th><?= $lang['th']['date']; ?></th>

                                    <th><?= $lang['th']['offers']; ?></th>

                                    <th><?= $lang['th']['budget']; ?></th>

                                    <th><?= $lang['th']['actions']; ?></th>

                                </tr>

                            </thead>

                            <tbody>
                                
                        <?php

                            $get_requests = $db->select("buyer_requests",array("seller_id" => $login_seller_id,"request_status" => "pending"),"DESC");
                            $count_requests = $get_requests->rowCount();
                            while($row_requests = $get_requests->fetch()){

                            $request_id = $row_requests->request_id;
                            $request_title = $row_requests->request_title;
                            $request_description = $row_requests->request_description;
                            $request_date = $row_requests->request_date;
                            $request_budget = $row_requests->request_budget;

                        ?>

                                <tr>

                                    <td> <?= $request_title; ?> </td>

                                    <td>
                                        <?= $request_description; ?> 
                                    </td>

                                    <td> <?= $request_date; ?>  </td>

                                    <td> 0 </td>

                                    <td class="text-success"> <?= showPrice($request_budget); ?>  </td>

                                    <td>

                                    <a href="delete_request?request_id=<?= $request_id; ?>" class="btn btn-outline-danger">
                                        Delete
                                    </a>

                                    </td>

                                </tr>
                                
                        <?php } ?>

                            </tbody>

                        </table>
                        
                        <?php
                       
                            if($count_requests == 0){
                                echo "<center><h3 class='pt-4 pb-4'><i class='fa fa-smile-o'></i> {$lang['manage_requests']['no_pending']} </h3></center>";
                            }
                        
                        ?>

                    </div>

                    </div>

                    <div id="unapproved" class="tab-pane fade">

                    <div class="table-responsive box-table">

                        <table class="table table-bordered">

                            <thead>

                                <tr>

                                    <th><?= $lang['th']['title']; ?></th>

                                    <th><?= $lang['th']['description']; ?></th>

                                    <th><?= $lang['th']['date']; ?></th>

                                    <th><?= $lang['th']['offers']; ?></th>

                                    <th><?= $lang['th']['budget']; ?></th>

                                    <th><?= $lang['th']['actions']; ?></th>

                                </tr>

                            </thead>

                            <tbody>
                                
                                <?php

                                $get_requests = $db->select("buyer_requests",array("seller_id" => $login_seller_id,"request_status" => "unapproved"),"DESC");
                            
                                $count_requests = $get_requests->rowCount();

                                while($row_requests = $get_requests->fetch()){

                                $request_id = $row_requests->request_id;

                                $request_title = $row_requests->request_title;

                                $request_description = $row_requests->request_description;

                                $request_date = $row_requests->request_date;

                                $request_budget = $row_requests->request_budget;

                                ?>

                                <tr>

                                    <td> <?= $request_title; ?> </td>

                                    <td>
                                        <?= $request_description; ?>
                                    </td>

                                    <td><?= $request_date; ?> </td>

                                    <td> 0 </td>

                                    <td class="text-success"> <?= showPrice($request_budget); ?> </td>

                                    <td>

                                    <a href="delete_request?request_id=<?= $request_id; ?>" class="btn btn-outline-danger">
                                        Delete
                                    </a>

                                    </td>

                                </tr>
                                
                                <?php } ?>

                            </tbody>

                        </table>
                        
                        <?php
                        
                            if($count_requests == 0){
                                echo "<center><h3 class='pt-4 pb-4'><i class='fa fa-smile-o'></i> {$lang['manage_requests']['no_unapproved']} </h3></center>";
                            }
                        
                        ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php require_once("../includes/footer.php"); ?>

</body>

</html>