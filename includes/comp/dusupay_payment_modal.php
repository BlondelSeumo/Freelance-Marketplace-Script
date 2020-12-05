<div id="payment-modal-1" class="modal fade" style="overflow-y:scroll;z-index:5051;"><!--- payment-modal Starts --->
   <div class="modal-dialog"><!--- modal-dialog Starts --->

      <div class="modal-content"><!--- modal-content Starts --->

         <div class="modal-header"><!-- modal-header Starts -->
            
            <h5 class="modal-title"> 
               <span class="float-left">Pay With Dusupay</span>
            </h5>

            <button class="closeExtendTimePayment close" data-dismiss="modal">
               <span>&times;</span>
            </button>

         </div><!-- modal-header Ends -->

         <div class="modal-body"><!--- modal-body Starts --->
         
            <form method="post" action="" id="dusupay-1"><!--- form Starts --->
               
               <input type="hidden" name="action" value="<?= $form_action; ?>">

               <div class="form-group"><!--- form-group Starts --->
                  <label>Select Your Country </label>
                  <select name="country" class="form-control" required="">
                     <option value="UG">Uganda</option>
                     <option value="KE">Kenya</option>
                     <option value="RW">Rwanda</option>
                     <option value="BI">Burundi</option>
                     <option value="GH">Ghana</option>
                     <option value="CM">Cameroon</option>
                     <option value="ZA">South Africa</option>
                     <option value="NG">Nigeria</option>
                     <option value="ZM">Zambia</option>
                     <option value="CI">Ivory Coast</option>
                     <option value="SN">Senegal</option>
                     <option value="TZ">Tanzania</option>
                     <option value="US">U.S.A</option>
                     <option value="GB">United Kingdom</option>
                     <option value="EU">Europe</option>
                  </select>
               </div><!--- form-group Ends --->

               <div class="form-group"><!--- form-group Starts --->
                  <label> Payment Method </label>
                  <select name="method" class="form-control" required="">
                     <option value="MOBILE_MONEY"> Mobile Money </option>
                     <option value="CARD"> Card </option>
                     <option value="BANK"> Bank </option>
                     <option value="CRYPTO">Crypto</option>
                  </select>
               </div><!--- form-group Ends --->

               <hr>

               <div class="form-group mb-0 text-center"><!--- form-group Starts --->

                  <button class="btn btn-success" type="submit">Continue</button>

                  <!-- <input type="submit" name="dusupay" value="Contine" class="btn btn-success" /> -->
                  <!-- <button type="submit" id="contine" data-toggle="modal" data-dismiss="modal" data-target="#payment-modal-2" class="btn btn-success">Contine</button> -->

               </div><!--- form-group Ends --->

            </form><!--- form Ends --->

         </div><!--- modal-body Ends --->
      
      </div><!--- modal-content Ends --->
   </div><!--- modal-dialog Ends --->
</div><!--- payment-modal Ends --->


<div id="payment-modal-2" class="modal fade" style="overflow-y:scroll;z-index:5051;"><!--- payment-modal Starts ---->
   <div class="modal-dialog"><!--- modal-dialog Starts --->

   </div><!--- modal-dialog Ends --->
</div><!--- payment-modal Ends --->

<script>

$(document).ready(function(){
   
   $("#dusupay-1").submit(function(event){

      $("#wait").addClass("loader");
      event.preventDefault();

      var country = $("#dusupay-1 select[name='country']").val();
      var method = $("#dusupay-1 select[name='method']").val();

      $.ajax({
      
         method: "POST",
         url: "<?= $site_url; ?>/includes/comp/check_provider_ids",
         data: $('#dusupay-1').serialize()

      }).done(function(data){

         console.log(data);

         if(data == "success"){

            $.ajax({
               method: "POST",   
               url: "<?= $site_url; ?>/includes/comp/dusupay_payment_modal_2",
               data: $('#dusupay-1').serialize()
            }).done(function(data){
               $("#wait").removeClass("loader");
               $("#payment-modal-1").modal('hide');
               $("#payment-modal-2").modal('show');
               $("#payment-modal-2 .modal-dialog").html(data);
            });

         }else{
            $("#wait").removeClass("loader");

            if(data == "There are no options found for "+method+" collections in "+country){
               alert("Your Selected Country Does Not Support This Payment Method Please Select Another One.")
            }else{
               alert(data);
            }

            console.log(data);

         }

      });

   });

});

</script>