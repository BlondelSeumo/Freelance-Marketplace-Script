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
        <h1><i class="menu-icon fa fa-users"></i> All Users </h1>
    </div>
</div>
</div>
<div class="col-sm-8">
<div class="page-header float-right">
    <div class="page-title">
        <ol class="breadcrumb text-right">
            <li class="active">All Users</li>
        </ol>
    </div>
</div>
</div>
</div>


<div class="container">

<div class="row"><!--- 2 row Starts --->

<div class="col-lg-12"><!--- col-lg-12 Starts --->

<div class="card"><!--- card Starts --->

<div class="card-body"><!--- card-body Starts --->

    <?php $count_sellers = $db->count("sellers"); ?>

    <h4 class="mb-4"> User Summary <small>All Users (<?= $count_sellers; ?>)</small> </h4>

    <div class="row"><!--- row Starts --->


        <div class="text-center border-box col-md-3"><!---- text-center border-box col-md-3 Starts --->

            <p> Top Rated Sellers </p>

            <?php $count_sellers = $db->count("sellers",array("seller_level" => 4)); ?>

            <h2><?= $count_sellers; ?></h2>

        </div><!---- text-center border-box col-md-3 Ends --->


        <div class="text-center border-box col-md-3"><!---- text-center border-box col-md-3 Starts --->

            <p> Level Two Sellers </p>

            <?php $count_sellers = $db->count("sellers",array("seller_level" => 3)); ?>

            <h2><?= $count_sellers; ?></h2>

        </div><!---- text-center border-box col-md-3 Ends --->


        <div class="text-center border-box col-md-3"><!---- text-center border-box col-md-3 Starts --->

            <p> Level One Sellers </p>

            <?php  $count_sellers = $db->count("sellers",array("seller_level" => 2)); ?>

            <h2><?= $count_sellers; ?></h2>

        </div><!---- text-center border-box col-md-3 Ends --->


        <div class="text-center col-md-3"><!---- text-center col-md-3 Starts --->

            <p> New Users </p>

            <?php $count_sellers = $db->count("sellers",array("seller_level" => 1));  ?>

            <h2><?= $count_sellers; ?></h2>

        </div><!---- text-center col-md-3 Ends --->


    </div>
    <!--- row Ends --->

</div>
<!--- card-body Ends --->

</div>
<!--- card Ends --->

</div>
<!--- col-lg-12 Ends --->

</div>
<!--- 2 row Ends --->



<div class="row mt-4">
<!--- 3 row Starts --->

<div class="col-lg-12">
<!--- col-lg-12 Starts --->

<div class="card">
<!--- card Starts --->

<div class="card-header"><!--- card-header Starts --->

    <h4 class="h4"> <i class="fa fa-money-bill-alt"></i> View All Users </h4>

</div><!--- card-header Ends --->

