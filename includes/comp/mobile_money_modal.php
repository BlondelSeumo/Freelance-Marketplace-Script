<?php if($dusupay_method == "MOBILE_MONEY"){ ?>

<div id="mobile-payment-modal" class="modal fade" style="overflow-y:scroll;z-index:5051;"><!--- payment-modal Starts --->
   <div class="modal-dialog"><!--- modal-dialog Starts --->
      <div class="modal-content"><!--- modal-content Starts --->

         <div class="modal-header"><!-- modal-header Starts -->
            
            <h5 class="modal-title"> 
               <span class="float-left">Mobile Money  </span>
            </h5>

            <button class="closeExtendTimePayment close" data-dismiss="modal">
               <span>&times;</span>
            </button>

         </div><!-- modal-header Ends -->

         <div class="modal-body"><!--- modal-body Starts --->
         
            <form method="post" action="<?= $form_action; ?>" <?= (isset($extendTimePayment))?"target='_blank'":""; ?>>
            
               <div class="form-group">
                  <label>Mobile Money Account Number</label>
                  <input type="text" name="account_number" placeholder="Enter Your Mobile Money Account Number" class="form-control" required=""/>
               </div>
               
               <?php if($dusupay_provider_id == "vodafone_gh"){ ?>
                  <div class="form-group">
                     <label>Mobile Money Voucher</label>
                     <input type="text" name="voucher" placeholder="Enter Your Mobile Money Voucher" class="form-control" required=""/>
                  </div>
               <?php } ?>

               <div class="form-group mb-0">
                  <input type="submit" name="dusupay" value="<?= $lang['button']['pay_with_dusupay']; ?>" class="btn btn-success">
               </div>

            </form>

         </div><!--- modal-body Ends --->
      
      </div><!--- modal-content Ends --->
   </div><!--- modal-dialog Ends --->
</div><!--- payment-modal Ends --->

<?php } ?>