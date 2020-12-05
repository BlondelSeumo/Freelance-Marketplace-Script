<?php
session_start();
include("includes/db.php");
if(isset($_SESSION['admin_email'])){
echo "<script>window.open('index?dashboard','_self');</script>";
}
$code = $input->get('code');
$select_admin = $db->select("admins",array("admin_pass" => $code));
$count_admin = $select_admin->rowCount();
if($count_admin == 0){
    echo "
    <script>
    alert('Your password change link is invalid.');
    window.open('index.php','_self');
    </script>";
}
$row_admin = $select_admin->fetch();
$admin_id = $row_admin->admin_id;
$admin_user_name = $row_admin->admin_user_name;
?>
<!doctype html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $site_name; ?> Admin - Forgot Password</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-icon.png">
    <!-- <link rel="shortcut icon" href="favicon.ico">-->
    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="assets/scss/style.css">
        <link rel="stylesheet" href="assets/css/sweat_alert.css">
    <script type="text/javascript" src="assets/js/sweat_alert.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
</head>
<body class="bg-dark">
    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo pb-4">
                    <a href="index.html">
                        <h2 class="text-white"> <?= $site_name; ?> <span class="badge badge-success p-2 font-weight-bold">ADMIN</span></h2>
                    </a>
                    <p class="lead text-white" style=" margin-top:15px; margin-bottom:-18px;">
                        Dear <?= $admin_user_name; ?>, you can change your password here.
                        </p>
                </div>
                <div class="login-form">
                    <form method="post">
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_pass" class="form-control" placeholder="New Password">
                        </div>
                          <div class="form-group">
                            <label>New Password Again</label>
                            <input type="password" name="new_pass_again" class="form-control" placeholder="Again New Password">
                        </div>
                        <button type="submit" name="submit" class="btn btn-success btn-flat m-b-15">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
    <?php
    if(isset($_POST['submit'])){
    $new_pass = $input->post('new_pass');
    $new_pass_again = $input->post('new_pass_again');
    if($new_pass != $new_pass_again){
        echo "
        <script>
        swal({
          type: 'warning',
          html: $('<div>')
            .text('Opps! Your passwords don\'t match. Please try again.'),
          animation: false,
          customClass: 'animated tada'
    })
    </script>
        ";
    }else{
    $encrypted_password = password_hash($new_pass, PASSWORD_DEFAULT);
    $update_password = $db->update("admins",array("admin_pass"=>$encrypted_password),array("admin_id"=>$admin_id));
    if($update_password){
        echo "
        <script>
            swal({
              type: 'success',
              text: 'Your password has been updated successfully. Redirecting you to login page...',
              timer: 5000,
                  onOpen: function(){
                  swal.showLoading()
                  }
                  }).then(function(){
              if (
                // Read more about handling dismissals
                window.open('$site_url/admin/login.php','_self')
              ) {
                console.log('Succesfully changed password')
              }
            })
        </script>";
    }
    }
    }
    ?>
</body>
</html>