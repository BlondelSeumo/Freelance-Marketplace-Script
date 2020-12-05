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
                        <h1><i class="menu-icon fa fa-cubes"></i> Languages </h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active"><a href="index?insert_language" class="btn btn-success">

                                            <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Language</span>

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

                        <i class="fa fa-money-bill-alt"></i> View All Languages

                    </h4>

                </div>
                <!--- card-header Ends --->

                <div class="card-body">
                    <!--- card-body Starts --->

				<table class="table table-bordered table-striped"><!--- table table-bordered table-striped Starts -->

				<thead>
				<tr>

				<th>No</th>
				<th>Title</th>
				<th>Actions:</th>

				</tr>
				</thead>

				<tbody>
				<?php

				$i = 0;
                
                $get_languages = $db->select("languages","");

                while($row_languages = $get_languages->fetch()){

                $id = $row_languages->id;

                $title = $row_languages->title;

				$i++;

				?>

				<tr>

				<td><?= $i; ?></td>

				<td width="200"><?= $title; ?></td>

				<td>
				<a class="btn text-white btn-success" href="index?language_settings=<?= $id; ?>" >
					<i class="fa fa-fw fa-cog"></i> Settings
				</a>

				<a class="btn text-white btn-primary" href="index?edit_language=<?= $id; ?>" >
					<i class="fa fa-trash-alt"></i> Edit
				</a>

                <?php if($title != "English"){ ?>

				<a class="btn text-white btn-danger" href="index?delete_language=<?= $id; ?>" onclick="if(!confirm('Are you sure you want to delete selected item.')){ return false; }">

				<i class="fa fa-trash-alt"></i> Delete

				</a>

                <?php }else{ ?>

                <button type="button" class="btn btn-danger" disabled>Delete</button>

                <?php } ?>

				</td>

				</tr>
				<?php } ?>

				</tbody>

				</table><!--- table table-bordered table-hover table-striped Starts -->

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
