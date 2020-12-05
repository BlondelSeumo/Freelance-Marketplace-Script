<?php


@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
    
$get_contact_support = $db->select("contact_support");
$row_contact_support = $get_contact_support->fetch();
$contact_email = $row_contact_support->contact_email;
	
$get_meta = $db->select("contact_support_meta",array('language_id' => $adminLanguage));
$row_meta = $get_meta->fetch();
$count_meta = $get_meta->rowCount();
$contact_heading = $row_meta->contact_heading;
$contact_desc = $row_meta->contact_desc;
	
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
                            <li class="active">Customer Support Settings</li>
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

                        <i class="fa fa-money-bill-alt"></i> Edit Customer Support Settings

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

                    <form action="" method="post">
                        <!--- form Starts --->

                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> From / Reply Email : </label>

                            <div class="col-md-6">

                                <input type="text" name="contact_email" class="form-control" required value="<?= $contact_email; ?>">

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Page Heading : </label>

                            <div class="col-md-6">

                                <input type="text" name="contact_heading" class="form-control" required value="<?= $contact_heading; ?>">

                            </div>

                        </div>
                        <!--- form-group row Ends --->

                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Page Short Description : </label>

                            <div class="col-md-6">

                                <textarea name="contact_desc" class="form-control" rows="6" required><?= $contact_desc; ?></textarea>

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                        <div class="form-group row">
                            <!--- form-group row Starts --->

                            <label class="col-md-3 control-label"></label>

                            <div class="col-md-6">

                                <input type="submit" name="submit" class="btn btn-success form-control" value="Update Customer Support Settings">

                            </div>

                        </div>
                        <!--- form-group row Ends --->


                    </form>
                    <!--- form Ends --->

                </div>
                <!--- card-body Edit --->

            </div>
            <!--- card Ends --->

        </div>
        <!--- col-lg-12 Ends --->

    </div>
    <!--- 2 row Ends --->


    <?php

        if(isset($_POST['submit'])){

        $contact_email = $input->post('contact_email');

        $contact_heading =  $input->post('contact_heading');

        $contact_desc =  $input->post('contact_desc');


        $update_contact_support = $db->update("contact_support",array("contact_email" => $contact_email));

        if($update_contact_support){
        
        if($count_meta == 1){

        $update_meta = $db->update("contact_support_meta",array("contact_heading" => $contact_heading,"contact_desc" => $contact_desc),array("language_id" => $adminLanguage));

        }else{

        $insert_meta = $db->insert("contact_support_meta",array("language_id" => $adminLanguage, "contact_heading" => $contact_heading,"contact_desc" => $contact_desc));

        }

        $insert_log = $db->insert_log($admin_id,"customer_support_settings","","updated");

        echo "<script>alert('Customer support settings has been updated successfully.');</script>";

        echo "<script>window.open('index?customer_support_settings','_self');</script>";


        }



        }

    ?>


</div>


<?php } ?>