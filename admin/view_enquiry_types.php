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
                        <h1><i class="menu-icon fa fa-phone-square"></i> Support Settings</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                             <li class="active"><a href="index?insert_enquiry_type" class="btn btn-success">

                                            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add New Inquiry</span>

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

                        View Inquiry Types

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <div class="table-responsive">
                        <!--- table-responsive Starts --->

                        <table class="table-bordered table">
                            <!--- table-bordered table table-hover Starts --->

                            <thead>
                                <!--- thead Starts --->

                                <tr>

                                    <th> Enquiry Type Id </th>

                                    <th> Enquiry Type Title </th>

                                    <th> Delete Enquiry Type </th>

                                    <th> Edit Enquiry Type </th>

                                </tr>

                            </thead>
                            <!--- thead Ends --->

                            <tbody>
                                <!--- tbody Starts --->

                                <?php

                                    $i = 0;


                                    $get_enquiry_types = $db->select("enquiry_types");

                                    while($row_enquiry_types = $get_enquiry_types->fetch()){

                                    $enquiry_id = $row_enquiry_types->enquiry_id;

                                    $enquiry_title = $row_enquiry_types->enquiry_title;

                                    $i++;

                                    ?>

                                    <tr>

                                        <td>
                                            <?= $i; ?>
                                        </td>

                                        <td>
                                            <?= $enquiry_title; ?>
                                        </td>

                                        <td>

                                            <a href="index?delete_enquiry_type=<?= $enquiry_id; ?>" class="btn btn-danger">

                                                <i class="fa fa-trash text-white"></i> <span class="text-white">Delete</span>

                                            </a>

                                        </td>

                                        <td>

                                            <a href="index?edit_enquiry_type=<?= $enquiry_id; ?>" class="btn btn-success">

                                                <i class="fa fa-pencil text-white"></i> <span class="text-white">Edit</span>

                                            </a>

                                        </td>


                                    </tr>

                                    <?php } ?>

                            </tbody>
                            <!--- tbody Ends --->

                        </table>
                        <!--- table-bordered table table-hover Ends --->

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