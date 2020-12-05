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
                        <h1><i class="menu-icon fa fa-cubes"></i> Categories </h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active"><a href="index?insert_cat" class="btn btn-success">

                                            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Category</span>

                                        </a></li>
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

                    <h4 class="h4">

                        <i class="fa fa-money-bill-alt"></i> View All Categories

                    </h4>

                </div><!--- card-header Ends --->

                <div class="card-body"><!--- card-body Starts --->

                    <table class="table table-bordered"><!--- table table-bordered table-hover Starts --->

                        <thead>

                            <tr>

                                <th>Category Id</th>

                                <th>Category Title</th>

                                <th>Category Description</th>

                                <th>Category Featured</th>

                                <th>Delete Category</th>

                                <th>Edit Category</th>

                            </tr>

                        </thead>

                        <tbody><!--- tbody Starts --->

                            <?php
   
                            $i = 0;
        
                            $get_cats = $db->select("categories");

                            while($row_cats = $get_cats->fetch()){
                                
                            $cat_id = $row_cats->cat_id;

                            $cat_featured = $row_cats->cat_featured;
                            

                            $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id, "language_id" => $adminLanguage));

                            $row_meta = $get_meta->fetch();

                            $cat_title = $row_meta->cat_title;

                            $cat_desc = $row_meta->cat_desc;

                            $i++;

                            ?>

                                <tr>

                                    <td>
                                        <?= $i; ?>
                                    </td>

                                    <td>
                                        <?= $cat_title; ?>
                                    </td>

                                    <td width="400">
                                        <?= $cat_desc; ?>
                                    </td>

                                    <td>
                                        <?= $cat_featured; ?>
                                    </td>

                                    <td>

                                        <a href="index?delete_cat=<?= $cat_id; ?>" onclick="return confirm('Deleting this category will delete all its sub-categories. Do you wish to proceed?');" class="btn btn-danger">

                                            <i class="fa fa-trash text-white" ></i> <span class="text-white">Delete</span>

                                        </a>

                                    </td>

                                    <td>

                                        <a href="index?edit_cat=<?= $cat_id; ?>" class="btn btn-success">

                                            <i class="fa fa-pencil text-white"></i> <span class="text-white">Edit Cat</span>

                                        </a>

                                    </td>



                                </tr>

                                <?php } ?>

                        </tbody>
                        <!--- tbody Ends --->

                    </table>
                    <!--- table table-bordered table-hover Ends --->

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