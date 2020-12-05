<?php
session_start();
require_once("../includes/db.php");
if(!isset($_SESSION['seller_user_name'])){
echo "<script>window.open('../login','_self')</script>";
}
$login_seller_user_name = $_SESSION['seller_user_name'];
$select_login_seller = $db->select("sellers",array("seller_user_name" => $login_seller_user_name));
$row_login_seller = $select_login_seller->fetch();
$login_seller_id = $row_login_seller->seller_id;
?>
<!DOCTYPE html>
<html lang="en" class="ui-toolkit">
<head>
	<title><?= $site_name; ?> - <?= $lang["titles"]["post_request"]; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?= $site_desc; ?>">
	<meta name="keywords" content="<?= $site_keywords; ?>">
	<meta name="author" content="<?= $site_author; ?>">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700,300,100" rel="stylesheet">
	<link href="../styles/bootstrap.css" rel="stylesheet">
    <link href="../styles/custom.css" rel="stylesheet"> <!-- Custom css code from modified in admin panel --->
	<link href="../styles/styles.css" rel="stylesheet">
	<link href="../styles/user_nav_styles.css" rel="stylesheet">
	<link href="../font_awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../styles/sweat_alert.css" rel="stylesheet">
    <link href="../styles/animate.css" rel="stylesheet">
	<!-- Optional: include a polyfill for ES6 Promises for IE11 and Android browser -->
	<script src="../js/ie.js"></script>
    <script type="text/javascript" src="../js/sweat_alert.js"></script>
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<?php if(!empty($site_favicon)){ ?>
    <link rel="shortcut icon" href="<?= $site_favicon; ?>" type="image/x-icon">
    <?php } ?>
