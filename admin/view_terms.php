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
                        <h1><i class="menu-icon fa fa-table"></i> Terms & Conditions </h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                    
                        <li class="active">

                        <a href="index?insert_term" class="btn btn-success">

                            <i class="fa fa-plus-circle text-white"></i> 

                            <span class="text-white">Add New Item</span>

                        </a>

                        </li>

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

                    <h4 class="h4">View All Terms</h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <div class="row">
                        <!--- row Starts --->

                        <?php

                        $get_terms = $db->select("terms",array("language_id" => $adminLanguage));

                        while($row_terms = $get_terms->fetch()){

                        $term_id = $row_terms->term_id;
                        
                        $term_title = $row_terms->term_title;

                        $term_description = substr($row_terms->term_description,0,300);

                        ?>

                            <div class="col-lg-4 col-md-6 mb-3"><!--- col-lg-4 col-md-6 mb-3 Starts --->

                                <div class="card">
                                    <!--- card Starts --->

                                    <div class="card-header">
                                        <!--- card-header Starts --->

                                        <h4 class="text-center">

                                            <?= $term_title; ?>

                                        </h4>

                                    </div>
                                    <!--- card-header Ends --->

                                    <div class="card-body">
                                        <!--- card-body Starts --->

                                        <p>
                                            <?= strip_tags($term_description); ?>
                                        </p>

                                    </div>
                                    <!--- card-body Ends --->

                                    <div class="card-footer">
                                        <!--- card-footer Starts --->

                                        <a href="index?delete_term=<?= $term_id; ?>" class="float-left btn btn-danger" title="Delete">

                                            <i class="fa fa-trash text-white"></i>

                                        </a>


                                        <a href="index?edit_term=<?= $term_id; ?>" class="float-right btn btn-success" title="Edit">

                                            <i class="fa fa-pencil text-white"></i>

                                        </a>

                                        <div class="clearfix"></div>

                                    </div>
                                    <!--- card-footer Ends --->

                                </div>
                                <!--- card Ends --->

                            </div>
                            <!--- col-lg-4 col-md-6 mb-3 Ends --->

                            <?php } ?>

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


</div>

<?php } ?>