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
                <h1><i class="menu-icon fa fa-users"></i> Admins</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">
                        <a href="index?insert_user" class="btn btn-success">

                        <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Admin</span>

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

                    <h4 class="h4">

                    <i class="fa fa-money-bill-alt"> </i> View Admins

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body"><!--- card-body Starts --->

                    <div class="table-responsive"><!--- table-responsive Starts --->

                        <table class="table table-bordered"><!--- table table-bordered table-hover Starts --->

                            <thead><!--- thead Starts --->

                                <tr>

                                    <th>Admin Name</th>

                                    <th>Admin Email</th>

                                    <th>Admin Image</th>

                                    <th>Admin Country</th>

                                    <th>Admin Job</th>

                                    <th>Delete Admin</th>

                                </tr>

                            </thead>
                            <!--- thead Ends --->

                            <tbody>
                                <!--- tbody Starts --->

                                <?php

                                    $get_admins = $db->select("admins");

                                    while($row_admins = $get_admins->fetch()){

                                    $admin_id = $row_admins->admin_id;

                                    $admin_name = $row_admins->admin_name;

                                    $admin_email = $row_admins->admin_email;

                                    $admin_image = $row_admins->admin_image;

                                    $admin_country = $row_admins->admin_country;

                                    $admin_job = $row_admins->admin_job;

                                ?>

                                    <tr>

                                        <td>
                                            <?= $admin_name; ?>
                                        </td>

                                        <td>
                                            <?= $admin_email; ?>
                                        </td>

                                        <td><img src="<?= getImageUrl("admins",$admin_image); ?>" width="60" height="60"></td>

                                        <td>
                                            <?= $admin_country; ?>
                                        </td>

                                        <td>
                                            <?= $admin_job; ?>
                                        </td>

                                        <td>

                                            <?php if($login_admin_id == $admin_id){ ?> You cannot delete yourself.

                                            <?php }else{ ?>

                                            <div class="dropdown"><!--- dropdown Starts --->

                                            <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">Actions</button>

                                            <div class="dropdown-menu"><!--- dropdown-menu Starts --->

                                            <a class="dropdown-item" href="index?user_profile=<?= $admin_id; ?>">

                                            <i class="fa fa-pencil"></i> Edit Profile

                                            </a>

                                            <a class="dropdown-item" href="index?edit_rights=<?= $admin_id; ?>">

                                            <i class="fa fa-lock"></i> Edit Rights

                                            </a>

                                            <a class="dropdown-item" href="index?delete_user=<?= $admin_id; ?>">
                                            <i class="fa fa-trash"></i> Delete
                                            </a>

                                            </div><!--- dropdown-menu Ends --->

                                            </div><!--- dropdown Ends --->

                                            <?php } ?>

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