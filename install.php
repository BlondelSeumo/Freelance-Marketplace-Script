<?php
  session_start();
  require_once("includes/config.php");
  require_once("libs/input.php");
  if(!empty(DB_HOST) and !empty(DB_USER) and !empty(DB_NAME)){
    echo "<script>window.open('index','_self'); </script>";
    exit();
  }
  if(isset($_SESSION["db_host"])){
    echo "<script>window.open('install2','_self'); </script>";
  }
?>
<!DOCTYPE html>
<html>
<head>
<title>Install Script - Step One</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
<link href="styles/bootstrap.css" rel="stylesheet">
<link href="styles/styles.css" rel="stylesheet">
<link href="styles/sweat_alert.css" rel="stylesheet">
<!--- stylesheet width modifications --->
<link href="styles/custom.css" rel="stylesheet">
<link href="font_awesome/css/font-awesome.css" rel="stylesheet">
<script src="js/jquery.min.js"></script>
<script src="js/sweat_alert.js"></script>
<style>
body {
  background-color: #001f3f;
}
.control-label {
  font-size: 16px;
  margin-top: 5px;
}
.card {
  box-shadow: 0px 0px 1px 2px #cccc;
}
</style>
</head>
<body class="is-responsive">

<div class="container"><!-- container Starts -->
<div class="row"><!-- row Starts -->
  <div class="col-md-2"></div>
  <div class="text-center col-md-8 mb-2 mt-5"><!-- col-md-12 mb-5 mt-5 Starts -->
      <h2 class="text-white"> Step One </h2><!---<img src="images/logo.png" width="100">-->
  </div><!-- col-md-12 mb-5 mt-5 Ends -->
</div><!-- row Ends -->
<div class="row"><!-- row Starts -->
  <div class="col-md-2"></div>
  <div class="col-md-8">
    <div class="card rounded-0 mb-4">
      <div class="card-body">
        <form method="post" enctype="multipart/form-data">
          <!-- form Starts -->
          <h3>Welcome</h3>
          <hr>
          <p>Welcome to the GigToDoScript installer. Finish all 3 steps in about 2 minutes, and start using the best freelance marketplace script in the world. All fields with (<span class="text-danger">*</span>) must be filled. For guidance refer to <a target="_blank" href="http://help.gigtodoscript.com/knowledge/details/4/.html" class="text-success">How To?</a> </p>
          <h3>Database Information</h3>
          <hr>
          <p>Before getting started, we need some database information in order to proceed.</p>
          <br>
          <div class="form-group row ">
              <!-- form-group row Starts -->
              <div class="col-md-3 control-label h5 mt-2"> Database Name <span class="text-danger">*</span></div>
              <div class="col-md-8">
                  <input type="text" name="db_name" class="form-control" required>
              </div>
          </div>
          <!-- form-group row Ends -->
          <div class="form-group row ">
              <!-- form-group row Starts -->
              <div class="col-md-3 control-label h5 mt-2"> Username <span class="text-danger">*</span></div>
              <div class="col-md-8">
                  <input type="text" name="db_username" class="form-control" required>
              </div>
          </div>
          <!-- form-group row Ends -->
          <div class="form-group row ">
              <!-- form-group row Starts -->
              <div class="col-md-3 control-label h5 mt-2"> Password </div>
              <div class="col-md-8">
                  <input type="text" name="db_pass" class="form-control">
              </div>
          </div>
          <!-- form-group row Ends -->
          <div class="form-group row ">
              <!-- form-group row Starts -->
              <div class="col-md-3 control-label h5 mt-2"> Database Host <span class="text-danger">*</span> </div>
              <div class="col-md-8">
                  <input type="text" name="db_host" class="form-control" required>
                  <small> e.g. localhost</small>
              </div>
          </div>
          <!-- form-group row Ends -->
          <hr>
          <div class="form-group row ">
              <!-- form-group row Starts -->
              <div class="col-md-7 control-label h5 mt-2"> </div>
              <div class="col-md-4">
                  <button type="submit" name="install" class="btn btn-primary form-control">Next <i class="fa fa-arrow-right"></i></button>
              </div>
          </div>
          <!-- form-group row Ends -->
        </form>
      </div>
    </div>
  </div>
</div>
<!-- row Ends -->
</div>
<?php

if(isset($_POST["install"])){
  $host = $input->post("db_host");
  $uname = $input->post("db_username");
  $pass = $input->post("db_pass");
  $database = $input->post("db_name"); //Change Your Database Name
  //ENTER THE RELEVANT INFO BELOW
  $filename ='gigtodo.sql';
  $command = file_get_contents($filename);
  try{
    $pdo = new PDO("mysql:host=$host;dbname=$database",$uname,$pass);
    /* execute multi query */
    $run = $pdo->prepare($command);   
    if($run->execute()){
      $_SESSION["db_host"] = $host;
      $_SESSION["db_username"] = $uname;
      $_SESSION["db_pass"] = $pass;
      $_SESSION["db_name"] = $database;
      echo "<script>window.open('install2','_self'); </script>";
    }
  }catch(PDOException $ex){
    echo "<h2 class='text-white text-center mb-3'>Something Wrong In Fields</h2>";  
  }
}
?>
</body>
</html>