</head>
<body class="is-responsive">
<?php 
require_once("../includes/user_header.php"); 
if($seller_verification != "ok"){
echo "
<div class='alert alert-danger rounded-0 mt-0 text-center'>
Please confirm your email to use this feature.
</div>
";
}else{
?>

<div class="container-fluid mt-5 mb-5">
  <h1 class="mb-5">
    <i class="fa fa-plus-circle" aria-hidden="true">
    </i>
    <?= $lang["titles"]["post_request"]; ?>
  </h1>
  <div class="row"><!--- row Starts --->
    <div class="col-xl-8 col-lg-8 post-request col-md-12 ">
      <?php 
		$form_errors = Flash::render("form_errors");
		$form_data = Flash::render("form_data");
		if(is_array($form_errors)){
		?>
      <div class="alert alert-danger">
        <!--- alert alert-danger Starts --->
        <ul>
          <?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
          <li>
            <?= $i ?>. 
            <?= ucfirst($error); ?>
          </li>
          <?php } ?>
        </ul>
      </div>
      <!--- alert alert-danger Ends --->
      <?php } ?>
      <div class="card rounded-0">
        <div class="card-body">
          <form method="post" enctype="multipart/form-data">
            <div class="row row-1">
              <div class="col-md-2 d-md-block d-none">
                <!--<i class="fa fa-pencil-square-o fa-4x text-success"></i>-->
                <img style="position:relative; top: -12px; left:15px;" width="64" src="../images/comp/book.png">
              </div>
              <div class="col-md-10 col-sm-12">
                <div class="row">
                  <div class="col-xl-9 col-lg-12">
                    <div class="form-group">
                      <input type="text" name="request_title" placeholder="<?= $lang['placeholder']['request_title']; ?>" class="form-control input-lg" required="" value="<?php isset($form_data['request_title'])? $form_data['request_title']:"" ;  ?>">
                    </div>
                    <div class="form-group">
                      <textarea name="request_description" id="textarea" rows="5" cols="73" maxlength="380" class="form-control" placeholder="<?= $lang['placeholder']['request_desc']; ?>" required=""><?php if (isset($form_data['request_description'])){echo $form_data['request_description'];} ?></textarea>
                    </div>
                    <div class="form-group">
                      <input type="file" name="request_file" id="file" >
                      <div class="font-weight-bold pull-right">
                        <span class="descCount"> 0 
                        </span> / 380 Max
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row-2">
              <hr class="card-hr">
              <h5 class="mb-5"><?= $lang['post_request']['heading_1']; ?></h5>
              <div class="row mb-2 ">
                <div class="col-md-2 d-md-block d-none">
                  <!-- <i class="fa fa-folder-open fa-4x text-success"></i> -->
                  <img style="position:relative; top: -12px; left:15px;" src="../images/comp/folder.png">
                </div>
                <div class="col-md-10 col-sm-12">
                  <div class="row">
                    <div class="col-xl-4 col-md-6 mb-2">
                      <select class="form-control" name="cat_id" id="category" required="">
                        <option value="" class="hidden">
                          <?= $lang['placeholder']['select_category']; ?>
                        </option>
                        <?php 
        								$get_cats = $db->select("categories");
        								while($row_cats = $get_cats->fetch()){
          								$cat_id = $row_cats->cat_id;
          								$get_meta = $db->select("cats_meta",["cat_id"=>$cat_id,"language_id"=>$siteLanguage]);
          								$row_meta = $get_meta->fetch();
          								$cat_title = $row_meta->cat_title;
        								?>
                        <option value="<?= $cat_id; ?>">  
                          <?= $cat_title; ?> 
                        </option>
                        <?php } ?>
                      </select>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-2">
                      <select class="form-control" name="child_id" id="sub-category" required="">
                        <option value="" class="hidden">
                          <?= $lang['placeholder']['select_sub_category']; ?>
                        </option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row-3">
              <hr class="card-hr">
              <h5 class="mb-4"> <?= $lang['post_request']['heading_2']; ?> </h5>
              <div class="row mb-4">
                <div class="col-md-1 d-md-block d-none">
                  <!--<i class="fa fa-clock-o fa-4x text-success "></i>-->
                  <img style="position:relative; left:15px;" src="../images/comp/timetable.png">
                </div>
                <div class="col-md-11 col-sm-12 mt-3 ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php
      						$get_delivery_times = $db->select("delivery_times");
      						while($row_delivery_times = $get_delivery_times->fetch()){
      						$delivery_proposal_title = $row_delivery_times->delivery_proposal_title;
      						?>
                  <label class="custom-control custom-radio">
                    <input type="radio" value="<?= $delivery_proposal_title; ?>" 
                    <?php if(isset($form_data['delivery_time']) and$form_data['delivery_time'] == $delivery_proposal_title){ echo "checked"; } ?> name="delivery_time" class="custom-control-input" required="">
                    <span class="custom-control-indicator"></span>
                    <span class="custom-control-description"> 
                      <?= $delivery_proposal_title; ?> 
                    </span>
                  </label>
                  <?php } ?>
                </div>
              </div>
            </div>
            <div class="row-4">
              <hr class="card-hr">
              <h5 class="mb-4"> <?= $lang['post_request']['heading_3']; ?></h5>
              <div class="row">
                <div class="col-md-1 d-md-block d-none">
                  <!--<i class="fa fa-money fa-4x text-success fa-work"></i>-->
                  <img style="position:relative; left:15px;" src="../images/comp/budget.png">
                </div>
                <div class="col-md-6 col-sm-12 offset-md-1 mt-3">
                  <div class="input-group form-curb">
                    <span class="input-group-addon font-weight-bold" > 
                      <?= $s_currency; ?> 
                    </span>
                    <input type="number" name="request_budget" min="5" placeholder="<?= $lang['placeholder']['5_minimum']; ?>" class="form-control input-lg" value="<?= $form_data['request_budget']; ?>" required>
                  </div>
                </div>
              </div>
            </div>
            <hr class="card-hr">
            <input type="submit" name="submit" value="<?= $lang['button']['submit_request']; ?>" style="cursor:pointer;" class="btn btn-success btn-lg float-right" >
          </form>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 request-sidebar"><!--- col-xl-3 col-md-2 request-sidebar Starts --->
		  <?php include("../includes/request_sidebar.php"); ?>
    </div><!--- col-xl-3 col-md-2 request-sidebar Ends --->
  </div> <!--- row Ends --->
</div><!--- container-fluid Ends --->

