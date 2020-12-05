
<form method="post" action="#" id="mobile-money-form">
   <button type="button" data-toggle="modal" data-target="#payment-modal-1" class="btn btn-lg btn-success btn-block">
      <?= $lang['button']['pay_with_dusupay']; ?>
   </button>
</form>

<?php include('dusupay_payment_modal.php'); ?>