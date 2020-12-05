<?php


@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
    
   if(isset($_GET['user_profile'])){
   	
      $edit_id = $input->get('user_profile');
      $edit_admin = $db->select("admins",array('admin_id'=>$edit_id));
      $row_edit = $edit_admin->fetch();
      $a_id = $row_edit->admin_id;
      $a_name = $row_edit->admin_name;
      $a_email = $row_edit->admin_email;
      $a_pass = $row_edit->admin_pass;
      $a_image = $row_edit->admin_image;
      $showImage = $row_edit->admin_image;
      $a_country = $row_edit->admin_country;
      $a_job = $row_edit->admin_job;
      $a_contact = $row_edit->admin_contact;
      $a_about = $row_edit->admin_about;
      $isS3 = $row_edit->isS3;
      	
   }

?>
<script src="../js/jquery.min.js"></script>

    <div class="breadcrumbs">

        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1><i class="menu-icon fa fa-user"></i> Admins</h1>
                </div>
            </div>
        </div>
        
        <div class="col-sm-8">
            <div class="page-header float-right">
                <div class="page-title">
                    <ol class="breadcrumb text-right">
                        <li class="active">Update Profile</li>
                    </ol>
                </div>
            </div>
        </div>

    </div>


<div class="container">

    <div class="row mt-2"><!--- 2 row Starts --->

        <div class="col-lg-12"><!--- col-lg-12 Starts --->

            <div class="card"><!--- card Starts --->

                <div class="card-header"><!--- card-header Starts --->

                    <h4 class="h4">

                        Edit Profile

                    </h4>

                </div><!--- card-header Ends --->

                <div class="card-body"><!--- card-body Starts --->

                    <form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Name : </label>

                            <div class="col-md-6">

                                <input type="text" name="admin_name" class="form-control" required value="<?= $a_name; ?>">

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Email : </label>

                            <div class="col-md-6">

                                <input type="email" name="admin_email" class="form-control" required value="<?= $a_email; ?>">

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Country : </label>

                            <div class="col-md-6">

                                <input type="text" name="admin_country" class="form-control" required value="<?= $a_country; ?>">

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                       <label class="col-md-3 control-label"> Admin Job : <br> <span class="text-muted"> ADMIN, ADMIN USA etc.</span> </label>

                            <div class="col-md-6">

                              <input type="text" name="admin_job" class="form-control" required value="<?= $a_job; ?>">

                            </div>

                        </div><!--- form-group row Ends --->

                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Contact : </label>

                            <div class="col-md-6">

                                <input type="text" name="admin_contact" class="form-control" required value="<?= $a_contact; ?>">

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin About/Role : </label>

                            <div class="col-md-6">

                         <textarea name="admin_about" class="form-control" rows="3"><?= $a_about; ?></textarea>

                            </div>

                        </div><!--- form-group row Ends --->


                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Image : </label>

                            <div class="col-md-6">

                                <input type="file" name="admin_image" class="form-control">

                                <br>

                                <img src="<?= getImageUrl("admins",$showImage); ?>" width="70" height="70">

                            </div>

                        </div><!--- form-group row Ends --->

                        <hr class="card-hr"/>

                        <h4 class="h3 mb-3">

                            Change Account Password

                            <span class="h6 text-muted mb-3">

                            <br> If you do not wish to change your password, then just leave the fields below empty.

                            </span>

                        </h4>

                        <div class="form-group row"><!--- form-group row Starts --->

                            <label class="col-md-3 control-label"> Admin Password : </label>

                            <div class="col-md-6 password-strength-checker">

                                <div class="input-group"><!--- input-group Starts --->

                                    <span class="input-group-addon">

                                        <i class="fa fa-check tick1 text-success"></i>

                                        <i class="fa fa-times cross1 text-danger"></i>

                                    </span>

                                    <input type="password" name="admin_pass" id="password" class="form-control">

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

                        <input type="password" name="confirm_admin_pass" id="confirm_password" class="form-control">

                        </div><!--- input-group Ends --->

                    </div>

                </div><!--- form-group row Ends --->

                <hr class="card-hr">

                <div class="form-group row"><!--- form-group row Starts --->

                    <label class="col-md-3 control-label"></label>

                    <div class="col-md-6">

                    <input type="submit" name="update" class="btn btn-success form-control" value="Update User Profile">

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
</script>



<!--- Tick and Cross code Ends  --->


<?php

if(isset($_POST['update'])){
	
   $admin_name = $input->post('admin_name');
   $admin_email = $input->post('admin_email');
   $admin_country = $input->post('admin_country');
   $admin_contact = $input->post('admin_contact');
   $admin_job = $input->post('admin_job');
   $admin_about = $input->post('admin_about');
   $admin_pass = $input->post('admin_pass');
   $confirm_admin_pass = $input->post('confirm_admin_pass');
	
   $admin_image = $_FILES['admin_image']['name'];
   $tmp_admin_image = $_FILES['admin_image']['tmp_name'];
	
   if(empty($admin_pass) and empty($confirm_admin_pass)){
      $encrypt_password = $a_pass;
   }else{
      if($admin_pass !== $confirm_admin_pass){
      echo "<script>alert('Your Password Does Not Match, Please Try Again.');</script>";
      echo "<script>window.open('index?user_profile=$a_id','_self');</script>";
      }else{
         $encrypt_password = password_hash($admin_pass, PASSWORD_DEFAULT);
      }
   }

   $allowed = array('jpeg','jpg','gif','png','tif','ico','webp');
   $file_extension = pathinfo($admin_image, PATHINFO_EXTENSION);

   if(!in_array($file_extension,$allowed) & !empty($admin_image)){
      echo "<script>alert('Your File Format Extension Is Not Supported.')</script>";
   }else{

      if(empty($admin_image)){
         $admin_image = $a_image;
         $isS3 = $isS3;
      }else{
         uploadToS3("admin_images/$admin_image",$tmp_admin_image);
         $isS3 = $enable_s3;
      }

      $update_admin = $db->update("admins",["admin_name"=>$admin_name,"admin_email"=>$admin_email,"admin_pass"=>$encrypt_password,"admin_image"=>$admin_image,"admin_contact"=>$admin_contact,"admin_country"=>$admin_country,"admin_job"=>$admin_job,"admin_about"=>$admin_about,"isS3"=>$isS3],["admin_id"=>$a_id]);

      if($update_admin){
         echo "
         <script>
            alert_success('Your User Profile Has Been Updated Successfully,So Please Login Again.','logout');
         </script>";
      }

   }
	
}

?>

</div>

<?php } ?>