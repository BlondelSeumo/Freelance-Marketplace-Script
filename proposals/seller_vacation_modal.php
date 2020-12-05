<?php

@session_start();

require_once("../includes/db.php");

if(!isset($_SESSION['seller_user_name'])){
	
echo "<script>window.open('../login','_self')</script>";
	
}

$login_seller_id = $input->post('seller_id');

?>

<div id="vacation-modal" class="modal fade"><!-- vacation-modal modal fade Starts -->

   <div class="modal-dialog"><!-- modal-dialog Starts -->

      <div class="modal-content"><!-- modal-content Starts -->

         <div class="modal-header"><!-- modal-header Starts -->

            <h5 class="modal-title"> <?= $lang['view_proposals']['vacation_mode']; ?> </h5>
            <button class="close" data-dismiss="modal"><span> &times; </span></button>

         </div><!-- modal-header Ends -->

         <div class="modal-body"><!-- modal-body p-0 Starts -->

            <form action="" method="post"><!--- form Starts --->
            	
               <div class="form-group mb-3"><!--- form-group Starts --->

                  <label> 
                     <?= $lang['label']['why']; ?> 
                     <span class="text-muted"><?= $lang['label']['optional']; ?></span> 
                  </label>

                  <select class="form-control float-right" name="seller_vacation_reason">

                     <option value="">Select</option>
                     <option>I'm going on vacation</option>
                     <option>I'm overbooked</option>
                     <option>Other</option>

                  </select>

               </div><!--- form-group Ends --->

               <br>

               <div class="form-group mt-3 mb-0"><!--- form-group Starts --->

                  <label> 
                     <?= $lang['label']['additional_information']; ?> 
                     <span class="text-muted"><?= $lang['label']['optional']; ?></span> 
                  </label>

                  <textarea name="seller_vacation_message" rows="4" class="form-control"></textarea>

               </div><!--- form-group Ends --->

            </form><!--- form Ends --->

         </div><!-- modal-body p-0 Ends -->

         <div class="modal-footer"><!--- modal-footer Starts --->

            <button id="activate" class="btn btn-success"><?= $lang['button']['activate']; ?></button>

         </div><!--- modal-footer Ends --->

      </div><!-- modal-content Ends -->

   </div><!-- modal-dialog Ends -->

</div><!-- vacation-modal modal fade Ends -->


<script>

$(document).ready(function(){
	
   $("#vacation-modal").modal('show');

   $(document).on('click','.close', function(){
      $("#turn_on_seller_vaction").attr({ class:'btn btn-lg btn-toggle' });
   });

   $(document).on('click','#activate', function(){
      seller_id = "<?= $login_seller_id; ?>";
      seller_vacation_reason = $("select[name='seller_vacation_reason']").val();
      seller_vacation_message = $("textarea[name='seller_vacation_message']").val();

      $.ajax({

      method:"POST",
      url: "seller_vacation",
      data: { seller_id: seller_id, seller_vacation_reason: seller_vacation_reason, seller_vacation_message: seller_vacation_message, turn_on: 'on' }

      }).done(function(data){

         $("#vacation-modal").modal('hide');
         $("#turn_on_seller_vaction").attr('id','turn_off_seller_vaction');
         
         swal({
            type: 'success',
            text: 'Vacation switched On.',
            padding: 40,
         });

      });

   });

});

</script>