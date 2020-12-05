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
                        <h1><i class="menu-icon fa fa-cubes"></i> Article Categories </h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active"><a href="index?insert_article_cat" class="btn btn-success">

                                            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Article Category</span>

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

                        <i class="fa fa-money-bill-alt"></i> View All Categories

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <table class="table table-bordered">
                        <!--- table table-bordered table-hover Starts --->

                        <thead>

                            <tr>

                                <th>Article Category Title</th>

                                <th>position</th>

                                <th width="200px">Action</th>

                            </tr>

                        </thead>


                        <tbody>
                            <!--- tbody Starts --->

                            <?php

                                $get_cats = $db->select("article_cat",array("language_id" => $adminLanguage));

                                while($row_cats = $get_cats->fetch()){

                                $article_cat_id = $row_cats->article_cat_id;

                                $article_cat_title = $row_cats->article_cat_title;

                                $position = $row_cats->position;


                                ?>

                                <tr>

                                    <td><?= $article_cat_title; ?></td>

                                    <td><?= $position; ?></td>

                                    <td>

                                        <a href="index?delete_article_cat=<?= $article_cat_id; ?>" onclick="return confirm('You are about to delete this category, do you wish to proceed ?');" class="btn btn-danger">

                                            <i class="fa fa-trash text-white" ></i> <span class="text-white">Delete</span>

                                        </a>

                                        <a href="index?edit_article_cat=<?= $article_cat_id; ?>"  class="btn btn-success">

                                            <i class="fa fa-trash text-white" ></i> <span class="text-white">Edit</span>

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
