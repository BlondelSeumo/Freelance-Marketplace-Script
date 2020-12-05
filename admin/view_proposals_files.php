<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
    
echo "<script>window.open('login','_self');</script>";
    
}else{

$directory = "../proposals/proposal_files/";

$handle = opendir($directory);

$restircted = array(".","..","Thumbs.db");

while($file = readdir($handle)){
    
if(!in_array($file, $restircted)){
    
$files_array[] = $file;
    
}
    
}

$count = 0;

$limit = 12;

if(isset($_GET['view_proposals_files'])){
    
$page = $_GET['view_proposals_files'];
    
if($page == 0){ $page = 1; }

}else{
    
$page = 1;
    
}

/// Page will start from 0 and multiply by per page

$start = ($page - 1) * $limit;

?>

    <div class="breadcrumbs">

        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1><i class="menu-icon fa fa-file"></i> Listing Files</h1>
                </div>
            </div>
        </div>

        <div class="col-sm-8">
            
            <div class="page-header float-right">
             
            <select id="fileSelect" class="form-control mt-2">

               <option data-url="index?view_proposals_files" selected="">Local Server</option>
               <option data-url="index?view_s3_proposals_files">AWS S3</option>
            
            </select>

            </div>
            
        </div>

    </div>


   <div class="container">
    
   <div class="row"><!--- 2 row Starts --->

    <?php

    for($i = $start; $i < count($files_array); $i++){

    $count ++;

    ?>

        <div class="col-lg-3 col-md-4 col-sm-6 mb-3"><!--- col-lg-3 col-md-4 col-sm-6 mb-3 Starts --->

            <div class="card"><!--- card Starts --->

                <div class="card-header"><!--- card-header Starts --->

                    <?= $files_array[$i]; ?>

                </div><!--- card-header Ends --->

                <div class="card-body text-center"><!--- card-body text-center Starts --->

                <?php

                if(exif_imagetype('../proposals/proposal_files/' . $files_array[$i]) == IMAGETYPE_JPEG or  exif_imagetype('../proposals/proposal_files/' . $files_array[$i]) == IMAGETYPE_PNG or exif_imagetype('../proposals/proposal_files/' . $files_array[$i]) == IMAGETYPE_GIF){

                ?>

                <img src="../proposals/proposal_files/<?= $files_array[$i]; ?>" class="img-fluid">

                <?php }else{ ?>

                <i class="fa fa-file fa-5x"></i>

                <?php } ?>

                </div>
                <!--- card-body text-center Ends --->

                <div class="card-footer"><!--- card-footer Starts --->


                <a href="../proposals/proposal_files/<?= $files_array[$i]; ?>" download class="float-left">

                <i class="fa fa-download"></i> Download

                </a>

                <a href="index?delete_proposal_file=<?= $files_array[$i]; ?>" class="float-right">

                <i class="fa fa-trash-alt"></i> Delete

                </a>

                </div><!--- card-footer Ends --->

            </div><!--- card Ends --->

        </div><!--- col-lg-3 col-md-4 col-sm-6 mb-3 Ends --->

        <?php if($limit == $count){ break; } ?>

         <?php } ?>

      </div><!--- 2 row Ends --->

      <div class="row"><!--- 3 row Starts --->

       <div class="col-lg-12"><!--- col-lg-12 Starts --->

           <ul class="pagination d-flex justify-content-center"><!--- pagination d-flex justify-content-center Starts --->

           <?php

            /// Using ceil function to divide the total records on per page

            $total_pages = ceil(count($files_array) / $limit);

            echo "<li class='page-item'><a href='index?view_proposals_files=1' class='page-link'> First Page </a></li>";

            echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?view_proposals_files=1'>1</a></li>";

            $i = max(2, $page - 4);

            if ($i > 2)

               echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";

            for (; $i < min($page + 15, $total_pages); $i++) {
                        
               echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?view_proposals_files=".$i."' class='page-link'>".$i."</a></li>";

            }
            if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}

            if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?view_proposals_files=$total_pages'>$total_pages</a></li>";}

            echo "<li class='page-item'><a href='index?view_proposals_files=$total_pages' class='page-link'>Last Page </a></li>";

           ?>

         </ul><!--- pagination d-flex justify-content-center Ends --->

      </div><!--- col-lg-12 Ends --->

   </div><!--- 3 row Ends --->

</div>

<script>
$(document).ready(function(){

    $("#fileSelect").change(function(){
        var url = $("#fileSelect option:selected").data("url");
        window.location.href = url;
    });
});
</script>

<?php } ?>