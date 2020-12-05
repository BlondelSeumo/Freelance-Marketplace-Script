<?php


@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
    
?>

<script src="../js/jquery.min.js"></script>

    <div class="breadcrumbs">

        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1><i class="menu-icon fa fa-user-plus"></i> Admin</h1>
                </div>
            </div>

        </div>
        <div class="col-sm-8">

            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Add New Admin</li>
                    </ol>
                </div>
            </div>

        </div>

    </div>


<div class="container">

    <div class="row mt-2"><!--- 2 row Starts --->

        <div class="col-lg-12"><!--- col-lg-12 Starts --->

        <?php 

        $form_errors = Flash::render("form_errors");

        $form_data = Flash::render("form_data");

        if(is_array($form_errors)){

        ?>

        <div class="alert alert-danger"><!--- alert alert-danger Starts --->
            
        <ul class="list-unstyled mb-0">
        <?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
        <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
        <?php } ?>
        </ul>

        </div><!--- alert alert-danger Ends --->

        <?php } ?>

            <div class="card"><!--- card Starts --->

                <div class="card-header"><!--- card-header Starts --->

                    <h4 class="h4">

                         Insert Admin

                    </h4>

                </div><!--- card-header Ends --->

                <div class="card-body"><!--- card-body Starts --->

                    <form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Name : </label>

                            <div class="col-md-6">

                                <input type="text" name="admin_name" class="form-control" required>

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Email : </label>

                            <div class="col-md-6">

                                <input type="email" name="admin_email" class="form-control" required>

                            </div>

                        </div><!--- form-group row Ends --->



                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Country : </label>

                            <div class="col-md-6">

                                <input type="text" name="admin_country" class="form-control" required>

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Job : <br><span class="text-muted text-small">E.g ADMIN, ADMIN USA etc.</span> </label>

                            <div class="col-md-6">

                                <input type="text" name="admin_job" class="form-control" required>

                            </div>

                        </div><!--- form-group row Ends --->

                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Contact : </label>

                            <div class="col-md-6">

                                <input type="text" name="admin_contact" class="form-control" required>

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Info/Role : </label>

                            <div class="col-md-6">

                                <textarea name="admin_about" class="form-control" rows="3"></textarea>

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Image : </label>

                            <div class="col-md-6">

                                <input type="file" name="admin_image" class="form-control" required>

                            </div>

                        </div><!--- form-group row Ends --->

                        <hr class="card-hr">

                        <h4 class="h3 mb-3"> Account Password </h4>

                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Password : </label>

                            <div class="col-md-6 password-strength-checker">

                                <div class="input-group"><!--- input-group Starts --->

                                    <span class="input-group-addon">

                                        <i class="fa fa-check tick1 text-success"></i>

                                        <i class="fa fa-times cross1 text-danger"></i>

                                    </span>

                                    <input type="password" name="admin_pass" id="password" class="form-control" required>

                                    <span class="input-group-addon">

                                    <div id="meter_wrapper">

                                    <span id="pass_type"></span>

                                    <div id="meter"></div>

                                </div>

                                </span>

                            </div><!--- input-group Ends --->

                        </div>

                </div><!--- form-group row Ends --->


                <div class="form-group row"><!--- form-group row Starts --->

                    <label class="col-md-3 control-label"> Confirm Admin Password : </label>

                    <div class="col-md-6">

                        <div class="input-group"><!--- input-group Starts --->

                            <span class="input-group-addon">

                            <i class="fa fa-check tick2 text-success"></i>

                            <i class="fa fa-times cross2 text-danger"></i>

                            </span>

                            <input type="password" name="confirm_admin_pass" id="confirm_password" class="form-control" required>

                        </div><!--- input-group Ends --->

                    </div>

                </div><!--- form-group row Ends --->

                <hr class="card-hr">

                <div class="form-group row"><!--- form-group row Starts --->

                    <label class="col-md-3 control-label"></label>

                    <div class="col-md-6">

                    <input type="submit" name="submit" class="btn btn-success form-control" value="Insert User">

                    </div>

                </div><!--- form-group row Ends --->



                </form><!--- form Ends --->

            </div><!--- card-body Ends --->

        </div><!--- card Ends --->

    </div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->



<!--- Password Strength checker code starts  --->

