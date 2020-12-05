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
                        <h1><i class="menu-icon fa fa-language"></i> Seller Language</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active"> <a href="index?insert_seller_language" class="btn btn-success"> <i class="fa fa-plus-circle"></i> Add Language</a></li>
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

                        View All Languages

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <div class="table-responsive">
                        <!--- table-responsive Starts --->

                        <table class="table table-bordered">
                            <!--- table table-bordered  Starts --->

                            <thead>
                                <!--- thead Starts --->

                                <tr>

                                    <th>Language Id</th>

                                    <th> Language Title</th>

                                    <th>Delete </th>

                                    <th>Edit</th>

                                </tr>

                            </thead>
                            <!--- thead Ends --->

                            <tbody>
                                <!--- tbody Starts --->

                                <?php

                                    $i = 0;

                                    $get_seller_languages = $db->select("seller_languages order by 1 DESC");

                                    while($row_seller_languages = $get_seller_languages->fetch()){

                                    $language_id = $row_seller_languages->language_id;

                                    $language_title = $row_seller_languages->language_title;

                                    $i++;

                                    ?>

                                    <tr>

                                        <td>
                                            <?= $i; ?>
                                        </td>

                                        <td>
                                            <?= $language_title; ?>
                                        </td>

                                        <td>

                                           <a href="index?delete_seller_language=<?= $language_id; ?>" onclick="return confirm('You are about to delete this language perminently. Proceed?');" class="btn btn-danger">

                                            <i class="fa fa-trash text-white"></i> <span class="text-white">Delete</span>

                                            </a>

                                        </td>

                                        <td>

                                            <a href="index?edit_seller_language=<?= $language_id; ?>" class="btn btn-success">

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