<?php } ?>
<script>
$(document).ready(function(){
	$('.h-2').css("visibility", "hidden");
	$('.h-3').css("visibility", "hidden");
	$('.h-4').css("visibility", "hidden");

	$('.container-fluid').hover(function(){
	$('.h-1').css("visibility", "visible");
	$('.h-2').css("visibility", "hidden");
	$('.h-3').css("visibility", "hidden");
	$('.h-4').css("visibility", "hidden");
	});

	$('.row-1').mouseover(function(){
	$('.h-1').css("visibility", "visible");
	$('.h-2').css("visibility", "hidden");
	$('.h-3').css("visibility", "hidden");
	$('.h-4').css("visibility", "hidden");
	});

	$('.row-2').mouseover(function(){
	$('.h-1').css("visibility", "hidden");
	$('.h-2').css("visibility", "visible");
	$('.h-3').css("visibility", "hidden");
	$('.h-4').css("visibility", "hidden");
	});

	$('.row-3').mouseover(function(){
	$('.h-1').css("visibility", "hidden");
	$('.h-2').css("visibility", "hidden");
	$('.h-3').css("visibility", "visible");
	$('.h-4').css("visibility", "hidden");
	});

	$('.row-4').mouseover(function(){
	$('.h-1').css("visibility", "hidden");
	$('.h-2').css("visibility", "hidden");
	$('.h-3').css("visibility", "hidden");
	$('.h-4').css("visibility", "visible");
	});

	$('.row-2,.row-3,.row-4').mouseout(function(){
	$('.h-1').css("visibility", "visible");
	$('.h-2').css("visibility", "hidden");
	$('.h-3').css("visibility", "hidden");
	$('.h-4').css("visibility", "hidden");
	});

	$("#textarea").keydown(function(){
	var textarea = $("#textarea").val();
	$(".descCount").text(textarea.length);	
	});	

	$("#sub-category").hide();

	$("#category").change(function(){
		$("#sub-category").show();	
		var category_id = $(this).val();
		$.ajax({
			url:"fetch_subcategory",
			method:"POST",
			data:{category_id:category_id},
			success:function(data){
				$("#sub-category").html(data);
			}
		});
	});

});
</script>
<?php
if(isset($_POST['submit'])){
	$rules = array(
	"request_title" => "required",
	"request_description" => "required",
	"cat_id" => "required",
	"child_id" => "required",
	"request_budget" => "number|required",
	"delivery_time" => "required");
	$messages = array("cat_id" => "you need to select a category","child_id" => "you need to select a child category");
	$val = new Validator($_POST,$rules,$messages);
	if($val->run() == false){
		Flash::add("form_errors",$val->get_all_errors());
		Flash::add("form_data",$_POST);
		echo "<script> window.open('post_request','_self');</script>";
	}else{
		$request_title = $input->post('request_title');
		$request_description = $input->post('request_description');
		$cat_id = $input->post('cat_id');
		$child_id = $input->post('child_id');
		$request_budget = $input->post('request_budget');
		$delivery_time = $input->post('delivery_time');
		$request_file = $_FILES['request_file']['name'];
		$request_file_tmp = $_FILES['request_file']['tmp_name'];
		$request_date = date("F d, Y");
		$allowed = array('jpeg','jpg','gif','png','tif','avi','mpeg','mpg','mov','rm','3gp','flv','mp4', 'zip','rar','mp3','wav','pdf','docx','txt');
		$file_extension = pathinfo($request_file, PATHINFO_EXTENSION);
		if(!empty($request_file)){
			if(!in_array($file_extension,$allowed)){
				echo "<script>alert('{$lang['alert']['extension_not_supported']}')</script>";
				echo "<script>window.open('post_request','_self')</script>";
				exit();
			}
			$request_file = pathinfo($request_file, PATHINFO_FILENAME);
			$request_file = $request_file."_".time().".$file_extension";
			uploadToS3("request_files/$request_file",$request_file_tmp);
		}
		$isS3 = $enable_s3;
		$insert_request = $db->insert("buyer_requests",array("seller_id"=>$login_seller_id,"cat_id"=>$cat_id,"child_id"=>$child_id,"request_title"=>$request_title,"request_description"=>$request_description,"request_file"=>$request_file,"delivery_time"=>$delivery_time,"request_budget"=>$request_budget,"request_date"=>$request_date,"isS3"=>$isS3,"request_status"=>'pending'));
		if($insert_request){
			echo "<script>
			    swal({
			      type: 'success',
			      text: 'Your request has been submitted successfully!',
			      timer: 3000,
			      onOpen: function(){
			      	swal.showLoading()
			      }
			    }).then(function(){
			      	window.open('manage_requests.php','_self');
			    });
			</script>";
		}
	}
}
?>
<?php require_once("../includes/footer.php"); ?>
</body>
</html>