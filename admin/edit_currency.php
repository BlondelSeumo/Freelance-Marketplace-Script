<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
   
echo "<script>window.open('login','_self');</script>";
   
}else{

if(isset($_GET['edit_currency'])){
      
  $edit_id = $input->get('edit_currency');

  $edit_currency = $db->select("site_currencies",array('id' => $edit_id));
  if($edit_currency->rowCount() == 0){
   echo "<script>window.open('index?dashboard','_self');</script>";
  }

  $row_edit = $edit_currency->fetch();
  $id = $row_edit->id;
  $currency_id = $row_edit->currency_id;
  $position = $row_edit->position;
  $code = $row_edit->code;
  $format = $row_edit->format;
  $rate = $row_edit->rate;

}  
   
?>

<div class="breadcrumbs">
   <div class="col-sm-4">
      <div class="page-header float-left">
        <div class="page-title">
            <h1><i class="menu-icon fa fa-cubes"></i> Site Currencies </h1>
        </div>
      </div>
   </div>
   <div class="col-sm-8">
      <div class="page-header float-right">
        <div class="page-title">
            <ol class="breadcrumb text-right">
                <li class="active"> Edit Currency</li>
            </ol>
        </div>
      </div>
   </div>
</div>


<div class="container"><!--- container Starts --->

   <div class="row"><!--- 2 row Starts --->

      <div class="col-lg-12"><!--- col-lg-12 Starts --->

         <?php 

         $form_errors = Flash::render("form_errors");
         $form_data = Flash::render("form_data");
         
         if(is_array($form_errors)){

         ?>

         <div class="alert alert-danger"><!--- alert alert-danger Starts --->
            <ul class="list-unstyled mb-0">
               <?php $i = 0; foreach ($form_errors as $error) { $i++; ?>
                 <li class="list-unstyled-item"><?= $i ?>. <?= ucfirst($error); ?></li>
               <?php } ?>
            </ul>
         </div><!--- alert alert-danger Ends --->

         <?php } ?>

         <div class="card"><!--- card Starts --->

            <div class="card-header"><!--- card-header Starts --->

              <h4 class="h4">Edit Currency</h4>

            </div><!--- card-header Ends --->

            <div class="card-body"><!--- card-body Starts --->

               <form action="" method="post" enctype="multipart/form-data"><!--- form Starts --->


                  <div class="form-group row"><!--- form-group row Starts --->
                     <label class="col-md-3 control-label"> Select Currency : </label>
                     <div class="col-md-6">
                        <select name="currency_id" class="form-control" required="">
                        <?php 
                        $get_currencies = $db->select("currencies");
                        while($row_currencies = $get_currencies->fetch()){
                        $id = $row_currencies->id;
                        $name = $row_currencies->name;
                        $symbol = $row_currencies->symbol;
                        ?>
                        <option <?php if($currency_id == $id){ echo "selected"; } ?> value="<?= $id; ?>"> 
                           <?= $name . " ($symbol)"; ?> 
                        </option>
                        <?php } ?>
                        </select>
                        <small class="form-text text-muted">Select Currency symbol.</small>
                     </div>
                  </div><!--- form-group row Ends --->


                  <div class="form-group row" required=""><!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Currency Symbol Position : </label>
                    <div class="col-md-6">
                      <select name="position" class="form-control">
                        <?php if($position == "left"){ ?>
                          <option value="left"> Left </option>
                          <option value="right"> Right </option>
                        <?php }elseif($position == "right"){ ?>
                          <option value="right"> Right </option>
                          <option value="left"> Left </option>
                        <?php } ?>
                      </select>
                      <small class="form-text text-muted">Enable or disable referrals on the website.</small>
                    </div>
                  </div><!--- form-group row Ends --->


                  <div class="form-group row"><!--- form-group row Starts --->

                     <label class="col-md-3 control-label">Currency Code : </label>

                     <div class="col-md-6">

                        <input type="text" name="code" class="form-control" placeholder="Currency ISO Code" value="<?= $code; ?>" required>
                        <small class="text-muted"> 
                          You Can Find Iso Currency Codes
                          <a class="text-primary" href="https://www2.1010data.com/documentationcenter/prod/1010dataReferenceManual/DataTypesAndFormats/currencyUnitCodes.html" target="_blank">Here</a>. For example, USA Currency ISO Code Is : USD 
                        </small>
                     </div>

                  </div><!--- form-group row Ends --->


                  <div class="form-group row" required=""><!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Currency Format : </label>
                    <div class="col-md-6">
                      <select name="format" class="form-control">
                        <?php if($format == "us"){ ?>
                          <option value="us"> 1,234,567.89 </option>
                          <option value="european"> 1.234.567,89 </option>
                        <?php }elseif($format == "european"){ ?>
                          <option value="european"> 1.234.567,89 </option>
                          <option value="us"> 1,234,567.89 </option>
                        <?php } ?>
                      </select>
                      <small class="form-text text-muted">Thousands Separator.</small>
                    </div>
                  </div><!--- form-group row Ends --->

                  <div class="form-group row d-none" required=""><!--- form-group row Starts --->
                    <label class="col-md-3 control-label"> Exchange Rate : </label>
                    <div class="col-md-6">
                      <input type="text" class="form-control" name="rate" placeholder="Write Currency Conversion Rate" value="<?= $rate; ?>" />
                      <small class="form-text text-muted">1 <?= "$s_currency_name ($s_currency)"; ?> Equals To</small>
                    </div>
                  </div><!--- form-group row Ends --->


                  <div class="form-group row"><!--- form-group row Starts --->

                     <label class="col-md-3 control-label"></label>

                     <div class="col-md-6">

                        <input type="submit" name="update" class="btn btn-success form-control" value="Update Currency">

                     </div>

                  </div><!--- form-group row Ends --->

               </form><!--- form Ends --->

            </div><!--- card-body Ends --->

         </div><!--- card Ends --->

      </div><!--- col-lg-12 Ends --->

   </div><!--- 2 row Ends --->

</div><!--- container row Ends --->

<?php

if(isset($_POST['update'])){

   $data = $input->post();
   unset($data['update']);

   $update = $db->update("site_currencies",$data,array("id" => $edit_id));

   if($update){

      $insert_log = $db->insert_log($admin_id,"site_currency",$edit_id,"updated");
      echo "<script>alert_success('One Currency Has Been Updated.','index?view_currencies');</script>";

   }

}
    
?>

<?php } ?>