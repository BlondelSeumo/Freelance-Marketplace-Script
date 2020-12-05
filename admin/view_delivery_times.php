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
                        <h1><i class="menu-icon fa fa-calendar"></i> Delivery Time</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                             <li class="active"><a href="index?insert_delivery_time" class="btn btn-success">

                                            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Delivery Time</span>

                                        </a></li>
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

            <div class="card">
                <!--- card Starts --->

                <div class="card-header">
                    <!--- card-header Starts --->

                    <h4 class="h4">

                        View Delivery Times

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

                                <tr>

                                    <th> Delivery Time Id </th>

                                    <th> Delivery Time Title </th>

                                    <th> Listing Delivery Time Title </th>

                                    <th> Delete Delivery Time </th>

                                    <th> Edit Delivery Time </th>

                                </tr>

                            </thead>

                            <tbody>
                                <!--- tbody Starts --->

                                <?php

                                        $i = 0;

                                        $get_delivery_times = $db->select("delivery_times","","DESC");

                                        while($row_delivery_times = $get_delivery_times->fetch()){

                                        $delivery_id = $row_delivery_times->delivery_id;

                                        $delivery_title = $row_delivery_times->delivery_title;

                                        $delivery_proposal_title = $row_delivery_times->delivery_proposal_title;

                                        $i++;

                                        ?>

                                    <tr>

                                        <td>
                                            <?= $i; ?>
                                        </td>

                                        <td>
                                            <?= $delivery_title; ?>
                                        </td>

                                        <td>
                                            <?= $delivery_proposal_title; ?>
                                        </td>

                                        <td>

                                            <a href="index?delete_delivery_time=<?= $delivery_id; ?>" onclick="return confirm('Do you really want to delete this delivery time permanently.');" class="btn btn-danger">

                                            <i class="fa fa-trash text-white"></i> <span class="text-white">Delete</span>

                                            </a>

                                        </td>


                                        <td>

                                            <a href="index?edit_delivery_time=<?= $delivery_id; ?>" class="btn btn-success">

                                            <i class="fa fa-pencil text-white"></i> <span class="text-white"> Edit </span>

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