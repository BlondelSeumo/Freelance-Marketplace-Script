<?php

@session_start();

if(!isset($_SESSION['admin_email'])){
	
echo "<script>window.open('login','_self');</script>";
	
}else{
    
?>


<script src="../js/jquery.min.js"></script>

<div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1><i class="menu-icon fa fa-gift"></i> Coupon Code / Insert</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li class="active">Add Coupon</li>
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


        <div class="card">
            <!--- card Starts --->

            <div class="card-header">
                <!--- card-header Starts --->

                <h4 class="h4">Add New Coupon</h4>

            </div>
            <!--- card-header Ends --->

            <div class="card-body">
                <!--- card-body Starts --->

                <form action="" method="post">
                    <!--- form Starts --->

                    <div class="form-group row">
                        <!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Coupon Title : </label>

                        <div class="col-md-6">

                            <input type="text" name="coupon_title" class="form-control" required>

                        </div>

                    </div>
                    <!--- form-group row Ends --->


                    <div class="form-group row">
                        <!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Coupon Price : </label>

                        <div class="col-md-3">

                            <select name="coupon_type" class="coupon-type form-control" required>
							
							<option value="fixed_price"> Fixed Price </option>
							
							<option value="discount_price"> Discount Percentage </option>
							
							</select>
							
                        </div>
						
						<div class="col-md-3">
						
					   <div class="input-group">
											
						<span class="input-group-addon">

						 <b><?= $s_currency; ?></b>

						</span>

                            <input type="number" name="coupon_price" class="form-control" value="1" min="1" required>

						</div>	
							
                        </div>

                    </div>
                    <!--- form-group row Ends --->


                    <div class="form-group row">
                        <!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Coupon Code : </label>

                        <div class="col-md-6">

                            <input type="text" name="coupon_code" class="form-control" required>

                        </div>

                    </div>
                    <!--- form-group row Ends --->


                    <div class="form-group row">
                        <!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Coupon Limit : </label>

                        <div class="col-md-6">

                            <input type="number" name="coupon_limit" class="form-control" value="1" min="1" required>

                        </div>

                    </div>
                    <!--- form-group row Ends --->



                    <div class="form-group row">
                        <!--- form-group row Starts --->

                        <label class="col-md-3 control-label"> Select Proposal/Service: </label>

                        <div class="col-md-6">

                            <select name="proposal_id" class="form-control" required>

                                <option value=""> Select A Proposal/Service to Apply Coupon </option>

                                <?php 

                                $get_proposals = $db->select("proposals",array("proposal_status"=>'active'));

                                while($row_proposals = $get_proposals->fetch()){

                                $proposal_id = $row_proposals->proposal_id;

                                $proposal_title = $row_proposals->proposal_title;

                                echo "<option value='$proposal_id'>$proposal_title</option>";

                                }

                                ?>

                          </select>

                        </div>

                    </div>
                    <!--- form-group row Ends --->


                    <div class="form-group row">
                        <!--- form-group row Starts --->

                        <label class="col-md-3 control-label"></label>

                        <div class="col-md-6">

                            <input type="submit" name="submit" class="btn btn-success form-control" value="Create Coupon">

                        </div>

                    </div>
                    <!--- form-group row Ends --->


                </form>
                <!--- form Ends --->

            </div>
            <!--- card-body Ends --->

        </div>
        <!--- card Ends --->

    </div>
    <!--- col-lg-12 Ends --->

</div>
<!--- 2 row Ends --->
		
 <script>
	
  $(document).ready(function(){
		
  $('.coupon-type').change(function(){
	  
  if($(this).val() == 'fixed_price'){ 
  
  $('.input-group-addon b').html('$');
	
  }
  
  if($(this).val() == 'discount_price'){ 
  
  $('.input-group-addon b').html('%');
	
  }
  
  });
		
  });
	
  </script>
	
<?php

if(isset($_POST['submit'])){

$rules = array(
"coupon_title" => "required",
"coupon_price" => "number|required",
"coupon_type" => "required",
"coupon_code" => "required",
"coupon_limit" => "number|required",
"proposal_id" => "required");

$messages = array("proposal_id" => "You must need to select a proposal for coupon.");

$val = new Validator($_POST,$rules,$messages);

if($val->run() == false){

Flash::add("form_errors",$val->get_all_errors());

Flash::add("form_data",$_POST);

echo "<script> window.open('index?insert_coupon','_self');</script>";

}else{


$coupon_code = $input->post('coupon_code');

$data = $input->post();

unset($data['submit']);
		
$check_coupons = $db->count("coupons",array("coupon_code" => $coupon_code));

if($check_coupons == 1){
	
echo "<script>alert('Coupon Code Has Been Applied Already.');</script>";
	
}else{

$insert_coupon = $db->insert("coupons",$data);
		
if($insert_coupon){
	
$insert_id = $db->lastInsertId();

$insert_log = $db->insert_log($admin_id,"coupon",$insert_id,"inserted");
    
echo "<script>alert('Coupon code created successfully.');</script>";
	
echo "<script>window.open('index?view_coupons','_self');</script>";
	
}	

}

}
	
}

?>

</div>

<?php } ?>