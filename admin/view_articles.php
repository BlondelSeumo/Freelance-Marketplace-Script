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
                        <h1><i class="menu-icon fa fa-book"></i> Knowledge Bank</h1>
                    </div>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                             <li class="active"><a href="index?insert_article" class="btn btn-success">

                                            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add New Article</span>

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

                    <h4 class="h4">

                       All Articles

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!---  card-body Starts --->

                    <table class="table table-bordered">

                      <thead>

                        <tr>

                          <th>Article's Heading</th>
                          <th>Article's Category</th>
                          <th>Article's Right Image</th>
                          <th>Article's ImageTop</th>
                          <th>Article's ImageBottom</th>
                          <th>Article's Status</th>
                          <th>Article's Action</th>


                        </tr>


                      </thead>

					<tbody>
					  
		<?php 
		
        $get_articles = $db->select("knowledge_bank",array("language_id" => $adminLanguage),"DESC");

        while($row_articles = $get_articles->fetch()){

        $article_id = $row_articles->article_id;
        
        $cat_id = $row_articles->cat_id;
        
        $article_url = $row_articles->article_url;

        $article_heading = $row_articles->article_heading;
        
        $right_image = $row_articles->right_image;
        
        $top_image = $row_articles->top_image;
        
        $bottom_image = $row_articles->bottom_image;
        
        $article_status = $row_articles->article_status;
        
        
        $get_categories = $db->select("article_cat",array("article_cat_id" => $cat_id));

        $row_categories = $get_categories->fetch();

        $article_cat_title = $row_categories->article_cat_title;

		?>
		
		<tr>
		
		<td><?= $article_heading; ?></td> 
		<td><?= $article_cat_title; ?></td> 
		<td><?= $right_image; ?></td> 
		<td><?= $top_image; ?></td> 
		<td><?= $bottom_image; ?></td> 
		<td><?= $article_status; ?></td>
		
		<td>
        		
        <a href="index?edit_article=<?= $article_id; ?>" class="text-success">

        Edit 

        </a> |

        <a href="index?delete_article=<?= $article_id; ?>" class="text-success">

        Delete

        </a>

		</td> 

		</tr>

        <?php } ?>					 
                    </tbody>

                    </table>


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
