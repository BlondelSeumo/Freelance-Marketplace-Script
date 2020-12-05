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
      <h1><i class="menu-icon fa fa-picture-o"></i> Slides</h1>
    </div>
  </div>
  </div>
  <div class="col-sm-8">
  <div class="page-header float-right">
    <div class="page-title">
      <ol class="breadcrumb text-right">
        <li class="active">
          <a href="index?insert_slide" class="btn btn-success">
            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add New Slide</span>
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
    View Slides <br><small>These slides will show up in logged in homepage</small>
  </h4>
</div>
<!--- card-header Ends --->

<div class="card-body">
    <!--- card-body Starts --->

    <div class="row">
        <!--- row Starts --->

        <?php

        $get_slides = $db->select("slider",array("language_id" => $adminLanguage));
        while($row_slides = $get_slides->fetch()){
        $slide_id = $row_slides->slide_id;
        $slide_name = $row_slides->slide_name;
        
        $slide_image = getImageUrl("slider",$row_slides->slide_image); 
        $s_extension = pathinfo($slide_image, PATHINFO_EXTENSION);

        ?>

            <div class="col-lg-4 col-md-6 mb-lg-3 mb-3">
                <!--- col-lg-3 col-md-6 mb-lg-0 mb-3 Starts --->

                <div class="card">
                    <!--- card Starts --->

                    <div class="card-header">

                        <h5 class="h5 text-center"><?= $slide_name; ?> </h5>

                    </div>

                    <div class="card-body"><!--- card-body Starts --->
                      
                      <?php if($s_extension == "mp4" or $s_extension == "webm" or $s_extension == "ogg"){ ?>
                        <video class="img-fluid" controls>
                          <source src="<?= $slide_image; ?>" type="video/mp4">
                        </video>
                      <?php }else{ ?>
                        <img src="<?= $slide_image; ?>" class="img-fluid">
                      <?php } ?>

                    </div><!--- card-body Ends --->

                    <div class="card-footer"><!--- card-footer Starts --->

                        <a href="index?delete_slide=<?= $slide_id; ?>" class="float-left btn btn-danger" title="Delete">

                            <i class="fa fa-trash text-white"></i> 

                        </a>

                        <a href="index?edit_slide=<?= $slide_id; ?>" class="float-right btn btn-success" title="edit">

                            <i class="fa fa-pencil text-white"></i> 

                        </a>

                        <div class="clearfix"></div>

                    </div><!--- card-footer Ends --->

                </div><!--- card Ends --->

            </div><!--- col-lg-3 col-md-6 mb-lg-0 mb-3 Ends --->

            <?php } ?>

    </div><!--- row Ends --->

</div><!--- card-body Ends --->

</div><!--- card Ends --->

</div><!--- col-lg-12 Ends --->

</div><!--- 2 row Ends --->

</div>

<?php } ?>