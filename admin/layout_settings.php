<?php
@session_start();
if(!isset($_SESSION['admin_email'])){
	echo "<script>window.open('login','_self');</script>";
}else{

$get_section = $db->select("home_section",array("language_id" => $adminLanguage));
$row_section = $get_section->fetch();
$count_section = $get_section->rowCount();
@$db_section_heading = $row_section->section_heading;
@$db_section_short_heading = $row_section->section_short_heading;

$get_bar = $db->select("announcement_bar",['language_id'=>$adminLanguage]);
$row_bar = $get_bar->fetch();
$count_bar = $get_bar->rowCount();
@$enable_bar = $row_bar->enable_bar;
@$bg_color = $row_bar->bg_color;
@$text_color = $row_bar->text_color;
@$bar_text = $row_bar->bar_text;

?>
<div class="breadcrumbs">
	<div class="col-sm-4">
		<div class="page-header float-left">
			<div class="page-title">
			  <h1><i class="menu-icon fa fa-cog"></i> Settings / Layout Settings</h1>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="page-header float-right">
			<div class="page-title">
				<ol class="breadcrumb text-right">
				  <li class="active">Layout Settings</li>
				</ol>
			</div>
		</div>
	</div>
</div>

<div class="container">
<div class="row"><!--- 2 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card"><!--- card Starts --->
<div class="card-header"><!--- card-header Starts --->
<h4 class="h4"><i class="fa fa-square"></i> Footer Layout Settings </h4>
</div><!--- card-header Ends --->
<div class="card-body row"><!--- card-body row Starts --->
<div class="col-md-3 border-right"><!--- col-md-3 border-right Starts --->
<ul class="nav nav-pills flex-column"><!--- nav nav-pills flex-column Starts --->
<li class="nav-item">
<a class="nav-link active" data-toggle="pill" href="#categories">Categories Column</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="pill" href="#about">About Column</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="pill" href="#follow">Follow Us Column</a>
</li>
</ul><!--- nav nav-pills flex-column Ends --->
</div><!--- col-md-3 border-right Ends --->
<div class="col-md-9"><!--- col-md-9 Starts --->
<div class="tab-content"><!--- tab-content Starts --->
<div id="categories" class="tab-pane fade show active"><!--- categories tab-pane fade show active Starts --->
<?php
$get_footer_links = $db->select("footer_links",array("link_section" => "categories","language_id" => $adminLanguage));
while($row_footer_links = $get_footer_links->fetch()){
$link_id = $row_footer_links->link_id;
$link_title = $row_footer_links->link_title;
$link_section = $row_footer_links->link_section;
?>
<div class="mb-2">
<?= $link_title; ?> - <span> <?= ucwords($link_section); ?> </span>
<a href="index?edit_link=<?= $link_id; ?>" class="mr-1 ml-2">
<i class="fa fa-pencil"></i>
</a>
<a href="index?delete_link=<?= $link_id; ?>" onclick="return confirm('Do you really want to delete this link permanently.')">
<i class="fa fa-trash"></i>
</a>
</div>
<?php } ?>
<div class="bg-light p-3 "><!--- bg-light p-3 Starts --->
<h4 class="pb-2"> Add New Link</h4>
<form class="form-inline pb-3" method="post">
<input type="text" name="link_title" placeholder="Link Title" class="form-control mb-3 mr-sm-2 mb-sm-0" required="">
<input type="text" name="link_url" placeholder="Link Url" class="form-control mb-3 mr-sm-2 mb-sm-0" required="">
<button type="submit" class="btn btn-success form-control" name="add_link_category">
<i class="fa fa-plus-circle"></i> Add Link
</button>
</form>
</div><!--- bg-light p-3 Ends --->
<?php
if(isset($_POST['add_link_category'])){
$link_title = $input->post("link_title");
$link_url = $input->post("link_url");
$insert_link = $db->insert("footer_links",array("language_id"=>$adminLanguage,"link_title"=>$link_title,"link_url"=>$link_url,"link_section"=>'categories'));
if($insert_link){
echo "<script>window.open('index?layout_settings','_self');</script>";
}	
}
?>
</div><!--- categories tab-pane fade show active Ends --->
<div id="about" class="tab-pane fade in"><!--- categories tab-pane fade in Starts --->
<?php
$get_footer_links = $db->select("footer_links",array("link_section" => "about","language_id" => $adminLanguage));
while($row_footer_links = $get_footer_links->fetch()){
$link_id = $row_footer_links->link_id;
$icon_class = $row_footer_links->icon_class;
$link_title = $row_footer_links->link_title;
$link_section = $row_footer_links->link_section;
?>
<div class="mb-2">
<i class="fa <?= $icon_class; ?>"></i> <?= $link_title; ?> - <span><?= ucwords($link_section); ?></span>
<a href="index?edit_link=<?= $link_id; ?>" class="mr-1 ml-2">
<i class="fa fa-pencil"></i>
</a>
<a href="index?delete_link=<?= $link_id; ?>" onclick="return confirm('Do you really want to delete this link permanently.')">
<i class="fa fa-trash"></i>
</a>
</div>
<?php } ?>
<div class="bg-light p-3"><!--- bg-light p-3 Starts --->
<h4 class="pb-2">Add New Link</h4>
<form class="form-inline pb-3" method="post">
<input type="text" name="icon_class" placeholder="FontAwesome Icon Class Name" class="form-control mb-3 mr-sm-2 mb-sm-0" required="">
<input type="text" name="link_title" placeholder="Link Title" class="form-control mb-3 mr-sm-2 mb-sm-0">
<input type="text" name="link_url" placeholder="Link Url" class="form-control mb-3 mr-sm-2 mb-sm-0">
<button type="submit" class="btn btn-success form-control" name="add_link_about">
<i class="fa fa-plus-circle"></i> Add Link
</button>
</form>
</div><!--- bg-light p-3 Ends --->
<?php
if(isset($_POST['add_link_about'])){
$icon_class = $input->post("icon_class");
$link_title = $input->post("link_title");
$link_url = $input->post("link_url");
$insert_link = $db->insert("footer_links",array("language_id"=>$adminLanguage,"icon_class"=>$icon_class,"link_title"=>$link_title,"link_url"=>$link_url,"link_section"=>'about'));
if($insert_link){
echo "<script>window.open('index?layout_settings','_self');</script>";
}	
}
?>
</div><!--- categories tab-pane fade in Ends --->
<div id="follow" class="tab-pane fade in"><!--- follow tab-pane fade in Starts --->
<?php
$get_footer_links = $db->select("footer_links",array("link_section" => "follow","language_id" => $adminLanguage));
while($row_footer_links = $get_footer_links->fetch()){
$link_id = $row_footer_links->link_id;
$icon_class = $row_footer_links->icon_class;
$link_section = $row_footer_links->link_section;
?>
<div class="mb-2">
<i class="fa <?= $icon_class; ?>"></i> - <span> <?= ucwords($link_section); ?> </span>
<a href="index?edit_link=<?= $link_id; ?>" class="mr-1 ml-2">
<i class="fa fa-pencil"></i>
</a>
<a href="index?delete_link=<?= $link_id; ?>" onclick="return confirm('Do you really want to delete this link permanently.')">
<i class="fa fa-trash"></i>
</a>
</div>
<?php } ?>
<div class="bg-light p-3"><!--- bg-light p-3 Starts --->
<h4 class="pb-2">Add New Link</h4>
<form class="form-inline pb-3" method="post">
<input type="text" name="icon_class" placeholder="FontAwesome Icon Class Name" class="form-control mb-3 mr-sm-2 mb-sm-0" required="">
<input type="text" name="link_url" placeholder="Link Url" class="form-control mb-3 mr-sm-2 mb-sm-0" required="">
<button type="submit" class="btn btn-success form-control" name="add_link_follow">
<i class="fa fa-plus-circle"></i> Add Link
</button>
</form>
</div><!--- bg-light p-3 Ends --->
<?php
if(isset($_POST['add_link_follow'])){
$icon_class = $input->post("icon_class");
$link_url = $input->post("link_url");
$insert_link = $db->insert("footer_links",array("language_id"=>$adminLanguage,"icon_class"=>$icon_class,"link_url"=>$link_url,"link_section"=>'follow'));
if($insert_link){
echo "<script>window.open('index?layout_settings','_self');</script>";
}	
}
?>
</div><!--- follow tab-pane fade in Ends --->
</div><!--- tab-content Ends --->
</div><!--- col-md-9 Ends --->
</div><!--- card-body row Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->


<div class="row"><!--- 2 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card mb-5"><!--- card mb-5 Starts --->
<div class="card-header"><!--- card-header Starts --->
<h4 class="h4"><i class="fa fa-money fa-fw"></i> Announcement Bar Settings </h4>
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->
<form method="post" enctype="multipart/form-data"><!--- form Starts --->

<div class="form-group row"><!--- form-group row Starts --->
  <label class="col-md-3 control-label"> Enable Announcement Bar : </label>
  <div class="col-md-6">
    <select name="enable_bar" class="form-control">
      <option value="1"> Yes </option>
      <option value="0" <?= ($enable_bar==0)?'selected':''; ?>> No </option>
    </select>
    <small class="form-text text-muted">Enable or Disable Announcement Bar on the website.</small>
  </div>
</div><!--- form-group row Ends --->

<div class="form-group row"><!--- form-group row Starts --->
   <label class="col-md-3 control-label"> Background Color : </label>
   <div class="col-md-6">
      <input type="color" name="bg_color" class="form-control p-0 pl-1 pr-1" value="<?= $bg_color; ?>">
   </div>
</div><!--- form-group row Ends --->

<div class="form-group row"><!--- form-group row Starts --->
   <label class="col-md-3 control-label"> Text Color : </label>
   <div class="col-md-6">
      <input type="color" name="text_color" class="form-control p-0 pl-1 pr-1" value="<?= $text_color; ?>">
   </div>
</div><!--- form-group row Ends --->

<div class="form-group row"><!--- form-group row Starts --->
  <label class="col-md-3 control-label"> Announcement Bar Text : </label>
  <div class="col-md-6">
    <textarea name="bar_text" class="form-control" rows="6" cols="19"><?= $bar_text; ?></textarea>
    <small class="text-muted">
      You can get font awesome icon code from <a class="text-primary" href="https://fontawesome.com/v4.7.0/icons/" target="_blank">this link.</a> Example of code: <?= htmlspecialchars('<i class="fa fa-globe"></i>'); ?>
    </small>
  </div>
</div><!--- form-group row Ends --->

<div class="form-group row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"></label>
<div class="col-md-6">
<input type="submit" name="update_bar" class="btn btn-success form-control" value="Update Announcement Bar Settings">
</div>
</div><!--- form-group row Ends --->

</form><!--- form Ends --->
</div><!--- card-body Ends --->
</div><!--- card mb-5 Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 2 row Ends --->

<?php

require_once("includes/removeJava.php");

if(isset($_POST['update_bar'])){

  $data = $input->post();

  $data['bar_text'] = removeJava($_POST['bar_text']);

  $data['bar_text'] = str_replace(
    array("<p>","</p>"),
    array("",""),
    $data['bar_text']
  );

  $data['last_updated'] = time();

  unset($data['update_bar']);
    
  if($count_bar > 0){
    $update_bar = $db->update("announcement_bar",$data,['language_id'=>$adminLanguage]);
  }else{
    $data['language_id'] = $adminLanguage;
    $update_bar = $db->insert("announcement_bar",$data);
  }
  if($update_bar){
    echo "
    <script>alert_success('Announcement Bar Settings Has Been Updated Successfully.','index?layout_settings')</script>";
  }

}

?>

<div class="row"><!--- 4 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card"><!--- card Starts --->
<div class="card-header"><!--- card-header Starts --->
<h4 class="h4">
<i class="fa fa-home fa-fw"></i>  Home Page Section
<small class="text-muted">
This section is located on the cover photo(s) on landing page
</small>
</h4>
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->
<form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->
<div class="form-group row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Section Heading : </label>
<div class="col-md-6">
<input type="text" name="section_heading" class="form-control" value="<?= $db_section_heading; ?>" required="">
</div>
</div><!--- form-group row Ends --->
<div class="form-group row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"> Section Short Heading : </label>
<div class="col-md-6">
<input type="text" name="section_short_heading" class="form-control" value="<?= $db_section_short_heading; ?>" required="">
</div>
</div><!--- form-group row Ends --->
<div class="form-group row"><!--- form-group row Starts --->
<label class="col-md-3 control-label"></label>
<div class="col-md-6">
<input type="submit" name="update_section" class="form-control btn btn-success" value="Update Section">
</div>
</div><!--- form-group row Ends --->
</form><!--- form Ends --->
</div><!--- card-body Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 4 row Ends --->
<div class="row pt-3"><!--- 3 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card mb-5"><!--- card mb-5 Starts --->
<div class="card-header"><!--- card-header Starts --->
<h4 class="h4">
<i class="fa fa-cubes fa-fw"></i> View Landing Page Slides
<small class="text-muted">
This slides are located on the landing page (signed out page)
</small>
</h4>
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->
<a class="btn btn-success btn-lg float-right" href="index?insert_home_slide">
<i class="fa fa-plus-circle"></i> Add New Box
</a>
<div class="clearfix mb-4"></div>
<div class="row"><!-- row Starts --->
<?php
$get_slides = $db->select("home_section_slider");
while($row_slides = $get_slides->fetch()){
$slide_id = $row_slides->slide_id;
$slide_name = $row_slides->slide_name;
$slide_image = $row_slides->slide_image;
$slide_image = getImageUrl("home_section_slider",$row_slides->slide_image);
$s_extension = pathinfo($slide_image, PATHINFO_EXTENSION);
?>
<div class="col-lg-4 col-md-6 mb-lg-3 mb-3"><!--- col-lg-3 col-md-6 mb-lg-0 mb-3 Starts --->
<div class="card"><!--- card Starts --->
<div class="card-header text-center">
<?= $slide_name; ?>
</div>
<div class="card-body p-1"><!--- card-body Starts --->

 <?php if($s_extension == "mp4" or $s_extension == "webm" or $s_extension == "ogg"){ ?>
   <video class="img-fluid" controls>
     <source src="<?= $slide_image; ?>" type="video/mp4">
   </video>
 <?php }else{ ?>
   <img src="<?= $slide_image; ?>" style="height: 150px; min-width: 100%;" class="img-fluid">
 <?php } ?>

</div><!--- card-body Ends --->
<div class="card-footer"><!--- card-footer Starts --->
<a href="index?delete_home_slide=<?= $slide_id; ?>" class="float-left btn btn-danger" title="Delete">
<i class="fa fa-trash text-white"></i> 
</a>
<a href="index?edit_home_slide=<?= $slide_id; ?>" class="float-right btn btn-success" title="edit">
<i class="fa fa-pencil text-white"></i> 
</a>
<div class="clearfix"></div>
</div><!--- card-footer Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-3 col-md-6 mb-lg-0 mb-3 Ends --->
<?php } ?>
</div><!-- row Ends --->
</div><!--- card-body Ends --->
</div><!--- card mb-5 Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 3 row Ends --->
<div class="row pt-3"><!--- 3 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card mb-5"><!--- card mb-5 Starts --->
<div class="card-header"><!--- card-header Starts --->
<h4 class="h4">
<i class="fa fa-cubes fa-fw"></i> View Cards
<small class="text-muted">
These boxes are located on the landing page, just below slider.
</small>
</h4>
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->
<a class="btn btn-success btn-lg float-right" href="index?insert_card">
<i class="fa fa-plus-circle"></i> Add New Card
</a>
<div class="clearfix mb-4"></div>
<div class="row"><!-- row Starts --->
<?php
$get_cards = $db->select("home_cards",array("language_id" => $adminLanguage));
while($row_cards = $get_cards->fetch()){
$card_id = $row_cards->card_id;
$card_title = $row_cards->card_title;
$card_desc = $row_cards->card_desc;
?>
<div class="col-lg-4 col-md-6 mb-lg-0 mb-3"><!--- col-lg-4 col-md-6 mb-lg-0 mb-3 Starts --->
<div class="card mb-3"><!--- card Starts --->
<div class="card-header text-center"><!--- card-header text-center Starts --->
<h4 class="h4"> <?= $card_title; ?> </h4>
</div><!--- card-header text-center Ends --->
<div class="card-body"><!--- card-body Starts --->
<p><?= $card_desc; ?></p>
</div><!--- card-body Ends --->
<div class="card-footer"><!--- card-footer Starts --->
<a href="index?delete_card=<?= $card_id; ?>" class="float-left">
<i class="fa fa-trash-alt"></i> Delete
</a>
<a href="index?edit_card=<?= $card_id; ?>" class="float-right">
<i class="fa fa-pencil-alt"></i> Edit
</a>
<div class="clearfix"> </div>
</div><!--- card-footer Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-4 col-md-6 mb-lg-0 mb-3 Ends --->
<?php } ?>
</div><!-- row Ends --->
</div><!--- card-body Ends --->
</div><!--- card mb-5 Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 4 row Ends --->
<div class="row pt-3"><!--- 3 row Starts --->
<div class="col-lg-12"><!--- col-lg-12 Starts --->
<div class="card mb-5"><!--- card mb-5 Starts --->
<div class="card-header"><!--- card-header Starts --->
<h4 class="h4">
<i class="fa fa-cubes fa-fw"></i> View Boxes
<small class="text-muted">
These boxes are located on the landing page, just below featured categories.
</small>
</h4>
</div><!--- card-header Ends --->
<div class="card-body"><!--- card-body Starts --->
<a class="btn btn-success btn-lg float-right" href="index?insert_box">
<i class="fa fa-plus-circle"></i> Add New Box
</a>
<div class="clearfix mb-4"></div>
<div class="row"><!-- row Starts --->
<?php
$get_boxes = $db->select("section_boxes",array("language_id" => $adminLanguage));
while($row_boxes = $get_boxes->fetch()){
$box_id = $row_boxes->box_id;
$box_title = $row_boxes->box_title;
$box_desc = $row_boxes->box_desc;
?>
<div class="col-lg-4 col-md-6 mb-lg-0 mb-3"><!--- col-lg-4 col-md-6 mb-lg-0 mb-3 Starts --->
<div class="card mb-3"><!--- card Starts --->
<div class="card-header text-center"><!--- card-header text-center Starts --->
<h4 class="h4">
<?= $box_title; ?>
</h4>
</div><!--- card-header text-center Ends --->
<div class="card-body"><!--- card-body Starts --->
<p><?= $box_desc; ?></p>
</div><!--- card-body Ends --->
<div class="card-footer"><!--- card-footer Starts --->
<a href="index?delete_box=<?= $box_id; ?>" class="float-left">
<i class="fa fa-trash-alt"></i> Delete
</a>
<a href="index?edit_box=<?= $box_id; ?>" class="float-right">
<i class="fa fa-pencil-alt"></i> Edit
</a>
<div class="clearfix"> </div>
</div><!--- card-footer Ends --->
</div><!--- card Ends --->
</div><!--- col-lg-4 col-md-6 mb-lg-0 mb-3 Ends --->
<?php } ?>
</div><!-- row Ends --->
</div><!--- card-body Ends --->
</div><!--- card mb-5 Ends --->
</div><!--- col-lg-12 Ends --->
</div><!--- 3 row Ends --->
<?php
if(isset($_POST['update_section'])){
$section_heading = $input->post('section_heading');
$section_short_heading = $input->post('section_short_heading');
if($count_section == 1){
$update_section = $db->update("home_section",array("section_heading" => $section_heading,"section_short_heading" => $section_short_heading),array("language_id" => $adminLanguage));
}else{
$update_section = $db->insert("home_section",array("language_id" => $adminLanguage,"section_heading" => $section_heading,"section_short_heading" => $section_short_heading));
}
if($update_section){
$insert_log = $db->insert_log($admin_id,"home_section","","updated");
echo "<script>alert('Home Page Section Has Been Updated Successfully.');</script>";
echo "<script>window.open('index?layout_settings','_self')</script>";
}
}
?>
<?php
$css_file = "../styles/custom.css";
if(file_exists($css_file)){
$css_file_data = file_get_contents($css_file);
}
?>
<div class="row mt-4">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="h4"><i class="fa fa-css3"></i> Custom Css </h4>
      </div>
      <div class="card-body">
        <p class="lead"> Enter Your Custom Css Code Here. </p>
        <form action="" method="post">
          <div class="form-group row">
            <div class="col-md-12">
              <textarea name="custom_css" class="form-control" rows="23"><?= $css_file_data; ?></textarea>
            </div>
          </div>
          <div class="form-group row">
            <!--- form-group row Starts --->
            <label class="col-md-3"></label>
            <div class="col-md-9">
              <input type="submit" name="save_changes" value="Save Changes" class="btn btn-success float-right">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
if(isset($_POST['save_changes'])){
	$newData = htmlspecialchars($_POST['custom_css'],ENT_NOQUOTES);
	$handle = fopen($css_file, "w");
	fwrite($handle, $newData);
	fclose($handle);
	if($db->insert_log($admin_id,"custom_css","","updated")){
		echo "<script>
	  swal({
		  type: 'success',
		  text: 'Css code updated!',
		  timer: 4000,
		  onOpen: function(){
		  	swal.showLoading();
		  }
	  }).then(function(){
	    window.open('index?layout_settings','_self');
	  });
		</script>";
	}
}
?>
</div>

<?php } ?>