<script>
    $(document).ready(function() {

        $("#password").keyup(function() {

        check_pass();

        });

    });

    function check_pass() {

        var val = document.getElementById("password").value;

        var meter = document.getElementById("meter");

        var no = 0;

        if (val != "") {

            // If the password length is less than or equal to 6
            if (val.length <= 6) no = 1;

            // If the password length is greater than 6 and contain any lowercase alphabet or any number or any special character
            if (val.length > 6 && (val.match(/[a-z]/) || val.match(/\d+/) || val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/))) no = 2;

            // If the password length is greater than 6 and contain alphabet,number,special character respectively
            if (val.length > 6 && ((val.match(/[a-z]/) && val.match(/\d+/)) || (val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) || (val.match(/[a-z]/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)))) no = 3;

            // If the password length is greater than 6 and must contain alphabets,numbers and special characters
            if (val.length > 6 && val.match(/[a-z]/) && val.match(/\d+/) && val.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) no = 4;

            if (no == 1) {

                $("#meter").animate({
                    width: '50px'
                }, 300);

                meter.style.backgroundColor = "red";

                document.getElementById("pass_type").innerHTML = "Very Weak";

            }

            if (no == 2) {

                $("#meter").animate({
                    width: '100px'
                }, 300);

                meter.style.backgroundColor = "#F5BCA9";

                document.getElementById("pass_type").innerHTML = "Weak";

            }

            if (no == 3) {

                $("#meter").animate({
                    width: '150px'
                }, 300);

                meter.style.backgroundColor = "#FF8000";

                document.getElementById("pass_type").innerHTML = "Good";

            }

            if (no == 4) {

                $("#meter").animate({
                    width: '200px'
                }, 300);

                meter.style.backgroundColor = "#00FF40";

                document.getElementById("pass_type").innerHTML = "Strong";

            }

        } else {

            meter.style.backgroundColor = "";

            document.getElementById("pass_type").innerHTML = "";

        }

    }
</script>



<!--- Password Strength checker code Ends  --->


<!--- Tick and Cross code starts  --->

<script>
    $(document).ready(function() {

        $('.tick1').hide();

        $('.cross1').hide();

        $('.tick2').hide();

        $('.cross2').hide();

        $('#confirm_password').focusout(function() {

            var password = $('#password').val();

            var confirmPassword = $('#confirm_password').val();

            if (password == confirmPassword) {

                $('.tick1').show();

                $('.cross1').hide();

                $('.tick2').show();

                $('.cross2').hide();

            } else {

                $('.tick1').hide();

                $('.cross1').show();

                $('.tick2').hide();

                $('.cross2').show();

            }

        });

    });
</script><!--- Tick and Cross code Ends  --->

<?php

if(isset($_POST['submit'])){

   $rules = array(
   "admin_name" => "required",
   "admin_email" => "required",
   "admin_country" => "required",
   "admin_job" => "required",
   "admin_country" => "required",
   "admin_image" => "required",
   "admin_pass" => "required",
   "confirm_admin_pass" => "required");

   $val = new Validator($_POST,$rules);

   if($val->run() == false){

      Flash::add("form_errors",$val->get_all_errors());
      Flash::add("form_data",$_POST);
      echo "<script> window.open('index?insert_review','_self');</script>";

   }else{

      $admin_email = $input->post('admin_email');
      $admin_pass = $input->post('admin_pass');
      $confirm_admin_pass = $input->post('confirm_admin_pass');
      $admin_image = $_FILES['admin_image']['name'];
      $tmp_admin_image = $_FILES['admin_image']['tmp_name'];

      $allowed = array('jpeg','jpg','gif','png','tif','ico','webp');
      $file_extension = pathinfo($admin_image, PATHINFO_EXTENSION);

      if(!in_array($file_extension,$allowed)){
         echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
      }else{

         $count_email = $db->count("admins",array("admin_email" => $admin_email));

         if($count_email > 0){
            echo "<script>alert('This Email Is Already Chosen, Please Try Another One.');</script>";
         }else{
          	
            if($admin_pass !== $confirm_admin_pass){
               echo "<script>alert('Your Password Does Not Match, Please Try Again.');</script>";
            }else{
              	
               $enctyp_password = password_hash($admin_pass, PASSWORD_DEFAULT);

               uploadToS3("admin_images/$admin_image",$tmp_admin_image);

               $data = $input->post();

               $data['isS3'] = $enable_s3;
               $data['admin_pass'] = $enctyp_password;
               $data['admin_image'] = $admin_image;

               unset($data['confirm_admin_pass']);
               unset($data['submit']);

               $insert_admin = $db->insert("admins",$data);
                  	
               if($insert_admin){

                  $insert_id = $db->lastInsertId();
                  $insert_rights = $db->insert("admin_rights",['admin_id'=>$insert_id]);
                  	
                  echo "<script>alert('Admin account created successfully.');</script>";
                  echo "<script>window.open('index?view_users','_self');</script>";

               }	
              	
            }

         }

      }

   }

}

?>

</div>

<?php } ?>