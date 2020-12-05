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
        <h1><i class="menu-icon fa fa-table"></i> Site Currencies</h1>
      </div>
    </div>
  </div>
  <div class="col-sm-8">
    <div class="page-header float-right">
       <div class="page-title">
        <ol class="breadcrumb text-right">
          <li class="active">
             <a href="index?insert_currency" class="btn btn-success">
              <i class="fa fa-plus-circle text-white"></i> <span class="text-white">Add Currency</span>
             </a>
          </li>
        </ol>
       </div>
    </div>
  </div>
</div>


<div class="container"><!--- container Starts --->

   <div class="row"><!--- 2 row Starts --->

      <div class="col-lg-12"><!--- col-lg-12 Starts --->
        
        <div class="alert alert-info mt-3">
          <i class="fa fa-info-circle"></i> Make sure to configure the currency converter in <a href="index?api_settings" class="text-success">Settings/Api Settings page.</a>
        </div>

         <div class="card"><!--- card Starts --->

            <div class="card-header"><!--- card-header Starts --->

              <h4 class="h4">

                  <i class="fa fa-money"></i> View All Currencies

              </h4>

            </div><!--- card-header Ends --->

            <div class="card-body"><!--- card-body Starts --->

               <table class="table table-bordered table-striped"><!--- table table-bordered table-striped Starts -->

               <thead>
               <tr>

               <th>No</th>
               <th>Name</th>
               <th>Code</th>
               <th>Symbol</th>
               <th>Symbol Position</th>
               <th>Actions:</th>

               </tr>
               </thead>

               <tbody>
               <?php

               $i = 0;
                   
               $get_currencies = $db->select("site_currencies");
               while($row = $get_currencies->fetch()){
               $id = $row->id;
               $currency_id = $row->currency_id;
               $code = $row->code;
               $position = $row->position;

               $get_currency = $db->select("currencies",array("id" =>$currency_id));
               $row_currency = $get_currency->fetch();
               $name = $row_currency->name;
               $symbol = $row_currency->symbol;

               $i++;

               ?>

               <tr>

               <td><?= $i; ?></td>

               <td width="200"><?= $name; ?></td>
               <td width="200"><?= $code; ?></td>
               <td width="200"><?= $symbol; ?></td>
               <td width="200"><?= $position; ?></td>

               <td>
               <!-- <a class="btn text-white btn-success" href="index?language_settings=<?= $id; ?>" >
                  <i class="fa fa-fw fa-cog"></i> Settings
               </a> -->

               <a class="btn text-white btn-primary" href="index?edit_currency=<?= $id; ?>" >
                  <i class="fa fa-pencil"></i> Edit
               </a>

               <a class="btn text-white btn-danger" href="index?delete_currency=<?= $id; ?>" onclick="if(!confirm('Are you sure you want to delete selected item.')){ return false; }">
                  <i class="fa fa-trash"></i> Delete
               </a>

               </td>

               </tr>
               <?php } ?>

               </tbody>

               </table><!--- table table-bordered table-hover table-striped Starts -->

            </div><!--- card-body Ends --->

         </div><!--- card Ends --->

      </div><!--- col-lg-12 Ends --->

   </div><!--- 2 row Ends --->

</div><!--- container Ends --->

<?php } ?>
