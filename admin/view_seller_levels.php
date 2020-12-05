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
                        <h1><i class="menu-icon fa fa-flash"></i> Seller levels </h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
               
            </div>
    
    </div>


<div class="container">

    <div class="row">
        <!--- 2 row Starts --->

        <div class="col-lg-12">
            <!--- col-lg-12 Starts --->

            <div class="card">
                <!--- card Starts --->

                <div class="card-header">
                    <!--- card-header Starts --->

                    <h4 class="h4">

                        <i class="fa fa-money-bill-alt"></i> View Seller Skills

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <div class="table-responsive">
                        <!--- table-responsive Starts --->

                        <table class="table table-bordered">
                            <!--- table table-bordered table-hover Starts --->

                            <thead>
                                <!--- thead Starts --->

                                <tr>

                                    <th>Seller Level Id</th>

                                    <th>Seller Level Title</th>

                                    <th>Edit Seller Level</th>

                                </tr>

                            </thead>
                            <!--- thead Ends --->

                            <tbody>
                                <!--- tbody Starts --->

                                <?php 

                                $i = 0;

                                $get_seller_levels = $db->select("seller_levels order by 1 DESC");

                                while($row_seller_levels = $get_seller_levels->fetch()){

                                $level_id = $row_seller_levels->level_id;


                                @$level_title =  $db->select("seller_levels_meta",array("level_id"=>$level_id,"language_id"=>$adminLanguage))->fetch()->title;

                                $i++;

                                ?>

                                <tr>

                                    <td><?= $i; ?></td>

                                    <td><?= $level_title; ?></td>

                                    <td>

                                        <a href="index?edit_seller_level=<?= $level_id; ?>" class="btn btn-success">

                                          <i class="fa fa-pencil text-white"></i> <span class="text-white">Edit</span>

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

                </div>
                <!--- card-body Ends --->

            </div>
            <!--- card Ends --->

        </div>
        <!--- col-lg-12 Ends --->

    </div>
    <!--- 2 row Ends --->



</div>


<?php } ?>