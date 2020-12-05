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
            <h1><i class="menu-icon fa fa-cubes"></i> Categories</h1>
        </div>
    </div>
</div>

<div class="col-sm-8">
    <div class="page-header float-right">
        <div class="page-title">

            <ol class="breadcrumb text-right">
                 <li class="active"><a href="index?insert_child_cat" class="btn btn-success">

                <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Sub Category</span>

            </a></li>
            
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

        <h4 class="h4">All Sub Categories</h4>

    </div>
    <!--- card-header Ends --->

    <div class="card-body">
        <!---  card-body Starts --->

        <div class="table-responsive">
            <!--- table-responsive Starts --->

            <table class="table table-bordered">
                <!--- table table-bordered table-hover Starts --->

                <thead>
                    <!--- thead Starts --->

                    <tr>

                        <th>Sub Category Id</th>

                        <th>Sub Category Title</th>

                        <th>Sub Category Description</th>

                        <th>Parent Category Title</th>

                        <th>Delete Sub Category</th>

                        <th>Edit Sub Category</th>

                    </tr>

                </thead><!--- thead Ends --->

                <tbody><!--- tbody Starts --->

                    <?php

                        $per_page = 10;

                        if(isset($_GET['view_child_cats'])){
                            
                        $page = $input->get('view_child_cats');

                        if($page == 0){ $page = 1; }
                            
                        }else{
                            
                        $page = 1;
                            
                        }

                        $i = ($page*$per_page)-10;

                        /// Page will start from 0 and multiply by per page

                        $start_from = ($page-1) * $per_page;


                        $get_child_cats = $db->query("select * from categories_children order by 1 DESC LIMIT :limit OFFSET :offset","",array("limit"=>$per_page,"offset"=>$start_from));

                        while($row_child_cats = $get_child_cats->fetch()){

                        $child_id = $row_child_cats->child_id;

                        $child_parent_id = $row_child_cats->child_parent_id;

                        
                        $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $adminLanguage));

                        $row_meta = $get_meta->fetch();

                        $child_title = $row_meta->child_title;

                        $child_desc = $row_meta->child_desc;


                        $get_meta = $db->select("cats_meta",array("cat_id" => $child_parent_id, "language_id" => $adminLanguage));

                        $row_meta = $get_meta->fetch();

                        $cat_title = $row_meta->cat_title;

                        $i++;

                     ?>

                        <tr>

                            <td>
                                <?= $i; ?>
                            </td>

                            <td>
                                <?= $child_title; ?>
                            </td>

                            <td width="400">
                                <?= $child_desc; ?>
                            </td>

                            <td>
                                <?= $cat_title; ?>
                            </td>

                            <td>

                                <a href="index?delete_child_cat=<?= $child_id; ?>" onclick="return confirm('Do you really want to delete this sub category permanently.');" class="btn btn-danger">

                                    <i class="fa fa-trash text-white" ></i> <span class="text-white">Delete</span>

                                </a>

                            </td>


                            <td>

                                <a href="index?edit_child_cat=<?= $child_id; ?>" class="btn btn-success">

                                    <i class="fa fa-pencil text-white"></i> <span class="text-white">Edit Cat</span>

                                </a>

                            </td>


                        </tr>

                        <?php } ?>

                </tbody>
                <!--- tbody Ends --->

            </table>
            <!--- table table-bordered table-hover Ends --->

        </div>
        <!--- table-responsive Ends --->

        <div class="d-flex justify-content-center">
            <!--- d-flex justify-content-center Starts --->

            <ul class="pagination">
                <!--- pagination Starts --->

                <?php

                /// Now Select All Data From Table

                $query = $db->query("select * from categories_children order by 1 DESC");

                /// Count The Total Records 

                $total_records = $query->rowCount();

                /// Using ceil function to divide the total records on per page

                $total_pages = ceil($total_records / $per_page);


                echo "<li class='page-item'><a href='index?view_child_cats=1' class='page-link'>First Page</a></li>";

                echo "<li class='page-item ".(1 == $page ? "active" : "")."'><a class='page-link' href='index?view_child_cats=1'>1</a></li>";
                
                $i = max(2, $page - 5);
                
                if ($i > 2)
                
                    echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";
                
                for (; $i < min($page + 6, $total_pages); $i++) {
                            
                    echo "<li class='page-item"; if($i == $page){ echo " active "; } echo "'><a href='index?view_child_cats=".$i."' class='page-link'>".$i."</a></li>";

                }

                if ($i != $total_pages and $total_pages > 1){echo "<li class='page-item' href='#'><a class='page-link'>...</a></li>";}

                if($total_pages > 1){echo "<li class='page-item ".($total_pages == $page ? "active" : "")."'><a class='page-link' href='index?view_child_cats=$total_pages'>$total_pages</a></li>";}

                echo "<li class='page-item'><a href='index?view_child_cats=$total_pages' class='page-link'>Last Page </a></li>";

                ?>

            </ul>
            <!--- pagination Ends --->

        </div>
        <!--- d-flex justify-content-center Ends --->


    </div>
    <!--- card-body Ends --->

</div>
<!--- card Ends --->

</div>
<!--- col-lg-12 Ends --->

</div>
<!--- 2 row Ends --->


</div>


<?php } ?>