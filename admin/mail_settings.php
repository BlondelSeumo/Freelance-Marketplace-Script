<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
	
    $get_smtp_settings = $db->select("smtp_settings");
    $row_smtp_settings = $get_smtp_settings->fetch();
    $library = $row_smtp_settings->library;
    $enable_smtp = $row_smtp_settings->enable_smtp;
    $host = $row_smtp_settings->host;
    $port = $row_smtp_settings->port;
    $secure = $row_smtp_settings->secure;
    $username = $row_smtp_settings->username;
    $password = $row_smtp_settings->password;

?>

<div class="breadcrumbs">
    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">
                <h1><i class="menu-icon fa fa-cog"></i> Settings / Mail Server</h1>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">
                <ol class="breadcrumb text-right">
                    <li class="active">Mail Server Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container pt-3"><!--- container Starts --->

    <div class="row"><!--- 3 row Starts --->

        <div class="col-lg-12"><!--- col-lg-12 Starts --->

            <div class="card mb-5"><!--- card mb-5 Starts -->

                <div class="card-header"><!--- card-header Starts --->

                    <h4 class="h4"> <i class="fa fa-envelope"></i> Mail Server Settings </h4>

                </div><!--- card-header Ends --->

                <div class="card-body"><!--- card-body Starts --->

                    <form action="" method="post"><!--- form Starts --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Mail Library : </label>

                            <div class="col-md-6">
                                <select name="library" class="form-control">
                                    <option value="php_mailer"> Php Mailer </option>
                                    <option value="swift_mailer" <?= ($library == "swift_mailer")?"selected":""; ?>> Swift Mailer </option>
                                </select>
                                <small class="text-muted">If you select <b>Swift Mailer</b>, you need to enable SMTP also for it to work.</small>
                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Enable Smtp : </label>

                            <div class="col-md-6">

                                <select name="enable_smtp" class="form-control">
                                    <?php if($enable_smtp == 'yes'){ ?>
                                        <option value="yes"> Yes </option>
                                        <option value="no"> No </option>
                                    <?php }elseif($enable_smtp == 'no'){ ?>
                                        <option value="no"> No </option>
                                        <option value="yes"> Yes </option>
                                    <?php } ?>
                                </select>

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Smtp Server : </label>

                        <div class="col-md-6">

                            <input type="text" name="host" class="form-control" value="<?= $host; ?>">

                        </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Secure Transport Layer : </label>

                        <div class="col-md-6">
                            <input type="text" name="secure" class="form-control" value="<?= $secure; ?>">
                        </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Port : </label>

                        <div class="col-md-6">

                            <input type="text" name="port" class="form-control" value="<?= $port; ?>">

                        </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Username : </label>

                        <div class="col-md-6">

                        <input type="text" name="username" class="form-control" value="<?= $username; ?>">

                        </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Password : </label>

                        <div class="col-md-6">

                        <input type="password" name="password" class="form-control" value="<?= $password; ?>">

                        </div>

                        </div><!--- form-group row Ends --->

                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> </label>

                            <div class="col-md-6">

                                <input type="submit" name="update_settings" class="btn btn-success form-control" value="Update Settings">

                            </div>

                        </div><!--- form-group row Ends --->

                    </form><!--- form Ends --->

                </div><!--- card-body Ends --->

            </div><!--- card mb-5 Ends -->

        </div><!--- col-lg-12 Ends --->

    </div><!---  3 row Ends --->
<br>
</div><!--- container Ends --->

<?php

if(isset($_POST['update_settings'])){

   $data = $input->post();
   unset($data['update_settings']);
    
    $update_settings = $db->update("smtp_settings",$data);
    if($update_settings){
      $insert_log = $db->insert_log($admin_id,"smtp_settings","","updated");
      echo "<script>alert_success('Mail Server Settings Updated Successfully.','index?mail_settings')</script>";
    }
		
}

?>

<?php } ?>