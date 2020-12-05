<?php

session_start();

include("includes/db.php");

if(isset($_SESSION['admin_email'])){	
  echo "<script>window.open('index?dashboard','_self');</script>";
}

$_SESSION['adminLanguage'] = 1;

?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
  
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  
    <title><?= $site_name; ?> - Admin Login</title>
  
    <meta name="description" content="Admin login. You will need admin credentials to access the admin panel. Reset your password if you have trouble remebering it.">
  
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">

    <!-- <link rel="shortcut icon" href="favicon.ico"> -->

    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="assets/scss/style.css">
    <link rel="stylesheet" href="assets/css/sweat_alert.css">
    
    <script type="text/javascript" src="assets/js/ie.js"></script>
    <script type="text/javascript" src="assets/js/sweat_alert.js"></script>
    

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    
    <style>
        
      .swal2-popup .swal2-styled.swal2-confirm {
  
          background-color: #28a745 !important;
      }
          
      .log-width{
          
          width: 550px;
          margin: 0 auto;
      }

    </style>

</head>
<body class="bg-dark">

<script src="../js/jquery.min.js"></script>


    <div class="sufee-login d-flex align-content-center flex-wrap">
        <div class="container">
            <div class="login-content">
                <div class="login-logo pb-4">
                    <a href="login.php">
                       <h2 class="text-white"> <?= $site_name; ?>  <span class="badge badge-success p-2 font-weight-bold">ADMIN</span></h2>
                    </a>
                </div>

          <?php if(isset($_GET['session_expired'])){ ?>
            
            <div class="alert alert-danger mb-3 alert-dismissible fade show">

                    <button type="button" class="close" data-dismiss="alert">

                    <span>&times;</span>

                    </button>

                    <span class=" mt-3"><i class="fa  fa-1x fa-exclamation-circle"></i> Your session has expired. Please login again!</span>
                    
              </div>
            
            <?php } ?>
                
                <div class="login-form">
     
                <form action="" id="myform" method="post" autocomplete="off">

                <div class="form-group">

                <label>Email</label>

                <input type="text" class="input form-control" value="<?php if(isset($_SESSION["r_email"])) { echo $_SESSION["r_email"]; } ?>" placeholder="Email or Username" name="admin_email" >

                </div>

                <div class="form-group">

                <label>Password</label>

                <input type="password" class="pass form-control" value="<?php if(isset($_SESSION["r_passoword"])) { echo $_SESSION["r_passoword"]; }else{ echo " "; } ?>" placeholder="Password" name="admin_pass" >

                </div>

                <div class="checkbox pb-2">

                <label>

                <input type="checkbox" <?php if(isset($_SESSION["r_email"])){ ?> checked="checked" <?php } ?> name="remember"> Remember Me

                </label>

                <label class="pull-right">

                <a href="forgot-password">Forgotten Password?</a>

                </label>

                </div>

                <button type="submit" class="btn btn-success btn-flat m-b-30 m-t-30" name="admin_login">Sign in</button>

                </form>
                    
                    
                </div>
                
            </div>
          
        </div>
   
    </div>
	
	  <script>

    $(document).ready(function(){
     
    <?php if(!isset($_SESSION["r_email"])){ ?>

    setTimeout(function(){
    	
    document.getElementById("myform").reset();

    $(".pass").val("");

    },100);
    	  
    <?php } ?>
    	  
    });
		
    </script>
   
<script src="assets/js/plugins.js"></script>

</body>

</html>

<?php

if(isset($_POST['admin_login'])){
	
  $admin_email = $input->post('admin_email');
  $admin_pass = $input->post('admin_pass');
  	
  $select_admins = $db->query("select * from admins where admin_email=:a_email OR admin_user_name=:a_user_name",array("a_email"=>$admin_email,"a_user_name"=>$admin_email));
  $count_admins = $select_admins->rowCount();

  if($count_admins != 0){
    $row_admins = $select_admins->fetch();
    $hash_password = $row_admins->admin_pass;
    $decrypt_password = password_verify($admin_pass, $hash_password);
  }else{
    $decrypt_password = 0;
  }
  	
  if($decrypt_password == 0){
  
    echo "<script>
      swal({
         type: 'warning',
         text: 'Opps! password or username is incorrect. Please try again.',
      });
    </script>";

  }else{
  		
    // $get_admin = $db->select("admins",array("admin_email"=>$admin_email,"admin_pass"=>$hash_password));
    $get_admin = $db->query("select * from admins where admin_email=:a_email OR admin_user_name=:a_user_name AND admin_pass=:a_pass",array("a_email"=>$admin_email,"a_user_name"=>$admin_email,"a_pass"=>$hash_password));

    if($get_admin->rowCount() == 1){
    	
      if(!empty($_POST["remember"])){  
        $_SESSION["r_email"] = $admin_email;
        $_SESSION["r_passoword"] = $admin_pass;
      }else{
        if(isset($_SESSION["r_email"])){ 
          unset($_SESSION["r_email"]); 
        }

        if(isset($_SESSION["r_passoword"])){  
          unset($_SESSION["r_passoword"]);  
    	  }
      }
    	
      $_SESSION['admin_email'] = $admin_email;
      $_SESSION['loggedin_time'] = time();
          
      echo "<script>
          swal({
          type: 'success',
          text: 'Successfully Logging you in...',
          timer: 4000,
          onOpen: function(){
          swal.showLoading()
          }
          }).then(function(){
            window.open('index?dashboard','_self');
          });
        </script>";
                
    }
  	
  }
	
}

?>