<div class="card-body"><!--- card-body Starts --->

                   
    <div class="row"><!--- row Starts --->

    <div class="offset-lg-4 col-md-4">

    <form action="" method="get">

    <input type="hidden" name="view_sellers" value="">

    <div class="input-group mb-3 mt-3 mt-lg-0">
      <input type="text" name="search" class="form-control" placeholder="Search Sellers" value="<?php if(isset($_GET['search'])){ echo $input->get('search'); } ?>">
      <div class="input-group-append">
        <button class="btn btn-success" type="submit"><i class="fa fa-search"></i></button>
      </div>
    </div>

    </form>

    </div>

    </div><!--- row Ends ---->


    <div class="table-responsive"><!--- table-responsive Starts --->

        <table class="table table-bordered"><!--- table table-bordered table-hover Starts --->

            <thead><!--- thead Starts --->

                <tr>

                    <th>User's No</th>

                    <th>User's Username</th>

                    <th>User's Email</th>

                    <th>Balance</th>

                    <th>Seller Level</th>

                    <th>Email Verified</th>

                    <th>Register Date</th>

                    <th>Actions</th>

                </tr>

            </thead><!--- thead Ends --->

            <tbody><!--- tbody Starts --->

            <?php


                $per_page = 8;

                if(isset($_GET['view_sellers'])){
                    
                    $page = $input->get('view_sellers');

                    if($page == 0){ $page = 1; }
                    
                }else{
                    
                    $page = 1;
                    
                }

                $i = ($page*$per_page)-8;

                if(isset($_GET['search'])){ $search = $input->get('search'); }else{ $search = ""; }

                /// Page will start from 0 and multiply by per page

                $start_from = ($page-1) * $per_page;

                $get_sellers = $db->query("select * from sellers where seller_user_name like :search order by 1 DESC LIMIT :limit OFFSET :offset",array("search"=>"%$search%"),array("limit"=>$per_page,"offset"=>$start_from));

                while($row_sellers = $get_sellers->fetch()){

                $seller_id = $row_sellers->seller_id;
                $seller_user_name = $row_sellers->seller_user_name;
                $seller_email = $row_sellers->seller_email;
                $seller_level = $row_sellers->seller_level;
                $seller_register_date = $row_sellers->seller_register_date;
                $seller_status = $row_sellers->seller_status;
                $seller_verification = $row_sellers->seller_verification;

                if($seller_verification == "ok"){
                    $email_verification = "Yes";
                }else{
                    $email_verification = "No";
                }

                $select_seller_accounts = $db->select("seller_accounts",array("seller_id" => $seller_id));
                $row_seller_accounts = $select_seller_accounts->fetch();
                $current_balance = $row_seller_accounts->current_balance;

                $level_title = $db->select("seller_levels_meta",array("level_id"=>$seller_level,"language_id"=>$adminLanguage))->fetch()->title;

                $i++;

                ?>

                    <tr>

                        <td>
                            <?= $i; ?>
                        </td>

                        <td>
                            <?= $seller_user_name; ?>
                        </td>

                        <td>
                            <?= $seller_email; ?>
                        </td>

                        <td>
                            <?= showPrice($current_balance); ?>
                        </td>

                        <td>
                            <?= $level_title; ?>
                        </td>

                        <td>
                            <?= $email_verification; ?>
                        </td>

                        <td>
                            <?= $seller_register_date; ?>
                        </td>

                        <td width="150px;">

                            <div class="dropdown"><!--- dropdown Starts --->

                            <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>

                                <div class="dropdown-menu" style="margin-left:-125px;"><!--- dropdown-menu Starts --->

                                    <a class="dropdown-item" href="index?single_seller=<?= $seller_id; ?>">

                                        <i class="fa fa-info-circle"></i> User's Details

                                    </a>
                                        
                                    <?php if($seller_verification != "ok"){ ?>
                                    <a class="dropdown-item" href="index?verify_email=<?= $seller_id; ?>">

                                        <i class="fa fa-envelope"></i> Verify Seller Email

                                    </a>
                                    <?php } ?>    

                                    <a target="_blank" class="dropdown-item" href="index?seller_login=<?= $seller_user_name; ?>">

                                        <i class="fa fa-sign-in"></i> Login As <?= $seller_user_name; ?>

                                    </a>

                                        
                                    <a class="dropdown-item" href="index?update_balance=<?= $seller_id; ?>">

                                    <i class="fa fa-money"></i> Change Seller Balance

                                    </a>


                                    <?php if($seller_status == "block-ban"){ ?>

                                    <a class="dropdown-item" href="index?unblock_seller=<?= $seller_id; ?>">

                                        <i class="fa fa-unlock"></i> Already Banned! Unblock Seller?

                                    </a>

                                    <?php }else{ ?>

                                    <a class="dropdown-item" href="index?ban_seller=<?= $seller_id; ?>">

                                        <i class="fa fa-ban"></i> Block / Ban User

                                    </a>

                                    <?php } ?>

                                </div><!--- dropdown-menu Ends --->

                            </div><!--- dropdown Ends --->

                        </td>

                    </tr>

                    <?php } ?>

            </tbody><!--- tbody Ends --->

        </table><!--- table table-bordered table-hover Ends --->

    </div><!--- table-responsive Ends --->


    <div class="d-flex justify-content-center"><!--- d-flex justify-content-center Starts --->

        <ul class="pagination"><!--- pagination Starts --->

            <?php

            /// Now Select All From Proposals Table

            $query = $db->query("select * from sellers where seller_user_name like :search ",array("search"=>"%$search%"));

            /// Count The Total Records 

            $total_records = $query->rowCount();

            /// Using ceil function to divide the total records on per page

            $total_pages = ceil($total_records / $per_page);

            echo "<li class='page-item'><a href='index?view_sellers=1&search=$search' class='page-link'> First Page </a></li>";

            echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?view_sellers=1&search=$search'>1</a></li>";
            
            $i = max(2, $page - 5);
            
            if ($i > 2)
            
                echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
            
            for (; $i < min($page + 6, $total_pages); $i++) {
                        
                echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?view_sellers=".$i."&search=$search' class='page-link'>".$i."</a></li>";

            }
            if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}

            if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?view_sellers=$total_pages&search=$search'>$total_pages</a></li>";}

            echo "<li class='page-item'><a href='index?view_sellers=$total_pages&search=$search' class='page-link'>Last Page </a></li>";

            ?>

        </ul><!--- pagination Ends --->

    </div><!--- d-flex justify-content-center Ends --->


</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 3 row Ends --->

</div>

<?php } ?>