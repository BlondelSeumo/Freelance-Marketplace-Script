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
                        <h1><i class="menu-icon fa fa-flash"></i>Seller Skills</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                             <li class="active"><a href="index?insert_seller_skill" class="btn btn-success">

                                            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add New Skill</span>

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

                                    <th>Seller Skill Id</th>

                                    <th>Seller Skill Title</th>

                                    <th>Delete Seller Skill</th>

                                    <th>Edit Seller Skill</th>

                                </tr>

                            </thead>
                            <!--- thead Ends --->

                            <tbody>
                                <!--- tbody Starts --->

                                <?php 

                                $i = 0;

                                $get_seller_skills = $db->select("seller_skills order by 1 DESC");

                                while($row_seller_skills = $get_seller_skills->fetch()){

                                $skill_id = $row_seller_skills->skill_id;

                                $skill_title = $row_seller_skills->skill_title;

                                $i++;

                                ?>

                                <tr>

                                    <td>
                                        <?= $i; ?>
                                    </td>

                                    <td>
                                        <?= $skill_title; ?>
                                    </td>

                                    <td>

                                        <a href="index?delete_seller_skill=<?= $skill_id; ?>" onclick="return confirm('Do you really want to delete this seller skill permanently.');" class="btn btn-danger">

                                            <i class="fa fa-trash text-white"></i> <span class="text-white">Delete</span>

                                        </a>

                                    </td>

                                    <td>

                                        <a href="index?edit_seller_skill=<?= $skill_id; ?>" class="btn btn-success">

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