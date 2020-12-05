<div class='card border-0'>
  <div class='card-body pb-3'>
    <h5 class='font-weight-normal mb-3'><strong>How it works</strong> <span class='badge badge-success badge-sm'>SITE RULE</span> </h5>
    <div class='price'>
    <b class='currency'><?= $s_currency; ?><span><?= $proposal_price; ?></span></b>
    </div>
    <p class='h6 line-height-full'>This is the base price. Unless you agree otherwise with seller, the delivered work will be as detailed on this page. Ordering extras may extend the delivery time.</p>
  </div>
</div>
<script>
var order_box = $('.order-box');
$('.popover').css({ 'max-width' : order_box.width() + 'px', left : '155px' });
</script>
